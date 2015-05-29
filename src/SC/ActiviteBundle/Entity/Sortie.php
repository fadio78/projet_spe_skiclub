<?php

namespace SC\ActiviteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sortie
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SC\ActiviteBundle\Entity\SortieRepository")
 */
class Sortie
{
   
    /**
     * @var string    
     *
     * @ORM\Column(name="dateSortie", type="string", length=255)
     * @ORM\Id
     */
    private $dateSortie;
    

    /**
     * @ORM\ManyToOne(targetEntity="SC\ActiviteBundle\Entity\Lieu")
     * @ORM\JoinColumn(nullable=false, name="nomLieu", referencedColumnName="nomLieu",onDelete="CASCADE")
     */
    private $lieu;


    /**
     * @ORM\ManyToOne(targetEntity="SC\ActiviteBundle\Entity\Activite")
     * @ORM\JoinColumn(nullable=false, name="idActivite", referencedColumnName="id",onDelete="CASCADE")
     * @ORM\Id
     */
    private $activite;

    /**
     * @ORM\ManyToOne(targetEntity="SC\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false, name="email", referencedColumnName="email")
     * 
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="SC\ActiviteBundle\Entity\Saison")
     * @ORM\JoinColumn(nullable=false, name="annee", referencedColumnName="annee")
     * @ORM\Id
     */
    private $saison;    

    /**
     * Set dateSortie
     *
     * @param string $dateSortie
     * @return Sortie
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
     * Set lieu
     *
     * @param \SC\ActiviteBundle\Entity\Lieu $lieu
     * @return Sortie
     */
    public function setLieu(\SC\ActiviteBundle\Entity\Lieu $lieu)
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
     * Set activite
     *
     * @param \SC\ActiviteBundle\Entity\Activite $activite
     * @return Sortie
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
     * Set User
     *
     * @param \SC\UserBundle\Entity\User $user
     * @return Sortie
     */
    public function setUser(\SC\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get User
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
     * @return Sortie
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
