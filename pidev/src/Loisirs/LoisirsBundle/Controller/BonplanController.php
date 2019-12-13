<?php

namespace Loisirs\LoisirsBundle\Controller;

use Loisirs\LoisirsBundle\Entity\Bonplan;
use Loisirs\LoisirsBundle\Form\BonplanType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BonplanController extends Controller
{
    public function AjoutBonplanAction(Request $request)
    {
        $bonplan=new Bonplan();
        if($request->getMethod()=="POST")
        {
            $x=$request->get('categorie');
            $y=$request->get('region');
            $z=$request->get('adresse');
            $bonplan->setCategorie($x);
            $bonplan->setRegion($y);
            $bonplan->setAdresse($z);
            $bonplan->setRating(0);
            $m=$this->getDoctrine()->getManager();
            $m->persist($bonplan);
            $m->flush();


            return $this->redirectToRoute('_affiche_bonplan');
        }
        return $this->render('LoisirsLoisirsBundle:Bonplan:ajout_bonplan.html.twig');
    }

    public function AfficheBonplanAction()
    {
        $m=$this->getDoctrine()->getManager();
        $bonplan=$m->getRepository('LoisirsLoisirsBundle:Bonplan')->findAll();

        return $this->render('LoisirsLoisirsBundle:Bonplan:affiche_bonplan.html.twig', array(
            'bonplan'=>$bonplan
        ));

    }
    public function SupprimerBonplanAction($id)
    {
        $m=$this->getDoctrine()->getManager();
        $bonplan=$m->getRepository('LoisirsLoisirsBundle:Bonplan')->find($id);
        $m->remove($bonplan);
        $m->flush();
        return  $this->redirectToRoute('_affiche_bonplan');

    }
    public function ModifierBonplanAction($id,Request $request)
    {
        $m=$this->getDoctrine()->getManager();
        $bonplan=$m->getRepository('LoisirsLoisirsBundle:Bonplan')->find($id);
        $form=$this->createForm(BonplanType::class,$bonplan);
        if($form->handleRequest($request)->isValid()) {
            $m->persist($bonplan);
            $m->flush();
            return $this->redirectToRoute('_affiche_bonplan');

        }
        return $this->render('LoisirsLoisirsBundle:Bonplan:modif_bonplan.html.twig', array('f'=>$form->createView()));


    }
    public function RechercherBonplanAction(Request $request)
    {
        $m=$this->getDoctrine()->getManager();
        $bon=$m->getRepository('LoisirsLoisirsBundle:Bonplan')->findAll();
        if($request->getMethod()=="POST") {

            $bon = $m->getRepository('LoisirsLoisirsBundle:Bonplan')->findBonplan($request->get('region'));

            return $this->render('LoisirsLoisirsBundle:Bonplan:recherche_bonplan.html.twig', array(
                // ...
                'bonplan' => $bon
            ));
        }
        return $this->render('LoisirsLoisirsBundle:Bonplan:recherche_bonplan.html.twig', array(
            // ...
            'bonplan'=>$bon
        ));

    }

    public function AfficheClientBonplanAction()
    {
        $m=$this->getDoctrine()->getManager();
        $bonplan=$m->getRepository('LoisirsLoisirsBundle:Bonplan')->findAll();

        return $this->render('LoisirsLoisirsBundle:Bonplan:afficheclient_bonplan.html.twig', array(
            'bonplan'=>$bonplan
        ));

    }

}
