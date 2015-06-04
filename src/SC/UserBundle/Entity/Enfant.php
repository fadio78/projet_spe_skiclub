<?php

namespace SC\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Enfant
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SC\UserBundle\Entity\EnfantRepository")
 */
class Enfant
{
   
    /**
    * @ORM\ManyToOne(targetEntity="SC\UserBundle\Entity\User")
    * @ORM\JoinColumn(nullable=false, name="email", referencedColumnName="email")
    * @ORM\Id 
    */
    private $userParent;

   
    /**
     * @var string
     *
     * @ORM\Column(name="nomEnfant", type="string", length=255)
     * @ORM\Id
     */
    private $nomEnfant;

    /**
     * @var string
     *
     * @ORM\Column(name="prenomEnfant", type="string", length=255)
     * @ORM\Id
     */
    private $prenomEnfant;

    /**
     * @ORM\ManyToOne(targetEntity="SC\UserBundle\Entity\NiveauSki")
     * @ORM\JoinColumn(nullable=false, name="niveau", referencedColumnName="niveau")
     * 
     */
    private $niveauSki;

    /**
     * @var \DateTime 
     *
     * @ORM\Column(name="dateNaissance", type="date")
     * 
     */
    private $dateNaissance;
    
    
    /**
     * @var string
     */
    
    private $prenomNom;
    
    /**
     * Set nomEnfant
     *
     * @param string $nomEnfant
     * @return Enfant
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
     * @return Enfant
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
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     * @return Enfant
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime 
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set userParent
     *
     * @param \SC\UserBundle\Entity\User $userParent
     * @return Enfant
     */
    public function setUserParent(\SC\UserBundle\Entity\User $userParent)
    {
        $this->userParent = $userParent;

        return $this;
    }

    /**
     * Get userParent
     *
     * @return \SC\UserBundle\Entity\User 
     */
    public function getUserParent()
    {
        return $this->userParent;
    }
    
    public function __toString() {
        return $this->getNomEnfant().' '.$this->getPrenomEnfant();
    }

    public function getPrenomNom()
    {
        return ($this -> prenomEnfant).' '.($this -> nomEnfant) ;
    }



    /**
     * Set niveauSki
     *
     * @param \SC\UserBundle\Entity\NiveauSki $niveauSki
     * @return Enfant
     */
    public function setNiveauSki(\SC\UserBundle\Entity\NiveauSki $niveauSki)
    {
        $this->niveauSki = $niveauSki;

        return $this;
    }

    /**
     * Get niveauSki
     *
     * @return \SC\UserBundle\Entity\NiveauSki 
     */
    public function getNiveauSki()
    {
        return $this->niveauSki;
    }
}
