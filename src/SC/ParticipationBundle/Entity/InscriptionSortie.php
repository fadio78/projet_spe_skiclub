<?php

namespace SC\ParticipationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SC\ActiviteBundle\Entity\Sortie;
use SC\UserBundle\Entity\Enfant;

/**
 * InscriptionSortie
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SC\ParticipationBundle\Entity\InscriptionSortieRepository")
 */
class InscriptionSortie
{
    /**
     * @var integer
     *
     * @ORM\Column(name="participation", type="integer")
     * @ORM\Id
     * 
     */
    private $participation;
    
}
