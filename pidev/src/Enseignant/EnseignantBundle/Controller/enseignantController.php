<?php

namespace Enseignant\EnseignantBundle\Controller;

use Enseignant\EnseignantBundle\Entity\Enseignant;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Enseignant\EnseignantBundle\Form\enseignantType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;









class enseignantController extends Controller
{/********** mobile *******/

    public function AllAction()
    {
        $task =$this->getDoctrine()->getManager()->getRepository('EnseignantEnseignantBundle:Enseignant')->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($task);
        return new JsonResponse($formatted);

    }

    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $enseignant = new Enseignant();
        $enseignant->setNom($request->get('nom'));
        $enseignant->setPrenom($request->get('prenom'));
        $enseignant->setAdresse($request->get('adresse'));
        $enseignant->setSpecial($request->get('special'));
        $enseignant->setPrix($request->get('prix'));
        $enseignant->setNbr($request->get('nbr'));
        $enseignant->setEmail($request->get('email'));
        $enseignant->setIduser($request->get('iduser'));

        $enseignant->setNomimage($request->get('nomimage'));

        $em->persist($enseignant);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($enseignant);
        return new JsonResponse($formatted);
    }



    public function DeleteprAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $rendezvous=$em->getRepository(Enseignant::class)->find($request->get("id"));
        $em->remove($rendezvous);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($rendezvous);
        return new JsonResponse($formatted);
    }


    public function UpdatepAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();

        $enseignant=$em->getRepository(Enseignant::class)->find($request->get("id"));

        $enseignant->setAdresse($request->get("adresse"));
        $enseignant->setPrix($request->get("prix"));
        $enseignant->setNbr($request->get("nbr"));


        $em->persist($enseignant);
        $em->flush();
        //   return new View("Succes",Response::HTTP_OK);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($enseignant);
        return new JsonResponse($formatted);
    }
    public function affichidAction($iduser)
    {
        $em=$this->getDoctrine()->getManager();
        $question=$em->getRepository(Enseignant::class)->findByIduser($iduser);

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($question);
        return new JsonResponse($formatted);
    }


    public function ensAction()
    {

        return $this->render('EnseignantEnseignantBundle:Enseignant:ListeEn.html.twig', array());
    }

    public function ajoutAction(Request $request)
    {
        $Enseignant = new Enseignant();
        $form = $this->createForm(enseignantType::class, $Enseignant);
$us=$this->getUser()->getId();
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $Enseignant->uploadProfilePicture();
            $Enseignant->setIduser($us);
            $em->persist($Enseignant); // insert into table
            $em->flush(); //executer
            return $this->redirectToRoute('Liste1');
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
            'f' => $form->createView()
        );
        return $this->render('EnseignantEnseignantBundle:Enseignant:ajout.html.twig',$data);

    }

    public function enAction(Request $request)
    {
        $m = $this->getDoctrine()->getManager();
        $mark = $m->getRepository('EnseignantEnseignantBundle:Enseignant')->findAll();

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
        'm' => $mark


        );

        return $this->render('EnseignantEnseignantBundle:Enseignant:listeEn.html.twig',$data);

    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchAction(Request $request)
    {
        $m = $this->getDoctrine()->getManager();
        $enseignant = $m->getRepository('EnseignantEnseignantBundle:Enseignant')->findAll();
        $session = $request->getSession();

        $authErrorKey = \Symfony\Component\Security\Core\Security::AUTHENTICATION_ERROR;
        $lastUsernameKey = \Symfony\Component\Security\Core\Security::LAST_USERNAME;

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
            'm' => $enseignant


        );

        return $this->render('EnseignantEnseignantBundle:Enseignant:listeEn.html.twig',$data );
    }

    public function search1Action(Request $request)
    {
        $m = $this->getDoctrine()->getManager();
        $mark = $m->getRepository('EnseignantEnseignantBundle:Enseignant')->findAll();

        if ($request->getMethod() == "POST") {
            $mark = $m->getRepository('EnseignantEnseignantBundle:Enseignant')->findEnseignant($request->get('special'));


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
             'm' => $mark


        );

        return $this->render('EnseignantEnseignantBundle:Enseignant:recherche.html.twig',$data);
    }
    public function search2Action(Request $request)
    {
        $m = $this->getDoctrine()->getManager();
        $mark = $m->getRepository('EnseignantEnseignantBundle:Enseignant')->findAll();


            $mark = $m->getRepository('EnseignantEnseignantBundle:Enseignant')->calcul();


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
            'm' => $mark


        );

        return $this->render('EnseignantEnseignantBundle:Enseignant:recherche.html.twig',$data );
    }

    public function supprimerAction($id,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $mark = $em->getRepository('EnseignantEnseignantBundle:Enseignant')->find($id);
        $em->remove($mark);
        $em->flush();
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
            'routename'=>$routeName
        );
        return $this->redirectToRoute('Liste4');
    }

    public function modifierAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $mark = $em->getRepository('EnseignantEnseignantBundle:Enseignant')->find($id);
        $form = $this->createForm(enseignantType::class, $mark);
        if ($form->handleRequest($request)->isValid()) {
            $em->persist($mark);
            $em->flush();
            return $this->redirectToRoute('Liste4');
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
            'f' => $form->createView()
        );
        return $this->render('EnseignantEnseignantBundle:Enseignant:ajout.html.twig', $data);
    }

    public function calculerAction(Request $request, $id) {

        $em = $this ->getDoctrine()->getManager();
        $nbr = $em->getRepository('EnseignantEnseignantBundle:Enseignant')->Calculer_prix($id);
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
            'nbs' => $nbr[0]
        );
        return $this -> render ('EnseignantEnseignantBundle:Enseignant:calculer.html.twig',$data
            );

    }




}