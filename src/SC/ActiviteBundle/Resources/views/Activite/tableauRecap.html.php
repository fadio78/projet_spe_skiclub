<HTML>
 <HEAD>
  <TITLE>Titre de la page</TITLE>
 </HEAD>
<?php echo $view->render('::layout.html.php'); ?>

 <BODY>
     <center/>
     <h2>Activite : <?php echo $nomAct?></h2>
     <h2>Saison : <?php echo $year?></h2>
     <br/><br/>
<ul>     
<?php
    foreach ($sorties as $sortie) { ?>
     <li> <?php  echo $sortie->getDateSortie().' '.$sortie->getLieu()->getNomLieu();?> </li>
     
     <br/>
<?php     
        $inscrits = $em->getRepository('SC\ActiviteBundle\Entity\InscriptionSortie')
                        ->findBy(array('dateSortie'=>$sortie->getDateSortie(),'idActivite'=>$id,'saison'=>$year,'lieu'=>$sortie->getLieu()->getNomLieu()));
?>   <?php      
        if(empty($inscrits)) { ?> 
       <ul> <li>  <?php  echo 'aucuns enfants inscrits'; ?></li> </ul>
        <?php } ?>
     
        <?php
        foreach($inscrits as $inscrit) { ?>
        <ul>  <li> <?php echo $inscrit->getNomEnfant().' '.$inscrit->getParticipation().' '.$inscrit->getEmailParent(); ?> </li> </ul>
 <br/>
<?php
        }
?>  
<br/> <br/>
<?php
    }                    
?>  
</ul>
    </BODY>
   
</HTML>     

