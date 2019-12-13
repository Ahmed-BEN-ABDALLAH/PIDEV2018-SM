<?php

namespace Loisirs\LoisirsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('LoisirsLoisirsBundle:Default:index.html.twig');
    }
}
