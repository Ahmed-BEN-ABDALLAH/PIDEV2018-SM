<?php

namespace BoutiqueBundle\Controller;

use blackknight467\StarRatingBundle\Form\RatingType;
use BoutiqueBundle\Entity\Commande;
use BoutiqueBundle\Entity\Produit;


use BoutiqueBundle\Entity\rating;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\BarChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class DefaultController extends Controller
{
    public function indexAction(Request $request)

        {

            return $this->render('BoutiqueBundle:Default:index.html.twig');
        }

    public function listProduitAction(Request $request)
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

        $routeName = $request->get('_route');
        $data=array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename'=>$routeName
        );
        return $this->render('BoutiqueBundle:Default:produit_left_sidebar.html.twig',$data);
    }



//    public function produitAction(Request $request)
//    {
//        $session = $request->getSession();
//
//        $authErrorKey = Security::AUTHENTICATION_ERROR;
//        $lastUsernameKey = Security::LAST_USERNAME;
//
//        // get the error if any (works with forward and redirect -- see below)
//        if ($request->attributes->has($authErrorKey)) {
//            $error = $request->attributes->get($authErrorKey);
//        } elseif (null !== $session && $session->has($authErrorKey)) {
//            $error = $session->get($authErrorKey);
//            $session->remove($authErrorKey);
//        } else {
//            $error = null;
//        }
//
//        if (!$error instanceof AuthenticationException) {
//            $error = null; // The value does not come from the security component.
//        }
//
//        // last username entered by the user
//        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);
//
//        $csrfToken = $this->has('security.csrf.token_manager')
//            ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
//            : null;
//
//        $routeName = $request->get('_route');
//        $data=array(
//            'last_username' => $lastUsername,
//            'error' => $error,
//            'csrf_token' => $csrfToken,
//            'routename'=>$routeName
//        );
//
//        return $this->render('BoutiqueBundle:Default:produit.html.twig',$data);
//    }

    public function singleProductAction(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();


        $user=$this->getUser();
        $idc=$user->getId();
        $idp=$id;
        $produit=$em->getRepository('BoutiqueBundle:Produit')->find($id);

        $rating=$em->getRepository('BoutiqueBundle:rating')->findOneBy(array("idc"=>$idc,"idproduit"=>$idp));
        if ($rating==null){
            $rating = new rating();
        }
        $form=$this->createFormBuilder($rating)
            ->add('rating', RatingType::class, [
                'label' => 'Rating'
            ])
            ->add('valider',SubmitType::class, array(
                'attr' => array(

                    'class'=>'btn btn-xs btn-primary'
                )))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
                $rating->setIdc($idc);
                $rating->setNomProduit($produit->getNom());
                $rating->setIdproduit($produit->getId());
                $rating->setIdf($produit->getIdf());
                $em->persist($rating);
                $em->flush();
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
            'f'=>$form->createView(),
            'produit'=>$produit,



        );
        return $this->render('BoutiqueBundle:Default:single_product.html.twig',$data);

    }

    public function categorie_productAction(Request $request)
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

        $routeName = $request->get('_route');
        $data=array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename'=>$routeName
        );
        return $this->render('BoutiqueBundle:Default:product_categories.html.twig',$data);
    }

    public function gestion_boutiqueAction(Request $request)
    {
        $m=$this->getDoctrine()->getManager();
        $user=$this->getUser();
        $iduser=$user->getId();
        $users=$m->getRepository('UserBundle:User')->find($iduser);
        if($users->isValide())
        {

        $cmds=array();

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
        $produits = new Produit();
        $user=$this->getUser();
        $idf=$user->getId();
        $m=$this->getDoctrine()->getManager();
        $produit=$m->getRepository('BoutiqueBundle:Produit')->findByIdf($idf);
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

        $form=$this->createFormBuilder($produits)
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
                //'clasns'=>'form-control border-color-4',
                'choices' => array(

                    'Garçon' => 'Garçon',
                    'Fille' => 'Fille',
                ),
                'attr' => array(
                    'placeholder' => 'Sexe',
                    'class'=>'form-control border-color-3'
                )))

            ->add('age',IntegerType::class, array(
                'attr' => array(
                    'placeholder' => 'Age',
                    'class'=>'form-control border-color-5'
                )))

            ->add('categorie', ChoiceType::class, array(
                'choices' => array(

                    'vêtement' => 'vêtement',
                    'chaussures' => 'chaussures',
                    'sous-vêtements' => 'sous-vêtements',
                    'pijama' => 'pijama',
                    'jouet'=>'jouet',
                    'lits pour enfants' => 'lits pour enfants',
                    'bureau' => 'bureau',
                    'bibliothéque' => 'bibliothéque',
                ),
                'attr' => array(
                    'placeholder' => 'categorie',
                    'class'=>'form-control border-color-4'
                )))

            ->add('description',TextareaType::class, array(
                'attr' => array(
                    'placeholder' => 'Description',
                    'class'=>'form-control border-color-1'
                )) )

            ->add('image', FileType::class,array('attr' => array(
                'class'=>'form-control border-color-6'
                //'class'=>'btn btn-default btn-file'
                )))

            ->add('Ajouter',SubmitType::class, array(
                'attr' => array(

                    'class'=>'btn btn-primary'
                )))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                $rate= new rating();

            $file = $form['image']->getData();

            $file->move("images/", $file->getClientOriginalName());
            $produits->setImage("images/".$file->getClientOriginalName());
            $produits->setIdf($idf);
            $em = $this->getDoctrine()->getManager();
            $em->persist($produits);
            $em->flush();
            $rate->setRating(0);
            $rate->setIdproduit($produits->getId());
            $rate->setNomProduit($produits->getNom());
            $em->persist($rate);
            $em->flush();


            return $this->redirectToRoute('boutique_gestion_boutique');
        }
        $routeName = $request->get('_route');
        ////////////////////////stat//////////////////
        $produit1 = $m->getRepository('BoutiqueBundle:Produit')->findByCategorie('vêtement');
        $cat= array();
        $v=0;
        foreach ($produit1 as $prod)
        {
            if($prod->getIdf()==$idf)
            {
                array_push($cat, $prod);
                $v=$v+1;
            }}
        $produit2 = $m->getRepository('BoutiqueBundle:Produit')->findByCategorie('chaussures');
        $cat2= array();
        $c=0;
        foreach ($produit2 as $prod2)
        {
            if($prod2->getIdf()==$idf)
            {
                array_push($cat2, $prod2);
                $c=$c+1;
            }}
        $produit3 = $m->getRepository('BoutiqueBundle:Produit')->findByCategorie('sous-vêtement');
        $cat3= array();
        $s=0;
        foreach ($produit3 as $prod3)
        {
            if($prod3->getIdf()==$idf)
            {
                array_push($cat3, $prod3);
                $s=$s+1;
            }}
        $produit4 = $m->getRepository('BoutiqueBundle:Produit')->findByCategorie('pijama');
        $cat4= array();
        $p=0;
        foreach ($produit4 as $prod4)
        {

            if($prod4->getIdf()==$idf)
            {  array_push($cat4, $prod4);
                $p=$p+1;
            }}
        $produit5 = $m->getRepository('BoutiqueBundle:Produit')->findByCategorie('jouet');
        $cat5= array();
        $j=0;
        foreach ($produit5 as $prod5)
        {  if($prod5->getIdf()==$idf)
        {
            array_push($cat5, $prod5);
            $j=$j+1;
        }}
        $produit6 = $m->getRepository('BoutiqueBundle:Produit')->findByCategorie('lits pour enfants');
        $cat6= array();
        $l=0;
        foreach ($produit6 as $prod6)
        {
            if($prod6->getIdf()==$idf)
            {
                array_push($cat6, $prod6);
                $l=$l+1;
            }}
        $produit7 = $m->getRepository('BoutiqueBundle:Produit')->findByCategorie('bureau');
        $cat7= array();
        $b=0;
        foreach ($produit7 as $prod7)
        {
            if($prod7->getIdf()==$idf)
            {
                array_push($cat7, $prod7);
                $b=$b+1;
            }}
        $produit8 = $m->getRepository('BoutiqueBundle:Produit')->findByCategorie('bibliothéque');
        $cat8= array();
        $bib=0;
        foreach ($produit8 as $prod8)
        {
            if($prod8->getIdf()==$idf)
            {
                array_push($cat8, $prod8);
                $bib=$bib+1;
            }}
        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable(
            [
                ['categorie','nombre de produit'],
                ['vêtement',$v],
                ['chassures',$c],
                ['sous-vêtement',$s],
                ['Pijamas',$p],
                ['Jouets',$j],
                ['Lit',$l],
                ['bureau',$b],
                ['bibliothéque',$bib],


            ]
        );
        $pieChart->getOptions()->setTitle('Nombre des produits selon les catégories');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);

        $commande1 = $m->getRepository('BoutiqueBundle:Commande')->findhistogramme($idf);
        $nom=array();
        $nbr=array();

        foreach ($commande1 as $comm){

            array_push($nom,$comm["nom"]);
            array_push($nbr,$comm["nbnom"]);
        }

        $bar = new BarChart();
        $bar->getData()->setArrayToDataTable([
            $nom,$nbr        ]);
        $bar->getOptions()->setTitle('Le nombre de chaque produits vendus');
        $bar->getOptions()->getHAxis()->setTitle('nombres des produits');
        $bar->getOptions()->getHAxis()->setMinValue(0);
        $bar->getOptions()->getVAxis()->setTitle('liste des produits');
        $bar->getOptions()->setWidth(650);
        $bar->getOptions()->setHeight(300);



        $data=array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'routename'=>$routeName,
            'produit'=>$produit,
            'commande'=>$cmds,
            'form'=>$form->createView(),'a'=>$commande1,
            'piechart' => $pieChart, 'barchart' => $bar
        );
        //return $this->render('@Boutique/Default/boutique/affiche_produit.html.twig',$data);


        return $this->render('BoutiqueBundle:Default/boutique:gestion_boutique.html.twig',$data);
    }
    else
    {
        return $this->render('@Boutique/Default/error.html.twig');
    }

    }

    function checkIfExist($array,$value){
        foreach ($array  as $key => $v){
            if ($v[0]->getIdc()==$value->getIdc()){
                return $key;
            }
        }
        return -1;
    }


    public function categorieAction(Request $request,$categorie)
    {
        $m=$this->getDoctrine()->getManager();
        $produit=$m->getRepository('BoutiqueBundle:Produit')->findByCategorie($categorie);
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
            'produit'=>$produit
        );
        return $this->render('@Boutique/Default/categories.html.twig',$data);



    }


    public function AllproductMobileAction()
    {
        $m=$this->getDoctrine()->getManager();
        $produit=$m->getRepository('BoutiqueBundle:Produit')->findAll();
        $arrayprod=array();
        foreach ($produit as $prod)
        {
            if($prod->getStock()>0)
            {
                array_push($arrayprod,$prod);
            }
        }
        $a= new Serializer([new ObjectNormalizer()]);
        $formatted =$a->normalize($arrayprod);
        return new JsonResponse($formatted);
    }

    public function mobileCommandeAction(Request $req)
    {
        $m=$this->getDoctrine()->getManager();
        $comande= new Commande();
        $comande->setIdp($req->get('idp'));
        $comande->setNom($req->get('nom'));
        $comande->setQuantite($req->get('quantite'));
        $comande->setStock($req->get('stock'));
        $comande->setPrix($req->get('prix'));
        $comande->setImage($req->get('image'));
        $comande->setIdc($req->get('idc'));
        $m->persist($comande);
        $m->flush();
        $serialize=new Serializer([new ObjectNormalizer()]);
        $a=$serialize->normalize($comande);
        return new JsonResponse($a);

    }

    public function mobilesetratingAction(Request $req)
    {
        $m=$this->getDoctrine()->getManager();

        $rating=$m->getRepository('BoutiqueBundle:rating')->findOneBy(array("idc"=>$req->get('idc'),"idproduit"=>$req->get('idP')));
        if ($rating==null){
            $rating = new rating();
        }


        $rating->setRating($req->get('rating'));
        $rating->setNomProduit($req->get('nomP'));
        $rating->setIdproduit($req->get('idP'));
        $rating->setIdc($req->get('idc'));
        $m->persist($rating);
        $m->flush();
        $serialize=new Serializer([new ObjectNormalizer()]);
        $a=$serialize->normalize($rating);
        return new JsonResponse($a);

    }


    public function quickviewMobileAction($id)
    {
        $m=$this->getDoctrine()->getManager();
        $produit=$m->getRepository('BoutiqueBundle:Produit')->findById($id);
        $a= new Serializer([new ObjectNormalizer()]);
        $formatted =$a->normalize($produit);
        return new JsonResponse($formatted);
        //return new JsonResponse(array("quick" => $formatted ));
    }

    public function updatestockMobileAction(Request $req, $id)
    {
        $m = $this->getDoctrine()->getManager();
        $produit = $m->getRepository('BoutiqueBundle:Produit')->find($id);
        $produit->setStock($produit->getStock()-$req->get('qnt'));

        $m->persist($produit);
        $m->flush();
        $serialize=new Serializer([new ObjectNormalizer()]);
        $a=$serialize->normalize($produit);
        return new JsonResponse($a);

    }
    }
