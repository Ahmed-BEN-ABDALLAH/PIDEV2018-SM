<?php

namespace Sante\SpecialisteBundle\Controller;

use Sante\SpecialisteBundle\Entity\horaireTravail;
use Sante\SpecialisteBundle\Form\horaireTravailType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;

class horaireTravailController extends Controller
{
    public function ajouterHoraire1Action(Request $request, $id)
    {
        $a = new horaireTravail();
        $form = $this->createForm(horaireTravailType::class, $a);
        if ($form->handleRequest($request)->isValid()) {
            $a->setId($id);
            $a->setJoursemaine("Lundi");
            $em = $this->getDoctrine()->getManager();
            $em->persist($a); // insert into table
            $em->flush(); //executer

           return $this->redirectToRoute('ajouterHoraire2',array('id' => $id));
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
            'routename'=>$routeName,'f' => $form->createView(),'jour'=>"Lundi"
        );
        return $this->render('SanteSpecialisteBundle:horaire:horaire.html.twig',$data);
        }
    public function ajouterHoraire2Action(Request $request, $id)
    {
        $a = new horaireTravail();
        $form = $this->createForm(horaireTravailType::class, $a);
        if ($form->handleRequest($request)->isValid()) {
            $a->setId($id);
            $a->setJoursemaine("Mardi");
            $em = $this->getDoctrine()->getManager();
            $em->persist($a); // insert into table
            $em->flush(); //executer

            return $this->redirectToRoute('ajouterHoraire3',array('id' => $id));
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
            'routename'=>$routeName,'f' => $form->createView(),'jour'=>"Mardi"
        );
        return $this->render('SanteSpecialisteBundle:horaire:horaire.html.twig', $data);
    }
    public function ajouterHoraire3Action(Request $request, $id)
    {
        $a = new horaireTravail();
        $form = $this->createForm(horaireTravailType::class, $a);
        if ($form->handleRequest($request)->isValid()) {
            $a->setId($id);
            $a->setJoursemaine("Mercredi");
            $em = $this->getDoctrine()->getManager();
            $em->persist($a); // insert into table
            $em->flush(); //executer

            return  $this->redirectToRoute('ajouterHoraire4',array('id' => $id));
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
            'routename'=>$routeName,'f' => $form->createView(),'jour'=>"Mercredi"
        );
        return $this->render('SanteSpecialisteBundle:horaire:horaire.html.twig',$data);
    }
    public function ajouterHoraire4Action(Request $request, $id)
    {
        $a = new horaireTravail();
        $form = $this->createForm(horaireTravailType::class, $a);
        if ($form->handleRequest($request)->isValid()) {
            $a->setId($id);
            $a->setJoursemaine("Jeudi");
            $em = $this->getDoctrine()->getManager();
            $em->persist($a); // insert into table
            $em->flush(); //executer

            return $this->redirectToRoute('ajouterHoraire5',array('id' => $id));
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
            'routename'=>$routeName,'f' => $form->createView(),'jour'=>"Jeudi"
        );
        return $this->render('SanteSpecialisteBundle:horaire:horaire.html.twig',$data);
    }
    public function ajouterHoraire5Action(Request $request, $id)
    {
        $a = new horaireTravail();
        $form = $this->createForm(horaireTravailType::class, $a);
        if ($form->handleRequest($request)->isValid()) {
            $a->setId($id);
            $a->setJoursemaine("Vendredi");
            $em = $this->getDoctrine()->getManager();
            $em->persist($a); // insert into table
            $em->flush(); //executer

            return $this->redirectToRoute('ajouterHoraire6',array('id' => $id));
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
            'routename'=>$routeName,'f' => $form->createView(),'jour'=>"Vendredi"
        );
        return $this->render('SanteSpecialisteBundle:horaire:horaire.html.twig', $data);
    }
    public function ajouterHoraire6Action(Request $request, $id)
    {
        $a = new horaireTravail();
        $form = $this->createForm(horaireTravailType::class, $a);
        if ($form->handleRequest($request)->isValid()) {
            $a->setId($id);
            $a->setJoursemaine("Samedi");
            $em = $this->getDoctrine()->getManager();
            $em->persist($a); // insert into table
            $em->flush(); //executer
            return $this->redirectToRoute('sante_specialiste_homepage');
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
            'routename'=>$routeName,'f' => $form->createView(),'jour'=>"Samedi"
        );
        return $this->render('SanteSpecialisteBundle:horaire:horaire.html.twig',$data);
    }

