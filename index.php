<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Tournoi Mario Kart</title>
<link rel="stylesheet" type="text/css" href="style.css" />
<script type="text/javascript">
function verifierUtilisateur()
{
	requete = determinerRequete();
	requete.onreadystatechange = changementEtat;

	pseudo = document.identifier.pseudo.value;
	mdp = document.identifier.mdp.value;
	PHPSESSID = document.identifier.PHPSESSID.value;
	
	requete.open('POST', 'connexionA.php',  true);
	requete.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	 
	var post = 'pseudo=' + pseudo + '&mdp=' + mdp + '&PHPSESSID=' + PHPSESSID;
	requete.send(post); 
	return false;
}

function determinerRequete()
{
	var requete;
	try 
	{  
		requete = new ActiveXObject('Msxml2.XMLHTTP');   
	}
	catch(e) 
	{
		try 
		{   
			requete = new ActiveXObject('Microsoft.XMLHTTP');    
		}
		catch(e2) 
		{
			try 
			{
				requete = new XMLHttpRequest();    
			}
			catch(e3) 
			{
				requete = false;
			}
		}
	}
	
	return requete;
}

function changementEtat()
{	
	if(requete.readyState == 4)
	{
		if(requete.status == 200)
		{
			xml = requete.responseXML;
			
			etat = xml.getElementsByTagName("etat")[0].childNodes[0].nodeValue;
			confirmation = xml.getElementsByTagName("confirmation")[0].childNodes[0].nodeValue;
			
			document.getElementById('message').innerHTML = confirmation;
			
			if(etat == 1)
			{
				document.getElementById('formulaireConnexion').innerHTML = '';
				setTimeout('location.href = "profil.php"', 2000);
			}
		}
		else
		{
			document.getElementById('message').innerHTML = 'Erreur ' + requete.status;
		}
	}
	else
	{
		document.getElementById('message').innerHTML = 'Vérification...';
	}
}
</script>
</head>
<body><div id="titre">
</div>
<?php
if(isset($_GET['action']) && $_GET['action'] == 'logout')
{
	session_destroy();
	echo '<p><strong>Vous avez bien été déconnecté</strong></p>';
}
else
{
	echo '<p><strong>Connexion</strong></p>';
}
?>
<div class="formulaire"><form action="connexion.php" method="post" onSubmit="return verifierUtilisateur();" name="identifier">
	<div id="message"></div>
	<p id="formulaireConnexion">
	Pseudo :<br />
	<input type="text" name="pseudo" /><br />
	Mot 	de passe :<br />
	<input type="password" name="mdp" /><br /><br />
	<input type="hidden" name="PHPSESSID" value="<?php echo session_id();?>" />
	<input type="submit" value="Se connecter" />
	</p>
</form></div>
<p><a href="inscription.php">Inscription au tournoi</a></p>
<p><a href="classement.php">Accéder au classement sans se connecter</a></p>
</body>
</html>
