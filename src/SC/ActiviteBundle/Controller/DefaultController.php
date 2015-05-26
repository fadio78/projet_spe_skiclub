<?php

namespace SC\ActiviteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SCActiviteBundle:Default:index.html.twig', array('name' => $name));
    }
}
