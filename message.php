<p>Envoyer un message à votre adversaire</p>
<?php

if(isset($_POST['message']) && $_POST['message'])
{
	echo '<p>Votre message a bien été envoyé à votre adversaire. Il pourra répondre s\'il le désire en passant par cette page.</p>';
			$message = 'Bonjour '.$pseudoAdversaire.' ! Votre adversaire '.$_SESSION['pseudo'].' vous a envoyé un message. Le voici :

'.stripslashes($_POST['message']).'

Et vous vous laissez dire des choses pareilles ? Pour répondre à votre adversaire, vous pouvez utiliser la fonction d\'envoi de message disponible sur la page de gestion des matchs.
N\'UTILISEZ PAS la fonction "Répondre" de votre client de messagerie. Pour répondre à votre adversaire, vous devez obligatoirement passer par le site. Cela permet à vous et votre adversaire de ne pas connaitre vos adresses, certaines personnent ne voulant pas que leur adresse e-mail soit connue.


En cas de problème, n\'hésitez pas à contacter Meuh par SFM. Bonne chance pour votre match !
';
			$sujet = 'Tournoi Mario Kart du Mario Museum : votre adversaire vous a envoyé un message.';
			include("mail.php");
			//echo $message;
			mail($emailAdversaire, $sujet, $message, $headers);
}
else
{
	?>
	<p>Bien qu'il soit conseillé de plutôt utiliser les SFM (vous êtes membre du MM, non ?), vous pouvez utiliser cette interface pour envoyer un message à votre adversaire,
	particulièrement utile si vous avez un problème avec le match prévu avec lui.</p>
	<div class="formulaire"><form method="post" action="profil.php?menu=voirmatch&amp;action=message">
	<p>
	<textarea cols="100" rows="20" name="message"></textarea>
	<br />
	<input type="submit" value="Envoyer le message" />
	<input type="hidden" name="PHPSESSID" value="<?php echo session_id();?>" />
	</p>
	</form></div>
	
	<?php
}
	echo '<p><a href="profil.php?menu=voirmatch">Retour aux informations du match</a></p>';
?>
