<?php session_start();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Tournoi Mario Kart</title>
<link rel="stylesheet" type="text/css" href="style.css" />
<?php
if($_SESSION['pseudo'])
{
	if($_SESSION['pseudo'] == 'Meuh')
	{
?>
<script type="text/javascript">
function determinerAjax()
{
	var ajax
	try 
	{  
		ajax = new ActiveXObject('Msxml2.XMLHTTP');   
	}
	catch (e) 
	{
		try 
		{   
			ajax = new ActiveXObject('Microsoft.XMLHTTP');    
		}
		catch (e2) 
		{
			try 
			{  
				ajax = new XMLHttpRequest();    
			}
			catch (e3) 
			{
				ajax = false;
				alert("Votre navigateur ne supporte pas AJAX !");
			}
		}
	}	
	return ajax;
}

function changerAjax()
{
	if(ajax.readyState == 4)
	{
		if(ajax.status  == 200)
		{
			document.getElementById("verifier").innerHTML = ajax.responseText;
		}
	}
	else
	{
		document.getElementById("verifier").innerHTML = 'Vérification...';
	}
}

function verifier()
{
	ajax = determinerAjax();
	mois = document.declencher.mois.value;
	jour = document.declencher.jour.value;
	annee = document.declencher.annee.value;
	ordre = document.declencher.ordre.value;
	
	post = "mois=" + mois + "&annee=" + annee + "&jour=" + jour + "&ordre=" +ordre;
	
	ajax.onreadystatechange = changerAjax;
	ajax.open('POST', 'verifier.php',  true);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	ajax.send(post); 
}
</script>
<?php 
	}
}
?>
</head>
<body><div id="titre">
</div>
<?php
if($_SESSION['pseudo'])
{
	echo '<p id="statut">Connecté sous le pseudo '.$_SESSION['pseudo'].' | <a href="profil.php">Retour au profil</a> | <a href="index.php?action=logout">Se déconnecter</a></p>';

	if(isset($_GET['menu']))
	{
		if($_GET['menu'] == 'disponibilite')
		{
			include('disponibilite.php');
		}
		elseif($_GET['menu'] == 'participants')
		{
			include('participants.php');
		}
		elseif($_GET['menu'] == 'informations')
		{
			include('informations.php');
		}
		elseif($_GET['menu'] == 'voirmatch')
		{
			include('voirmatch.php');
		}
		else
		{
			die('Option invalide');
		}
	}
	else
	{
	?>
	<p><strong><a href="profil.php?menu=voirmatch">Voir et gérer le prochain match prévu</a></strong></p>
	
	<p><a href="profil.php?menu=disponibilite">Gérer ses disponibilités</a></p>
	<p><a href="profil.php?menu=informations">Modifier ses informations (mot de passe, adresse e-mail, code ami)</a></p>
	<p><a href="profil.php?menu=participants">Liste des matchs et participants</a></p>
	<?php
	if($_SESSION['pseudo'] == 'Meuh')
	{
		?>
		<p>Administration</p>
		<div class="formulaire"><form action="profil.php?menu=tour&amp;action=declencher&amp" method="post" name="declencher">
			<p>
			Date : <input type="text" name="jour" size="2" maxlength="2" />/<input type="text" name="mois" size="2" maxlength="2" />/<input type="text" name="annee" size="4" maxlength="4" /><br />
			Tour : <input type="text" name="tour" size="1" /><br />
			Ordre :
			<select name="ordre" size="1">
				<option value="ListeAdversaires">ListeAdversaires</option>
				<option value="NbVictoires">NbVictoires</option>
				<option value="CodeAmi">CodeAmi</option>
				<option value="Email">Email</option>
				<option value="MDP">MDP</option>
				<option value="ID">ID</option>
				<option value="Pseudo">Pseudo</option>
			</select>
			<input type="button" value="Vérifier" onClick="verifier();">
			<div id="verifier"></div>
			<br />
			<input type="submit" value="Déclencher le tour suivant" />
			<input type="hidden" name="PHPSESSID" value="<?php echo session_id();?>" />
			</p>
		</form></div>
		<?php
		
		if(isset($_GET['menu']) && $_GET['menu'] == "tour")
		{
			if($_GET['action'] == "declencher")
			{
				$confirmation = 1;
				include("declencher.php");
			}
		}
	}
	
	}
}
else
{
	echo '<p>Vous ne pouvez accéder à cette page directement. Connectez-vous à partir de la <a href="index.php">page d\'accueil</a>.</p>';
}
?>
</body>
</html>
