<?php
if($IDGagnant != 0)
{
	echo '<p>Le match a déjà été joué, vous ne pouvez donc pas le reporter, hé !</p>';
}
else
{
	include('traduction.php');
	$requete = 'SELECT * FROM disponibilite WHERE IDParticipant = '.$IDAdversaire.'';
	$reponse = mysql_query($requete);
		while($donnees = mysql_fetch_array($reponse))
	{
		$decalageAdversaire = $donnees['decalage'];
	}
	
	echo '<p>Reporter le match</p>';
	if($dateRepport == 0)
	{
		if($_POST['jour'] && $_POST['mois'] && $_POST['annee'] && $_POST['heure'] && $_POST['minute'])
		{
			echo '<p>Un e-mail a été envoyé à votre adversaire, avec la date que vous venez de proposer. Vous serez informé de sa décision dès qu\'il répondra.</p>';
			$dateRepport = mktime($_POST['heure'], $_POST['minute'], 00, $_POST['mois'], $_POST['jour'], $_POST['annee']);
			$dateRepport -= $_SESSION['decalage']*3600;
			
			//echo ''.str_replace($motsAnglais, $motsFrancais, date('l d F Y à G:i', $dateRepport)).'<br />';
			
			$requete = 'UPDATE matchs SET DateReport="'.$dateRepport.'", QuiRepporte='.$_SESSION['ID'].'  WHERE ID='.$IDMatch.'';
			$reponse = mysql_query($requete);
			
			$message = 'Bonjour '.$pseudoAdversaire.' ! Votre adversaire '.$_SESSION['pseudo'].' désire reporter le match qui était prévu. 
Pour connaitre la date choisie, rendez-vous sur votre profil, puis cliquez sur "Voir et gérer le prochain match prévu" puis sur "Proposer une autre date pour le match".
Vous pourrez alors accepter de reporter le match à la date chosie, ou refuser et proposer une autre date.

En cas de problème, n\'hésitez pas à discuter avec votre adversaire, ou à contacter Meuh par SFM. À la prochaine !';
			$sujet = 'Tournoi Mario Kart du Mario Museum : votre adversaire veut reporter le match.';
			include("mail.php");
			//echo $message;
			mail($emailAdversaire, $sujet, $message, $headers);
			
		}
		else
		{
			echo '<p>Votre prochain match est prévu le '.str_replace($motsAnglais, $motsFrancais, date('l d F Y à G:i', $date)).' (soit le '.str_replace($motsAnglais, $motsFrancais, date('l d F Y à G:i', $date+$_SESSION['decalage']*3600)).' en prenant en compte votre décalage horaire).<br />
			Si vous ne pouvez participer au match, vous devez alors proposer une nouvelle date. Cette date sera soumise à votre adversaire, et si il accepte, le match sera déplacé à cette date.</p>';
			
			
			if($decalageAdversaire != 0 || $_SESSION['decalage'] != 0)
			{
				echo '<p>Votre adversaire possède '.$decalageAdversaire.' heure(s) de décalage avec le serveur, et vous en possèdez '.$_SESSION['decalage'].'. Prenez garde à proposer une heure
				qui soit possible pour vous deux : si, avec le décalage, vous proposez à votre adversaire de jouer à 2 heures du matin chez lui, il risque fortement de refuser !</p>'; 
			}
			
			?>
			<div class="formulaire"><form action="profil.php?menu=voirmatch&amp;action=reporter" method="post">
			<p>
			Reporter le match le  <input type="text" name="jour" size="2" maxlength="2" />/<input type="text" name="mois" size="2" maxlength="2" />/<input type="text" name="annee" size="4" maxlength="4" />
			à <input type="text" name="heure" size="2" maxlength="2" />:<input type="text" name="minute" size="2" maxlength="2" /><br />
			Attention, la date est à entrer au format JJ/MM/AAAA, et l'heure est votre heure locale.<br /><br />
			<input type="hidden" name="PHPSESSID" value="<?php echo session_id();?>" />
			<input type="submit" />
			<br />
			</p>
			</form></div>
			<?php
		}
	}
	elseif($IDRepport == $_SESSION['ID'])
	{
		echo '<p>Vous avez déjà proposé de reporter le match. Votre adversaire en a été informé, et vous serez prévenu de sa réponse. Contactez-le en cas de problème à ce sujet.</p>';
	}
	else
	{
		if($_GET['reporter'] == 'accepter')
		{
			echo '<p>Vous avez accepté de reporter le match. Celui-ci est maintenant prévu à la date proposée par votre adversaire. Notez-la dans votre tête !</p>';
			$requete = 'UPDATE matchs SET Date="'.$dateRepport.'", DateReport="0", QuiRepporte="0" WHERE ID='.$IDMatch.'';
			$reponse = mysql_query($requete);
			$message = 'Bonjour '.$pseudoAdversaire.' ! Votre adversaire '.$_SESSION['pseudo'].' a accepté votre demande de reporter le match. Il se fera donc à la date que vous aviez proposé.
Si vous l\'avez oubliée en cours de route, elle se trouve toujours sur la page de gestion des matchs, accessible depuis votre profil. Bonne chance ! 

En cas de problème, n\'hésitez pas à discuter avec votre adversaire, ou à contacter Meuh par SFM. À la prochaine !';
			$sujet = 'Tournoi Mario Kart du Mario Museum : votre adversaire a accepté votre demande.';
			include("mail.php");
			//echo $message;
			mail($emailAdversaire, $sujet, $message, $headers);
		}
		elseif($_GET['reporter'] == 'refuser')
		{
			echo '<p>Vous avez refusé de reporter le match. Si vous voulez proposer une nouvelle date, vous pouvez le faire en cliquant sur "Proposer une autre date" sur la page de gestion des matchs.</p>';
			$requete = 'UPDATE matchs SET DateReport="0", QuiRepporte="0"  WHERE ID='.$IDMatch.'';
			$reponse = mysql_query($requete);
			$message = 'Bonjour '.$pseudoAdversaire.' ! Votre adversaire '.$_SESSION['pseudo'].' a refusé votre demande de reporter le match. Désolé !
Vous pouvez proposer une nouvelle date, ou attendre que votre adversaire le fasse. En cas de problème, n\'hésitez pas à discuter avec votre adversaire, ou à contacter Meuh par SFM. 

À la prochaine !';
			$sujet = 'Tournoi Mario Kart du Mario Museum : votre adversaire a refusé votre demande.';
			include("mail.php");
			//echo $message;
			mail($emailAdversaire, $sujet, $message, $headers);
		}
		else
		{
			echo '<p>Votre adversaire veut reporter le match à une date ultérieure. Il désire que le match ait lieu le  '.str_replace($motsAnglais, $motsFrancais, date('l d F Y à G:i', $dateRepport)).' 
			(soit le '.str_replace($motsAnglais, $motsFrancais, date('l d F Y à G:i', $dateRepport+$_SESSION['decalage']*3600)).' en prenant en compte votre décalage horaire).<br />
			Si vous acceptez, le match sera reporté à cette date. Si vous refusez, il sera maintenu à la date actuelle, et vous pourrez si vous le souhaitez proposer une nouvelle date.<br /><br />
			<a href="profil.php?menu=voirmatch&amp;action=reporter&amp;reporter=accepter">Accepter de reporter le match</a><br /> 
			<a href="profil.php?menu=voirmatch&amp;action=reporter&amp;reporter=refuser">Refuser de reporter le match</a><br /> 
			</p>';
		}
	}
}
echo '<p><a href="profil.php?menu=voirmatch">Retour aux informations du match</a></p>';

?>
