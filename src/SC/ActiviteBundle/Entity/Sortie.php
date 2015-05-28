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
     * @ORM\Column(name="dateSortie", type="string")
     * @ORM\Id
     */
    private $dateSortie;


    /**
     * @ORM\ManyToOne(targetEntity="SC\ActiviteBundle\Entity\Lieu",cascade={"remove"})
     * @ORM\JoinColumn(nullable=false, name="nomLieu", referencedColumnName="nomLieu")
     */
    private $lieu;


    /**
     * @ORM\ManyToOne(targetEntity="SC\ActiviteBundle\Entity\Activite")
     * @ORM\JoinColumn(nullable=false, name="idActivite", referencedColumnName="id")
     * @ORM\Id
     */
    private $activite;

    /**
     * Set dateSortie
     *
     * @param \DateTime $dateSortie
     * @return \DateTime
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
}

