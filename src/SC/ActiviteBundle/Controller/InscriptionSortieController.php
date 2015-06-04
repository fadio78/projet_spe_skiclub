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
use SC\ActiviteBundle\Form\voirActiviteType;
use SC\UserBundle\Entity\User;
use SC\UserBundle\Entity\Licence;
use SC\ActiviteBundle\Entity\Sortie;
use SC\ActiviteBundle\Entity\SortieRepository;
use SC\ActiviteBundle\Entity\Lieu;
use SC\ActiviteBundle\Entity\Saison;
use SC\ActiviteBundle\Entity\InscriptionSortie;
use SC\ActiviteBundle\Form\InscriptionSortieType;
use Symfony\Component\HttpFoundation\Cookie;



class InscriptionSortieController extends Controller 
{
    //genere une page d'erreur si besoin
    public function pageErreur($message) {
        $response = new Response;
        $response->setContent($message);
        $response->setStatusCode(Response::HTTP_NOT_FOUND);
        return $response;
    }
    
    //liste les enfants d'un utilisateur
    public function getEnfantAction($id,Request $request,$dateSortie,$lieu) {
        
        if ($request->getSession()->get('email') == null) {
            return $this->pageErreur("Vous devez être connecté pour accèder à ce lien");
        }             
        
        $em = $this->getDoctrine()->getManager();
        $season = new Saison;
        $year = $season->connaitreSaison();
        $activite = $em->getRepository('SC\ActiviteBundle\Entity\Activite')->find($id);
        $saison = $em->getRepository('SC\ActiviteBundle\Entity\Saison')->findOneByAnnee($year);
        $nomLieu = $em->getRepository('SC\ActiviteBundle\Entity\Lieu')->findOneByNomLieu($lieu);
        $sortie = $em->getRepository('SC\ActiviteBundle\Entity\Sortie')
                                                        ->findOneBy(array('dateSortie' => $dateSortie,'activite' =>  $activite, 
                                                                                            'saison'=>$saison,'lieu'=>$nomLieu));
        $parents = $em->getRepository('SC\UserBundle\Entity\User')->findOneByEmail($request->getSession()->get('email'));
        
        if(is_null($activite) OR is_null($sortie) OR is_null($nomLieu)) {
            return $this->pageErreur("paramètres entrés invalides");
        }
        $request->getSession()->set('sortie', $sortie);
        $enfants = $em->getRepository('SC\UserBundle\Entity\Enfant')->findBy(array('userParent' => $parents));
        
        return $this->render('SCActiviteBundle:Sortie:viewEnfant.html.twig', array('enfants'=> $enfants,'activite'=> $activite));        
        
      /*  if (is_null($activite)==true) {
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
            //$listParticipant = $em->getRepository('SC\ActiviteBundle\Entity\InscriptionSortie')->findBy(array('sortie'=>$sortie,'emailParent'=>$enfant));
            $listParticipant = $em->getRepository('SC\ActiviteBundle\Entity\InscriptionSortie')->findAll();
            
            $em->persist($inscription);
            $em->flush();
            
            $request->getSession()->set('inscription', $inscription);
            return $this->render('SCActiviteBundle::viewEnfant.html.twig', array('enfants'=> $enfant, 'sortie' => $sortie));
        }
        return $this->render('SCActiviteBundle::viewSortie.html.twig', array(
            'form' => $form->createView(), 'inscription' => 1,
            ));*/
    }
    
