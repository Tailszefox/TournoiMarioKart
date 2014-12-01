<?php
if($confirmation == 1)
{
	include('traduction.php');
	
	if($_GET['timestamp'])
		$timestamp = $_GET['timestamp'];
	else
		$timestamp = mktime(00, 00, 00, $_POST['mois'], $_POST['jour'], $_POST['annee']);
	
	if($_GET['tour'])
		$tour = $_GET['tour'];
	else
		$tour = $_POST['tour'];
	
	if($_GET['ordre'])
		$ordre = $_GET['ordre'];
	else
		$ordre = $_POST['ordre'];
	
	include('config.php');
	$requete = 'SELECT * FROM participants ORDER BY '.$ordre.'';
	$reponse = mysql_query($requete);
		
	$i = 0;
	
	while($donnees = mysql_fetch_array($reponse))
	{
		if($donnees['Elimine'] == 0)
		{
			$participantID[$i] = $donnees['ID'];
			$participantPseudo[$i] = $donnees['Pseudo'];
			$participantEmail[$i] = $donnees['Email'];
			$participantListeAdversaires[$i] = $donnees['ListeAdversaires'];
			$participantCase[$i] = 0;
			$i++;
		}
	}
	
	$nbParticipants  = $i;
	$nbMatchs = $nbParticipants/2;
	$match = 0;
	
	echo '<p>'.$i.' joueurs vous participer à ce tour, soit '.$nbMatchs.'  matchs. Classement des joueurs par '.$ordre.' :<br />';
	for($j = 0; $j < $i; $j++)
	{
		echo $participantPseudo[$j].' ';
	}
	
	echo '<br /><br />-----------------------------------------';
	
	for($participant1 = 0; $participant1 < $nbParticipants; $participant1++)
	{
		if($participantCase[$participant1] == 0)
		{
			echo '<br /><br /><strong>Recherche d\'adversaire pour '.$participantPseudo[$participant1].'...</strong><br /><br />';
			$requete = 'SELECT * FROM disponibilite WHERE IDParticipant='.$participantID[$participant1].'';
			$reponse = mysql_query($requete);
			
			while($donnees = mysql_fetch_array($reponse))
			{
				$participant1Continent = $donnees['continent'];	
				$participant1Semaine = $donnees['semaine'];
				$participant1Mercredi = $donnees['mercredi'];
				$participant1Samedi = $donnees['samedi'];
				$participant1Dimanche = $donnees['dimanche'];
				$participant1Soiree = $donnees['soiree'];
			}
			
			for($participant2 = 0; $participant2 < $nbParticipants; $participant2++)
			{
				//On va pas jouer contre soi-même non plus, ni un gars qui joue déjà.
				if($participant1 != $participant2 && $participantCase[$participant2] == 0 && strpos($participantListeAdversaires[$participant1], ';'.$participantID[$participant2].';') === FALSE)
				{
					echo $participantPseudo[$participant1].' va-t-il affronter '.$participantPseudo[$participant2].' ?<br />';
					$requete = 'SELECT * FROM disponibilite WHERE IDParticipant='.$participantID[$participant2].'';
					$reponse = mysql_query($requete);
					
					while($donnees = mysql_fetch_array($reponse))
					{
						$participant2Continent = $donnees['continent'];	
						$participant2Semaine = $donnees['semaine'];
						$participant2Mercredi = $donnees['mercredi'];
						$participant2Samedi = $donnees['samedi'];
						$participant2Dimanche = $donnees['dimanche'];
						$participant2Soiree = $donnees['soiree'];
					}
					
					//Données récupérées, comparaisons pour savoir s'il est possible de faire un match entre ces deux personnes
					if($participant1Semaine == $participant2Semaine && $participant1Semaine == 1)
					{
						echo "Oui : ils sont tous les deux disponibles en semaine";
						$participantCase[$participant1] = 1;
						$participantCase[$participant2] = 1;
						
						$participant1Match[$match] = $participant1;
						$participant2Match[$match] = $participant2;
						$MatchJour[$match] = 'semaine';
						$match++;
						break;
					}
					elseif($participant1Samedi == $participant2Samedi && $participant1Samedi == 1)
					{
						echo "Oui : ils sont tous les deux disponibles le samedi";
						$participantCase[$participant1] = 1;
						$participantCase[$participant2] = 1;
						
						$participant1Match[$match] = $participant1;
						$participant2Match[$match] = $participant2;
						$MatchJour[$match] = 'samedi';
						$match++;
						break;
					}
					elseif($participant1Dimanche == $participant2Dimanche && $participant1Dimanche == 1)
					{
						echo "Oui : ils sont tous les deux disponibles le dimanche";
						$participantCase[$participant1] = 1;
						$participantCase[$participant2] = 1;
						
						$participant1Match[$match] = $participant1;
						$participant2Match[$match] = $participant2;
						$MatchJour[$match] = 'dimanche';
						$match++;
						break;
					}
					elseif($participant1Continent == $participant2Continent)
					{
						echo "Ils sont du même continent";
						if($participant1Mercredi == $participant2Mercredi && $participant1Mercredi == 1)
						{
							echo " et sont disponibles le mercredi";
							$participantCase[$participant1] = 1;
							$participantCase[$participant2] = 1;
						
							$participant1Match[$match] = $participant1;
							$participant2Match[$match] = $participant2;
							$MatchJour[$match] = 'mercredi';
							$match++;
							break;
						}
						elseif($participant1Soiree == $participant2Soiree && $participant1Soiree == 1)
						{							
							echo " et sont disponibles en soirée";
							$participantCase[$participant1] = 1;
							$participantCase[$participant2] = 1;
						
							$participant1Match[$match] = $participant1;
							$participant2Match[$match] = $participant2;
							$MatchJour[$match] = 'soiree';
							$match++;
							break;
						}
						else
						{
							echo " mais ne sont pas disponibles en même temps";
						}
					}
					
					echo '<br />';
				}
			}
			
			if($participantCase[$participant1] == 0)
			{
				echo "Impossible de trouver d'aversaire...";
			}
		}
	}
	
	echo '<br /><br />-----------------------------------------';
	echo '<br /><br /><strong>Recherche d\'adversaires pour les participants pas encore casés...</strong><br />';
	for($participant1 = 0; $participant1 < $nbParticipants; $participant1++)
	{
		if($participantCase[$participant1] == 0)
		{
			for($participant2 = 0; $participant2 < $nbParticipants; $participant2++)
			{
				//On va pas jouer contre soi-même non plus, ni un gars qui joue déjà.
				if($participant1 != $participant2 && $participantCase[$participant2] == 0 && strpos($participantListeAdversaires[$participant1], ';'.$participantID[$participant2].';') === FALSE)
				{					
					echo '<br />'.$participantPseudo[$participant1].' affrontera '.$participantPseudo[$participant2].'...';
					$participantCase[$participant1] = 1;
					$participantCase[$participant2] = 1;
											
					$participant1Match[$match] = $participant1;
					$participant2Match[$match] = $participant2;
					$MatchJour[$match] = 'aucun';
					$match++;
					break;
				}
			}
		}
	}
	
	//Recherche d'adversaires terminée
	echo '<br /><br />-----------------------------------------';
	echo '<br /><br /><strong>Résumé des affrontements</strong><br />';

	for($i = 0; $i < $match; $i++)
	{		
		echo 'Match '.$i.' : '.$participantPseudo[$participant1Match[$i]].' affrontera '.$participantPseudo[$participant2Match[$i]].'.<br />';
	}
	
	if($match != $nbMatchs)
		echo '<br />Il y a moins de matchs que prévu. Essayez avec un autre ordre !';
	else
		echo '<br /><a href="profil.php?menu=tour&amp;action=declencher&amp;confirmation=1&amp;timestamp='.$timestamp.'&amp;tour='.$tour.'&amp;ordre='.$ordre.'">Confirmer</a>';
	
	if($_GET['confirmation'] > 0)
	{
		
		echo '<br /><br />-----------------------------------------';
		echo '<br /><br /><strong>Détermination des heures</strong><br />';
		echo 'Début du tour le '.str_replace($motsAnglais, $motsFrancais, date('l d F Y - G:i', $timestamp)).'<br /><br />';
		for($i = 0; $i < $match; $i++)
		{
			$dateMatch[$i] = $timestamp;
			
			echo 'Match '.$i.' <br />';
			
			if($MatchJour[$i] == 'semaine')
			{
				$jour = date('w', $timestamp);
				if($jour == 0)
					$dateMatch[$i] += 86400;
				elseif($jour == 6)
					$dateMatch[$i] += 86400*2;
					
				//Ajustement à 16 heures
				$dateMatch[$i] += 57600;
				
				echo 'En semaine : le '.str_replace($motsAnglais, $motsFrancais, date('l d F Y - G:i', $dateMatch[$i])).'<br />';	
			}
			elseif($MatchJour[$i] == 'samedi')
			{
				$jour = date('w', $timestamp);
				$difference = 6 - $jour;
				if($difference <= 0)
				{
					$difference += 7;
				}
				$dateMatch[$i] += 86400*$difference;
				
				//Ajustement à 16 heures
				$dateMatch[$i] += 57600;
				
				echo 'Le samedi : le '.str_replace($motsAnglais, $motsFrancais, date('l d F Y - G:i', $dateMatch[$i])).'<br />';		
			}
			elseif($MatchJour[$i] == 'dimanche')
			{
				$jour = date('w', $timestamp);
				$difference = 7 - $jour;
				if($difference <= 0)
				{
					$difference += 7;
				}
				$dateMatch[$i] += 86400*$difference;
				
				//Ajustement à 16 heures
				$dateMatch[$i] += 57600;
				
				echo 'Le dimanche : le '.str_replace($motsAnglais, $motsFrancais, date('l d F Y - G:i', $dateMatch[$i])).'<br />';	
			}
			elseif($MatchJour[$i] == 'mercredi')
			{
				$jour = date('w', $timestamp);
				$difference = 3 - $jour;
				if($difference <= 0)
				{
					$difference += 7;
				}
				$dateMatch[$i] += 86400*$difference;
				
				//Ajustement à 16 heures
				$dateMatch[$i] += 57600;
				
				echo 'Le mercredi : le '.str_replace($motsAnglais, $motsFrancais, date('l d F Y - G:i', $dateMatch[$i])).'<br />';	
			}
			elseif($MatchJour[$i] == 'soiree')
			{
				//Ajustement à 19 heures
				$dateMatch[$i] += 68400;
				
				echo 'En soirée : le '.str_replace($motsAnglais, $motsFrancais, date('l d F Y - G:i', $dateMatch[$i])).'<br />';	
			}
			elseif($MatchJour[$i] == 'aucun')
			{				
				$jour = date('w', $timestamp);
				$difference = 6 - $jour;
				if($difference <= 0)
				{
					$difference += 7;
				}
				$dateMatch[$i] += 86400*$difference;
				
				//Ajustement à 20 heures
				$dateMatch[$i] += 72000;
				
				echo 'Défaut vers samedi : le '.str_replace($motsAnglais, $motsFrancais, date('l d F Y - G:i', $dateMatch[$i])).'<br />';		
			}
			
			echo '<br />';	
		}
		
		echo '<a href="profil.php?menu=tour&amp;action=declencher&amp;confirmation=2&amp;timestamp='.$timestamp.'&amp;tour='.$tour.'&amp;ordre='.$ordre.'">Confirmer</a>';
		
		if($_GET['confirmation'] > 1)
		{
			echo '<br /><br />-----------------------------------------';
			echo '<br /><br /><strong>Écriture dans la base de données</strong><br /><br />';
			
			for($i = 0; $i < $match; $i++)
			{
				$participant1 = $participantPseudo[$participant1Match[$i]];
				$participant2 = $participantPseudo[$participant2Match[$i]];
				$participant1ID = $participantID[$participant1Match[$i]];
				$participant2ID = $participantID[$participant2Match[$i]];
				$participant1Email = $participantEmail[$participant1Match[$i]];
				$participant2Email = $participantEmail[$participant2Match[$i]];
				$participant1ListeAdversaires = $participantListeAdversaires[$participant1Match[$i]];
				$participant2ListeAdversaires = $participantListeAdversaires[$participant2Match[$i]];

				$requete = 'SELECT * FROM disponibilite WHERE IDParticipant='.$participant1ID.'';
				$reponse = mysql_query($requete);
			
				while($donnees = mysql_fetch_array($reponse))
				{
					$participant1Continent = $donnees['continent'];
					$participant1Decalage = $donnees['decalage'];
				}
				
				$requete = 'SELECT * FROM disponibilite WHERE IDParticipant='.$participant2ID.'';
				$reponse = mysql_query($requete);
			
				while($donnees = mysql_fetch_array($reponse))
				{
					$participant2Continent = $donnees['continent'];
					$participant2Decalage = $donnees['decalage'];
				}
				
				$heure = $dateMatch[$i];
				
				echo 'Ajout du match '.$i.' : '.$participant1.' ('.$participant1Continent.') VS '.$participant2.' ('.$participant2Continent.') le '.str_replace($motsAnglais, $motsFrancais, date('l d F Y - G:i', $heure)).'<br />';
				echo 'Envoi d\'un email à '.$participant1Email.' et à '.$participant2Email.'.<br /><br />';
				
				//Envoi du mail au premier participant
				$sujet = 'Tournoi Mario Kart : au travail !';
				
				if($participant1Decalage != 0)
				{
					$heureDecalage = $heure + $participant1Decalage*3600;
					$messageHeure = 'D\'après votre décalage horaire, le match se déroulera donc le '.str_replace($motsAnglais, $motsFrancais, date('l d F Y à G:i', $heureDecalage)).' chez vous.';
				}
				else
				{
					$messageHeure = '';
				}
				
				$message = 'Bonjour '.$participant1.'.
Un match pour le tournoi a été décidé, et il se fera entre vous et '.$participant2.'. Ce match se déroulera le '.str_replace($motsAnglais, $motsFrancais, date('l d F Y à G:i', $heure)).'. '.$messageHeure.' 
Rendez vous sur votre profil pour avoir plus d\'information sur ce match (code ami de votre adversaire, circuits choisis...) ou pour communiquer avec '.$participant2.'. 

Cependant, si cette date ne vous convient pas, vous pouvez toujours vous rendre sur votre profil afin de demander à reporter le match à une date ultérieure. Votre adversaire peut d\'ailleurs faire de même.
Si vous ne participez pas au match sans avoir demandé à le reporter, votre adversaire gagnera par forfait...Vous n\'allez pas laisser '.$participant2.' gagner aussi facilement, quand même ! 
Notez donc bien la date quelque part si vous avez peur de l\'oublier. Vous pouvez la retrouver à tout moment sur la page de gestions des matchs, dans votre profil.

Enfin, en cas de problème, n\'hésitez pas à contacter Meuh par SFM. Bon match !';
//echo "$message<br />";

				include('mail.php');		
				mail($participant1Email, $sujet, $message, $headers);
				
				//Envoi du mail au deuxième participant
				$sujet = 'Tournoi Mario Kart : au travail !';
				
				if($participant2Decalage != 0)
				{
					$heureDecalage = $heure + $participant2Decalage*3600;
					$messageHeure = 'D\'après votre décalage horaire, le match se déroulera donc le '.str_replace($motsAnglais, $motsFrancais, date('l d F Y à G:i', $heureDecalage)).' chez vous.';
				}
				else
				{
					$messageHeure = '';
				}
				
				$message = 'Bonjour '.$participant2.'.
Un match pour le tournoi a été décidé, et il se fera entre vous et '.$participant1.'. Ce match se déroulera le '.str_replace($motsAnglais, $motsFrancais, date('l d F Y à G:i', $heure)).'. '.$messageHeure.' 
Rendez vous sur votre profil pour avoir plus d\'information sur ce match (code ami de votre adversaire, circuits choisis...) ou pour communiquer avec '.$participant1.'. 

Cependant, si cette date ne vous convient pas, vous pouvez toujours vous rendre sur votre profil afin de demander à reporter le match à une date ultérieure. Votre adversaire peut d\'ailleurs faire de même.
Si vous ne participez pas au match sans avoir demandé à le reporter, votre adversaire gagnera par forfait...Vous n\'allez pas laisser '.$participant1.' gagner aussi facilement, quand même !
Notez donc bien la date quelque part si vous avez peur de l\'oublier. Vous pouvez la retrouver à tout moment sur la page de gestions des matchs, dans votre profil.

Enfin, en cas de problème, n\'hésitez pas à contacter Meuh par SFM. Bon match !';
//echo "$message<br /><br />";

				include('mail.php');		
				mail($participant2Email, $sujet, $message, $headers);
				
				//Choix des circuits
				$circuit1 = rand(0, 31);
				$circuit2 = rand(0, 31);
				$circuit3 = rand(0, 31);
				$circuit4 = rand(0, 31);
				
				while($circuit1 == $circuit2 || $circuit1 == $circuit3 || $circuit1 == $circuit4 || $circuit2 == $circuit3 || $circuit2 == $circuit4 || $circuit3 == $circuit4)
				{
					$circuit1 = rand(0, 31);
					$circuit2 = rand(0, 31);
					$circuit3 = rand(0, 31);
					$circuit4 = rand(0, 31);
				}
				
				$requete = 'INSERT INTO matchs (ID, ID1, ID2, Circuit1, Circuit2, Circuit3, Circuit4, Date, Tour) VALUES("", "'.$participant1ID.'", "'.$participant2ID.'", '.$circuit1.', '.$circuit2.', '.$circuit3.', '.$circuit4.', "'.$heure.'", "'.$tour.'")';
				//echo "$requete<br />";
				$reponse = mysql_query($requete);
				
				$participant1ListeAdversaires .= ';'.$participant2ID.';';
				$requete = 'UPDATE participants SET ListeAdversaires="'.$participant1ListeAdversaires.'" WHERE ID='.$participant1ID.'';
				//echo "$requete<br />";
				$reponse = mysql_query($requete);
				
				$participant2ListeAdversaires .= ';'.$participant1ID.';';
				$requete = 'UPDATE participants SET ListeAdversaires="'.$participant2ListeAdversaires.'" WHERE ID='.$participant2ID.'';
				//echo "$requete<br />";
				$reponse = mysql_query($requete);
				
			}
			
			echo '<br />Matchs ajoutés, participants informés.';
		}
	}
	
	mysql_close();
	echo '</p>';

}
?>
