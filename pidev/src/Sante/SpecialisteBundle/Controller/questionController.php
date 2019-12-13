<?php

namespace Sante\SpecialisteBundle\Controller;

use Doctrine\DBAL\Schema\View;
use Sante\SpecialisteBundle\Entity\question;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Config\Definition\Exception\Exception;

class questionController extends Controller
{

    /*
         * @Method("POST")
         */

    public function ajoutMobileAction(Request $request)
    {

        $em=$this->getDoctrine()->getManager();
        $q = new question();
        $q->setObjet($request->get("objet"));
        $q->setQuestion($request->get("question"));
        $q->setIdparent($request->get("idparent"));
        $q->setIdmedecin($request->get("idmedecin"));
        $q->setVocal($request->get("imagename"));
        $q->setReponse(0);

        try {
            $em = $this->getDoctrine()->getManager();
            $em->persist($q);
            $em->flush();
            return new JsonResponse(array('info' => 'success'));
        } catch (Exception $e) {
            return new JsonResponse(array('info' => 'error'));
        }
    }

    /*
    * @Method("POST")
    */
    public function uploadImageAction(Request $request)
    {
        $imagename = $request->request->get('imagename');
        $Imagecode = $request->request->get('image');

        define('UPLOAD_DIR', 'C:/xampp/htdocs/pidev/web/questions/');
        $img = base64_decode($Imagecode);
        $uid = uniqid();
        $file = UPLOAD_DIR . $imagename . '.jpg';
        $success = file_put_contents($file, $img);
        if ($success) {
            return new JsonResponse(array('info' => $imagename .'.jpg'));
        } else {
            return new JsonResponse(array('info' => 'erreur'));
        }
    }

    public function listeQuestionsClientMobileAction($idparent)
    {

        $em=$this->getDoctrine()->getManager();
        $pediatress = $em->getRepository('SanteSpecialisteBundle:question')->findBy(array('idparent'=>$idparent));
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($pediatress);
        return new JsonResponse($formatted);
    }



    public function listeQuestionsMedecinMobileAction($idmedecin)
    {
       $em=$this->getDoctrine()->getManager();
        $pediatress = $em->getRepository('SanteSpecialisteBundle:question')->findBy(array('idmedecin'=>$idmedecin,'reponse'=>0));
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($pediatress);
        return new JsonResponse($formatted);
    }

    public function findquestbyIdAction($id)
    {

        $em=$this->getDoctrine()->getManager();
        $pediatress = $em->getRepository('SanteSpecialisteBundle:question')->findBy(array('id'=>$id));
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($pediatress);
        return new JsonResponse($formatted);
    }
    public function UpdatequestionmobileAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $question=$em->getRepository(question::class)->find($request->get("id"));
        $question->setObjet($request->get("objet"));
        $question->setQuestion($request->get("question"));

        $em->persist($question);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($question);
        return new JsonResponse($formatted);
    }

    public function nbquestionmobileAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $question=$em->getRepository(question::class)->nbquestion($request->get("idparent"));

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($question);
        return new JsonResponse($formatted);
    }

    public function DeletequestionmobileAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $rendezvous=$em->getRepository(question::class)->find($request->get("id"));
        $em->remove($rendezvous);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($rendezvous);
        return new JsonResponse($formatted);
    }
    public function repondrequestionmobileAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $question=$em->getRepository(question::class)->find($request->get("id"));
        $question->setReponse(1);


        $em->persist($question);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($question);
        return new JsonResponse($formatted);
    }
}
