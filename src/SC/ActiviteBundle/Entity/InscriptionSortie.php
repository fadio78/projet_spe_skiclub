<?php

namespace SC\ActiviteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InscriptionSortie
 *
 * @ORM\Table("SkiClub_InscriptionSortie")
 * @ORM\Entity(repositoryClass="SC\ActiviteBundle\Entity\InscriptionSortieRepository")
 */
class InscriptionSortie
{
    /**
     * @var integer
     * @ORM\Column(name="participation", type="integer")
     */
    private $participation;
    
    /**
     * @var string
     * @ORM\Column(name="dateSortie", type="string",length=30)
     * @ORM\Id
     */   
    private $dateSortie;

    /**
     * @var integer
     * @ORM\Column(name="idActivite", type="integer")
     * @ORM\Id
     */   
    private $idActivite;  
    
    
    
    /**
     * @var string
     * @ORM\Column(name="email", type="string",length=30)
     * @ORM\Id
     */    
    private $emailParent;
    
    /**
     * @var string
     * @ORM\Column(name="nomEnfant", type="string",length=30)
     * @ORM\Id
     */    
    private $nomEnfant;     

    /**
     * @var string
     * @ORM\Column(name="prenomEnfant", type="string",length=30)
     * @ORM\Id
     */    
    private $prenomEnfant;     
    
    /**
     * @var integer
     * @ORM\Column(name="saison", type="integer")
     * @ORM\Id
     */
    private $saison; 



    /**
     * Set participation
     *
     * @param integer $participation
     * @return InscriptionSortie
     */
    public function setParticipation($participation)
    {
        $this->participation = $participation;

        return $this;
    }

    /**
     * Get participation
     *
     * @return integer 
     */
    public function getParticipation()
    {
        return $this->participation;
    }

    /**
     * Set dateSortie
     *
     * @param string $dateSortie
     * @return InscriptionSortie
     */
    public function setDateSortie($dateSortie)
    {
        $this->dateSortie = $dateSortie;

        return $this;
    }

    /**
     * Get dateSortie
     *
     * @return string 
     */
    public function getDateSortie()
    {
        return $this->dateSortie;
    }

    /**
     * Set idActivite
     *
     * @param integer $idActivite
     * @return InscriptionSortie
     */
    public function setIdActivite($idActivite)
    {
        $this->idActivite = $idActivite;

        return $this;
    }

    /**
     * Get idActivite
     *
     * @return integer 
     */
    public function getIdActivite()
    {
        return $this->idActivite;
    }

    /**
     * Set emailParent
     *
     * @param string $emailParent
     * @return InscriptionSortie
     */
    public function setEmailParent($emailParent)
    {
        $this->emailParent = $emailParent;

        return $this;
    }

    /**
     * Get emailParent
     *
     * @return string 
     */
    public function getEmailParent()
    {
        return $this->emailParent;
    }

    /**
     * Set nomEnfant
     *
     * @param string $nomEnfant
     * @return InscriptionSortie
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
     * @return InscriptionSortie
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
     * Set saison
     *
     * @param integer $saison
     * @return InscriptionSortie
     */
    public function setSaison($saison)
    {
        $this->saison = $saison;

        return $this;
    }

    /**
     * Get saison
     *
     * @return integer 
     */
    public function getSaison()
    {
        return $this->saison;
    }

    
    public function __toString() {
        return $this->nomEnfant.' '.$this->dateSortie.' '.$this->lieu;
    }
}
