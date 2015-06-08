<?php

namespace SC\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NiveauSki
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SC\UserBundle\Entity\NiveauSkiRepository")
 */
class NiveauSki
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="niveau", type="string", length=255)
     */
    private $niveau;
    
    /**
     * Set niveau
     *
     * @param string $niveau
     * @return NiveauSki
     */
    public function setNiveau($niveau)
    {
        $this->niveau = $niveau;

        return $this;
    }

    /**
     * Get niveau
     *
     * @return string 
     */
    public function getNiveau()
    {
        return $this->niveau;
    }
}
