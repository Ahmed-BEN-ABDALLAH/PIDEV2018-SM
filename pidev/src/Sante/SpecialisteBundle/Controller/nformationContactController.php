<?php

namespace Sante\SpecialisteBundle\Controller;

use Sante\SpecialisteBundle\Entity\nformationContact;
use Sante\SpecialisteBundle\Form\nformationContactType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


use Symfony\Component\Form\Extension\Core\Type\EmailType;

use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



class nformationContactController extends Controller
{
    public function ajouterinfoContactAction(Request $request, $id)
    {
        $a = new nformationContact();
        $form = $this->createForm(nformationContactType::class, $a);
        if ($form->handleRequest($request)->isValid()) {
            $a->setId($id);
            $em = $this->getDoctrine()->getManager();
            $em->persist($a); // insert into table
            $em->flush(); //executer
            return $this->redirectToRoute('ajouterHoraire1', array('id' => $id));
        }
        return $this->render('SanteSpecialisteBundle:infocontact:infocontact.html.twig', array('f' => $form->createView()));


    }

    public function modifierinfoContactAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $mark = $em->getRepository('SanteSpecialisteBundle:nformationContact')->findBy(array('id' => $id));
        $form=$this->createFormBuilder($mark)->add('telephone', NumberType::class)->add('numCabinet',NumberType::class)->add('mail',EmailType::class)->add('suivant',SubmitType::class) ->getForm();

        if ($form->handleRequest($request)->isValid() && $form->handleRequest($request)->isSubmitted() ) {
            $em->persist($mark);
            $em->flush();
            return $this->redirectToRoute('modifierHoraire', array('id' => $id));
        }
        return $this->render('SanteSpecialisteBundle:infocontact:modifieinfocontact.html.twig', array('f' => $form->createView()));

    }
}