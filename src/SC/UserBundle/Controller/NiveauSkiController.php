<?php
// src/SC/NiveauSkiBundle/Controller/NiveauSkiController.php
namespace SC\UserBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use SC\UserBundle\Entity\NiveauSki;
use SC\UserBundle\Form\NiveauSkiType;
use SC\UserBundle\Form\NiveauSkiEditType;




class NiveauSkiController extends Controller 
{
    
    public function indexAction(Request $request)
    {
        $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('SCUserBundle:NiveauSki');
        $listeNiveauSki = $repository->findAll(); 
        return $this->render('SCUserBundle:NiveauSki:index.html.twig',array('listeNiveauSki' => $listeNiveauSki));
        
    }
    
    
    
        
    public function viewAction($niveau,Request $request)
    {
        // Ici, on récupérera la licence correspondant à typeNiveauSki
        $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('SCUserBundle:NiveauSki');
        //on recupere la licence
        $niveauSki = $repository->find($niveau);
        if (null === $niveauSki) {
          throw new NotFoundHttpException("Le niveau de ski ".$niveau." n'existe pas.");
        }
        
        return $this->render('SCUserBundle:NiveauSki:view.html.twig', array('niveauSki' => $niveauSki));
    }
    
    
    

    public function addAction(Request $request)
    {
        $niveauSki = new NiveauSki();
        $form = $this->get('form.factory')->create(new NiveauSkiType(), $niveauSki);
        $form->handleRequest($request);
            if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $repository = $em ->getRepository('SCUserBundle:NiveauSki');
            $niveauExiste = $repository ->findOneBy(array('niveau' => $niveauSki ->getNiveau()));
            if ($niveauExiste == null)
            {
                $em->persist($niveauSki);
                $em->flush();
                $request->getSession()->getFlashBag()->add('info', 'niveau ski bien enregistrée');
                return $this->redirect($this->generateUrl('sc_niveauSki_view', array('niveau' => $niveauSki->getNiveau())));
            }
            else
            {
                $request->getSession()->getFlashBag()->add('info', 'niveau ski existe déjà');
                return $this->render('SCUserBundle:NiveauSki:add.html.twig', array('form' => $form->createView()));
            }
        }
        return $this->render('SCUserBundle:NiveauSki:add.html.twig', array('form' => $form->createView()));
        
    }

    public function editAction($niveau , Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $niveauSki = $em->getRepository('SCUserBundle:NiveauSki')-> find ($niveau);

        if (null === $niveauSki) {
            throw new NotFoundHttpException("Le niveau de ski ".$niveau." n'existe pas.");
        }

        $form = $this->createForm(new NiveauSkiEditType(), $niveauSki);

        if ($form->handleRequest($request)->isValid()) {
            $repository = $em ->getRepository('SCUserBundle:NiveauSki');
            $niveauExiste = $repository ->findOneBy(array('niveau' => $niveauSki ->getNiveau()));
            if ($niveauExiste == null)
            {
                $em->flush();
                $request->getSession()->getFlashBag()->add('info', 'NiveauSki bien modifiée.');
                return $this->redirect($this->generateUrl('sc_niveauSki_view', array('niveau' => $niveauSki->getNiveau())));
            }
            else
            {
                $request->getSession()->getFlashBag()->add('info', 'niveau ski existe déjà');
                return $this->render('SCUserBundle:NiveauSki:add.html.twig', array('form' => $form->createView()));
            }
        }
        return $this->render('SCUserBundle:NiveauSki:edit.html.twig', array('form'   => $form->createView(),'niveauSki' => $niveauSki));
    }
             
}