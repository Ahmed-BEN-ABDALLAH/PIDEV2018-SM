<?php

namespace Sante\SpecialisteBundle\Controller;

use Sante\SpecialisteBundle\Entity\reponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class reponseController extends Controller
{


    public function ajoutMobileAction(Request $request)
    {

        $em=$this->getDoctrine()->getManager();
        $q = new reponse();
        $q->setReponseText($request->get("reponseText"));
        $q->setIdquestion($request->get("idquestion"));
        $q->setIdparent($request->get("idparent"));
        $q->setIdmedecin($request->get("idmedecin"));
        $em->persist($q); // insert into table
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($q);
        return new JsonResponse($formatted);
    }
    public function listeReponsesMedecinMobileAction($idmedecin)
    {

        $em=$this->getDoctrine()->getManager();
        $pediatress = $em->getRepository('SanteSpecialisteBundle:reponse')->findBy(array('idmedecin'=>$idmedecin));
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($pediatress);
        return new JsonResponse($formatted);
    }
    public function nbreponsemobileAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $question=$em->getRepository(reponse::class)->nbreponse($request->get("idparent"));

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($question);
        return new JsonResponse($formatted);
    }
    public function UpdatereponsemobileAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $question=$em->getRepository(reponse::class)->find($request->get("id"));
        $question->setReponseText($request->get("reponseText"));


        $em->persist($question);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($question);
        return new JsonResponse($formatted);
    }

    public function FindrepquesClientMobileAction($idquestion)
    {

        $em=$this->getDoctrine()->getManager();
        $pediatress = $em->getRepository('SanteSpecialisteBundle:reponse')->findBy(array('idquestion'=>$idquestion));
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($pediatress);
        return new JsonResponse($formatted);
    }
}
