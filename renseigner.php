<?php
	if(isset($_POST['renseigner']))
	{
		$reponseParticipant = $_POST['renseigner'];
		$requete = 'UPDATE matchs SET ReponseP'.$P.' = '.$reponseParticipant.' WHERE ID='.$IDMatch.'';
		$reponse = mysql_query($requete);
		
		if($reponseAdversaire == 0)
		{
			echo '<p>Votre adversaire n\'a pas encore donné l\'issue du match. Pour éviter les tricheries, les deux participants doivent le faire. 
			<br />Un mail vous sera envoyé quand votre adversaire aura fait son choix.</p>';
		}
		elseif($reponseAdversaire == 1)
		{
			if($reponseParticipant == 1)
			{
				echo '<p>Votre adversaire dit que c\'est lui qui a gagné ! Soit l\'un de vous deux ment (c\'est très mal !), soit...<br />
				Si vous avez terminé sur une égalité, trouvez un moyen de vous départager, puis revenez sur cette page pour renseigner le vrai gagnant.<br />
				Si le match n\'a pas eu lieu, sélectionnez plutôt l\'option appropriée.</p>';
				
				$message = 'Bonjour '.$pseudoAdversaire.' !
Votre adversaire, '.$_SESSION['pseudo'].', a donné sa réponse concernant l\'issue du match que vous avez joué contre lui. Cependant, nous avons un problème.
Votre adversaire dit que c\'est lui qui a gagné ! Soit l\'un de vous deux ment (c\'est très mal !), soit...
Si vous avez terminé sur une égalité, trouvez un moyen de vous départager, puis revenez sur la page de gestion du match pour renseigner le vrai gagnant.
Si le match n\'a pas eu lieu, sélectionnez plutôt l\'option appropriée.';

			}
			elseif($reponseParticipant == 2)
			{
				echo '<p>Allez va, ça arrive à tout le monde. Votre défaite a été notée, mais que cela ne vous décourage pas.<br />
				Ressaisissez-vous, et préparez-vous pour votre prochain match, que vous allez gagner cette fois !</p>';
				$requete = 'UPDATE matchs SET IDGagnant = '.$IDAdversaire.' WHERE ID='.$IDMatch.'';
				$reponse = mysql_query($requete);
				
				$nbVictoiresParticipant += 0;
				$nbVictoiresAdversaire += 1;
				
				$requete = 'UPDATE participants SET NbVictoires='.$nbVictoiresParticipant.' WHERE ID='.$_SESSION['ID'].'';
				//echo "$requete<br />";
				$reponse = mysql_query($requete);
				
				$requete = 'UPDATE participants SET NbVictoires='.$nbVictoiresAdversaire.' WHERE ID='.$IDAdversaire.'';
				//echo "$requete<br />";
				$reponse = mysql_query($requete);
				
				$message = 'Bonjour '.$pseudoAdversaire.' !
Votre adversaire, '.$_SESSION['pseudo'].', a donné sa réponse concernant l\'issue du match que vous avez joué contre lui.
Bonne nouvelle, votre victoire est désormais officielle, félicitations !';
			}
			elseif($reponseParticipant == 3)
			{
				echo '<p>Votre adversaire dit que le match a eu lieu, et qu\'il l\'a gagné.<br />
				Il est évident qu\'il y a eu un problème, vous devez donc contacter votre adversaire pour faire toute la lumière sur cette affaire. Quand ce sera fait, revenez ici pour donner votre réponse.</p>';
				$message = 'Bonjour '.$pseudoAdversaire.' ! 
Votre adversaire, '.$_SESSION['pseudo'].', a donné sa réponse concernant l\'issue du match que vous avez joué contre lui. Cependant, il affirme que vous n\'avez pas participé.
Il est évident qu\'il y a eu un problème, vous devez donc contacter le contacter pour faire toute la lumière sur cette affaire. Quand ce sera fait, revenez sur l a page de gestion du match pour donner votre réponse.
';
			}
			elseif($reponseParticipant == 4)
			{
				echo '<p>D\'après votre adversaire, le match a effectivement eu lieu. Mais puisque vous dites que vous n\'avez pas pu y participer, c\'est à lui que revient la victoire.<br />
				La prochaine fois, si vous ne pouvez pas participer à un match, pensez à en reporter la date à partir de votre profil !</p>';
				
				$requete = 'UPDATE matchs SET IDGagnant = '.$IDAdversaire.' WHERE ID='.$IDMatch.'';
				$reponse = mysql_query($requete);
				
				$nbVictoiresParticipant += 0;
				$nbVictoiresAdversaire += 1;
				
				$requete = 'UPDATE participants SET NbVictoires='.$nbVictoiresParticipant.' WHERE ID='.$_SESSION['ID'].'';
				//echo "$requete<br />";
				$reponse = mysql_query($requete);
				
				$requete = 'UPDATE participants SET NbVictoires='.$nbVictoiresAdversaire.' WHERE ID='.$IDAdversaire.'';
				//echo "$requete<br />";
				$reponse = mysql_query($requete);
				
				$message = 'Bonjour '.$pseudoAdversaire.' ! 
Votre adversaire, '.$_SESSION['pseudo'].', a donné sa réponse concernant l\'issue du match que vous avez joué contre lui. Votre adversaire dit ne pas avoir participé au match. 
C\'est étrange, puisque vous dites avoir joué, mais peu importe, car ainsi vous gagnez par forfait. Félicitations !';
			}
		}
		elseif($reponseAdversaire == 2)
		{
			if($reponseParticipant == 1)
			{
				echo '<p>Félicitation pour votre victoire ! Celle-ci est désormais gravée dans le marbre.<br />À bientôt pour un prochain match.</p>';
				
				$requete = 'UPDATE matchs SET IDGagnant = '.$_SESSION['ID'].' WHERE ID='.$IDMatch.'';
				$reponse = mysql_query($requete);
				
				$nbVictoiresParticipant += 1;
				$nbVictoiresAdversaire += 0;
				
				$requete = 'UPDATE participants SET NbVictoires='.$nbVictoiresParticipant.' WHERE ID='.$_SESSION['ID'].'';
				//echo "$requete<br />";
				$reponse = mysql_query($requete);
				
				$requete = 'UPDATE participants SET NbVictoires='.$nbVictoiresAdversaire.' WHERE ID='.$IDAdversaire.'';
				//echo "$requete<br />";
				$reponse = mysql_query($requete);
				
				$message = 'Bonjour '.$pseudoAdversaire.' ! 
Votre adversaire, '.$_SESSION['pseudo'].', a donné sa réponse concernant l\'issue du match que vous avez joué contre lui.
Il a bien confirmé que vous aviez perdu le match. Ce n\'est pas grave, vous aurez l\'occasion de prouver à nouveau votre valeur !';
			}
			elseif($reponseParticipant == 2)
			{
				echo '<p>Votre adversaire a lui aussi dit avoir perdu...Trop modeste pour admettre avoir gagné ?<br />
				Dans tous les cas, l\'un de vous deux doit forcément gagner. Si vous avez terminé sur une égalité, trouvez un moyen de vous départager, puis revenez sur cette page pour renseigner le vrai gagnant.<br />
				Si le match n\'a pas eu lieu, sélectionnez plutôt l\'option appropriée.</p>';
				$message = 'Bonjour '.$pseudoAdversaire.' ! 
Votre adversaire, '.$_SESSION['pseudo'].', a donné sa réponse concernant l\'issue du match que vous avez joué contre lui. Cependant, il dit avoir perdu lui aussi. Trop modeste pour admettre avoir gagné ?
Dans tous les cas, l\'un de vous deux doit forcément gagner. Si vous avez terminé sur une égalité, trouvez un moyen de vous départager, puis revenez sur la page de gestion du match pour renseigner le vrai gagnant.
Si le match n\'a pas eu lieu, sélectionnez plutôt l\'option appropriée, là aussi sur la page de gestion du match.';
			}
			elseif($reponseParticipant == 3)
			{
				echo '<p>Votre adversaire dit que le match a eu lieu, mais qu\'il a perdu.<br />
				Il est évident qu\'il y a eu un problème, vous devez donc contacter votre adversaire pour faire toute la lumière sur cette affaire. Quand ce sera fait, revenez ici pour donner votre réponse.</p>';
				$message = 'Bonjour '.$pseudoAdversaire.' ! 
Votre adversaire, '.$_SESSION['pseudo'].', a donné sa réponse concernant l\'issue du match que vous avez joué contre lui. Cependant, il dit que vous n\'avez pas participé, alors que vous nous avez dit avoir perdu.
Il est évident qu\'il y a eu un problème, vous devez donc contacter votre adversaire pour faire toute la lumière sur cette affaire. Quand ce sera fait, revenez sur la page de gestion des matchs pour donner votre réponse.';
			}
			elseif($reponseParticipant == 4)
			{
				echo '<p>D\'après votre adversaire, le match a effectivement eu lieu. Mais puisque vous dites que vous n\'avez pas pu y participer, c\'est à lui que revient la victoire.<br />
				La prochaine fois, si vous ne pouvez pas participer à un match, pensez à en reporter la date à partir de votre profil !</p>';
				
				$requete = 'UPDATE matchs SET IDGagnant = '.$IDAdversaire.' WHERE ID='.$IDMatch.'';
				$reponse = mysql_query($requete);
				
				$nbVictoiresParticipant += 0;
				$nbVictoiresAdversaire += 1;
				
				$requete = 'UPDATE participants SET NbVictoires='.$nbVictoiresParticipant.' WHERE ID='.$_SESSION['ID'].'';
				//echo "$requete<br />";
				$reponse = mysql_query($requete);
				
				$requete = 'UPDATE participants SET NbVictoires='.$nbVictoiresAdversaire.' WHERE ID='.$IDAdversaire.'';
				//echo "$requete<br />";
				$reponse = mysql_query($requete);
				
				$message = 'Bonjour '.$pseudoAdversaire.' ! 
Votre adversaire, '.$_SESSION['pseudo'].', a donné sa réponse concernant l\'issue du match que vous avez joué contre lui. Vous gagnez par forfait, puisque votre adversaire a dit qu\'il n\'avait pas pu participer.
Féliciations, mais attention, les prochains matchs ne seront pas auss faciles !';
			}
		}
		elseif($reponseAdversaire == 3)
		{
			if($reponseParticipant == 1 || $reponseParticipant == 2)
			{
				echo '<p>D\'après votre adversaire, vous n\'avez en fait pas participé au match. Si c\'est le cas, revenez en arrière et cliquez sur "Je n\'ai pas pu participer au match"<br />
				Si vous êtes sûr d\'avoir participé au match, parlez à votre adversaire pour savoir pourquoi il pense que ce n\'est pas le cas, puis revenez sur cette page pour donner votre réponse.</p>';
				$message = 'Bonjour '.$pseudoAdversaire.' ! 
Votre adversaire, '.$_SESSION['pseudo'].', a donné sa réponse concernant l\'issue du match que vous avez joué contre lui. Votre adversaire dit avoir participé au match, alors que vous pensez que ce n\'est pas le cas
Vous devriez le contacter, car il y a eu un problème de communication entre vous. Quand ce sera fait, retournez sur la page de gestion du match pour donner le vrai gagnant.';
			}
			elseif($reponseParticipant == 3)
			{
				echo '<p>Votre adversaire dit exactement la même chose : d\'après lui, c\'est VOUS qui n\'avez pas participé.<br />
				Si vous êtes sûr d\'avoir été là à l\'heure, il se peut que votre adversaire se soit trompé sur l\'horaire. 
				Contactez-le pour régler ce problème, puis revenez ensuite sur cette page pour donner votre  réponse.</p>';
				$message = 'Bonjour '.$pseudoAdversaire.' ! 
Votre adversaire, '.$_SESSION['pseudo'].', a donné sa réponse concernant l\'issue du match que vous avez joué contre lui. Cependant, d\'après lui, c\'est VOUS qui n\'avez pas participé.
Si vous êtes sûr d\'avoir été là à l\'heure, il se peut que votre adversaire se soit trompé sur l\'horaire. Contactez-le pour régler ce problème, puis revenez ensuite sur la page de gestion des matchs pour donner votre réponse.';
			}
			elseif($reponseParticipant == 4)
			{
				echo '<p>Votre adversaire a en effet dit que le match n\'avait pas eu lieu parce que vous n\'êtes pas venu l\'affronter. Vous perdez donc par forfait, désolé !<br />
				La prochaine fois, si vous ne pouvez pas participer à un match, pensez à en reporter la date à partir de votre profil !</p>';
				
				$requete = 'UPDATE matchs SET IDGagnant = '.$IDAdversaire.' WHERE ID='.$IDMatch.'';
				$reponse = mysql_query($requete);
				
				$nbVictoiresParticipant += 0;
				$nbVictoiresAdversaire += 1;
				
				$requete = 'UPDATE participants SET NbVictoires='.$nbVictoiresParticipant.' WHERE ID='.$_SESSION['ID'].'';
				//echo "$requete<br />";
				$reponse = mysql_query($requete);
				
				$requete = 'UPDATE participants SET NbVictoires='.$nbVictoiresAdversaire.' WHERE ID='.$IDAdversaire.'';
				//echo "$requete<br />";
				$reponse = mysql_query($requete);
				
				$message = 'Bonjour '.$pseudoAdversaire.' ! 
Votre adversaire, '.$_SESSION['pseudo'].', a donné sa réponse concernant l\'issue du match que vous avez joué contre lui. Vous gagnez par forfait, puisque votre adversaire a bien confirmé qu\'il n\'a pas pu participer.
Féliciations, mais attention, les prochains matchs ne seront pas auss faciles !';
			}
		}
		elseif($reponseAdversaire == 4)
		{
			if($reponseParticipant == 1 || $reponseParticipant == 2)
			{
				echo '<p>D\'après votre adversaire, vous n\'avez en fait pas participé au match. Si c\'est le cas, revenez en arrière et cliquez sur "Je n\'ai pas pu participer au match"<br />
				Si vous êtes sûr d\'avoir participé au match, parlez à votre adversaire pour savoir pourquoi il pense que ce n\'est pas le cas, puis revenez sur cette page pour donner votre réponse.</p>';
				$message = 'Bonjour '.$pseudoAdversaire.' ! 
Votre adversaire, '.$_SESSION['pseudo'].', a donné sa réponse concernant l\'issue du match que vous avez joué contre lui. Votre adversaire dit avoir participé au match, alors que vous pensez que ce n\'est pas le cas
Vous devriez le contacter, car il y a eu un problème de communication entre vous. Quand ce sera fait, retournez sur la page de gestion du match pour donner le vrai gagnant.';
			}
			elseif($reponseParticipant == 3)
			{
				echo '<p>Votre adversaire a en effet dit qu\'il n\'avait pas pu participer. En conséquence, vous gagnez par forfait.<br />
				Ne criez pas victoire trop vite cependant, ce n\'est pas ainsi que vous allez gagner tous vos matchs. Au travail !</p>';
				
				$requete = 'UPDATE matchs SET IDGagnant = '.$_SESSION['ID'].' WHERE ID='.$IDMatch.'';
				$reponse = mysql_query($requete);
				
				$nbVictoiresParticipant += 1;
				$nbVictoiresAdversaire += 0;
				
				$requete = 'UPDATE participants SET NbVictoires='.$nbVictoiresParticipant.' WHERE ID='.$_SESSION['ID'].'';
				//echo "$requete<br />";
				$reponse = mysql_query($requete);
				
				$requete = 'UPDATE participants SET NbVictoires='.$nbVictoiresAdversaire.' WHERE ID='.$IDAdversaire.'';
				//echo "$requete<br />";
				$reponse = mysql_query($requete);
				
				$message = 'Bonjour '.$pseudoAdversaire.' ! 
Votre adversaire, '.$_SESSION['pseudo'].', a donné sa réponse concernant l\'issue du match que vous avez joué contre lui. Votre défaite par forfait a été confirmée par votre adversaire. Désolé !
La prochaine fois, si vous ne pouvez pas participer à un match, pensez à en changer l\'horaire, pour que ce genre d\'évènements n\'arrive plus.';
			}
			elseif($reponseParticipant == 4)
			{
				echo '<p>Votre adversaire n\'a pas pu participer au match non plus.<br />Par conséquent, vous perdez tous les deux votre match. La prochaine fois, pensez à reporter votre match si la date prévue ne vous convient pas !</p>';
				$message = 'Bonjour '.$pseudoAdversaire.' ! 
Votre adversaire, '.$_SESSION['pseudo'].', a donné sa réponse concernant l\'issue du match que vous avez joué contre lui. Il a répondu que lui non plus n\'a pu participer au match.
Par conséquent, vous perdez tous les deux votre match. La prochaine fois, pensez à reporter votre match si la date prévue ne vous convient pas !';

				$requete = 'UPDATE matchs SET IDGagnant = -1 WHERE ID='.$IDMatch.'';
				$reponse = mysql_query($requete);
				
				$nbVictoiresParticipant += 0;
				$nbVictoiresAdversaire += 0;
				
				$requete = 'UPDATE participants SET NbVictoires='.$nbVictoiresParticipant.' WHERE ID='.$_SESSION['ID'].'';
				$reponse = mysql_query($requete);
				
				$requete = 'UPDATE participants SET NbVictoires='.$nbVictoiresAdversaire.' WHERE ID='.$IDAdversaire.'';
				$reponse = mysql_query($requete);
			}
		}
		
		if($reponseAdversaire != 0)
		{
		$message .= "\r\n\r\n".'En cas de problème ou pour toute question, n\'hésitez pas à contacter Meuh par SFM. Sur ce, à la prochaine !';
		$sujet = 'Tournoi Mario Kart du Mario Museum : votre adversaire à donné les résultats !';
		include("mail.php");
		//echo $message;
		mail($emailAdversaire, $sujet, $message, $headers);
		}
	}
	elseif($IDGagnant == -1)
	{
		echo '<p>Le match est déjà considéré comme une égalité, il est donc impossible de changer les résultats.<br />
		Lors de votre prochain match, assurez-vous d\'être à l\'heure pour éviter ce genre de mésaventure, ou reportez-le si la date ne vous convient pas !</p>';
	}
	elseif($IDGagnant != 0)
	{
		echo '<p>La victoire d\'un des participants a déjà été déclarée, il est donc impossible de changer les résultats.<br />
		Si vraiment vous et votre adversaire avez fait une erreur et voulez changer l\'issue du match, contactez Meuh par SFM. Une fois le  tour suivant commencé, il sera trop tard !</p>';
	}
	else
	{
	?>
	<p>Vous avez terminé votre match ? Parfait ! Utilisez cette page pour en donner les résultats<br />
	Attention, une fois la victoire de l'un des deux participants déclarée, il n'est pas possible de retourner en arrière. Faites attention à ce que vous choisissez.<br />
	Par ailleurs, si vous n'avez pas pu participer à un match à cause d'un empêchement, merci de choisir la dernière option. Votre adversaire gagnera par forfait, mais cela évitera de bloquer tout le tournoi.</p>
	<div class="formulaire"><form action="profil.php?menu=voirmatch&amp;action=renseigner" method="post">
	<p class="alignerformulaire">
		<input type="radio" name="renseigner" value="1" /> J'ai gagné le match<br />
		<input type="radio" name="renseigner" value="2" /> J'ai perdu le match<br />
		<input type="radio" name="renseigner" value="3" /> Mon adversaire n'a pas participé au match<br />
		<input type="radio" name="renseigner" value="4" /> Je n'ai pas pu participer au match
	</p>
	<p>
		<input type="submit" value="Envoyer les résultats" />
		<input type="hidden" name="PHPSESSID" value="<?php echo session_id();?>" />
	</p>
	</form>
	</div>
	<?php
	}
	echo '<p><a href="profil.php?menu=voirmatch">Retour aux informations du match</a></p>';
