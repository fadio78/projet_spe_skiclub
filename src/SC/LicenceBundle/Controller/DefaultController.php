<?php

namespace SC\LicenceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SCLicenceBundle:Default:index.html.twig', array('name' => $name));
    }
}