    public function modifierHoraireAction(Request $request, $id)
    {
        $id=$this->get('security.token_storage')->getToken()->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $mark1 = $em->getRepository('SanteSpecialisteBundle:horaireTravail')->findOneBy(array('id' => $id,"joursemaine"=>"Lundi"));
        $form1 = $this->createForm(horaireTravailType::class, $mark1);
        if ($form1->handleRequest($request)->isValid()) {
            $mark1->setJoursemaine("Lundi");
            $mark1->setId($id);
            $em->persist($mark1);
            $em->flush();
            return $this->redirectToRoute('modifierHoraire',array('id' => $id));
        }
        $mark2 = $em->getRepository('SanteSpecialisteBundle:horaireTravail')->findOneBy(array('id' => $id,"joursemaine"=>"Mardi"));
        $form2 = $this->createForm(horaireTravailType::class, $mark2);
        if ($form2->handleRequest($request)->isValid()) {
            $mark2->setJoursemaine("Mardi");
            $mark2->setId($id);
            $em->persist($mark2);
            $em->flush();
            return $this->redirectToRoute('modifierHoraire',array('id' => $id));
        }
        $mark3 = $em->getRepository('SanteSpecialisteBundle:horaireTravail')->findOneBy(array('id' => $id,"joursemaine"=>"Mercredi"));
        $form3 = $this->createForm(horaireTravailType::class, $mark3);
        if ($form3->handleRequest($request)->isValid()) {
            $mark3->setJoursemaine("Mercredi");
            $mark3->setId($id);
            $em->persist($mark3);
            $em->flush();
            return $this->redirectToRoute('modifierHoraire',array('id' => $id));
        }
        $mark4 = $em->getRepository('SanteSpecialisteBundle:horaireTravail')->findOneBy(array('id' => $id,"joursemaine"=>"Jeudi"));
        $form4 = $this->createForm(horaireTravailType::class, $mark4);
        if ($form4->handleRequest($request)->isValid()) {
            $mark4->setJoursemaine("Jeudi");
            $mark4->setId($id);
            $em->persist($mark4);
            $em->flush();
            return $this->redirectToRoute('modifierHoraire',array('id' => $id));
        }
        $mark5 = $em->getRepository('SanteSpecialisteBundle:horaireTravail')->findOneBy(array('id' => $id,"joursemaine"=>"Vendredi"));
        $form5 = $this->createForm(horaireTravailType::class, $mark5);
        if ($form5->handleRequest($request)->isValid()) {
            $mark5->setJoursemaine("Vendredi");
            $mark5->setId($id);
            $em->persist($mark5);
            $em->flush();
            return $this->redirectToRoute('modifierHoraire',array('id' => $id));
        }
        $mark6 = $em->getRepository('SanteSpecialisteBundle:horaireTravail')->findOneBy(array('id' => $id,"joursemaine"=>"Samedi"));
        $form6 = $this->createForm(horaireTravailType::class, $mark6);
        if ($form6->handleRequest($request)->isValid()) {
            $mark6->setJoursemaine("Samedi");
            $mark6->setId($id);
            $em->persist($mark6);
            $em->flush();
            return $this->redirectToRoute('modifierHoraire',array('id' => $id));
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
            'routename'=>$routeName,'f1' => $form1->createView(),'f2' => $form2->createView(),'f3' => $form3->createView(),'f4' => $form4->createView(),'f5' => $form5->createView(),'f6' => $form6->createView(),'id'=>$id
        );
        return $this->render('SanteSpecialisteBundle:horaire:modifierhoraire.html.twig', $data);

    }
}
