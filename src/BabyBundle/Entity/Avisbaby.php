<?php

namespace BabyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Avisbaby
 *
 * @ORM\Table(name="avisbaby")
 * @ORM\Entity(repositoryClass="BabyBundle\Repository\AvisbabyRepository")
 */
class Avisbaby
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
     * @var int|null
     *
     * @ORM\Column(name="idu", type="integer", nullable=true)
     */
    private $idu;

    /**
     * @var int|null
     *
     * @ORM\Column(name="idb", type="integer", nullable=true)
     */
    private $idb;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nbrdislike", type="integer", nullable=true)
     */
    private $nbrdislike;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nbrlike", type="integer", nullable=true)
     */
    private $nbrlike;

    /**
     * @var int|null
     *
     * @ORM\Column(name="moyenne", type="integer", nullable=true)
     */
    private $moyenne;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idu.
     *
     * @param int|null $idu
     *
     * @return Avisbaby
     */
    public function setIdu($idu = null)
    {
        $this->idu = $idu;

        return $this;
    }

    /**
     * Get idu.
     *
     * @return int|null
     */
    public function getIdu()
    {
        return $this->idu;
    }

    /**
     * Set idb.
     *
     * @param int|null $idb
     *
     * @return Avisbaby
     */
    public function setIdb($idb = null)
    {
        $this->idb = $idb;

        return $this;
    }

    /**
     * Get idb.
     *
     * @return int|null
     */
    public function getIdb()
    {
        return $this->idb;
    }

    /**
     * Set nbrdislike.
     *
     * @param int|null $nbrdislike
     *
     * @return Avisbaby
     */
    public function setNbrdislike($nbrdislike = null)
    {
        $this->nbrdislike = $nbrdislike;

        return $this;
    }

    /**
     * Get nbrdislike.
     *
     * @return int|null
     */
    public function getNbrdislike()
    {
        return $this->nbrdislike;
    }

    /**
     * Set nbrlike.
     *
     * @param int|null $nbrlike
     *
     * @return Avisbaby
     */
    public function setNbrlike($nbrlike = null)
    {
        $this->nbrlike = $nbrlike;

        return $this;
    }

    /**
     * Get nbrlike.
     *
     * @return int|null
     */
    public function getNbrlike()
    {
        return $this->nbrlike;
    }

    /**
     * Set moyenne.
     *
     * @param int|null $moyenne
     *
     * @return Avisbaby
     */
    public function setMoyenne($moyenne = null)
    {
        $this->moyenne = $moyenne;

        return $this;
    }

    /**
     * Get moyenne.
     *
     * @return int|null
     */
    public function getMoyenne()
    {
        return $this->moyenne;
    }
}
