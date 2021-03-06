<?php

namespace SC\ActiviteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue; 

/**
 * InscriptionStage
 *
 * @ORM\Table("SkiClub_InscriptionStage")
 * @ORM\Entity(repositoryClass="SC\ActiviteBundle\Entity\InscriptionStageRepository")
 */
class InscriptionStage
{
    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="SC\ActiviteBundle\Entity\Activite")
     * @ORM\JoinColumn(nullable=false, name="id", referencedColumnName="id",onDelete="CASCADE")
     * @ORM\Id
     */
    private $activite;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="SC\UserBundle\Entity\User") 
     * @ORM\JoinColumn(nullable=false, name="email", referencedColumnName="email", onDelete="CASCADE")
     * @ORM\Id
     */
    private $user;

    /**
     * @var string
     * 
     * @ORM\Column(name="nomEnfant", type="string")
     * @ORM\Id
     */
    private $nomEnfant;

    /**
     * @var string
     *
     * @ORM\Column(name="prenomEnfant", type="string")
     * @ORM\Id
     */
    private $prenomEnfant;

    /**
     * @var string
     *
     * @ORM\Column(name="debutStage", type="string", length=20)
     * @ORM\Id
     */
    private $debutStage;

    /**
     * @var string
     * @ORM\Column(name="finStage", type="string", length=20)
     * @ORM\Id
     */
    private $finStage;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="SC\ActiviteBundle\Entity\Saison")
     * @ORM\JoinColumn(nullable=false, name="annee", referencedColumnName="annee",onDelete="CASCADE")
     */
    private $saison;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="prixPayeStage", type="integer")
     */
    private $prixPayeStage;



    /**
     * Set user
     *
     * @param \SC\UserBundle\Entity\User $user
     * @return Activite
     */
    public function setUser(\SC\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \SC\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set nomEnfant
     *
     * @param string $nomEnfant
     * @return InscriptionStage
     */
    public function setNomEnfant($nomEnfant)
    {
        $this->nomEnfant = $nomEnfant;

        return $this;
    }

    /**
     * Get nomEnfant
     *
     * @return string 
     */
    public function getNomEnfant()
    {
        return $this->nomEnfant;
    }

    /**
     * Set prenomEnfant
     *
     * @param string $prenomEnfant
     * @return InscriptionStage
     */
    public function setPrenomEnfant($prenomEnfant)
    {
        $this->prenomEnfant = $prenomEnfant;

        return $this;
    }

    /**
     * Get prenomEnfant
     *
     * @return string 
     */
    public function getPrenomEnfant()
    {
        return $this->prenomEnfant;
    }

    /**
     * Set debutStage
     *
     * @param string $debutStage
     * @return InscriptionStage
     */
    public function setDebutStage($debutStage)
    {
        $this->debutStage = $debutStage;

        return $this;
    }

    /**
     * Get debutStage
     *
     * @return string 
     */
    public function getDebutStage()
    {
        return $this->debutStage;
    }

    /**
     * Set finStage
     *
     * @param string $finStage
     * @return InscriptionStage
     */
    public function setFinStage($finStage)
    {
        $this->finStage = $finStage;

        return $this;
    }

    /**
     * Get finStage
     *
     * @return string 
     */
    public function getFinStage()
    {
        return $this->finStage;
    }

    /**
     * Set prixPayeStage
     *
     * @param integer $prixPayeStage
     * @return InscriptionStage
     */
    public function setPrixPayeStage($prixPayeStage)
    {
        $this->prixPayeStage = $prixPayeStage;

        return $this;
    }

    /**
     * Get prixPayeStage
     *
     * @return integer 
     */
    public function getPrixPayeStage()
    {
        return $this->prixPayeStage;
    }
    
        /**
     * Set activite
     *
     * @param Activite $activity
     * @return Stage
     */
    public function setActivite(Activite $activity)
    {
        $this->activite = $activity;

        return $this;
    }

    /**
     * Get activite
     *
     * @return Activite 
     */
    public function getActivite()
    {
        return $this->activite;
    }
    
    /**
     * Set saison
     *
     * @param \SC\ActiviteBundle\Entity\Saison $saison
     * @return Stage
     */
    public function setSaison($saison)
    {
        $this->saison = $saison;

        return $this;
    }

    /**
     * Get saison
     *
     * @return \SC\ActiviteBundle\Entity\Saison 
     */
    public function getSaison()
    {
        return $this->saison;
    }
}