<?php

namespace SC\ActiviteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InscriptionActivite
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SC\ActiviteBundle\Entity\InscriptionActiviteRepository")
 */
class InscriptionActivite
{


    /**
     * @var integer
     *
     * @ORM\Column(name="prixPayeActivite", type="integer")
     */
    private $prixPayeActivite;
    
    


    
    /**
     * @ORM\ManyToOne(targetEntity="SC\ActiviteBundle\Entity\Activite")
     * @ORM\Id
     * @ORM\JoinColumn(nullable=false,name="id", referencedColumnName="id")
     */
    private $activite;
    
    /**
     * @ORM\ManyToOne(targetEntity="SC\ActiviteBundle\Entity\Saison")
     * @ORM\Id
     * @ORM\JoinColumn(nullable=false,name="annee", referencedColumnName="annee")
     */
    private $saison;
    
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="email", type="string")
     */
    
    private $email;
    
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="nomEnfant", type="string")
     */
 
    
    private $nomEnfant;
    
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="prenomEnfant", type="string")
     */
    

    
    private $prenomEnfant;
    
    
    


    /**
     * Set prixPayeActivite
     *
     * @param integer $prixPayeActivite
     * @return InscriptionActivite
     */
    public function setPrixPayeActivite($prixPayeActivite)
    {
        $this->prixPayeActivite = $prixPayeActivite;

        return $this;
    }

    /**
     * Get prixPayeActivite
     *
     * @return integer 
     */
    public function getPrixPayeActivite()
    {
        return $this->prixPayeActivite;
    }


    /**
     * Set activite
     *
     * @param \SC\ActiviteBundle\Entity\Activite $activite
     * @return InscriptionActivite
     */
    public function setActivite(\SC\ActiviteBundle\Entity\Activite $activite)
    {
        $this->activite = $activite;

        return $this;
    }
    

    /**
     * Get activite
     *
     * @return \SC\ActiviteBundle\Entity\Activite 
     */
    public function getActivite()
    {
        return $this->activite;
    }

    /**
     * Set saison
     *
     * @param \SC\ActiviteBundle\Entity\Saison $saison
     * @return InscriptionActivite
     */
    public function setSaison(\SC\ActiviteBundle\Entity\Saison $saison)
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
     * Set email
     *
     * @param string $email
     * @return InscriptionActivite
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
     * Set nomEnfant
     *
     * @param string $nomEnfant
     * @return InscriptionActivite
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
     * @return InscriptionActivite
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
}
