<?php

namespace Sante\SpecialisteBundle\Controller;

use Sante\SpecialisteBundle\Entity\adresseCabinet;
use Sante\SpecialisteBundle\Form\adresseCabinetType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class adresseCabinetController extends Controller
{
    public function ajoutadresseAction(Request $request,$id)
    {
        $a= new adresseCabinet();
        $form = $this->createForm(adresseCabinetType::class,$a);


        if($form->handleRequest($request)->isValid())
        { $a->setId($id);
            $em = $this->getDoctrine()->getManager();
            $em->persist($a); // insert into table
            $em->flush(); //executer
            return $this->redirectToRoute('ajouterinfocontact',array('id' => $id));
        }
        return $this->render('SanteSpecialisteBundle:adresse:ajouteradresse.html.twig', array( 'f'=>$form->createView()  ));


}

    public function modifieradresseCabinetAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $mark=$em->getRepository('SanteSpecialisteBundle:adresseCabinet')->findBy(array('id'=>$id));
        $form=$this->createForm(adresseCabinetType::class,$mark[0]);
        if ($form->handleRequest($request)->isValid())
        {$mark->setId($id);
            $em->persist($mark);
            $em->flush();
            return $this->redirectToRoute('modifierinfocontact',array('id' => $id));
        }


        return $this->render('SanteSpecialisteBundle:adresse:modifieradresse.html.twig', array( 'f'=>$form->createView()  ));
    }




}
