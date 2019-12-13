<?php

namespace BabySitters\BabySittersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BabySittersBabySittersBundle:Default:index.html.twig');
    }
}
