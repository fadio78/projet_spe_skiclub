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
    * @ORM\JoinColumn(nullable=false)
    */
    private $userParent;

    /**
     * @var string
     *
     * @ORM\Column(name="nomEnfant", type="string", length=255)
     */
    private $nomEnfant;

    /**
     * @var string
     *
     * @ORM\Column(name="prenomEnfant", type="string", length=255)
     */
    private $prenomEnfant;

    /**
     * @var string
     *
     * @ORM\Column(name="niveauSki", type="string", length=255)
     * 
     * @ORM\Id
     */
    private $niveauSki;

    /**
     * @var string
     *
     * @ORM\Column(name="dateNaissance", type="string", length=255)
     * 
     * @ORM\Id
     */
    private $dateNaissance;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     * 
     * @ORM\Id
     */
    private $email;

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
     * Set niveauSki
     *
     * @param string $niveauSki
     * @return Enfant
     */
    public function setNiveauSki($niveauSki)
    {
        $this->niveauSki = $niveauSki;

        return $this;
    }

    /**
     * Get niveauSki
     *
     * @return string 
     */
    public function getNiveauSki()
    {
        return $this->niveauSki;
    }

    /**
     * Set dateNaissance
     *
     * @param string $dateNaissance
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
     * @return string 
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Enfant
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    /**
     * Get userParent
     *
     * @return user 
     */
    public function getUserParent()
    {
        return $this->userParent;
    }
    
    /**
     * Set userParent
     *
     * @param user $user
     * @return Enfant
     */
    public function setUserParent($user)
    {
        $this->userParent = $user;

        return $this;
    }    
    
}
