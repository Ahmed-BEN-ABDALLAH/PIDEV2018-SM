<?php

namespace JardinDenfant\ProfilJDBundle\Controller;

use Doctrine\ORM\Mapping\ClassMetadataFactory;
use JardinDenfant\ProfilJDBundle\Entity\UserEvents;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use JardinDenfant\ProfilJDBundle\Entity\Evenement;
class UserEventsController extends Controller
{
    public function nombreInscriMobileAction(Request $request,$ide)
    {
        $em = $this->getDoctrine()->getManager();
        $ide=$request->get('ide');
        /* $locationRepo = $em->getRepository('JardinDenfantProfilJDBundle:UserEvents');
         $nb = $locationRepo->getNb($request->get('ide'));
         return $nb;*/
        //Liste des evenements
        $locationRepo = $em->getRepository('JardinDenfantProfilJDBundle:UserEvents');

            $nb = $locationRepo->getNb($ide);





        return new JsonResponse(array("nombre" => $nb));


    }

    public function inscrirerMobileAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $i = new UserEvents();


        $ide=$request->get('ide');
        $iduser=$request->get('iduser');
        $locationRepo = $em->getRepository('JardinDenfantProfilJDBundle:UserEvents');
        $nb = $locationRepo->getNb($ide);

        $user=$this->getDoctrine()->getRepository('UserBundle:User')->findById($iduser);
        $num=$em->getRepository('JardinDenfantProfilJDBundle:Evenement')->findOneBy(array('ide'=>$ide));
        $i->setUser($user[0]);
        //exit(VarDumper::dump($user[0]));
        $i->setEvenement($num);
        //
        // exit(VarDumper::dump($i));
        $nbr=$num->getNbrPlaceMax();
        if($nb< $nbr)
        {

            $em->persist($i);
            $em->flush();
            $listUeJson = array ();

               $listUeJson[] = array(
                    "id" => $i->getId(),

                    "id_user"=>$i->getUser()->getId(),

                    "ideE"=>$i->getEvenement()->getIde()
                );




            return new JsonResponse(array('listUE' => $listUeJson));




        }
        else { return new JsonResponse(array("res" => 0));}



    }


    public function desinscrireMobileAction(Request $request)
    {


        $em =$this->getDoctrine()->getManager();

        $num=$em->getRepository('JardinDenfantProfilJDBundle:Evenement')->findOneBy(array('ide'=>$request->get('ide')));


       // $user = $this->getUser();
        //$id = $user->getId();
        $useins=$em->getRepository('JardinDenfantProfilJDBundle:UserEvents')->findevents($request->get('iduser'),$request->get('ide'));

        return new JsonResponse(array("res" => 0));









    }



    public function inscrirerAction(Request $request,$ide)
    {


        $i = new UserEvents();
        $user = $this->getUser();
        $id = $user->getId();

        //$i->setId(1);
        $i->setUser($user);
        //$i->setEvenement($ide);
            $em =$this->getDoctrine()->getManager();

        $num=$em->getRepository('JardinDenfantProfilJDBundle:Evenement')->findOneBy(array('ide'=>$ide));
        $i->setEvenement($num);
       // exit(VarDumper::dump($i));
        $nbr=$num->getNbrPlaceMax();
        if($this->nombreInscriAction($ide)< $nbr)
            {
                $em->persist($i); //insert dans la bD
                $em->flush(); //execution
                // return $this->redirectToRoute('_succes');
                return $this->render('JardinDenfantProfilJDBundle:UserEvents:inscrirer.html.twig', array(
                    // ...
                ));
            }
        else if ($this->nombreInscriAction($ide)>= $nbr){
               return $this->render('JardinDenfantProfilJDBundle:UserEvents:erreur.html.twig', array(// ...
                ));
            }


    }


    public function nombreInscriAction($ide)
    {
        $em = $this->getDoctrine()->getManager();
        $locationRepo = $em->getRepository('JardinDenfantProfilJDBundle:UserEvents');
        $nb = $locationRepo->getNb($ide);
        return $nb;
    }


    public function AnnulerInscritAction(Request $request,$ide)
    {
        $em =$this->getDoctrine()->getManager();

        $num=$em->getRepository('JardinDenfantProfilJDBundle:Evenement')->findOneBy(array('ide'=>$ide));


        $user = $this->getUser();
        $id = $user->getId();
        $useins=$em->getRepository('JardinDenfantProfilJDBundle:UserEvents')->findevents($id,$ide);
       // $i = new UserEvents();
        //$i=$useins;
        //exit(VarDumper::dump($i));
       // if($useins!=null){
          //  exit(VarDumper::dump('eeee'));

               // $em->remove($useins); //insert dans la bD
              //  $em->flush(); //execution

           // }
        //$i->setId(1);
        //$i->setUser($user);
        //$i->setEvenement($ide);
        //$i->setEvenement($num);
        // exit(VarDumper::dump($i));
        //
        //$nbr=$num->getNbrPlaceMax();



           // $em->remove($); //insert dans la bD
          //  $em->flush(); //execution
            // return $this->redirectToRoute('_succes');
            return $this->redirectToRoute('afficher_e');


        }

    function verifierClickAction (Request $request,$ide)
    {
        $em =$this->getDoctrine()->getManager();

        $num=$em->getRepository('JardinDenfantProfilJDBundle:Evenement')->findOneBy(array('ide'=>$ide));


        $user = $this->getUser();
        $id = $user->getId();
        $useins=$em->getRepository('JardinDenfantProfilJDBundle:UserEvents')->findevents($id,$ide);
        // $i = new UserEvents();
        //$i=$useins;
        //exit(VarDumper::dump($i));
        if($useins=$id){
            exit(VarDumper::dump('eeee'));}


    }




}
