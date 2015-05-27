<?php

namespace SC\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {          
        return $this->render('SCUserBundle:Default:index.html.twig');
    }
}
