<HTML>
 <HEAD>
  <TITLE>Titre de la page</TITLE>
 </HEAD>
<?php echo $view->render('::layout.html.php'); ?>

 <BODY>
     <center/>
     <h2>Activite : <?php echo $nomAct?></h2>
     <h2>Saison : <?php echo $year?></h2>

<ul>     
<?php
    foreach ($sorties as $sortie) { ?>      <br/><br/>
 
  <li>   <?php  echo $sortie->getDateSortie().' '.$sortie->getLieu()->getNomLieu();?> </li>
     
<?php     
        $inscrits = $em->getRepository('SC\ActiviteBundle\Entity\InscriptionSortie')
                        ->findBy(array('dateSortie'=>$sortie->getDateSortie(),'idActivite'=>$id,'saison'=>$year,'lieu'=>$sortie->getLieu()->getNomLieu()));
?>   <?php      
        if(empty($inscrits)) { ?> 
        <br/>
       <center> <b> <?php  echo 'aucun enfant inscrit'; ?></b> </center>
        <?php } else {?>
  <table border="1" cellpadding="10" cellspacing="1" width="50%">
        <tr> 
            <th><center>Nom </center></th>
            <th><center>Prenom </center></th>
            <th><center>Confirmé</center></th>
        <tr/> 
       
        <?php

        foreach($inscrits as $inscrit) { ?>
       
     

            <tr>     
                <td><center> <?php echo $inscrit->getNomEnfant(); ?>  </center></td>  
                <td><center>  <?php echo $inscrit->getPrenomEnfant(); ?>   </center></td>  
                
                    <?php if ($inscrit->getParticipation() == 1) {?> 
                       <td width=5  bgcolor="#01DF01"><center></center></td>
                    <?php } else { ?> 
                        <td width=5  bgcolor="#DF0101"><center></center></td>
                  <?php } ?> 
            </tr> 
       
<?php
        }
?>  
<br/> <br/> </table>
<?php
        } }                    
?>  
</ul>
    </BODY>
   
</HTML>     

