<?php

namespace SC\ActiviteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Saison
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SC\ActiviteBundle\Entity\SaisonRepository")
 */
class Saison
{

    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(name="annee", type="integer")
     */
    private $annee;

    /**
     * Set annee
     *
     * @param integer $annee
     * @return Saison
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * Get annee
     *
     * @return integer 
     */
    public function getAnnee()
    {
        return $this->annee;
    }
}
