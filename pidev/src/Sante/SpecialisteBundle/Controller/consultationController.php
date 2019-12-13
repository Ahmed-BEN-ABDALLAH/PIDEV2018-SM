<?php

namespace Sante\SpecialisteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class consultationController extends Controller
{


    public function listeConsultationClientMobileAction($idenfant)
    {

        $em=$this->getDoctrine()->getManager();
        $pediatress = $em->getRepository('SanteSpecialisteBundle:consultation')->findBy(array('idenfant'=>$idenfant));
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($pediatress);
        return new JsonResponse($formatted);
    }
}
