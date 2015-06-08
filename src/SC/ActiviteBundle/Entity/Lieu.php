<?php

namespace SC\ActiviteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lieu
 *
 * @ORM\Table("SkiClub_Lieu")
 * @ORM\Entity(repositoryClass="SC\ActiviteBundle\Entity\LieuRepository")
 */
class Lieu
{
    /**
     * @var string
     *
     * @ORM\Column(name="nomLieu", type="string", length=255)
     * @ORM\Id
     */
    private $nomLieu;

    /**
     * Set nomLieu
     *
     * @param string $nomLieu
     * @return Lieu
     */
    public function setNomLieu($nomLieu)
    {
        $this->nomLieu = $nomLieu;

        return $this;
    }

    /**
     * Get nomLieu
     *
     * @return string 
     */
    public function getNomLieu()
    {
        return $this->nomLieu;
    }
}
