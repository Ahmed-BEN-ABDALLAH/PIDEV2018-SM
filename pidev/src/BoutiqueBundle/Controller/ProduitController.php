<?php

namespace BoutiqueBundle\Controller;

use blackknight467\StarRatingBundle\Form\RatingType;
use BoutiqueBundle\Entity\Commande;
use BoutiqueBundle\Entity\Produit;
use BoutiqueBundle\Entity\rating;
use BoutiqueBundle\Form\ProduitType;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;



class ProduitController extends Controller
{
    public  function Ajouter_produitAction(Request $request)
    {
        $produit = new Produit();
        $session = $request->getSession();
        $user=$this->getUser();


        $idf=$user->getId();
        $authErrorKey = Security::AUTHENTICATION_ERROR;
        $lastUsernameKey = Security::LAST_USERNAME;

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

        $csrfToken = $this->has('security.csrf.token_manager')
            ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
            : null;

        $routeName = $request->get('_route');



        $form=$this->createFormBuilder($produit)
            ->add('nom', \Symfony\Component\Form\Extension\Core\Type\TextType::class, array(
                'attr' => array(
                    'placeholder' => 'nom du produit',
                    'class'=>'form-control border-color-1'
                )))
            ->add('stock',IntegerType::class, array(
                'attr' => array(
                    'placeholder' => 'Stock',
                    'class'=>'form-control border-color-2'
                )) )
            ->add('prix',IntegerType::class, array(
                'attr' => array(
                    'placeholder' => 'Prix',
                    'class'=>'form-control border-color-4'
                )))
            ->add('genre', ChoiceType::class, array(
                //'class'=>'form-control border-color-4',
                'choices' => array(

                    'Garçon' => 'Garçon',
                    'Fille' => 'Fille',
                ),
                'attr' => array(
                    'placeholder' => 'Sexe',
                    'class'=>'form-control border-color-3'
                )))

            ->add('categorie', ChoiceType::class, array(
                'choices' => array(

                    'vêtement' => 'vêtement',
                    'chaussures' => 'chaussures',
                    'sous-vêtements' => 'sous-vêtements',
                    'pijama' => 'pijama',
                    'lits pour enfants' => 'lits pour enfants',
                    'bureau' => 'bureau',
                    'bibliothéque' => 'bibliothéque',
                ),
                'attr' => array(
                    'placeholder' => 'categorie',
                    'class'=>'form-control border-color-4'
                )))

            ->add('age',IntegerType::class, array(
                'attr' => array(
                    'placeholder' => 'Age',
                    'class'=>'form-control border-color-5'
                )))
            ->add('description',TextareaType::class, array(
                'attr' => array(
                    'placeholder' => 'Description',
                    'class'=>'form-control border-color-1'
                )) )

            ->add('image', FileType::class,array('data_class' => null))
            ->add('Ajouter',SubmitType::class, array(
                'attr' => array(

                    'class'=>'btn btn-primary'
                )))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $file = $form['image']->getData();

            $file->move("images/", $file->getClientOriginalName());
            $produit->setImage("images/".$file->getClientOriginalName());
            $produit->setIdf($idf);
            $em = $this->getDoctrine()->getManager();

            $em->persist($produit);
            $em->flush();


            return $this->redirectToRoute('boutique_afficher_produit');
        }
        $data=array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename'=>$routeName,
            'form'=>$form->createView()
        );

        return $this->render('modifer_produit.html.twig'
            ,$data);
    }
