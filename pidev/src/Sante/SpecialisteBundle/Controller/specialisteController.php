<?php

namespace Sante\SpecialisteBundle\Controller;

use Sante\SpecialisteBundle\Entity\specialiste;
use Sante\SpecialisteBundle\Form\specialisteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class specialisteController extends Controller
{
    public function ajoutAction(Request $request,$id)
    {

        $specialiste= new specialiste();
           $form = $this->createForm(specialisteType::class,$specialiste);


           if($form->handleRequest($request)->isValid())
           {   $specialiste->setId($id);
               $em = $this->getDoctrine()->getManager();
               $em->persist($specialiste); // insert into table
               $em->flush(); //executer
               return $this->redirectToRoute('ajoutadresseCabinet',array('id' => $id));
           }
           return $this->render('SanteSpecialisteBundle:specialiste:Ajoutspecialiste.html.twig', array( 'f'=>$form->createView()  ));


    }

    public function modifierAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $mark=$em->getRepository('SanteSpecialisteBundle:specialiste')->findBy(array('id'=>$id));
        $form=$this->createForm(specialisteType::class,$mark[0]);
        if ($form->handleRequest($request)->isValid())
        {
            $em->persist($mark);
            $em->flush();
            return $this->redirectToRoute('modifieradresseCabinet',array('id' => $id));
        }


        return $this->render('SanteSpecialisteBundle:specialiste:modifierspecialiste.html.twig', array( 'f'=>$form->createView()  ));
    }




    public function listePediatresAction()
    {

        return $this->render('SanteSpecialisteBundle:specialiste:listeSpecialiste.html.twig', array( ));
    }
    public function listePediatresautAction()
    {

        return $this->render('SanteSpecialisteBundle:specialiste:listeSpecialisteauthentif.html.twig', array( ));
    }
    public function listeOrthophonistesAction()
    {

        return $this->render('SanteSpecialisteBundle:specialiste:listeOrthophonistes.html.twig', array( ));
    }
    public function listeOrthophonistesautAction()
    {

        return $this->render('SanteSpecialisteBundle:specialiste:listeOrthophonistesauthentif.html.twig', array( ));
    }
    public function AfficherForumAction()
    {

        return $this->render('SanteSpecialisteBundle:specialiste:Forum.html.twig', array( ));
    }
    public function AfficherForumautAction()
    {

        return $this->render('SanteSpecialisteBundle:specialiste:Forumauthentif.html.twig', array( ));
    }
    public function layoutsanteauthentifAction()
    {

        return $this->render('SanteSpecialisteBundle:layout:layoutsanteauthentif.html.twig', array( ));
    }
}
