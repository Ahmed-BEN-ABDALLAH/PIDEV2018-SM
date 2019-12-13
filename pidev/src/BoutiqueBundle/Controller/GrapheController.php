<?php

namespace BoutiqueBundle\Controller;

use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Histogram;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\BarChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BoutiqueBundle\Entity\Produit;


class GrapheController extends Controller
{
        public function chartLineAction()
        {
            $m = $this->getDoctrine()->getManager();
            $user=$this->getUser();
            $idf=$user->getId();
            $commande = $m->getRepository('BoutiqueBundle:Commande')->findhistogramme($idf);
            $nom=array();
            $nbr=array();

            foreach ($commande as $comm){

                array_push($nom,$comm["nom"]);
                array_push($nbr,$comm["1"]);
            }

            $bar = new BarChart();
            $bar->getData()->setArrayToDataTable([
                $nom,$nbr        ]);
            $bar->getOptions()->setTitle('Le nombre de chaque produits vendus');
            $bar->getOptions()->getHAxis()->setTitle('nobres des produits');
            $bar->getOptions()->getHAxis()->setMinValue(0);
            $bar->getOptions()->getVAxis()->setTitle('liste des produits');
            $bar->getOptions()->setWidth(900);
            $bar->getOptions()->setHeight(300);
            return $this->render('@Boutique/Default/boutique/stat.html.twig', array('barchart' => $bar,'aa'=>$commande));

    }


    public function PieChartAction()
    {
        $m = $this->getDoctrine()->getManager();
        $user=$this->getUser();
        $idf=$user->getId();
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

        $commande = $m->getRepository('BoutiqueBundle:Commande')->findhistogramme($idf);
        $nom=array();
        $nbr=array();

        foreach ($commande as $comm){

            array_push($nom,$comm["nom"]);
            array_push($nbr,$comm["1"]);
        }

        $bar = new BarChart();
        $bar->getData()->setArrayToDataTable([
            $nom,$nbr        ]);
        $bar->getOptions()->setTitle('Le nombre de chaque produits vendus');
        $bar->getOptions()->getHAxis()->setTitle('nobres des produits');
        $bar->getOptions()->getHAxis()->setMinValue(0);
        $bar->getOptions()->getVAxis()->setTitle('liste des produits');
        $bar->getOptions()->setWidth(900);
        $bar->getOptions()->setHeight(300);


        return $this->render('@Boutique/Default/boutique/piechart.html.twig', array('piechart' => $pieChart, 'barchart' => $bar));
    }

    public function histogrammeAction()
    {
        $m = $this->getDoctrine()->getManager();
        $user=$this->getUser();
        $idf=$user->getId();
        $tab=array();
        $commande = $m->getRepository('BoutiqueBundle:Commande')->findhistogramme();
       // $commande2 = $m->getRepository('BoutiqueBundle:Commande')->find2histogramme();

        $nom=array();
        $nbr=array();



        foreach ($commande as $comm){
            array_push($nom,$comm["nom"]);
            array_push($nbr,$comm["1"]);
        }






        $bar = new BarChart();
        $bar->getData()->setArrayToDataTable([
            ['nom du produit', 'le plus vendu'],
            [$nom, $nbr ]

        ]);
        $bar->getOptions()->setTitle('Population of Largest U.S. Cities');
        $bar->getOptions()->getHAxis()->setTitle('Population of Largest U.S. Cities');
        $bar->getOptions()->getHAxis()->setMinValue(0);
        $bar->getOptions()->getVAxis()->setTitle('City');
        $bar->getOptions()->setWidth(900);
        $bar->getOptions()->setHeight(500);
        return $this->render('@Boutique/Default/boutique/histogramme.html.twig', array('histo' => $bar,'aa'=>$commande,'nom'=>$nom,'nbr'=>$nbr));
    }


}
