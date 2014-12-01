<?php
session_start();
if($_POST['pseudo'] && $_POST['mdp'])
{
	$pseudoEntre = htmlspecialchars($_POST['pseudo']);
	$mdp = md5($_POST['mdp']);
	
	include('config.php');
	$requete = 'SELECT * FROM participants WHERE Pseudo="'.$pseudoEntre.'" AND MDP="'.$mdp.'"';
	$reponse = mysql_query($requete);
	
	if(mysql_affected_rows() == 1)
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<head>
<?php
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
	echo '<meta http-equiv="refresh" content="3;url=profil.php"/>';
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
}
elseif($etat == 2)
{
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
}
else
{
	echo '<meta http-equiv="refresh" content="3;url=index.php"/>';
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
}
?>
<title>Tournoi Mario Kart</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body><div id="titre">
</div>
<div id="connexion">
<?php
if($etat == 1)
{
	echo '<p>Vous êtes maintenant connecté. Vous allez être redirigé sur votre <a href="profil.php">profil</a>.</p>';
}
elseif($etat == 2)
{
	session_destroy();
	echo '<p>Le pseudo entré n\'existe pas, ou le mot de passe est incorrect.<br />
	<a href="inscription.php">Inscrivez-vous</a> si vous ne l\'êtes pas encore, ou <a href="index.php">retournez en arrière</a> et vérifiez votre saisie</a></p>';
}
else
{
	echo '<p>Veuillez vous connecter avant d\'entrer sur cette page. Vous allez être redirigé vers <a href="index.php>l\'accueil</a></p>';
}

if($_POST['pseudo'] && $_POST['mdp'])
{
	mysql_close();
}
?>
</div>
</body>
</html>
