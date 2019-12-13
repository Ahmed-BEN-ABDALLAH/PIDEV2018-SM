<?php

namespace JardinDenfant\ProfilJDBundle\Controller;

use JardinDenfant\ProfilJDBundle\Entity\Evenement;
use JardinDenfant\ProfilJDBundle\Entity\ProfilJD;
use JardinDenfant\ProfilJDBundle\Form\EvenementType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\VarDumper\VarDumper;
use JardinDenfant\ProfilJDBundle\Entity\UserEvents;

use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class EvenementController extends Controller
{
    public function allAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        //Liste des evenements
        $listEvenements = $em->getRepository('JardinDenfantProfilJDBundle:Evenement')->findAll();
        //exit(VarDumper::dump( $listEvenements));


        $listEvenementsJson = array();
        foreach ($listEvenements as $evenement) {
            $listEvenementsJson[] = array(
                "id" => $evenement->getIde(),

                "nom" => $evenement->getNonE(),
                "image" => $evenement -> getImage(),
                "date" => "" . strtotime($evenement->getStart()->format('Y-m-d H:i:s')) . "",
                "description" => $evenement->getApropos(),
                "adresse" =>$evenement->getAdresse(),
                "nbplace"=>$evenement->getNbrPlaceMax(),


            );
        }

        return new JsonResponse(array("listEvenements" => $listEvenementsJson

            ));



    }

    public function afficherBoutonMobileAction (Request $request)
{
    $em = $this->getDoctrine()->getManager();
    $listEvenements = $em->getRepository('JardinDenfantProfilJDBundle:Evenement')->findAll();
    $useins=$em->getRepository('JardinDenfantProfilJDBundle:UserEvents')->findevn($request->get('id'));
   // exit(VarDumper::dump($useins));
    $tab=array();
    foreach ($listEvenements as $v) {

        foreach ($useins as $u) {

            if ($v->getIde() == $u->getEvenement()->getIde())
            {

                 $tab[]=$v->getIde();

            }

        }

    }

    return new JsonResponse(

       array("tab"=>$tab)) ;

}



    public function allByJardinAction(Request $request)
    {


        $em = $this->getDoctrine()->getManager();

        $num=$em->getRepository('JardinDenfantProfilJDBundle:ProfilJD')->findBy(
            array('numauth'=>$request->get('numauth')));
        //exit(VarDumper::dump($num));
        $numauth=$num[0]->getNumauth();
       // exit(VarDumper::dump($numauth));
//Liste des evenements
        $listEvenements = $em->getRepository("JardinDenfantProfilJDBundle:Evenement")->findBy(array('numauth'=>$numauth));



        $listEvenementsJson = array();
        foreach ($listEvenements as $evenement) {
            $listEvenementsJson[] = array(
                "id" => $evenement->getIde(),

                "nom" => $evenement->getNonE(),
                "image" => $evenement -> getImage(),
                "date" => "" . strtotime($evenement->getStart()->format('Y-m-d H:i:s')) . "",
                "description" => $evenement->getApropos(),
                "adresse" =>$evenement->getAdresse(),
                "nbplace"=>$evenement->getNbrPlaceMax(),

            );
        }

        return new JsonResponse(array("listEvenements" => $listEvenementsJson));



    }

    public function supprimerEMobileAction (Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $mark = $em->getRepository('JardinDenfantProfilJDBundle:Evenement')->find($request->get("id"));
        $mark-> setUserevents(null) ;


        $em->remove($mark);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($mark);
        return new JsonResponse($formatted);

    }

    public function UpdateMobileAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $ev = $em->getRepository('JardinDenfantProfilJDBundle:Evenement')->find($request->get("id"));
       // exit(VarDumper::dump($ev));

        $ev->setNonE($request->get('nomE'));
        $ev->setApropos($request->get('apropos'));
       /* $date= $request->get('start');
        $dateStart=date('Y-m-d', strtotime(str_replace('-','/', $date)));
        $dateS = new \DateTime($dateStart);
        $ev->setStart($dateS);*/
        $ev->setAdresse($request->get('adresse'));
        $ev->setNbrPlaceMax($request->get('nbplace'));



            $em->persist($ev);
            $em->flush();
        $listEvenementsJson = array();

            $listEvenementsJson[] = array(
                "id" => $ev->getIde(),

                "nom" => $ev->getNonE(),
                "image" => $ev -> getImage(),
               //  "date" => "" . strtotime($ev->getStart()->format('Y-m-d H:i:s')) . "",
                "description" => $ev->getApropos(),
                "adresse" =>$ev->getAdresse(),
                "nbplace"=>$ev->getNbrPlaceMax(),



            );


       if ($listEvenementsJson!=null) {return new JsonResponse(array("listEvenements" => $listEvenementsJson));}

    else { return new JsonResponse(array("res" => 0));}
        }



    /*
     * @Method("POST")
     */
    public function ajoutEMobileAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $p = new Evenement();
        $p = new Evenement();
        $p->setNonE($request->get('nomE'));

        $p->setApropos($request->get('apropos'));
      $date= $request->get('start');
        $dateStart=date('Y-m-d', strtotime(str_replace('-','/', $date)));
        $dateS = new \DateTime($dateStart);
        $p->setStart($dateS);
        $p->setAdresse($request->get('adresse'));
        $p->setNbrPlaceMax($request->get('nbplace'));
        $p->setNumauth($request->get('numauth'));
       $p->setImage($request->get('imagename'));
        try {
            $em = $this->getDoctrine()->getManager();
            $em->persist($p);
            $em->flush();
            return new JsonResponse(array('info' => 'success'));
        } catch (Exception $e) {
            return new JsonResponse(array('info' => 'error'));
        }

    }




    /*
     * @Method("POST")
     */
    public function uploadImageAction(Request $request)
    {
        $imagename = $request->request->get('imagename');
        $Imagecode = $request->request->get('image');
        define('UPLOAD_DIR', 'C:/wamp64/www/pidev_hella/pidev/web/uploads/images/');
        $img = base64_decode($Imagecode);
        $uid = uniqid();
        $file = UPLOAD_DIR . $imagename . '.jpg';
        $success = file_put_contents($file, $img);
        if ($success) {
            return new JsonResponse(array('info' => $imagename .'.jpg'));
        } else {
            return new JsonResponse(array('info' => 'erreur'));
        }
    }


    public function ajoutEAction(Request $request)
    {

        $e = new Evenement();
        $user = $this->getUser();
        $id = $user->getId();
        $form = $this->createForm(EvenementType::class, $e);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $file = $form['image']->getData();

            $file->move("images/", $file->getClientOriginalName());
            $e->setImage($file->getClientOriginalName());
            $em = $this->getDoctrine()->getManager();
            $num=$em->getRepository('JardinDenfantProfilJDBundle:ProfilJD')->findOneBy(array('id'=>$id));
            $e->setNumAuth($num->getNumauth());
            //exit(VarDumper::dump($num->getNumauth()));

            $em->persist($e);
            $em->flush();
           // $this->get('session')->setFlash('success', 'l evenement  a bien été supprimée !');
            return $this->redirectToRoute('affichEventsParJardin');
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



        return $this->render('JardinDenfantProfilJDBundle:Evenement:ajout_e.html.twig', $data);

    }

    public function modifEAction($ide, Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $mark = $em->getRepository('JardinDenfantProfilJDBundle:Evenement')->find($ide);
        $form = $this->createForm(EvenementType::class, $mark);

        if ($form->handleRequest($request)->isValid()) {
            $file = $form['image']->getData();

            $file->move("images/", $file->getClientOriginalName());
            $mark->setImage($file->getClientOriginalName());
            $em->persist($mark);
            $em->flush();
            return $this->redirectToRoute('affichEventsParJardin');
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

        return $this->render('JardinDenfantProfilJDBundle:Evenement:modif_e.html.twig', $data);
    }

    public function supprimeEAction($ide)
    {

        $em = $this->getDoctrine()->getManager();

        $mark = $em->getRepository('JardinDenfantProfilJDBundle:Evenement')->find($ide);
        $mark-> setUserevents(null) ;


        $em->remove($mark);
        $em->flush();

            return $this->redirectToRoute('affichEventsParJardin');


    }

    public function rechercheEAction(Request $request)
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
        $data = array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename' => $routeName,
        );

        return $this->render('JardinDenfantProfilJDBundle:Evenement:recherche_e.html.twig', array(// ...
        ));
    }

    public function afficherEAction(Request $request)
    {


        $em = $this->getDoctrine()->getManager();
        $ev = $em->getRepository("JardinDenfantProfilJDBundle:Evenement")->triEventsByDate();
       // $ev1= $em->getRepository("JardinDenfantProfilJDBundle:Evenement")->findBy();
        $user = $this->getUser();
        $id = $user->getId();
       // $num=$em->getRepository('JardinDenfantProfilJDBundle:Evenement')->findOneBy(array('ide'=>$ev));
        $useins=$em->getRepository('JardinDenfantProfilJDBundle:UserEvents')->findevn($id);
       // exit(VarDumper::dump($useins));
        $tab=array();
        foreach ($ev as $v) {

            foreach ($useins as $u) {

                    if ($v->getIde() == $u->getEvenement()->getIde())
                    {
                        $tab[]=$v->getIde();
                    }
            }

    }

 //exit(VarDumper::dump($tab));

        /**
         * @var  $paginator \Knp\Component\Pager\Paginator
         */
        $paginator  = $this->get('knp_paginator');

        $evts=$paginator->paginate(
            $ev,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',3)
        );
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
            "evts" => $evts,
            "useins"=>$useins,
            'tab'=>$tab,
        );


        return $this->render('JardinDenfantProfilJDBundle:Evenement:afficher_e.html.twig', $data);
    }


    public function calanderAction(Request $request)
    {


        $em = $this->getDoctrine()->getManager();
        $ev = $em->getRepository("JardinDenfantProfilJDBundle:Evenement")->findAll();
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
            "ev" => $ev
        );
        return $this->render('JardinDenfantProfilJDBundle:Evenement:test.html.twig', $data);
    }

    public function loadCalendrierDataAction( )
    {

        $em = $this->getDoctrine()->getManager();
          $listCategorieJson[] = array(
            "id" => "1",
            "libelle" => "placeholder",
            "couleur" => "#4475c4",
        );

        //Liste des evenements
        $listEvenements = $em->getRepository('JardinDenfantProfilJDBundle:Evenement')->findAll();
        $listEvenementsJson = array();
        foreach ($listEvenements as $evenement) {
            $listEvenementsJson[] = array(
                "id" => $evenement->getIde(),
                "categorie" => "placeholder",
                "titre" => $evenement->getNonE(),
               "image" => $evenement -> getImage(),
                "date" => "" . strtotime($evenement->getStart()->format('Y-m-d H:i:s')) . "",
                "description" => $evenement->getApropos(),
                "local" =>$evenement->getAdresse(),
                //"lien" => "event/show/" . $evenement->getId(),
                //"prix" => "15"
            );
        }

        return new JsonResponse(array('listCategories' => $listCategorieJson,"listEvenements" => $listEvenementsJson));
    }

    public function afficherEventsParJardinAction(Request $request)
    {


        $em = $this->getDoctrine()->getManager();


        $user = $this->getUser();
        $id = $user->getId();

        $em = $this->getDoctrine()->getManager();
        $num=$em->getRepository('JardinDenfantProfilJDBundle:ProfilJD')->findOneBy(array('id'=>$id));
        $numauth=$num->getNumauth();

        $ev = $em->getRepository("JardinDenfantProfilJDBundle:Evenement")->findBy(array('numauth'=>$numauth));

        //exit(VarDumper::dump($ev));

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
            "ev" => $ev,



        );


        return $this->render('JardinDenfantProfilJDBundle:Evenement:afficherEventsParJardin.html.twig', $data);
    }




}
