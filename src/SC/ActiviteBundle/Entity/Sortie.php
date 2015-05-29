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
     * @var integer
     *
     * @ORM\Column(name="idSortie", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idSortie;    

    /**
     * @var \DateTime    
     *
     * @ORM\Column(name="dateSortie", type="datetime")
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
     */
    private $activite;


    /**
     * Set lieu
     *
     * @param Lieu $venue
     * @return Sortie
     */
    public function setLieu($venue)
    {
        $this->lieu = $venue;

        return $this;
    }

    /**
     * Get lieu
     *
     * @return Lieu 
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * Set activite
     *
     * @param Activite $activity
     * @return Sortie
     */
    public function setActivite(Activite $activity)
    {
        $this->activite = $activity;

        return $this;
    }

    /**
     * Get activite
     *
     * @return Activite 
     */
    public function getActivite()
    {
        return $this->activite;
    }




    /**
     * Set dateSortie
     *
     * @param \DateTime $dateSortie
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
     * @return \DateTime 
     */
    public function getDateSortie()
    {
        return $this->dateSortie;
    }

    /**
     * Get idSortie
     *
     * @return integer 
     */
    public function getIdSortie()
    {
        return $this->idSortie;
    }
}
