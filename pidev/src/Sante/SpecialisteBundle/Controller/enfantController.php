<?php

namespace Sante\SpecialisteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class enfantController extends Controller
{
    public function listeEnfantparentMobileAction($id)
    {

        $em=$this->getDoctrine()->getManager();
        $pediatress = $em->getRepository('SanteSpecialisteBundle:enfant')->findBy(array('idparent'=>$id));
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($pediatress);
        return new JsonResponse($formatted);
    }
}
