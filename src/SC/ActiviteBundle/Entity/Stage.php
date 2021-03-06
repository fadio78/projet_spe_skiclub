<?php

namespace SC\ActiviteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stage
 *
 * @ORM\Table("SkiClub_Stage")
 * @ORM\Entity(repositoryClass="SC\ActiviteBundle\Entity\StageRepository")
 */
class Stage
{
    /**
     * @ORM\ManyToOne(targetEntity="SC\ActiviteBundle\Entity\Activite")
     * @ORM\JoinColumn(nullable=false, name="id", referencedColumnName="id",onDelete="CASCADE")
     * @ORM\Id
     */
    private $activite;

    /**
     * @var string
     *
     * @ORM\Column(name="debutStage", type="string", length=20)
     * @ORM\Id
     */
    private $debutStage;

    /**
     * @var string
     *
     * @ORM\Column(name="finStage", type="string", length=20)
     * @ORM\Id
     */
    private $finStage;

    /**
     * @var string
     *
     * @ORM\Column(name="nomStage", type="string", length=255)
     */
    private $nomStage;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true, name="description", type="text")
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="prixStage", type="integer")
     */
    private $prixStage;

    /**
     * @var integer
     *
     * @ORM\Column(name="charges", type="integer")
     */
    private $charges;
    
    /**
     * @ORM\ManyToOne(targetEntity="SC\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false, name="email",referencedColumnName="email",onDelete="CASCADE")
     * 
     */
    private $user;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="SC\ActiviteBundle\Entity\Lieu")
     * @ORM\JoinColumn(nullable=false, name="nomLieu", referencedColumnName="nomLieu",onDelete="CASCADE")
     */
    private $lieu;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="SC\ActiviteBundle\Entity\Saison")
     * @ORM\JoinColumn(nullable=false, name="annee", referencedColumnName="annee",onDelete="CASCADE")
     */
    private $saison;
    

    /**
     * Set debutStage
     *
     * @param string $debutStage
     * @return Stage
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
     * @return Stage
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
     * Set nomStage
     *
     * @param string $nomStage
     * @return Stage
     */
    public function setNomStage($nomStage)
    {
        $this->nomStage = $nomStage;

        return $this;
    }

    /**
     * Get nomStage
     *
     * @return string 
     */
    public function getNomStage()
    {
        return $this->nomStage;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Stage
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
     * Set prixStage
     *
     * @param integer $prixStage
     * @return Stage
     */
    public function setPrixStage($prixStage)
    {
        $this->prixStage = $prixStage;

        return $this;
    }

    /**
     * Get prixStage
     *
     * @return integer 
     */
    public function getPrixStage()
    {
        return $this->prixStage;
    }

    /**
     * Set charges
     *
     * @param integer $charges
     * @return Stage
     */
    public function setCharges($charges)
    {
        $this->charges = $charges;

        return $this;
    }

    /**
     * Get charges
     *
     * @return integer 
     */
    public function getCharges()
    {
        return $this->charges;
    }

    /**
     * Set lieu
     *
     * @param \SC\ActiviteBundle\Entity\Lieu $lieu
     * @return Stage
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * Get lieu
     *
     * @return \SC\ActiviteBundle\Entity\Lieu 
     */
    public function getLieu()
    {
        return $this->lieu;
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
    
}
