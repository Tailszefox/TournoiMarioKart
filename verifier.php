<?php
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
	
	
	for($participant1 = 0; $participant1 < $nbParticipants; $participant1++)
	{
		if($participantCase[$participant1] == 0)
		{
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
						if($participant1Mercredi == $participant2Mercredi && $participant1Mercredi == 1)
						{
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
						}
					}
					
				}
			}
			
			if($participantCase[$participant1] == 0)
			{
			}
		}
	}

	for($participant1 = 0; $participant1 < $nbParticipants; $participant1++)
	{
		if($participantCase[$participant1] == 0)
		{
			for($participant2 = 0; $participant2 < $nbParticipants; $participant2++)
			{
				//On va pas jouer contre soi-même non plus, ni un gars qui joue déjà.
				if($participant1 != $participant2 && $participantCase[$participant2] == 0 && strpos($participantListeAdversaires[$participant1], ';'.$participantID[$participant2].';') === FALSE)
				{					
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
	if($match != $nbMatchs)
		echo 'Il y a moins de matchs que prévu. Essayez avec un autre ordre !';
	else
		echo 'Tous les participants ont un adversaire !';
	
?>
