<?php

namespace Enseignant\EnseignantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('EnseignantEnseignantBundle:Default:index.html.twig');
    }
}
