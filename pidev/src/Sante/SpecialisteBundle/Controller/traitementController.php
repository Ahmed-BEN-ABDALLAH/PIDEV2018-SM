<?php

namespace Sante\SpecialisteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class traitementController extends Controller
{
    public function listeTraitementClientMobileAction($idconsultation)
    {

        $em=$this->getDoctrine()->getManager();
        $pediatress = $em->getRepository('SanteSpecialisteBundle:traitement')->findBy(array('idconsultation'=>$idconsultation));
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($pediatress);
        return new JsonResponse($formatted);
    }
}
