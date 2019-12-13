<?php

namespace BabySitters\BabySittersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BabysitterController extends Controller
{
    public function ajoutAction()
    {
        return $this->render('BabySittersBabySittersBundle:Babysitter:ajout.html.twig', array(
            // ...
        ));
    }

    public function afficherBAction()
    {
        return $this->render('BabySittersBabySittersBundle:Babysitter:afficher_b.html.twig', array(
            // ...
        ));
    }

    public function modifierAction()
    {
        return $this->render('BabySittersBabySittersBundle:Babysitter:modifier.html.twig', array(
            // ...
        ));
    }

}
