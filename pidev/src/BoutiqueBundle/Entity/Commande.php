<?php

namespace BoutiqueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 *
 * @ORM\Table(name="commande")
 * @ORM\Entity(repositoryClass="BoutiqueBundle\Repository\CommandeRepository")
 */
class Commande
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var int
     *
     * @ORM\Column(name="etat", type="integer",nullable=true)
     */

    private $etat='0';

    /**
     * @var int
     *
     * @ORM\Column(name="idp", type="integer",nullable=true)
     */

    private $idp;

    /**
     * @var int
     *
     * @ORM\Column(name="idc", type="integer",nullable=true)
     */
    private $idc;


    /**
     * @var string
     *
     * @ORM\Column(name="nomc", type="string", length=255,nullable=true)
     */
    private $nomc;


    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255,nullable=true)
     */
    private $nom;

    /**
     * @var int
     *
     * @ORM\Column(name="prix", type="integer",nullable=true)
     */
    private $prix;

    /**
     * @var int
     *
     * @ORM\Column(name="idf", type="integer",nullable=true)
     */
    private $idf;

    /**
     * @var int
     *
     * @ORM\Column(name="stock", type="integer",nullable=true)
     */
    private $stock;

    /**
     * @var int
     *
     * @ORM\Column(name="quantite", type="integer",nullable=true)
     */
    private $quantite;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=5000,nullable=true)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255,nullable=true)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="age", type="integer",nullable=true)
     */
    private $age;

    /**
     * @var string
     *
     * @ORM\Column(name="genre", type="string", length=255,nullable=true)
     */
    private $genre;



    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idp
     *
     * @param integer $idp
     *
     * @return Commande
     */
    public function setIdp($idp)
    {
        $this->idp = $idp;

        return $this;
    }

    /**
     * Get idp
     *
     * @return integer
     */
    public function getIdp()
    {
        return $this->idp;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Commande
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prix
     *
     * @param integer $prix
     *
     * @return Commande
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return integer
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set idf
     *
     * @param integer $idf
     *
     * @return Commande
     */
    public function setIdf($idf)
    {
        $this->idf = $idf;

        return $this;
    }

    /**
     * Get idf
     *
     * @return integer
     */
    public function getIdf()
    {
        return $this->idf;
    }

    /**
     * Set stock
     *
     * @param integer $stock
     *
     * @return Commande
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return integer
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Set quantite
     *
     * @param integer $quantite
     *
     * @return Commande
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return integer
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Commande
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Commande
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set idc
     *
     * @param integer $idc
     *
     * @return Commande
     */
    public function setIdc($idc)
    {
        $this->idc = $idc;

        return $this;
    }

    /**
     * Get idc
     *
     * @return integer
     */
    public function getIdc()
    {
        return $this->idc;
    }

    /**
     * Set age
     *
     * @param integer $age
     *
     * @return Commande
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return integer
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set genre
     *
     * @param string $genre
     *
     * @return Commande
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get genre
     *
     * @return string
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Set nomc.
     *
     * @param string $nomc
     *
     * @return Commande
     */
    public function setNomc($nomc)
    {
        $this->nomc = $nomc;

        return $this;
    }

    /**
     * Get nomc.
     *
     * @return string
     */
    public function getNomc()
    {
        return $this->nomc;
    }

    /**
     * Set etat.
     *
     * @param int $etat
     *
     * @return Commande
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat.
     *
     * @return int
     */
    public function getEtat()
    {
        return $this->etat;
    }
}
