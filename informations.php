<p><strong>Modifier ses informations</strong></p>
<?php
if(isset($_GET['action']) && $_GET['action'] == 'changer')
{
	echo '<p>';
	include('config.php');
	
	if($_POST['mdp'] != '')
	{
		$mdp = md5($_POST['mdp']);
		echo 'Votre mot de passe a été modifié.<br />';
		$requete = 'UPDATE participants SET MDP = "'.$mdp.'" WHERE ID = "'.$_SESSION['ID'].'"';
		$reponse = mysql_query($requete);
	}
	
	if($_POST['ami1'] != '' && $_POST['ami2'] != '' && $_POST['ami3'] != '')
	{		
		echo 'Votre code ami a été modifié.<br />';
		$codeami = htmlspecialchars($_POST['ami1']).'-'.htmlspecialchars($_POST['ami2']).'-'.htmlspecialchars($_POST['ami3']);
		$requete = 'UPDATE participants SET CodeAmi = "'.$codeami.'" WHERE ID = "'.$_SESSION['ID'].'"';
		$reponse = mysql_query($requete);
	}
	
	if($_POST['email'] != '')
	{
		echo 'Votre adresse email a été modifiée. Un mail de confirmation vous a été envoyé. Si vous ne le recevez pas, vérifiez que l\'adresse que vous venez d\'entrer est correcte.';
		$requete = 'UPDATE participants SET Email = "'.$email.'" WHERE ID = "'.$_SESSION['ID'].'"';
		$reponse = mysql_query($requete);
		
		$message = 'Bonjour '.$_SESSION['pseudo'].' !
Ce message vous a été envoyé afin de confirmer votre changement d\'adresse e-mail. Puisque vous l\'avez reçu, cela signifie que votre adresse est valide : vous pourrez donc recevoir des notifications concernant vos matchs. 
En cas de problème, n\'hésitez pas à contacter Meuh par SFM.

Amusez-vous bien !';
		
		$sujet = 'Confirmation de changement d\'adresse email au tournoi Mario Kart du Mario Museum';
		
		include("mail.php");
		mail(htmlspecialchars($_POST['email']), $sujet, $message, $headers);
	}
	
	mysql_close();
	echo '</p>';
}
?>
<p>Cette page vous permet de modifier votre mot de passe, votre adresse e-mail ou votre code ami si vous l'avez entré de manière incorrecte. Laissez le champ vide si vous ne désirez pas changer l'information.</p>

<div class="formulaire"><form action="profil.php?menu=informations&amp;action=changer" method="post">
<p>
Nouveau mot de passe :<br />
<input type="password" name="mdp" /><br />
Nouvelle adresse e-mail :<br />
<input type="text" size="50" name="email" /><br />
Nouveau code ami :<br />
<input type="text" name="ami1" size="4" maxlength="4" />-<input type="text" name="ami2" size="4" maxlength="4" />-<input type="text" name="ami3" size="4" maxlength="4" /><br />
<br /><input type="submit" />
<input type="hidden" name="PHPSESSID" value="<?php echo session_id();?>" />
</p>
</form></div>
