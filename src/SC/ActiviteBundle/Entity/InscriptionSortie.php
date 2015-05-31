<?php

namespace SC\ActiviteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InscriptionSortie
 *
 * @ORM\Table()
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
     * @ORM\ManyToOne(targetEntity="SC\ActiviteBundle\Entity\Sortie")
     * @ORM\JoinColumn(nullable=false, name="dateSortie", referencedColumnName="dateSortie")
     * @ORM\Id
     */    
    private $sortie;

    /**
     * @ORM\ManyToOne(targetEntity="SC\ActiviteBundle\Entity\Activite")
     * @ORM\JoinColumn(nullable=false, name="idActivite", referencedColumnName="id")
     * @ORM\Id
     */   
    private $idActivite;  
    
    
    /**
     * @ORM\ManyToOne(targetEntity="SC\UserBundle\Entity\Enfant")
     * @ORM\JoinColumn(nullable=false, name="email", referencedColumnName="email")
     * @ORM\Id
     */    
    private $emailParent;
    
    /**
     * @var string
     * @ORM\Column(name="nomEnfant", type="string")
     * @ORM\Id
     */    
    private $nomEnfant;     

    /**
     * @var string
     * @ORM\Column(name="prenomEnfant", type="string")
     * @ORM\Id
     */    
    private $prenomEnfant;     
 

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
     * Set sortie
     *
     * @param \SC\ActiviteBundle\Entity\Sortie $sortie
     * @return InscriptionSortie
     */
    public function setSortie(\SC\ActiviteBundle\Entity\Sortie $sortie)
    {
        $this->sortie = $sortie;

        return $this;
    }

    /**
     * Get sortie
     *
     * @return \SC\ActiviteBundle\Entity\Sortie 
     */
    public function getSortie()
    {
        return $this->sortie;
    }


    /**
     * Set emailParent
     *
     * @param \SC\UserBundle\Entity\Enfant $emailParent
     * @return InscriptionSortie
     */
    public function setEmailParent(\SC\UserBundle\Entity\Enfant $emailParent)
    {
        $this->emailParent = $emailParent;

        return $this;
    }

    /**
     * Get emailParent
     *
     * @return \SC\UserBundle\Entity\Enfant 
     */
    public function getEmailParent()
    {
        return $this->emailParent;
    }


    /**
     * Set idActivite
     *
     * @param \SC\ActiviteBundle\Entity\Activite $idActivite
     * @return InscriptionSortie
     */
    public function setIdActivite(\SC\ActiviteBundle\Entity\Activite $idActivite)
    {
        $this->idActivite = $idActivite;

        return $this;
    }

    /**
     * Get idActivite
     *
     * @return \SC\ActiviteBundle\Entity\Activite 
     */
    public function getIdActivite()
    {
        return $this->idActivite;
    }
}
