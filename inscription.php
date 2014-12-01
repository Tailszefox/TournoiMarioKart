<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Tournoi Mario Kart</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body><div id="titre">
</div>
<p>Inscription au tournoi</p>
<?php 
if($_POST['pseudo'] && $_POST['mdp'] && $_POST['ami1'] &&$_POST['ami2'] &&$_POST['ami3'] && $_POST['email'])
{
	$pseudoEntre = htmlspecialchars($_POST['pseudo']);
	$mdp = md5($_POST['mdp']);
	$codeami = htmlspecialchars($_POST['ami1']).'-'.htmlspecialchars($_POST['ami2']).'-'.htmlspecialchars($_POST['ami3']);
	$email = htmlspecialchars($_POST['email']);
	
	require('config.php');
	//Vérification de la non-existence d'un gars avec le même pseudo
	$requete = 'SELECT * FROM participants WHERE Pseudo="'.$pseudoEntre.'" OR CodeAmi="'.$codeami.'" OR Email="'.$email.'"';
	$reponse = mysql_query($requete);
	
	if(mysql_affected_rows() > 0)
	{
		echo '<p>Le pseudo, le code ami ou l\'adresse email que vous voulez utiliser existe déjà. Vous essayez de vous inscrire deux fois ou quoi ?<br /></p>';
	}
	else
	{
		$requete = 'INSERT INTO participants (ID, Pseudo, MDP, CodeAmi, Email) VALUES("", "'.$pseudoEntre.'", "'.$mdp.'", "'.$codeami.'", "'.$email.'")';
		$reponse = mysql_query($requete);
		
		$requete = 'SELECT ID FROM participants WHERE Pseudo="'.$pseudoEntre.'"';
		$reponse = mysql_query($requete);
		
		while($donnees = mysql_fetch_array($reponse))
		{
			$IDparticipant = $donnees['ID'];
		}
		
		$requete = 'INSERT INTO disponibilite (ID, IDparticipant) VALUES("", "'.$IDparticipant.'")';
		$reponse = mysql_query($requete);
		
		echo '<p>Votre inscription a bien été prise en compte. Connectez-vous à partir de la page d\'accueil pour régler vos options.<br />
		Un mail vous a également été envoyé à l\'adresse indiquée. Si vous ne le recevez pas, rendez vous dans votre profil pour pouvoir la changer. Si vous avez entré une fausse adresse e-mail, vous ne pourrez recevoir
		les notifications concernant les matchs, et vous louperez des messages importants qui pourraient vous faire perdre.<br /><br />';
		
		$message = 'Bonjour '.$pseudoEntre.' !
Ce message vous a été envoyé pour confirmer votre inscription au tournoi. Puisque vous l\'avez reçu, cela veut dire que votre adresse e-mail est bien valide, vous recevrez donc un message lorsque vous devrez participer à un match.
N\'oubliez pas de vous connecter et de modifier vos préférences pour améliorer la sélection de vos adversaires.
Si vous êtes confronté à un problème, envoyez un SFM à Meuh.

Sur ce, bon amusement !';
		
		$sujet = 'Confirmation d\'inscription au tournoi Mario Kart du Mario Museum';
		
		include('mail.php');
		mail($email, $sujet, $message, $headers);

	}
	mysql_close();
}
else
{
/*
<p>Veuillez remplir tous les champs afin de pouvoir vous inscrire au tournoi.<br />
Votre adresse e-mail sera utilisée pour vous informer de l'heure et de la date de votre prochain match. Elle ne sera jamais donnée aux autres candidats !</p>
<div class="formulaire"><form method="post" action="inscription.php">
	<p>
	Pseudo :<br />
	<input type="text" name="pseudo" /><br /><br />
	Mot de passe :<br />
	<input type="password" name="mdp" /><br /><br />
	Code ami Mario Kart Wii :<br />
	<input type="text" name="ami1" size="4" maxlength="4" />-<input type="text" name="ami2" size="4" maxlength="4" />-<input type="text" name="ami3" size="4" maxlength="4" /><br /><br />
	Adresse e-mail:<br />
	<input type="text" name="email" size="50" /><br />
	<br />
	<input type="submit" value="S'inscrire" />
	<input type="hidden" name="PHPSESSID" value="<?php echo session_id();?>" />
	</p>
*/
?>
<p>Désolé, les inscriptions sont fermées pour cette session du tournoi !<br />Vous pourrez toujours retenter votre chance lors d'une prochaine session.
En attendant, n'hésitez pas à suivre les résultats de la session qui se déroule actuellement.</p>
<?php
}
?>
</form></div>
<p><a href="index.php">Retour à l'accueil</a></p>
</body>
</html>
