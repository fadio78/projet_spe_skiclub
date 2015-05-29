<?php

namespace SC\ActiviteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PrixPayeActivite
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SC\ActiviteBundle\Entity\PrixPayeActiviteRepository")
 */
class PrixPayeActivite
{


    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(name="prixPayeActivite", type="integer")
     */
    private $prixPayeActivite;


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
     * Set prixPayeActivite
     *
     * @param integer $prixPayeActivite
     * @return PrixPayeActivite
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
}
