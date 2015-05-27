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
     * @var \DateTime
     *
     * @ORM\Column(name="debutSaison", type="datetime")
     * @ORM\Id
     */
    private $debutSaison;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="finSaison", type="datetime")
     * @ORM\Id
     */
    private $finSaison;


    /**
     * Set debutSaison
     *
     * @param \DateTime $debutSaison
     * @return Saison
     */
    public function setDebutSaison($debutSaison)
    {
        $this->debutSaison = $debutSaison;

        return $this;
    }

    /**
     * Get debutSaison
     *
     * @return \DateTime 
     */
    public function getDebutSaison()
    {
        return $this->debutSaison;
    }

    /**
     * Set finSaison
     *
     * @param \DateTime $finSaison
     * @return Saison
     */
    public function setFinSaison($finSaison)
    {
        $this->finSaison = $finSaison;

        return $this;
    }

    /**
     * Get finSaison
     *
     * @return \DateTime 
     */
    public function getFinSaison()
    {
        return $this->finSaison;
    }
}
