<?php
/**
 * Created by PhpStorm.
 * User: AHMED
 * Date: 07/02/2018
 * Time: 12:16
 */

namespace DonateBundle\Controller;


use Doctrine\DBAL\Types\IntegerType;
use DonateBundle\DonateBundle;
use DonateBundle\Entity\ProduitDonation;
use DonateBundle\Form\ProduitDonationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;

use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use UserBundle\Entity\User;

class DonateController extends  Controller
{
    public function DonateAction()
    {
        return $this->render('@Donate/Donate.html.twig');

    }



    public function AjoutAction(Request $request)
    {



        $produit= new ProduitDonation();

        $user=$this->getUser();
        $n=$user->getUsername();
        $idu=$user->getId();


        $produit->setDatenow(new  \DateTime('now'));

        $form = $this->createFormBuilder($produit)


            ->add('nom', TextType::class)


            ->add('categorie', ChoiceType::class, array(
                'choices' => array(

                    'outils school' => 'outils school',
                    'clothes' => 'clothes',
                    'Autre' => 'Autre'
                ),
            ))
            //->add('quantite', \Symfony\Component\Form\Extension\Core\Type\IntegerType::class)


            ->add('description', TextareaType::class)



            ->add('genre',ChoiceType::class,
                array('choices' => array(
                    'Fille' => 'Fille',
                    'Garçon' => 'Garçon',
                ),
                    'choices_as_values' => true,'multiple'=>false,'expanded'=>true))


            ->add('tel', \Symfony\Component\Form\Extension\Core\Type\IntegerType::class)

            ->add('adresse',TextType::class)
            ->add('image', FileType::class, array('data_class' => null))


            ->add('valider', SubmitType::class)

            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var UploadedFile $file


             */
$produit->setEtat("en attente");
            $produit->setIdu($idu);

            $produit->setNomuser($n);
            $file = $form['image']->getData();

            $produit = $form->getData();
            $file->move('uploads/images/', $file->getClientOriginalName());
            $produit->setImage("uploads/images/" . $file->getClientOriginalName());
            $em = $this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('ajoutD');


        }


        /** @var $session Session */
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
            'form'=> $form->createView(),


        );
        return $this->render('@Donate/AjoutDonate.html.twig',$data);

    }







    public function suppdAction($id){
        $em=$this->getDoctrine()->getManager();
        $voiture=$em->getRepository("DonateBundle:ProduitDonation")->find($id);
        $em->remove($voiture);
        $em->flush();
        return $this->redirectToRoute("maprod");

    }







    public function ListDoAction(Request $request)
    {

        $produit = $this->getDoctrine()
            ->getRepository(ProduitDonation::class)
            ->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($produit);
        return new JsonResponse($formatted);

    }


    public function MyListDoAction($id)
    {

        $produit = $this->getDoctrine()
            ->getRepository(ProduitDonation::class)
            ->findByIdu($id);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($produit);
        return new JsonResponse($formatted);

    }


    public function listDAction(Request $request)
    {




        $produit = $this->getDoctrine()
            ->getRepository(ProduitDonation::class)
            ->findAll();
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
           $produit,
           $request->query->get('page', 1)/*page number*/, 4/*limit per page*/
       );

        /** @var $session Session */
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
            'produit'=>$pagination,
            'all'=>$produit
        );
        return $this->render('@Donate/ListeDonate.html.twig',$data);

    }




    public function DetailDAction(Request $request,$id)
    {



        $em=$this->getDoctrine()->getManager();

        $detaild=$em->getRepository("DonateBundle:ProduitDonation")->find($id);


        /** @var $session Session */
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
            'detaild'=>$detaild

        );
        return $this->render('@Donate/DetailD.html.twig',$data);

    }














    public function maprodAction(Request $request)
    {
        $user = $this->getUser();
        $idb = $user->getId();
        /** @var $session Session */
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
        $maprod = $this->getDoctrine()->getManager()->getRepository(ProduitDonation::class)
            ->findByIdu($idb);


        /*
                $paginator  = $this->get('knp_paginator');
                $pagination = $paginator->paginate(
                    $maprod,
                    $request->query->get('page', 1),
                    3
                );
        */

        $data = array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename' => $routeName,
            'maprod' => $maprod

        );


        return $this->render('@Donate/maprod.html.twig', $data);
    }








    public function updateDAction(Request $request,$id){




        $em=$this->getDoctrine()->getManager();
        $produit=$em->getRepository('DonateBundle:ProduitDonation')->find($id);
        $azert=$produit->getImage();
        $form=$this->createForm(ProduitDonationType::class,$produit);
        if($form->handleRequest($request)->isValid()) {
            $file = $form['image']->getData();



            $file->move("images/", $file->getClientOriginalName());
            $produit->setImage("images/".$file->getClientOriginalName());
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('maprod');
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
            'form'=>$form->createView(),
            'aze'=>$azert
        );
        return $this->render('@Donate/updateD.html.twig',$data);


    }







    public function jaimeAction($id)
    {



        $em = $this->getDoctrine()->getManager();
        $voiture = $em->getRepository("DonateBundle:ProduitDonation")->find($id);
        $voiture->setNbj($voiture->getNbj()+1);


        $em->flush();



        /*  foreach ($maparti as $particip) {

              $ide = $particip->getIde();
              if ($ide == $id) {
                  $Test = 1;
              }
          }
          $message = 0;
          if ($Test == 0) {

              $message = 1;



              $data = array(
                  'alert' => $message,

              );


          } else {
              $message = 2;
              $data = array(
                  'alert' => $message);


          }*/

        $response=new JsonResponse();
        return $response;
    }


    public function rechercheAjaxDAction(Request $request)
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






        $em=$this->getDoctrine()->getManager();

        if ($request->isXmlHttpRequest()) {
            $search  = $request->get('search');
            dump($search);
            $event = new ProduitDonation();
            $repo  = $em->getRepository('DonateBundle:ProduitDonation');
            $event = $repo->findAjaxD($search);
            return $this->render('@Donate/componentD.html.twig', array('produit' => $event,$data));
        }


    }


    public  function AddDoAction(Request $request){


        $em = $this->getDoctrine()->getManager();
        $evenement= new ProduitDonation();
        $evenement->setNom($request->get('nom'));
        $evenement->setNomuser($request->get('nomuser'));
        $evenement->setAdresse($request->get('adresse'));
        $evenement->setDescription($request->get('description'));
        $evenement->setImage($request->get('image'));

        $evenement->setIdu($request->get('idu'));
        $evenement->setGenre($request->get('genre'));
        $evenement->setTel($request->get('tel'));

        $em->persist($evenement);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($evenement);
        return new JsonResponse($formatted);

    }



    public function DeleteDoAction($id)
    {


        $em = $this->getDoctrine()->getManager();
        $voiture = $em->getRepository("DonateBundle:ProduitDonation")->find($id);
        $em->remove($voiture);

        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($voiture);
        return new JsonResponse($formatted);

    }




}