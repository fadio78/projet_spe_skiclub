<?php

namespace SC\ActiviteBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use SC\ActiviteBundle\Entity\Activite;
use SC\ActiviteBundle\Form\ActiviteType;
use SC\ActiviteBundle\Form\LieuType;
use SC\ActiviteBundle\Form\SortieType;
use SC\ActiviteBundle\Form\ActiviteEditType;
use SC\UserBundle\Entity\User;
use SC\UserBundle\Entity\Licence;
use SC\ActiviteBundle\Entity\Sortie;
use SC\ActiviteBundle\Entity\Lieu;
use SC\ActiviteBundle\Entity\Saison;
use SC\ActiviteBundle\Entity\InscriptionSortie;
use SC\ActiviteBundle\Form\InscriptionSortieType;


class InscriptionSortieController extends Controller 
{
    //genere une page d'erreur si besoin
    public function pageErreur($message) {
        $response = new Response;
        $response->setContent($message);
        $response->setStatusCode(Response::HTTP_NOT_FOUND);
        return $response;
    }
    
    //permet de s'inscrire à une nouvelle sortie
    public function choixSortieAction($id,Request $request) {
           
        $em = $this->getDoctrine()->getManager();
        $activite = $this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Activite')->find($id);
        if (is_null($activite)==true) {
            return $this->pageErreur("l'activite demandée n'existe pas !");
        }
        
        $inscription = new InscriptionSortie;
        //on recupere le userParent
        $email = $request->getSession()->get('email'); 
        $parents = $this->getDoctrine()->getManager()->getRepository('SC\UserBundle\Entity\User')->findOneByEmail($email);
        $form = $this->get('form.factory')->create(new InscriptionSortieType($parents,$activite), $inscription);
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            //on teste si l'enfant est deja inscrit
            $sortie = $inscription->getSortie();
            $enfant = $inscription->getEmailParent();

            //mise a jour des differents champs de l'instance d'InscriptionObjet
            $inscription->setIdActivite($activite);
            $inscription->setNomEnfant($enfant->getNomEnfant());
            $inscription->setPrenomEnfant($enfant->getPrenomEnfant());
            $inscription->setParticipation(0);

            //requete sur inscriptionsorite impossible ??
            //$listParticipant = $em->getRepository('SC\ActiviteBundle\Entity\InscriptionSortie')->findAll();
            $em->persist($inscription);
            $em->flush();
            
            $request->getSession()->set('inscription', $inscription);
            return $this->render('SCActiviteBundle::viewEnfant.html.twig', array('enfants'=> $enfant, 'sortie' => $sortie));
        }
        return $this->render('SCActiviteBundle::viewSortie.html.twig', array(
            'form' => $form->createView(), 'inscription' => 1,
            ));
    }
}    