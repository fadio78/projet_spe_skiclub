<?php
// src/SC/UserBundle/Controller/AdminController.php;

namespace SC\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use SC\UserBundle\Entity\User;
use SC\UserBundle\Form\UserType;
use Doctrine\ORM\EntityRepository;

class AdminController extends Controller
{
    public function indexAction(Request $request)
    {
        return $this->render('SCUserBundle:Admin:index.html.twig');
        
        
    }
    public function listUsersAction(Request $request)
    {
        $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('SCUserBundle:User');
          $listUsers = $repository->findAll(); 
          $listUsersInactif = $repository->compteInactif();
        
        return $this->render('SCUserBundle:Admin:listUsers.html.twig',
                array('listUsers' => $listUsers,
                      'listUsersInactif'=>$listUsersInactif
                )
                );
        
        
    }
        public function activerCompteAction(Request $request, $email)
    {
        $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('SCUserBundle:User');
           
           $repository->activerCompte($email);
        $request->getSession()->getFlashBag()->add('info', 'Compte bien activité ');
        return $this->redirect($this->generateUrl('sc_admin_listUsers'));
        
        
    }

    
    
    
    
}