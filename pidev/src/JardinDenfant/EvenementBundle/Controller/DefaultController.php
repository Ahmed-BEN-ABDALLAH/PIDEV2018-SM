<?php

namespace JardinDenfant\EvenementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('JardinDenfantEvenementBundle:Default:index.html.twig');
    }
}
