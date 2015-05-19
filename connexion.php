<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Se connecter </title>
    </head>
    <body>
        <h2>Page de teeeeest</h2>
        

	<?php
function aaa(){
		echo '<form method="post" action="connexion.php">
		<fieldset>
		<legend>Connexion</legend>
		<p>
		<label for="pseudo">Pseudo :</label><input name="pseudo" type="text" id="pseudo" /><br />
		<label for="password">Mot de Passe :</label><input type="password" name="password" id="password" />
		</p>
		</fieldset>
		<p><input type="submit" value="Connexion" /></p></form>
		<a href="./register.php">Pas encore inscrit ?</a>
	 
		</div>
		</body>
		</html>';
		}
	if (!isset($_POST['pseudo'])) //On est dans la page de formulaire
	{
		
		aaa();

	}
	else
	{
    		$message='';
   		 if (empty($_POST['pseudo']) || empty($_POST['password']) ) //Oublie d'un champ
   		 {
        		$message = '<p>une erreur s\'est produite pendant votre identification. Vous devez remplir tous les champs</p> <p>Cliquez <a href="./connexion.php">ici</a> pour revenir</p>';
		aaa();
   		 }
    		else //On check le mot de passe
    		{
		$db = new PDO('mysql:host=localhost;dbname=users;charset=utf8', 'root', '');
        	$query=$db->prepare('SELECT * FROM users WHERE pseudo = :pseudo');
        	$query->bindValue(':pseudo',$_POST['pseudo'], PDO::PARAM_STR);
        	$query->execute();
        	$data=$query->fetch();
		//if ($data['membre_mdp'] == md5($_POST['password'])) // Acces OK !
		if($data['mdp'] == $_POST['password'])// Acces OK !
		{
	    		$_SESSION['pseudo'] = $data['pseudo'];
	    		$message = '<p>Bienvenue '.$data['pseudo'].', vous êtes maintenant connecté!</p>
			<p>Cliquez <a href="./index.php">ici</a> pour revenir à la page d accueil</p>';  
		}
		else // Acces pas OK !
		{
	    		$message = '<p>Une erreur s\'est produite pendant votre identification.<br /> Le mot de passe ou le pseudo entré n\'est pas correcte.</p><p>Cliquez <a href="./connexion.php">ici</a> pour revenir à la page précédente <br /><br />Cliquez <a href="./index.php">ici</a> pour revenir à la page d accueil</p>';
		aaa();
		}
    $query->CloseCursor();
    }
    echo $message.'</div></body></html>';

}
	?>
    </body>
</html>
