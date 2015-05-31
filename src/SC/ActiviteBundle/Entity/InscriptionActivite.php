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
     * @ORM\ManyToOne(targetEntity="SC\UserBundle\Entity\Enfant")
     * @ORM\Id
     * @ORM\JoinColumn(nullable=false, name="email", referencedColumnName = "email")
     */
    
    private $email;
    
    /**
     * @ORM\ManyToOne(targetEntity="SC\UserBundle\Entity\Enfant")
     * @ORM\Id
     * @ORM\JoinColumn(nullable=false, name="nomEnfant", referencedColumnName = "nomEnfant")
     */
    
    private $nomEnfant;
    
    /**
     * @ORM\ManyToOne(targetEntity="SC\UserBundle\Entity\Enfant")
     * @ORM\Id
     * @ORM\JoinColumn(nullable=false, name="prenomEnfant", referencedColumnName = "prenomEnfant")
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
     * @param \SC\UserBundle\Entity\Enfant $email
     * @return InscriptionActivite
     */
    public function setEmail(\SC\UserBundle\Entity\Enfant $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return \SC\UserBundle\Entity\Enfant 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set nomEnfant
     *
     * @param \SC\UserBundle\Entity\Enfant $nomEnfant
     * @return InscriptionActivite
     */
    public function setNomEnfant(\SC\UserBundle\Entity\Enfant $nomEnfant)
    {
        $this->nomEnfant = $nomEnfant;

        return $this;
    }

    /**
     * Get nomEnfant
     *
     * @return \SC\UserBundle\Entity\Enfant 
     */
    public function getNomEnfant()
    {
        return $this->nomEnfant;
    }

    /**
     * Set prenomEnfant
     *
     * @param \SC\UserBundle\Entity\Enfant $prenomEnfant
     * @return InscriptionActivite
     */
    public function setPrenomEnfant(\SC\UserBundle\Entity\Enfant $prenomEnfant)
    {
        $this->prenomEnfant = $prenomEnfant;

        return $this;
    }

    /**
     * Get prenomEnfant
     *
     * @return \SC\UserBundle\Entity\Enfant 
     */
    public function getPrenomEnfant()
    {
        return $this->prenomEnfant;
    }
}
