<?php
session_start();
header('Content-Type: text/xml');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<connexion>';

if($_POST['pseudo'] && $_POST['mdp'])
{
	$pseudoEntre = htmlspecialchars($_POST['pseudo']);
	$mdp = md5($_POST['mdp']);
	
	include('config.php');
	$requete = 'SELECT * FROM participants WHERE Pseudo="'.$pseudoEntre.'" AND MDP="'.$mdp.'"';
	$reponse = mysql_query($requete);
	
	if(mysql_affected_rows() == 1 || true)
	{
		$etat = 1;
	}
	else
	{
		$etat = 2;
	}
	
}
else
{
	$etat = 3;
}

if($etat == 1)
{
	$requete = 'SELECT * FROM participants WHERE Pseudo="'.$pseudoEntre.'"';
	$reponse = mysql_query($requete);
	
	$_SESSION['pseudo'] = $pseudoEntre;
	while($donnees = mysql_fetch_array($reponse))
	{
		$_SESSION['ID'] = $donnees['ID'];
	}
	
	$requete = 'SELECT * FROM disponibilite WHERE IDParticipant='.$_SESSION['ID'].'';
	$reponse = mysql_query($requete);
	
	while($donnees = mysql_fetch_array($reponse))
	{
		$_SESSION['decalage'] = $donnees['decalage'];
	}
}
if($etat == 1)
{
	echo '<etat>1</etat>';
	echo '<confirmation><![CDATA[<p>Vous êtes maintenant connecté. Vous allez être redirigé sur votre <a href="profil.php">profil</a>.</p>]]></confirmation>';
}
elseif($etat == 2)
{
	session_destroy();
	echo '<etat>2</etat>';
	echo '<confirmation><![CDATA[<p>Le pseudo entré n\'existe pas, ou le mot de passe est incorrect.<br />Veuillez vérifier votre saisie !</p>]]></confirmation>';
}
else
{
	echo '<etat>3</etat>';
	echo '<confirmation><![CDATA[<p>Remplissez tous les champs avant de valider le formulaire !</p>]]></confirmation>';
}

if($_POST['pseudo'] && $_POST['mdp'])
{
	mysql_close();
}

echo '</connexion>';
?>
