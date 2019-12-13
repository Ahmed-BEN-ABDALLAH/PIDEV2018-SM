<?php

namespace JardinDenfant\ProfilJDBundle\Controller;

use JardinDenfant\ProfilJDBundle\Entity\ProfilJD;
use JardinDenfant\ProfilJDBundle\Form\ProfilJD2Type;
use JardinDenfant\ProfilJDBundle\Form\ProfilJDType;
use JardinDenfant\ProfilJDBundle\Form\RechercheForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
//use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\VarDumper\VarDumper;


class ProfilJDController extends Controller
{

    public function findJardinByIdUserAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
       $iduser= $request->get('id');

        $jardin = $em->getRepository('JardinDenfantProfilJDBundle:ProfilJD')->finduser($iduser);
        //exit(VarDumper::dump($jardin[0]));

      $num= $jardin[0] ->getNumauth();
        return new JsonResponse( array("numauth"=>$num)

        );

    }


    public function allAction (Request $request)
    {

        $em = $this->getDoctrine()->getManager();



        //Liste des evenements
        $listJardin = $em->getRepository('JardinDenfantProfilJDBundle:ProfilJD')->findAll();
        $listJardinJson = array();
        foreach ($listJardin as $j) {
            $listJardinJson[] = array(
                "numauth" => $j->getNumauth(),
                "nom" => $j->getNomJD(),
                "image" => $j -> getImage(),
                // "date" => "" . strtotime($evenement->getStart()->format('Y-m-d H:i:s')) . "",
                "description" => $j->getDescription(),
                "adresse"=>$j->getAdresse(),
                "numero" =>$j->getNum(),


            );
        }

        return new JsonResponse(array("listJardin" => $listJardinJson));
    }
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $p = new ProfilJD();
        $p->setNumauth($request->get('numauth'));
        $p->setNomJD($request->get('nomjd'));
        $p->setAdresse($request->get('adresse'));
        $p->setDescription($request->get('description'));
        $p->setNum($request->get('num'));
        $em->persist($p);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($p);
        return new JsonResponse($formatted);
    }

    public function indexAction(Request $request)
    {
        $session = $request->getSession();

        $authErrorKey = Security::AUTHENTICATION_ERROR;
        $lastUsernameKey = Security::LAST_USERNAME;

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

        $csrfToken = $this->has('security.csrf.token_manager')
            ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
            : null;

        $routeName = $request->get('_route');
        $data=array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename'=>$routeName,

        );
        return $this->render('JardinDenfantProfilJDBundle:ProfilJD:layout.html.twig');
    }
    public function afficherPJDAction(Request $request)
    {
        $m=$this-> getDoctrine()->getManager();//service fi west l ORM
        $user=$this->getUser();
        $iduser=$user->getId();
        $mark =$m->getRepository('JardinDenfantProfilJDBundle:ProfilJD')->findById($iduser);

        $session = $request->getSession();

        $authErrorKey = Security::AUTHENTICATION_ERROR;
        $lastUsernameKey = Security::LAST_USERNAME;

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

        $csrfToken = $this->has('security.csrf.token_manager')
            ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
            : null;

        $routeName = $request->get('_route');
        $data=array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename'=>$routeName,
            "m"=>$mark
        );
        return $this->render('JardinDenfantProfilJDBundle:ProfilJD:afficher_pjd.html.twig',$data);


    }

    public function ajouterPJDAction(Request $request)
    {

        $jd = new ProfilJD();
        $user=$this->getUser();
        $id=$user->getId();

        $form = $this->createForm(ProfilJDType::class, $jd);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $jd->setRating(0);
            $jd->setValide(0);
            $jd->setId($id);
            $em = $this->getDoctrine()->getManager();

            $em->persist($jd);
            $em->flush();
            return $this->redirectToRoute('afficher_p_j_d');
        }
       $session = $request->getSession();

        $authErrorKey = Security::AUTHENTICATION_ERROR;
        $lastUsernameKey = Security::LAST_USERNAME;

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

        $csrfToken = $this->has('security.csrf.token_manager')
            ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
            : null;

        $routeName = $request->get('_route');
        $data = array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename' => $routeName,
            'form' => $form->createView()
        );
        return $this->render('JardinDenfantProfilJDBundle:ProfilJD:aj.html.twig', $data);
    }

        function supprimerAction($numauth)
        {
            $em = $this->getDoctrine()->getManager();
            $mark = $em->getRepository('JardinDenfantProfilJDBundle:ProfilJD')->find($numauth);
            $em->remove($mark);
            $em->flush();

            return $this->redirectToRoute('afficher_tout');


        }

        function modifierPJDAction($numauth, Request $request)
        {
            $em = $this->getDoctrine()->getManager();
            $mark = $em->getRepository('JardinDenfantProfilJDBundle:ProfilJD')->find($numauth);
            $form = $this->createForm(ProfilJDType::class, $mark);

            if ($form->handleRequest($request)->isValid()) {
                $em->persist($mark);
                $em->flush();
                return $this->redirectToRoute('afficher_p_j_d');
            }
            $session = $request->getSession();

            $authErrorKey = Security::AUTHENTICATION_ERROR;
            $lastUsernameKey = Security::LAST_USERNAME;

            // get the error if any (works with forward and redirect -- see below)
            if ($request->attributes->has($authErrorKey)) {
                $error = $request->attributes->get($authErrorKey);
            } elseif (null !== $session && $session->has($authErrorKey)) {
                $error = $session->get($authErrorKey);
                $session->remove($authErrorKey);
            } else {
                $error = null;
            }

            if (!$error instanceof AuthenticationException) {
                $error = null; // The value does not come from the security component.
            }

            // last username entered by the user
            $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

            $csrfToken = $this->has('security.csrf.token_manager')
                ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
                : null;

            $routeName = $request->get('_route');
            $data = array(
                'last_username' => $lastUsername,
                'error' => $error,
                'csrf_token' => $csrfToken,
                'routename' => $routeName,
                'form' => $form->createView()
            );
            return $this->render('JardinDenfantProfilJDBundle:ProfilJD:modifier_pjd.html.twig', $data);

        }

        function profilAction(Request $request, $numauth)
        {

            $em = $this->getDoctrine()->getManager();
            $mark = $em->getRepository('JardinDenfantProfilJDBundle:ProfilJD')->find($numauth);
           // exit(VarDumper::dump($mark));


            $session = $request->getSession();

            $authErrorKey = Security::AUTHENTICATION_ERROR;
            $lastUsernameKey = Security::LAST_USERNAME;

            // get the error if any (works with forward and redirect -- see below)
            if ($request->attributes->has($authErrorKey)) {
                $error = $request->attributes->get($authErrorKey);
            } elseif (null !== $session && $session->has($authErrorKey)) {
                $error = $session->get($authErrorKey);
                $session->remove($authErrorKey);
            } else {
                $error = null;
            }

            if (!$error instanceof AuthenticationException) {
                $error = null; // The value does not come from the security component.
            }

            // last username entered by the user
            $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

            $csrfToken = $this->has('security.csrf.token_manager')
                ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
                : null;

            $routeName = $request->get('_route');
            $data = array(
                'last_username' => $lastUsername,
                'error' => $error,
                'csrf_token' => $csrfToken,
                'routename' => $routeName,
                "m"=>$mark
            );
            return $this->render('JardinDenfantProfilJDBundle:ProfilJD:profil.html.twig', $data);
            // return $this->render('JardinDenfantProfilJDBundle:ProfilJD:profil.html.twig',array('modeles'=>$models));

        }

    function affichtoutAction(Request $request)
    {

        $m = $this->getDoctrine()->getManager();//service fi west l ORM
        $jd = $m->getRepository('JardinDenfantProfilJDBundle:ProfilJD')->findvalide();





        if ($request->getMethod() == "POST") {

            $jd = $m->getRepository('JardinDenfantProfilJDBundle:ProfilJD')
                ->findadresse(
                    $request->get('adresse'));
            //  exit(VarDumper::dump($jd));



            $session = $request->getSession();

            $authErrorKey = Security::AUTHENTICATION_ERROR;
            $lastUsernameKey = Security::LAST_USERNAME;

            // get the error if any (works with forward and redirect -- see below)
            if ($request->attributes->has($authErrorKey)) {
                $error = $request->attributes->get($authErrorKey);
            } elseif (null !== $session && $session->has($authErrorKey)) {
                $error = $session->get($authErrorKey);
                $session->remove($authErrorKey);
            } else {
                $error = null;
            }

            if (!$error instanceof AuthenticationException) {
                $error = null; // The value does not come from the security component.
            }

            // last username entered by the user
            $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

            $csrfToken = $this->has('security.csrf.token_manager')
                ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
                : null;

            $routeName = $request->get('_route');
            $data = array(
                'last_username' => $lastUsername,
                'error' => $error,
                'csrf_token' => $csrfToken,
                'routename' => $routeName,
                "jd" => $jd,
                //'Form'=>$Form->createView()


            );

            return $this->render('JardinDenfantProfilJDBundle:ProfilJD:affichertout_pjd.html.twig',$data

            );}


        $session = $request->getSession();

        $authErrorKey = Security::AUTHENTICATION_ERROR;
        $lastUsernameKey = Security::LAST_USERNAME;

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

        $csrfToken = $this->has('security.csrf.token_manager')
            ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
            : null;

        $routeName = $request->get('_route');
        $data = array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename' => $routeName,
            "jd" => $jd,
            //'Form'=>$Form->createView()

        );

            return $this->render('JardinDenfantProfilJDBundle:ProfilJD:affichertout_pjd.html.twig',  $data);
        }
    public function  mapAction ($numauth,Request $request)
    {
        $m = $this->getDoctrine()->getManager();//service fi west l ORM
        $mark = $m->getRepository('JardinDenfantProfilJDBundle:ProfilJD')->find($numauth);
        $lng=$mark->getLongitude();
        $lat=$mark->getLatitude();




        $session = $request->getSession();

        $authErrorKey = Security::AUTHENTICATION_ERROR;
        $lastUsernameKey = Security::LAST_USERNAME;

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

        $csrfToken = $this->has('security.csrf.token_manager')
            ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
            : null;

        $routeName = $request->get('_route');
        $data=array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename'=>$routeName,
            'lat'=>$lat,'lng'=>$lng
        );

        return $this->render('JardinDenfantProfilJDBundle:ProfilJD:map.html.twig',$data);
    }
    public function afficherAdminAction (Request $request)
{
    $m = $this->getDoctrine()->getManager();//service fi west l ORM
    $jd = $m->getRepository('JardinDenfantProfilJDBundle:ProfilJD')->findNotValide();
    /**
     * @var  $paginator \Knp\Component\Pager\Paginator
     */
    $paginator  = $this->get('knp_paginator');

    $jds=$paginator->paginate(
        $jd,
        $request->query->getInt('page',1),
        $request->query->getInt('limit',3)
    );
    //dump(get_class($paginator));
    $session = $request->getSession();

    $authErrorKey = Security::AUTHENTICATION_ERROR;
    $lastUsernameKey = Security::LAST_USERNAME;

    // get the error if any (works with forward and redirect -- see below)
    if ($request->attributes->has($authErrorKey)) {
        $error = $request->attributes->get($authErrorKey);
    } elseif (null !== $session && $session->has($authErrorKey)) {
        $error = $session->get($authErrorKey);
        $session->remove($authErrorKey);
    } else {
        $error = null;
    }

    if (!$error instanceof AuthenticationException) {
        $error = null; // The value does not come from the security component.
    }

    // last username entered by the user
    $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

    $csrfToken = $this->has('security.csrf.token_manager')
        ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
        : null;

    $routeName = $request->get('_route');
    $data=array(
        'last_username' => $lastUsername,
        'error' => $error,
        'csrf_token' => $csrfToken,
        'routename'=>$routeName,
        //'jd'=>$jd,
        'jds'=>$jds

    );

    return $this->render('JardinDenfantProfilJDBundle:ProfilJD:affichejardinAdmin.html.twig',$data);
}



