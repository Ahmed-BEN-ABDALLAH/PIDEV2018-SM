<?php

namespace Loisirs\LoisirsBundle\Controller;

use Loisirs\LoisirsBundle\Entity\Blague;
use Loisirs\LoisirsBundle\Form\BlagueType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BlagueController extends Controller
{
    public function AjoutBlagueAction(request $request)
    {
        $blague=new Blague();
        if($request->getMethod()=="POST")
        {
            $x=$request->get('titre');
            $y=$request->get('desc');
            $blague->setTitre($x);
            $blague->setDescription($y);

            $m=$this->getDoctrine()->getManager();
            $m->persist($blague);
            $m->flush();


            return $this->redirectToRoute('_affiche_blague');
        }
        return $this->render('LoisirsLoisirsBundle:Blague:ajout_blague.html.twig');
    }
    public function AfficheBlagueAction()
    {
        $m=$this->getDoctrine()->getManager();
        $blague=$m->getRepository('LoisirsLoisirsBundle:Blague')->findAll();

        return $this->render('LoisirsLoisirsBundle:Blague:affiche_blague.html.twig', array('blague'=>$blague));
    }

    public function SupprimerBlagueAction($id)
    {
        $m=$this->getDoctrine()->getManager();
        $blague=$m->getRepository('LoisirsLoisirsBundle:Blague')->find($id);
        $m->remove($blague);
        $m->flush();
        return  $this->redirectToRoute('_affiche_blague');

    }
    public function ModifierBlagueAction($id,Request $request)
    {
        $m=$this->getDoctrine()->getManager();
        $blague=$m->getRepository('LoisirsLoisirsBundle:Blague')->find($id);
        $form=$this->createForm(BlagueType::class,$blague);
        if($form->handleRequest($request)->isValid()) {
            $m->persist($blague);
            $m->flush();
            return $this->redirectToRoute('_affiche_blague');

        }
        return $this->render('LoisirsLoisirsBundle:Blague:modif_blague.html.twig', array('f'=>$form->createView()));


    }
    public function RechercherBlagueAction(Request $request)
    {
        $m=$this->getDoctrine()->getManager();
        $blague=$m->getRepository('LoisirsLoisirsBundle:Blague')->findAll();
        if($request->getMethod()=="POST") {

            $blague = $m->getRepository('LoisirsLoisirsBundle:Blague')->findBlague($request->get('titre'));

            return $this->render('LoisirsLoisirsBundle:Blague:recherche_blague.html.twig', array(
                // ...
                'blague' => $blague
            ));
        }
        return $this->render('LoisirsLoisirsBundle:Blague:recherche_blague.html.twig', array(
            // ...
            'blague'=>$blague
        ));

    }
    public function AfficheClientBlagueAction()
    {
        $m=$this->getDoctrine()->getManager();
        $blague=$m->getRepository('LoisirsLoisirsBundle:Blague')->findAll();

        return $this->render('LoisirsLoisirsBundle:Blague:afficheclient_blague.html.twig', array('blague'=>$blague));
    }

}
