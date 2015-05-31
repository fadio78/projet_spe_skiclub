<?php

namespace SC\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * EnfantRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EnfantRepository extends EntityRepository
{

  public function getPublishedQueryBuilder($parent)
  {
    return $this->createQueryBuilder('a')
      ->where('a.userParent = :parents')
      ->setParameter('parents', $parent);
  }

}
