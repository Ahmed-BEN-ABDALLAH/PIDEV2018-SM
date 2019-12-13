<?php

namespace Sante\articleBundle\Controller;

use Sante\articleBundle\Entity\article;
use Sante\articleBundle\Entity\likearticle;
use Sante\articleBundle\Entity\notifarticle;
use Sante\articleBundle\Form\article2Type;
use Sante\articleBundle\Form\articleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Tests\Fixtures\ToString;
use Symfony\Component\Templating\PhpEngine;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;

class rticleController extends Controller
{

}
