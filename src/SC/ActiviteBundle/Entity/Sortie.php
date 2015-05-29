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
}
