<?php

namespace SC\LicenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Licence
 *
 * @ORM\Table("SkiClub_Licence")
 * @ORM\Entity(repositoryClass="SC\LicenceBundle\Entity\LicenceRepository")
 */
class Licence
{


    /**
     * @var string
     *
     * @ORM\Column(name="typeLicence", type="string", length=255)
     * @ORM\Id
     */
    private $typeLicence;

    /**
     * @var integer
     *
     * @ORM\Column(name="prixLicence", type="integer")
     */
    private $prixLicence;

    /**
     * Set typeLicence
     *
     * @param string $typeLicence
     * @return Licence
     */
    public function setTypeLicence($typeLicence)
    {
        $this->typeLicence = $typeLicence;

        return $this;
    }

    /**
     * Get typeLicence
     *
     * @return string 
     */
    public function getTypeLicence()
    {
        return $this->typeLicence;
    }

    /**
     * Set prixLicence
     *
     * @param integer $prixLicence
     * @return Licence
     */
    public function setPrixLicence($prixLicence)
    {
        $this->prixLicence = $prixLicence;

        return $this;
    }

    /**
     * Get prixLicence
     *
     * @return integer 
     */
    public function getPrixLicence()
    {
        return $this->prixLicence;
    }
}
