<?php

namespace SC\ActiviteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Activite
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SC\ActiviteBundle\Entity\ActiviteRepository")
 */
class Activite
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nomActivite", type="string", length=255)
     */
    private $nomActivite;

    /**
     * @var string
     * 
     * @ORM\Column(nullable=true,name="description", type="text")
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="prixActivite", type="integer")
     */
    private $prixActivite;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="SC\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false, name="email",referencedColumnName="email")
     * 
     */
    private $user; 
    
    
    /**
     * @ORM\ManyToOne(targetEntity="SC\LicenceBundle\Entity\Licence")
     * @ORM\JoinColumn(nullable=true,name="typeLicence",referencedColumnName="typeLicence")
     */ 
    private $licence; 
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nomActivite
     *
     * @param string $nomActivite
     * @return Activite
     */
    public function setNomActivite($nomActivite)
    {
        $this->nomActivite = $nomActivite;

        return $this;
    }

    /**
     * Get nomActivite
     *
     * @return string 
     */
    public function getNomactivite()
    {
        return $this->nomActivite;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Activite
     */
    public function setDescription($description )
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
     * Set prixActivite
     *
     * @param integer $prixActivite
     * @return Activite
     */
    public function setPrixActivite($prixActivite)
    {
        $this->prixActivite = $prixActivite;

        return $this;
    }

    /**
     * Get prixActivite
     *
     * @return integer 
     */
    public function getPrixActivite()
    {
        return $this->prixActivite;
    }

    /**
     * Set licence
     *
     * @param \SC\LicenceBundle\Entity\Licence $licence
     * @return Activite
     */
    public function setLicence(\SC\LicenceBundle\Entity\Licence $licence = null)
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
