<?php

namespace Sante\SpecialisteBundle\Controller;

use Sante\SpecialisteBundle\Entity\rendezvous;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Config\Definition\Exception\Exception;

class rendezvousController extends Controller
{
    public function listerendezvousmedecinajoutMobileAction($daterendez,$cinmedecin)
    {

        $em=$this->getDoctrine()->getManager();
        $pediatress = $em->getRepository('SanteSpecialisteBundle:rendezvous')->findBy(array('date'=>$daterendez,'cinmedecin'=>$cinmedecin));
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($pediatress);
        return new JsonResponse($formatted);
    }
    public function listerendezvousmedecinminuteajoutMobileAction($daterendez,$cinmedecin,$heure)
    {

        $em=$this->getDoctrine()->getManager();
        $pediatress = $em->getRepository('SanteSpecialisteBundle:rendezvous')->findBy(array('date'=>$daterendez,'cinmedecin'=>$cinmedecin,'heure'=>$heure));
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($pediatress);
        return new JsonResponse($formatted);
    }
    public function listeaffichagemenuMobileAction($daterendez,$cinmedecin,$heure,$minute)
    {

        $em=$this->getDoctrine()->getManager();
        $pediatress = $em->getRepository('SanteSpecialisteBundle:rendezvous')->findBy(array('date'=>$daterendez,'cinmedecin'=>$cinmedecin,'heure'=>$heure,'minute'=>$minute));
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($pediatress);
        return new JsonResponse($formatted);
    }

    public function listerendezvousClientMobileAction($idparent)
    {

        $em=$this->getDoctrine()->getManager();
        $pediatress = $em->getRepository('SanteSpecialisteBundle:rendezvous')->findBy(array('idparent'=>$idparent));
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($pediatress);
        return new JsonResponse($formatted);
    }

    public function listeConfirmatinMedecinMobileAction($cinmedecin)
    {

        $em=$this->getDoctrine()->getManager();
        $pediatress = $em->getRepository('SanteSpecialisteBundle:rendezvous')->findBy(array('cinmedecin'=>$cinmedecin,'presence'=>'non confirmé'));
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($pediatress);
        return new JsonResponse($formatted);
    }
    public function listeVerif1Action($idparent,$nom,$cinmedecin)
    {

        $em=$this->getDoctrine()->getManager();
        $pediatress = $em->getRepository('SanteSpecialisteBundle:rendezvous')->findBy(array('cinmedecin'=>$cinmedecin,'idparent'=>$idparent,'nomenfant'=>$nom));
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($pediatress);
        return new JsonResponse($formatted);
    }


    public function DeleteredezvousmobileAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $rendezvous=$em->getRepository(rendezvous::class)->find($request->get("idrendezvous"));
        $em->remove($rendezvous);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($rendezvous);
        return new JsonResponse($formatted);
    }

    public function ajoutRendezVousAction(Request $request)
    {


        $q = new rendezvous();
        $q->setPresence($request->get("presence"));
        $q->setNomenfant($request->get("nomenfant"));
        $q->setIdparent($request->get("idparent"));
        $q->setCinmedecin($request->get("cinmedecin"));
        $q->setMinute($request->get("minute"));
        $q->setHeure($request->get("heure"));
        $q->setDate($request->get("date"));

        try {
            $em = $this->getDoctrine()->getManager();
            $em->persist($q);
            $em->flush();
            return new JsonResponse(array('info' => 'success'));
        } catch (Exception $e) {
            return new JsonResponse(array('info' => 'error'));
        }

    }

    public function ConfirmerRendezVousmobilemedAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $question=$em->getRepository(rendezvous::class)->find($request->get("id"));
        $question->setPresence("confirmé");


        $em->persist($question);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($question);
        return new JsonResponse($formatted);
    }


    }
