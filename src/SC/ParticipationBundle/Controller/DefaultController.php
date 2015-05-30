<?php

namespace SC\ParticipationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SCParticipationBundle:Default:index.html.twig', array('name' => $name));
    }
}
