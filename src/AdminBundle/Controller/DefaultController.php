<?php

namespace AdminBundle\Controller;

use BabyBundle\Entity\Mail;
use DonateBundle\Entity\ProduitDonation;
use DonateBundle\Form\ProduitDonationType;
use EventsBundle\Entity\Evenement;
use EventsBundle\Form\EvenementType;
use GuzzleHttp\Psr7\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AdminBundle:Default:index.html.twig');
    }
public function AdminAction()
    {
        return $this->render('AdminBundle::Admin.html.twig');
    }



    public function ListDoAction()
    {

        $produit = $this->getDoctrine()
            ->getRepository(ProduitDonation::class)
            ->findAll();
        return $this->render('AdminBundle::Donation.html.twig',array('all'=>$produit));
    }

    public function ListEvAction()
    {

        $produit = $this->getDoctrine()
            ->getRepository(Evenement::class)
            ->findAll();
        return $this->render('AdminBundle::Events.html.twig',array('all'=>$produit));
    }





    public function DeleteDAction($id){
        $em=$this->getDoctrine()->getManager();
        $voiture=$em->getRepository("DonateBundle:ProduitDonation")->find($id);
        $em->remove($voiture);
        $em->flush();
        return $this->redirectToRoute("ListDo");

    }



    public function deleteEAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $voiture=$em->getRepository("EventsBundle:Evenement")->find($id);
        $em->remove($voiture);
        $em->flush();
        return $this->redirectToRoute("ListEv");

    }



    public function approAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $voiture=$em->getRepository("DonateBundle:ProduitDonation")->find($id);

        $user=$em->getRepository("UserBundle:User")->find($voiture->getIdu());
        $userEmail=$user->getEmail();
        $message = \Swift_Message::newInstance()
            ->setSubject("votre donnation")
            ->setFrom("ben.benabdallah.ahmed00@gmail.com")
            ->setTo($userEmail)
            ->setBody("votre donnation a ete accepte");
        $this->get('mailer')->send($message);


        $voiture->setEtat("Accepté");
        $voiture->setAppro(1);

        $em->flush();
        return new JsonResponse($em);

    }



    public function desaproAction($id){
        $em=$this->getDoctrine()->getManager();
        $voiture=$em->getRepository("DonateBundle:ProduitDonation")->find($id);
        $voiture->setEtat("Refusé");
        $voiture->setAppro(0);

        $em->flush();
        return new JsonResponse($em);

    }





    public function updateDoAction(\Symfony\Component\HttpFoundation\Request $request,$id){




        $em=$this->getDoctrine()->getManager();
        $produitD=$em->getRepository(ProduitDonation::class)->find($id);
        $azert=$produitD->getImage();
        $form=$this->createForm(ProduitDonationType::class,$produitD);
        if($form->handleRequest($request)->isValid()) {
            $file = $form['image']->getData();



            $file->move("images/", $file->getClientOriginalName());
            $produitD->setImage("images/".$file->getClientOriginalName());
            $em->persist($produitD);
            $em->flush();
            return $this->redirectToRoute('ListDo');
        }



        return $this->render('@Admin/updateDo.html.twig',array( 'form'=>$form->createView(), 'aze'=>$azert));


    }



    public function updateEvAction(\Symfony\Component\HttpFoundation\Request $request, $id)
    {


        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository('EventsBundle:Evenement')->find($id);
        $azert = $produit->getImage();
        $form = $this->createForm(EvenementType::class, $produit);
        if ($form->handleRequest($request)->isValid()) {
            $file = $form['image']->getData();


            $produit->setDatedeb($request->get('datedeb'));
            $produit->setDatefin($request->get('datefin'));


            $file->move("images/", $file->getClientOriginalName());
            $produit->setImage("images/" . $file->getClientOriginalName());
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('ListEv');
        }

;
        $data = array(

            'form' => $form->createView(),
            'aze' => $azert,
            'modele' => $produit

        );
        return $this->render('@Admin/updateEv.html.twig', $data);


    }





}