public function valideAdminAction($numauth,Request $request)
{

    $m = $this->getDoctrine()->getManager();//service fi west l ORM
    $jd = $m->getRepository('JardinDenfantProfilJDBundle:ProfilJD')->find($numauth);
    $session = $request->getSession();

    $authErrorKey = Security::AUTHENTICATION_ERROR;
    $lastUsernameKey = Security::LAST_USERNAME;

    // get the error if any (works with forward and redirect -- see below)
    if ($request->attributes->has($authErrorKey)) {
        $error = $request->attributes->get($authErrorKey);
    } elseif (null !== $session && $session->has($authErrorKey)) {
        $error = $session->get($authErrorKey);
        $session->remove($authErrorKey);
    } else {
        $error = null;
    }

    if (!$error instanceof AuthenticationException) {
        $error = null; // The value does not come from the security component.
    }

    // last username entered by the user
    $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

    $csrfToken = $this->has('security.csrf.token_manager')
        ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
        : null;

    $routeName = $request->get('_route');
    $data=array(
        'last_username' => $lastUsername,
        'error' => $error,
        'csrf_token' => $csrfToken,
        'routename'=>$routeName,
        "jd"=>$jd,

    );


    $jd->setValide(1);
    $m->persist($jd);
    $m->flush();

    return $this->redirectToRoute('afficheAdmin');
}

    public function DetailAction(Request $request,$numauth)
    {
        $em = $this->getDoctrine()->getManager();

        $mark = $em->getRepository('JardinDenfantProfilJDBundle:ProfilJD')->find($numauth);
        $x=$mark->getRating();
        $form = $this->createForm(ProfilJD2Type::class, $mark);

        $form->handleRequest($request);
       if ($form->isSubmitted()) {

           $y=$mark->getRating();

           $mark->setRating($x+$y);

           // exit(VarDumper::dump($mark));
            $em->persist($mark);

            $em->flush();
            return $this->redirectToRoute('afficher_tout');
        }

        $session = $request->getSession();

        $authErrorKey = Security::AUTHENTICATION_ERROR;
        $lastUsernameKey = Security::LAST_USERNAME;

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

        $csrfToken = $this->has('security.csrf.token_manager')
            ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
            : null;

        $routeName = $request->get('_route');
        $data=array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename'=>$routeName,
            'form' => $form->createView(),
'mark'=>$mark
        );


        return $this->render('JardinDenfantProfilJDBundle:ProfilJD:Detail.html.twig',$data);
    }

    public function rechercheAjaxAction(Request $request)
    {

        $pj=new ProfilJD();

        $m=$this->getDoctrine()->getManager();
        $blagues=$m->getRepository('JardinDenfantProfilJDBundle:ProfilJD')->findAll();
        $form=$this->createForm(RechercheForm::class,$pj);
        $form->handleRequest($request);
        if($request->isXmlHttpRequest()){
            $serializer=new Serializer(array(new ObjectNormalizer()));

            $blagues=$m->getRepository('JardinDenfantProfilJDBundle:ProfilJD')->findadresse($request->get('adresse'));
            $data=$serializer->normalize($blagues);
            return new JsonResponse($data);
        }









        $session = $request->getSession();

        $authErrorKey = Security::AUTHENTICATION_ERROR;
        $lastUsernameKey = Security::LAST_USERNAME;

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

        $csrfToken = $this->has('security.csrf.token_manager')
            ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
            : null;

        $routeName = $request->get('_route');
        $data = array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename' => $routeName,
            'blagues' => $blagues,
            'form'=>$form->createView()
            //'Form'=>$Form->createView()

        );






        return $this->render('JardinDenfantProfilJDBundle:ProfilJD:affichertout_pjd.html.twig',$data);













    }

}




