<?php

namespace SC\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LicenceEnfant
 *
 * @ORM\Table("SkiClub_LicenceEnfant")
 * @ORM\Entity(repositoryClass="SC\UserBundle\Entity\LicenceEnfantRepository")
 */
class LicenceEnfant
{


    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="NomEnfant", type="string", length=255)
     */
    private $nomEnfant;

    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="prenomEnfant", type="string", length=255)
     */
    private $prenomEnfant;
    
    /**
     * @ORM\ManyToOne(targetEntity="SC\ActiviteBundle\Entity\Saison")
     * @ORM\Id
     * @ORM\JoinColumn(nullable=false,name="annee", referencedColumnName="annee")
     */
    private $saison;

    /**
     * @ORM\ManyToOne(targetEntity="SC\LicenceBundle\Entity\Licence")
     * @ORM\Id
     * @ORM\JoinColumn(nullable=true,name="typeLicence",referencedColumnName="typeLicence")
     */ 
    private $licence;
    


    /**
     * Set email
     *
     * @param string $email
     * @return LicenceEnfant
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
     * @return LicenceEnfant
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
     * @return LicenceEnfant
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
     * @param \SC\ActiviteBundle\Entity\Saison $saison
     * @return LicenceEnfant
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
     * Set licence
     *
     * @param \SC\LicenceBundle\Entity\Licence $licence
     * @return LicenceEnfant
     */
    public function setLicence(\SC\LicenceBundle\Entity\Licence $licence)
    {
        $this->licence = $licence;

        return $this;
    }

    /**
     * Get licence
     *
     * @return \SC\LicenceBundle\Entity\Licence 
     */
    public function getLicence()
    {
        return $this->licence;
    }
}
