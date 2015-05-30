<?php

namespace SC\UserBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository implements UserProviderInterface
{
     public function loadUserByUsername($username)
    {
        $q = $this
            ->createQueryBuilder('u')
            ->where('u.username = :username OR u.email = :email')
            //->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery();

        try {
            // La méthode Query::getSingleResult() lance une exception
            // s'il n'y a pas d'entrée correspondante aux critères
            $user = $q->getSingleResult();
        } catch (NoResultException $e) {
            throw new UsernameNotFoundException(sprintf('Unable to find an active admin AcmeUserBundle:User object identified by "%s".', $username), 0, $e);
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(
                sprintf(
                    'Instances of "%s" are not supported.',
                    $class
                )
            );
        }

        return $this->find($user->getEmail());
        //return $this->find($user->getId());
    }

    public function supportsClass($class)
    {
        return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
    }
    
    
    
    public function getUser($email)
    {
        $qb = $this->createQueryBuilder('u');
        $qb->where('u.email = :email');
    
        return $qb
          ->getQuery()
          ->getResult();
 
    }
    // Permet de récuperer les adresse non active  
    public function compteInactif()
    {
        $qb = $this->createQueryBuilder('a');

        $qb->where('a.isActive = :isActive')
            ->setParameter('isActive', false)            
        ;

        return $qb
            ->getQuery()
            ->getResult()
        ;
    }
    //permet d'activer le compte inactif
        public function activerCompte($email)
    {
      $query = $this->_em->createQuery('UPDATE SCUserBundle:User a SET a.isActive = true where a.email = :email ')
                       ->setParameter('email', $email);
       $query->execute();
  
      
    }
  
  
  
}