    // met a jout la table  inscriptionSortie
    public function inscrireEnfantAction($id,Request $request,/*$userParent,*/$nomEnfant,$prenomEnfant) {
        
        if ($request->getSession()->get('email') == null) {
            return $this->pageErreur("Vous devez être connecté pour accèder à ce lien");
        }             
        
        $em = $this->getDoctrine()->getManager();
        $activite = $em->getRepository('SC\ActiviteBundle\Entity\Activite')->find($id);
        $season = new Saison;
        $year = $season->connaitreSaison();
        $saison = $em->getRepository('SC\ActiviteBundle\Entity\Saison')->find($year);
        $userParent = $request->getSession()->get('email');
        //on verifie que les parametres sont bons
        if (is_null($saison) OR is_null($activite)) {
            return $this->pageErreur('paramètres entrés invalides');
        }
        //on verifie que l'enfant est inscrit a l'activite
        if ($this->inscritActivite($activite,$saison,$nomEnfant,$prenomEnfant,$userParent)==false) {
            return $this->pageErreur($nomEnfant.' '.'non inscrit à cette activité');
        }
        //on verifie que l'enfant n'est pas deja inscrit a cette sortie
        $sortie = $request->getSession()->get('sortie');
        if ($this->estInscrit($id,$sortie, $userParent, $nomEnfant, $prenomEnfant,$year)==true) {
            return $this->pageErreur($nomEnfant.' '.'est déja inscrit à cette sortie');
        }

        $inscriptionSortie = new InscriptionSortie;
        $inscriptionSortie->setDateSortie($sortie->getDateSortie());
        $inscriptionSortie->setEmailParent($userParent);
        $inscriptionSortie->setIdActivite($id);
        $inscriptionSortie->setNomEnfant($nomEnfant);
        $inscriptionSortie->setPreNomEnfant($prenomEnfant);
        $inscriptionSortie->setParticipation(0);
        $inscriptionSortie->setSaison($year);
        $inscriptionSortie->setLieu($sortie->getLieu()->getNomLieu());
        
        //on persist
        $em->persist($inscriptionSortie);
        $em->flush();
        $request->getSession()->getFlashBag()->add('info', 'votre inscription a bien été prise en compte');
        $mesInscriptions = $em->getRepository('SC\ActiviteBundle\Entity\InscriptionSortie')->findBy(array('emailParent'=>$userParent,'saison'=>$year));
        $request->getSession()->set('mesInscriptions', $mesInscriptions);
        return $this->render('SCActiviteBundle:Activite:view.html.twig', array('activite'=> $activite));
    }
    

    
    //retourne true si l'enfant est deja inscrit a la sortie
    //false sinon
    public function estInscrit($id,$sortie,$userParent,$nomEnfant,$prenomEnfant,$year) {
        $em = $this->getDoctrine()->getManager();
        $listeInscrit = $em->getRepository('SC\ActiviteBundle\Entity\InscriptionSortie')
                                ->findOneBy(array('dateSortie'=>$sortie->getDateSortie(),'idActivite'=>$id, 
                                        'emailParent'=>$userParent,'nomEnfant' => $nomEnfant, 'prenomEnfant'=> $prenomEnfant,'saison'=>$year,'lieu'=>$sortie->getLieu()->getNomLieu()));
        if($listeInscrit == null) {
            return false;
        }
        else {
            return true;
        }
    }
    
    //retourne true si l'enfant est inscrit a l'activite pour la saison courante
    //false sinon
    public function inscritActivite($activite,$saison,$nomEnfant,$prenomEnfant,$emailParent) {
        $em = $this->getDoctrine()->getManager();
        //on regarde si l'enfant est inscrit a l'activite pour la saison donnee
        $enfant = $em->getRepository('SC\ActiviteBundle\Entity\InscriptionActivite')
                        ->findOneBy(array('activite' => $activite, 'saison' => $saison, 'email' => $emailParent, 'nomEnfant'=>$nomEnfant,'prenomEnfant'=>$prenomEnfant));
        if ($enfant == null) {
            return false;
        }
        else {
            return true;
        }
    }
    //permet de lister les personnes inscrites aux sorties
    public function inscritsAction($id,Request $request) {
        
        if ($request->getSession()->get('email') == null) {
            return $this->pageErreur("Vous devez être connecté pour accèder à ce lien");
        }         
        
        $em = $this->getDoctrine()->getManager();
        $season = new Saison;
        $year = $season->connaitreSaison();        
        $saison = $em->getRepository('SC\ActiviteBundle\Entity\Saison')->find($year);
        $activite = $em->getRepository('SC\ActiviteBundle\Entity\Activite')->find($id);
        
        if (is_null($activite)) {
            return $this->pageErreur('paramètres entrés invalides');
        }
        
        $sorties = $em->getRepository('SC\ActiviteBundle\Entity\Sortie')
                            ->findBy(array('activite'=>$activite,'saison'=>$saison));
        $nomAct = $activite->getNomactivite();
        /*$inscriptions = $em->getRepository('SC\ActiviteBundle\Entity\InscriptionSortie')
                                ->findBy(array('dateSortie'=>$sorties->getDateSortie(),'idActivite'=>$id,'saison'=>$year));
        $request->getSession()->set('em',$em);*/
        return $this->render('SCActiviteBundle:Activite:tableauRecap.html.php', array('id'=> $id,'year'=>$year,'sorties'=>$sorties,'em'=> $em,'nomAct'=>$nomAct));
        
    }
    
