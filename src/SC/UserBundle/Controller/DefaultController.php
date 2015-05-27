<?php

namespace SC\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {    
        $em = $this->getDoctrine()->getManager();
        $listUser = $em->getRepository('SCUserBundle:User')->findAll();
        foreach($listUser as $cli) {
            if ($cli->getEmail() === 'sgbd@hotmail.com') {
                $em->remove($cli);
                $em->flush();         
            }
        }
        
        return $this->render('SCUserBundle:Default:index.html.twig');
    }
}
