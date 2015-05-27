<?php

namespace SC\LicenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Licence
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SC\LicenceBundle\Entity\LicenceRepository")
 */
class Licence
{


    /**
     * @var string
     *
     * @ORM\Column(name="typelicence", type="string", length=255)
     * @ORM\Id
     */
    private $typelicence;

    /**
     * @var integer
     *
     * @ORM\Column(name="prixlicence", type="integer")
     */
    private $prixlicence;

    /**
     * Set typelicence
     *
     * @param string $typelicence
     * @return Licence
     */
    public function setTypelicence($typelicence)
    {
        $this->typelicence = $typelicence;

        return $this;
    }

    /**
     * Get typelicence
     *
     * @return string 
     */
    public function getTypelicence()
    {
        return $this->typelicence;
    }

    /**
     * Set prixlicence
     *
     * @param integer $prixlicence
     * @return Licence
     */
    public function setPrixlicence($prixlicence)
    {
        $this->prixlicence = $prixlicence;

        return $this;
    }

    /**
     * Get prixlicence
     *
     * @return integer 
     */
    public function getPrixlicence()
    {
        return $this->prixlicence;
    }
}