    public function voirActiviteAction(Request $request) {
        
        if ($request->getSession()->get('email') == null) {
            return $this->pageErreur("Vous devez être connecté pour accèder à ce lien");
        }     
        
        $em = $this->getDoctrine()->getManager();
        $saison = new Saison;
        $year = $saison->connaitreSaison();
        $defaultData = array('message' => 'Type your message here');
        $parents = $em->getRepository('SC\UserBundle\Entity\User')->findOneByEmail($request->getSession()->get('email'));
        $listEnfants = $em->getRepository('SC\UserBundle\Entity\Enfant')->findBy(array('userParent' => $parents));
        $form = $this->createFormBuilder($defaultData)
           ->add('activite', 'entity', array('class'=> 'SC\ActiviteBundle\Entity\Activite','property' => 'nomActivite', 'multiple' => false,'expanded' => false,'required' => true 
            ))
            ->add('valider','submit')    
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()){
            $data = $form->getData();
            $activite = $data['activite'];
            $id = $activite->getId();
            $mesSorties = $em->getRepository('SC\ActiviteBundle\Entity\InscriptionSortie')->findBy(array('idActivite' => $id,'emailParent'=>$request->getSession()->get('email'),'saison' => $year));
            
            return $this->render('SCUserBundle:Security:mesSorties.html.twig', array('activite'=> $activite, 'mesSorties' => $mesSorties,'saison' => $year ));
        }
        
