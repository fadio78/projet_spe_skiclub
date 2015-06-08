<?php

namespace SC\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Adhesion
 *
 * @ORM\Table("SkiClub_Adhesion")
 * @ORM\Entity(repositoryClass="SC\UserBundle\Entity\AdhesionRepository")
 */
class Adhesion
{
   
    
    /**
     * @ORM\ManyToOne(targetEntity="SC\UserBundle\Entity\User")
     * @ORM\Id
     * @ORM\JoinColumn(nullable=false,name="email",referencedColumnName="email",onDelete="CASCADE")
     */
  private $user;

    /**
     * @ORM\ManyToOne(targetEntity="SC\ActiviteBundle\Entity\Saison")
     * @ORM\Id
     * @ORM\JoinColumn(nullable=false,name="annee",referencedColumnName="annee",onDelete="CASCADE")
     */
  private $saison;

    /**
     * @var integer
     *
     * @ORM\Column(name="modalite", type="integer")
     */
    private $modalite;

    /**
     * @var integer
     *
     * @ORM\Column(name="remise", type="integer")
     */
    private $remise;

    /**
     * @var integer
     *
     * @ORM\Column(name="montantPaye", type="integer")
     */
    private $montantPaye;

    /**
     * @var boolean
     *
     * @ORM\Column(name="adhesionAnnuel", type="boolean")
     */
    private $adhesionAnnuel;


 
    /**
     * Set modalite
     *
     * @param integer $modalite
     * @return Adhesion
     */
    public function setModalite($modalite)
    {
        $this->modalite = $modalite;

        return $this;
    }

    /**
     * Get modalite
     *
     * @return integer 
     */
    public function getModalite()
    {
        return $this->modalite;
    }

    /**
     * Set remise
     *
     * @param integer $remise
     * @return Adhesion
     */
    public function setRemise($remise)
    {
        $this->remise = $remise;

        return $this;
    }

    /**
     * Get remise
     *
     * @return integer 
     */
    public function getRemise()
    {
        return $this->remise;
    }

    /**
     * Set montantPaye
     *
     * @param integer $montantPaye
     * @return Adhesion
     */
    public function setMontantPaye($montantPaye)
    {
        $this->montantPaye = $montantPaye;

        return $this;
    }

    /**
     * Get montantPaye
     *
     * @return integer 
     */
    public function getMontantPaye()
    {
        return $this->montantPaye;
    }

    /**
     * Set adhesionAnnuel
     *
     * @param boolean $adhesionAnnuel
     * @return Adhesion
     */
    public function setAdhesionAnnuel($adhesionAnnuel)
    {
        $this->adhesionAnnuel = $adhesionAnnuel;

        return $this;
    }

    /**
     * Get adhesionAnnuel
     *
     * @return boolean 
     */
    public function getAdhesionAnnuel()
    {
        return $this->adhesionAnnuel;
    }

    /**
     * Set user
     *
     * @param \SC\UserBundle\Entity\User $user
     * @return Adhesion
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
     * Set saison
     *
     * @param \SC\ActiviteBundle\Entity\Saison $saison
     * @return Adhesion
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
}