///////////////////////////admin////////////////////////////////
    public function fournisseurAction(Request $request)
    {
        $m=$this->getDoctrine()->getManager();
        $users=$m->getRepository('UserBundle:User')->findAll();
        $array=array();
        foreach ($users as $user)
        {
            if($user->hasRole('ROLE_VENDEUR'))
            {
                array_push($array,$user );
            }
        }
        $session = $request->getSession();

        $lastUsernameKey = Security::LAST_USERNAME;
        $authErrorKey = Security::AUTHENTICATION_ERROR;

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

        $csrfToken = $this->has('security.csrf.token_manager')
            ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
            : null;

        $routeName = $request->get('_route');
        $data = array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename' => $routeName,
            'user'=>$array
        );
        return $this->render('@Boutique/Default/fournisseur.html.twig',$data);
    }


    public function AfficheP_AdminAction(Request $request)
    {
        $m=$this->getDoctrine()->getManager();

        $produit=$m->getRepository('BoutiqueBundle:Produit')->findAll();

        $session = $request->getSession();

        $lastUsernameKey = Security::LAST_USERNAME;
        $authErrorKey = Security::AUTHENTICATION_ERROR;

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

        $csrfToken = $this->has('security.csrf.token_manager')
            ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
            : null;

        $routeName = $request->get('_route');
        $data = array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename' => $routeName,
            'produit'=>$produit
        );
        return $this->render('@Boutique/Default/administrateur.html.twig',$data);
    }
    public function Supprimer_adminAction($id)
    {

        $m = $this->getDoctrine()->getManager();
        $produit = $m->getRepository('BoutiqueBundle:Produit')->find($id);
        $m->remove($produit);
        $m->flush();
        return $this->redirectToRoute('AfficheP_Admin');
    }
    public function ValueFournisseurAction($id)
    {
        $m = $this->getDoctrine()->getManager();
        $user=$m->getRepository('UserBundle:User')->find($id);
        $user->setValide(1);
        $m->persist($user);
        $m->flush();
        return $this->redirectToRoute('fournisseur');
    }
    public function desaprouverFournisseurAction($id)
    {
        $m = $this->getDoctrine()->getManager();
        $user=$m->getRepository('UserBundle:User')->find($id);
        $user->setValide(0);
        $m->persist($user);
        $m->flush();
        return $this->redirectToRoute('fournisseur');
    }

    public function AjoutCommandeAction(Request $request)
    {    $em=$this->getDoctrine()->getManager();
        $user=$this->getUser();
        $iduser=$user->getId();
        $nomuser=$user->getUsername();
        $adresse=$user->getAdresse();
        $rue=$user->getAdresseRue();
        $session = $request->getSession();
        $produitsArray=array();
        $table = array();
        $cartProductsId=$session->get("produits");
        $m = $this->getDoctrine()->getManager();
        $prixtotal=0;
        if($cartProductsId!=null)
        {
            foreach($cartProductsId as $idproduit)
            {
                array_push($produitsArray,$m->getRepository('BoutiqueBundle:Produit')->find($idproduit));
            }

            foreach($produitsArray as $tab)
            {

                if (in_array($tab, $table)){

                    $tab->setQuantite($tab->getQuantite()+1);
                }

                else{

                    array_push($table,$tab);
                    $tab->setQuantite($tab->getQuantite()+1);

                }

            }
            foreach($table as $total)
            {        $commande=new Commande();

                $commande->setIdp($total->getId());
                $commande->setNom($total->getNom());
                $commande->setAge($total->getAge());
                $commande->setPrix($total->getPrix());
                $commande->setNomc($nomuser);
                $commande->setStock($total->getStock());
                $commande->setImage($total->getImage());
                $commande->setGenre($total->getGenre());
                $commande->setDescription($total->getDescription());
                $commande->setIdf($total->getIdf());
                $commande->setQuantite($total->getQuantite());
                $commande->setIdc($iduser);

                $prixtotal=$prixtotal+($total->getQuantite()*$total->getPrix());
                $total->setQuantite(0);


                $em->persist($commande);



            }
            $em->flush();
        }

        $session = $request->getSession();

        $lastUsernameKey = Security::LAST_USERNAME;
        $authErrorKey = Security::AUTHENTICATION_ERROR;

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

        $csrfToken = $this->has('security.csrf.token_manager')
            ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
            : null;

        $routeName = $request->get('_route');
        $session->set("produits",null);
        $data = array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename' => $routeName,
            'iduser'=>$iduser,
            'nomuser'=>$nomuser,
            'adresse'=>$adresse,
            'rue'=>$rue,
            'prixtotal' =>$prixtotal,


        );
        return $this->render('@Boutique/Default/boutique/livraison.html.twig', $data);


    }

    public function SupprimerAction($id)
    {

        $m = $this->getDoctrine()->getManager();
        $produit = $m->getRepository('BoutiqueBundle:Produit')->find($id);
        $m->remove($produit);
        $m->flush();
        return $this->redirectToRoute('boutique_gestion_boutique');
    }

    public function modifierAction(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $produit=$em->getRepository('BoutiqueBundle:Produit')->find($id);
        $form=$this->createForm(ProduitType::class,$produit);
        if($form->handleRequest($request)->isValid()) {
            $file = $form['image']->getData();

            $file->move("images/", $file->getClientOriginalName());
            $produit->setImage("images/".$file->getClientOriginalName());
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('boutique_gestion_boutique');
        }
        $session = $request->getSession();

        $authErrorKey = Security::AUTHENTICATION_ERROR;
        $lastUsernameKey = Security::LAST_USERNAME;

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

        $csrfToken = $this->has('security.csrf.token_manager')
            ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
            : null;

        $routeName = $request->get('_route');
        $data=array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename'=>$routeName,
            'form'=>$form->createView()
        );
        return $this->render('@Boutique/Default/boutique/modifer_produit.html.twig',$data);
    }

    public function AffichageAction(Request $request)
    {
        $m = $this->getDoctrine()->getManager();
        $listproduit = $m->getRepository('BoutiqueBundle:Produit')->findAll();
        $rating = $m->getRepository('BoutiqueBundle:rating')->avgrating();
        $r= new rating();
        $session = $request->getSession();

        //knpPaginator
        $produit  = $this->get('knp_paginator')->paginate(
            $listproduit, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            6/*limit per page*/
        );

        $produitsArray=array();
        $table = array();
        $cartProductsId=$session->get("produits");
        $m = $this->getDoctrine()->getManager();



        $prixtotal=0;
        if($cartProductsId!=null)
        {
            foreach($cartProductsId as $idproduit)
            {
                array_push($produitsArray,$m->getRepository('BoutiqueBundle:Produit')->find($idproduit));
            }

            foreach($produitsArray as $tab)
            {

                if (in_array($tab, $table)){

                    $tab->setQuantite($tab->getQuantite()+1);
                }

                else{

                    array_push($table,$tab);
                    $tab->setQuantite($tab->getQuantite()+1);

                }

            }
            if($table==null)
            {
//                array_push($table,null);

            }
            else{



            foreach($table as $total)
            {
                $prixtotal=$prixtotal+($total->getQuantite()*$total->getPrix());
            }
            }

        }
///////////////////////////////////
        $session = $request->getSession();

        $lastUsernameKey = Security::LAST_USERNAME;
        $authErrorKey = Security::AUTHENTICATION_ERROR;

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

        $csrfToken = $this->has('security.csrf.token_manager')
            ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
            : null;




        $routeName = $request->get('_route');
        $data = array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename' => $routeName,
            'produit' => $produit,
            'produita' => $table,
            'prixtotal' =>$prixtotal,
            'rating'=>$rating,
            'r'=>$r,
            'firas'=>$listproduit
        );
        $em=$this->getDoctrine()->getManager();


        return $this->render('@Boutique/Default/produit.html.twig', $data);

    }

    public function AddCartAction(Request $request)
    {

        $session = $request->getSession();
        $produitsArray=array();
        $table = array();
        $cartProductsId=$session->get("produits");
        $m = $this->getDoctrine()->getManager();
        $prixtotal=0;
        if($cartProductsId!=null)
        {
        foreach($cartProductsId as $idproduit)
        {
            array_push($produitsArray,$m->getRepository('BoutiqueBundle:Produit')->find($idproduit));
        }

        foreach($produitsArray as $tab)
        {

           if (in_array($tab, $table)){

               $tab->setQuantite($tab->getQuantite()+1);
           }

           else{

               array_push($table,$tab);
               $tab->setQuantite($tab->getQuantite()+1);

           }

        }
        foreach($table as $total)
        {
            $prixtotal=$prixtotal+($total->getQuantite()*$total->getPrix());
        }

        }

        $session = $request->getSession();

        $lastUsernameKey = Security::LAST_USERNAME;
        $authErrorKey = Security::AUTHENTICATION_ERROR;

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

        $csrfToken = $this->has('security.csrf.token_manager')
            ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
            : null;

        $routeName = $request->get('_route');
        $data = array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename' => $routeName,
            'produits' => $table,
            'prixtotal' =>$prixtotal
        );
        return $this->render('@Boutique/Default/cart_page.html.twig', $data);

    }

    public function setProduitToSessionAction($id ,Request $request){
        $session = $request->getSession();
        $sessionArray=$session->get("produits");
        if($sessionArray==null){
            $sessionArray = array();
            $session->set("produits",$sessionArray);

        }    array_push($sessionArray,$id);
        $session->set("produits",$sessionArray);
        return new JsonResponse(array('data' =>$array=$session->get("produits")));




    //return new JsonResponse(array('data' =>$array=$session->get("produits")));
    }

    public function voir_commandeAction(Request $request)
    {
        $cmds=array();
        $m=$this->getDoctrine()->getManager();
        $user=$this->getUser();
        $iduser=$user->getId();
        $commande=$m->getRepository('BoutiqueBundle:Commande')->findByIdf($iduser);

        if(!(empty($commande)||is_null($commande))){
            $cmds[0]=array();
            array_push($cmds[0],$commande[0]);
            for ($x = 1; $x < count($commande); $x++) {

                $pos=$this->checkIfExist($cmds,$commande[$x]);
                if($pos==-1){
                    array_push($cmds,array($commande[$x])) ;
                }else{
                    array_push($cmds[$pos],$commande[$x]);
                }
            }
        }



        $session = $request->getSession();

        $lastUsernameKey = Security::LAST_USERNAME;
        $authErrorKey = Security::AUTHENTICATION_ERROR;

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

        $csrfToken = $this->has('security.csrf.token_manager')
            ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
            : null;

        $routeName = $request->get('_route');
        $data = array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename' => $routeName,
            'commande'=>$cmds
        );
        return $this->render('@Boutique/Default/boutique/Voir_commande.html.twig',$data);
    }

    public function getallProduitFromSessionAction(Request $request){
        $session = $request->getSession();
        $array=$session->get("produits");
        $response = new JsonResponse(array('data' => $array));
        return $response;
    }

    function checkIfExist($array,$value){
        foreach ($array  as $key => $v){
           if ($v[0]->getIdc()==$value->getIdc()){
            return $key;
           }
        }
        return -1;
    }

    public function AddPanierAction(Request $request)
    {
        $session = $request->getSession();

        $produitsArray=array();
        $table = array();
        $cartProductsId=$session->get("produits");
        $m = $this->getDoctrine()->getManager();
        $prixtotal=0;
        if($cartProductsId==null){
            $cartProductsId = array();
            $session->set("produits",$cartProductsId);

        }
        if($cartProductsId!=null)
        {
            foreach($cartProductsId as $idproduit)
            {
                array_push($produitsArray,$m->getRepository('BoutiqueBundle:Produit')->find($idproduit));
            }

            foreach($produitsArray as $tab)
            {

                if (in_array($tab, $table)){

                    $tab->setQuantite($tab->getQuantite()+1);
                }

                else{

                    array_push($table,$tab);
                    $tab->setQuantite($tab->getQuantite()+1);

                }

            }


        }
        else{
            array_push($table,null);
        }

//        if($table==null)
//        {
//            array_push($table,null);
//
//        }
//        else{
//            foreach($table as $total)
//            {
//                $prixtotal=$prixtotal+($total->getQuantite()*$total->getPrix());
//            }
//        }


        $session = $request->getSession();

        $lastUsernameKey = Security::LAST_USERNAME;
        $authErrorKey = Security::AUTHENTICATION_ERROR;

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

        $csrfToken = $this->has('security.csrf.token_manager')
            ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
            : null;

        $routeName = $request->get('_route');
        $data = array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename' => $routeName,
            'produita' => $table,
            'prixtotal' =>$prixtotal
        );
        return $this->render('template.html.twig', $data);

    }

    public function pdfAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $user=$this->getUser();
        $iduser=$user->getId();
        $session = $request->getSession();
        $produitsArray=array();
        $table = array();
        $cartProductsId=$session->get("produits");
        $m = $this->getDoctrine()->getManager();
        $prixtotal=0;
        if($cartProductsId!=null)
        {
            foreach($cartProductsId as $idproduit)
            {
                array_push($produitsArray,$m->getRepository('BoutiqueBundle:Produit')->find($idproduit));
            }

            foreach($produitsArray as $tab)
            {

                if (in_array($tab, $table)){

                    $tab->setQuantite($tab->getQuantite()+1);
                }

                else{

                    array_push($table,$tab);
                    $tab->setQuantite($tab->getQuantite()+1);

                }

            }


        }

        $data = array(
            'iduser'=>$iduser,
            'prixtotal' =>$prixtotal,


        );

        $snappy = $this->get('knp_snappy.pdf');


        $html = $this->renderView('@Boutique/Default/boutique/pdf.html.twig', $data);

        $filename = 'MonFacturePDF';

        return new Response(
            $snappy->getOutputFromHtml($html),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '.pdf"'
            )
        );
    }

    public function getProductByIdAction($id){
        $em=$this->getDoctrine()->getManager();
        $encoders = array(new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $product=$em->getRepository(Produit::class)->find($id);

        $response = new Response($serializer->serialize($product, 'json'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }

    public function delsessionAction(Request $request,$id,$quantite){
        $i=0;
        $quantite=$quantite+1;
        $j=0;
        $session = $request->getSession();
        $cartProductsId=$session->get("produits");
        foreach ($cartProductsId as $a)
        {

            if($a==$id)
            {

                $i=$i+1;
                if($i==$quantite)
                {
                    unset($cartProductsId[$j]);
                }
            }
            $j=$j+1;
        }
        $a=array_values($cartProductsId);
        $session->set("produits",$a);

        return new JsonResponse(array('data' =>$array=$session->get("produits")));
    }

    public function deletefromcartAction(Request $request,$id)
    {
        $j=0;
        //$array1=array();
        $session = $request->getSession();
        $cartProductsId=$session->get("produits");
        foreach ($cartProductsId as $del)
        {

            if($del == $id)
            {
                unset($cartProductsId[$j]);
            }

            $j=$j+1;

        }
        //$array1=array_values($cartProductsId);
        $session->set("produits",array_values($cartProductsId));
        return new JsonResponse(array('data' =>$array=$session->get("produits")));
    }

    public function validercommandeAction($id){

        $m = $this->getDoctrine()->getManager();
        $produit = $m->getRepository('BoutiqueBundle:Commande')->find($id);
        $produit->setEtat(1);
        $m->persist($produit);
        $m->flush();
        return $this->redirectToRoute('boutique_gestion_boutique');
    }



    public function recherchepAjaxAction(Request $request)
    {


        $session = $request->getSession();

        $authErrorKey = Security::AUTHENTICATION_ERROR;
        $lastUsernameKey = Security::LAST_USERNAME;

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

        $csrfToken = $this->has('security.csrf.token_manager')
            ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
            : null;
        $rating = $this->getDoctrine()->getManager()->getRepository('BoutiqueBundle:rating')->avgrating();
        $r= new rating();
        $routeName = $request->get('_route');






        $em=$this->getDoctrine()->getManager();

        if ($request->isXmlHttpRequest()) {
            $search  = $request->get('search');
            dump($search);

            $repo  = $em->getRepository('BoutiqueBundle:Produit');
            $event = $repo->findAjax($search);
            $data = array(
                'last_username' => $lastUsername,
                'error' => $error,
                'csrf_token' => $csrfToken,
                'routename' => $routeName,
                'r'=>$r,
                'rating'=>$rating,
                'produit' => $event,

            );

            return $this->render('@Boutique/Default/component.html.twig', $data);
        }
        return $this->render('@Boutique/Default/component.html.twig', array());


    }

}
