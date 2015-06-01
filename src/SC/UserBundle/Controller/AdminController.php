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
    public function listUsersInactifAction(Request $request)
    {
        $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('SCUserBundle:User');
           
          $listUsersInactif = $repository->compteInactif();
        
        return $this->render('SCUserBundle:Admin:listUsersInactif.html.twig',
                array('listUsersInactif'=>$listUsersInactif )
                );
        
        
    }
        public function listNoAdminAction(Request $request)
    {
        $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('SCUserBundle:User');
           
          $listNoAdmin = $repository->noAdmin();
        
        return $this->render('SCUserBundle:Admin:listNoAdmin.html.twig',
                array('listNoAdmin'=>$listNoAdmin )
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
        return $this->redirect($this->generateUrl('sc_admin_listUsersInactif'));
        
        
    }
            public function supprimerCompteAction(Request $request, $email)
    {
        $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('SCUserBundle:User');
           
           $repository->supprimerCompte($email);
        $request->getSession()->getFlashBag()->add('info', 'Compte bien supprimé ');
        return $this->redirect($this->generateUrl('sc_admin_listUsersInactif'));
        
        
    }

           public function activerAdminAction(Request $request, $email)
    {
        $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('SCUserBundle:User');
           
           $repository->activerAdmin($email);
        $request->getSession()->getFlashBag()->add('info', 'new Admin ! ');
        return $this->redirect($this->generateUrl('sc_admin_listNoAdmin'));
        
        
    } 
               public function gestionCompteAction(Request $request, $email)
    {
        $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('SCUserBundle:User');
           
           $user= $repository->findOneby(['email' => $email]);
           
        $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('SCUserBundle:Adhesion');
           
           $adhesion = $repository->findOneby(
                   array('user' => $email,
                         'saison'=> 2014
                   ));
              
           
        return $this->render('SCUserBundle:Admin:gestionCompte.html.twig',
                array('user'=>$user ,
                      'adhesion'=>$adhesion
                )
                
                );
        
        
    }

               public function ajoutMontantAction(Request $request, $email)
    {

        $annee=2014; 
        $montant = $_POST['_montant'];
        $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('SCUserBundle:Adhesion');
         
           $adhesion = $repository->ajoutMontant($email,$annee,$montant);
              
             
         return  $this->gestionCompteAction( $request, $email);
        
        
    }
              public function ajoutRemiseAction(Request $request, $email)
            {

                $annee=2014; 
        $montant = $_POST['_remise'];
        $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('SCUserBundle:Adhesion');
         
           $adhesion = $repository->ajoutRemise($email,$annee,$montant);
              
             
         return  $this->gestionCompteAction( $request, $email);
        
        
    }
    
                  public function adhererAction(Request $request, $email)
            {

                $annee=2014; 
        $adhesion = $_POST['_adhesion'];
        $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('SCUserBundle:Adhesion');
         if ($adhesion == 1){
          $adhesion = $repository->adherer($email,$annee);
         }     
             
         return  $this->gestionCompteAction( $request, $email);
        
        
    }
    
}