        return $this->render('SCUserBundle:Security:monCompte.html.twig', array('form' => $form->createView(),'voirActivite' => 1,'nom'=> $request->getSession()->get('email'), 'listEnfants'=>$listEnfants));
    }
    
    // permet de proposer deux choix a l'utilisateur lorsqu'il clique sur un lien
    // annuler sortie ou confirmer participation
    public function getChoixAction($id,Request $request,$dateSortie,$lieu,$nomEnfant,$prenomEnfant) {
        
        if ($request->getSession()->get('email') == null) {
            return $this->pageErreur("Vous devez être connecté pour accèder à ce lien");
        }             
        
        $em = $this->getDoctrine()->getManager();
        $saison = new Saison;
        $year = $saison->connaitreSaison();
        $activite = $em->getRepository('SC\ActiviteBundle\Entity\Activite')->find($id);
        //on verifie que les parametres sont valides
        if($this->parametreValide($id, $dateSortie, $lieu) == false) {
            return $this->pageErreur("informations fournies non correctes");
        }
        //liste des des inscriptions des enfants de l'utilisateur
        $mesSorties = $em->getRepository('SC\ActiviteBundle\Entity\InscriptionSortie')->findBy(array('idActivite' => $id,'emailParent'=>$request->getSession()->get('email'),'saison' => $year));
        //liste des inscrits pour la sorties demandée
        $inscrits = $em->getRepository('SC\ActiviteBundle\Entity\InscriptionSortie')->findBy(array('idActivite' => $id,'saison' => $year,'dateSortie' => $dateSortie, 'lieu'=>$lieu));
        
        return $this->render('SCUserBundle:Security:mesSorties.html.twig', array('activite'=> $activite, 'mesSorties' => $mesSorties,'choix'=>1,'inscrits'=>$inscrits,'nomEnfant'=>$nomEnfant,'prenomEnfant' => $prenomEnfant, 'dateSortie' => $dateSortie, 'lieu'=>$lieu,'saison'=>$year));
    }
    
    //met a jour la valeur participation lorsque qu'un utilisateur confirme sa participation a une sortie
    public function validationAction($id,Request $request,$dateSortie,$lieu,$nomEnfant,$prenomEnfant) {
        
        if ($request->getSession()->get('email') == null) {
            return $this->pageErreur("Vous devez être connecté pour accèder à ce lien");
        }             
        
        $em = $this->getDoctrine()->getManager();       
        //on verifie que les infos sont correctes
        if($this->parametreValide($id, $dateSortie, $lieu) == false) {
            return $this->pageErreur("informations fournies non correctes");
        }
        $sortie = $this->getSortie($id, $dateSortie, $lieu);        
        $parents = $em->getRepository('SC\UserBundle\Entity\User')->findOneByEmail($request->getSession()->get('email'));
        //on recupere les enfants pour la vue principale
        $listEnfants = $em->getRepository('SC\UserBundle\Entity\Enfant')->findBy(array('userParent' => $parents));
        $saison = new Saison;
        $year = $saison->connaitreSaison(); 
        //on verifie que l'enfant est bien un des fils de l'utilisateur, et qu'il est bien inscirt a la sortie
        if($this->estEnfant($nomEnfant,$prenomEnfant,$parents) == false OR $this->estInscrit($id,$sortie,$request->getSession()->get('email'),$nomEnfant,$prenomEnfant,$year) == false) {
            return $this->pageErreur("Erreur si l'identité de l'enfant");
        }
        //on met a jour l'entite
        $em->getRepository('SC\ActiviteBundle\Entity\InscriptionSortie')
                                ->confirmationParticipation($id,$dateSortie,$lieu,$nomEnfant,$prenomEnfant,$year,$request->getSession()->get('email'));
        $request->getSession()->getFlashBag()->add('info', 'la confirmation de votre participation a bien ete prise en compte');
         
        return $this->render('SCUserBundle:Security:monCompte.html.twig',array('listEnfants'=>$listEnfants));
    }
    
    //pour verifier que l'enfant est bien un fils de l'utilisateur
    //return true si l'utilisateur a bien comme fils ,nomEnfant prenomEnfant
    //false sinon
    public function estEnfant($nomEnfant,$prenomEnfant,$parents) {
        $em = $this->getDoctrine()->getManager();
        $mesEnfants = $em->getRepository('SC\UserBundle\Entity\Enfant')->findBy(array('userParent' => $parents));
        foreach ($mesEnfants as $enfant) {
            if ($enfant->getNomEnfant() == $nomEnfant AND $enfant->getPrenomEnfant() == $prenomEnfant) {
                return true;
            }
        }
        return false;
    }
    
    // supprime de la table inscription sortie l'enfant pour la sortie consideree sur l'annee et l'activite
    public function annulationAction($id,Request $request,$dateSortie,$lieu,$nomEnfant,$prenomEnfant) {
        
        if ($request->getSession()->get('email') == null) {
            return $this->pageErreur("Vous devez être connecté pour accèder à ce lien");
        }            
        
        $saison = new Saison;
        $year = $saison->connaitreSaison(); 
        $em = $this->getDoctrine()->getManager();
        $parents = $em->getRepository('SC\UserBundle\Entity\User')->findOneByEmail($request->getSession()->get('email'));          
        //on verifie que les infos sont correctes
        if($this->parametreValide($id, $dateSortie, $lieu) == false) {
            return $this->pageErreur("informations fournies non correctes");
        }
        $sortie = $this->getSortie($id, $dateSortie, $lieu);
        //on verifie que l'enfant est bien un des fils de l'utilisateur, et qu'il est bien inscirt a la sortie        
        if($this->estEnfant($nomEnfant,$prenomEnfant,$parents) == false OR $this->estInscrit($id,$sortie,$request->getSession()->get('email'),$nomEnfant,$prenomEnfant,$year) == false) {
            return $this->pageErreur("Erreur si l'identité de l'enfant");
        }      
        //on recupere les enfants pour la vue principale
        $listEnfants = $em->getRepository('SC\UserBundle\Entity\Enfant')->findBy(array('userParent' => $parents));        
        //on supprime de la table
        $inscription = $em->getRepository('SC\ActiviteBundle\Entity\InscriptionSortie')
                                ->findOneBy(array('idActivite' =>$id,'dateSortie'=>$dateSortie,'lieu' => $lieu,'emailParent'=> $request->getSession()->get('email'),'nomEnfant' => $nomEnfant, 'prenomEnfant' => $prenomEnfant, 'saison' => $year));
        if(is_null($inscription)) {
            return $this->pageErreur("vous n'etes pas inscrit à cette sortie");
        }
        
        $em->remove($inscription);
        $em->flush();
        
        $request->getSession()->getFlashBag()->add('info', 'annulation enregistree'); 
        return $this->render('SCUserBundle:Security:monCompte.html.twig',array('listEnfants'=>$listEnfants));
    }
    
    //permet de s'assurer que les parametres sont valides
    public function parametreValide($id,$dateSortie,$lieu) {
        $em = $this->getDoctrine()->getManager();
        $saison = new Saison;
        $year = $saison->connaitreSaison();
        //on s'assure que les parametres n'ont pas ete changer a la main
        $saison = $em->getRepository('SC\ActiviteBundle\Entity\Saison')->findOneByAnnee($year);
        $lieuObjet = $em->getRepository('SC\ActiviteBundle\Entity\Lieu')->findOneByNomLieu($lieu);
        $activite = $em->getRepository('SC\ActiviteBundle\Entity\Activite')->find($id);
        $sortie = $em->getRepository('SC\ActiviteBundle\Entity\Sortie')->findOneBy(array('lieu'=> $lieuObjet, 'activite'=>$activite,'dateSortie'=>$dateSortie));        
    
        if(is_null($activite) OR is_null($lieuObjet) OR is_null($sortie)) {
            return false;
        }
        else {
            return true;
        }
    }
    
    public function getSortie($id,$dateSortie,$lieu) {
        $em = $this->getDoctrine()->getManager();
        $season = new Saison;
        $year = $season->connaitreSaison();
        $activite = $em->getRepository('SC\ActiviteBundle\Entity\Activite')->find($id);
        $saison = $em->getRepository('SC\ActiviteBundle\Entity\Saison')->findOneByAnnee($year);
        $nomLieu = $em->getRepository('SC\ActiviteBundle\Entity\Lieu')->findOneByNomLieu($lieu);
        $sortie = $em->getRepository('SC\ActiviteBundle\Entity\Sortie')
                                                        ->findOneBy(array('dateSortie' => $dateSortie,'activite' =>  $activite, 
                                                                                            'saison'=>$saison,'lieu'=>$nomLieu));        
    
        return $sortie;
    }
    
    
    public function obtenirSortieAction($id,Request $request) {
        
        if ($request->getSession()->get('email') == null) {
            return $this->pageErreur("Vous devez être connecté pour accèder à ce lien");
        }
        $em = $this->getDoctrine()->getManager();
        $activite = $em->getRepository('SC\ActiviteBundle\Entity\Activite')->find($id);
        
        if(is_null($activite)) {
            return $this->pageErreur("activite inconnue");
        }
        
        $saison = new Saison;
        $year = $saison->connaitreSaison();
        $defaultData = array('message' => 'Type your message here');

        $form = $this->createFormBuilder($defaultData)
           ->add('sortie', 'entity', array('class'=> 'SC\ActiviteBundle\Entity\Sortie','multiple' => false,'expanded' => false,'required' => true,  'query_builder' => function (SortieRepository $repository) use ($id,$year) { return $repository-> 
             getSortie($id,$year); }))
            ->add('valider','submit')    
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()){
            $data = $form->getData();
            $sortie = $data['sortie'];
            $inscrits = $em->getRepository('SC\ActiviteBundle\Entity\InscriptionSortie')->findBy(array('idActivite' => $id,'saison' => $year,'lieu' => $sortie->getLieu()->getNomLieu(), 'dateSortie' => $sortie->getDateSortie()));
            
            return $this->render('SCActiviteBundle:Sortie:inscritSortie.html.twig', array('activite'=> $activite, 'inscrits' => $inscrits,'saison' => $year, 'sortie' => $sortie ));
        }
        
        return $this->render('SCActiviteBundle:Sortie:ListSorties.html.twig', array('form' => $form->createView(),'activite'=> $activite,'saison' => $year ));        
        
    }
    
}    