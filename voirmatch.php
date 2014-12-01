<p><strong>Informations sur votre prochain match</strong></p>

<?php
require('config.php');
include('traduction.php');
$requete = 'SELECT * FROM matchs WHERE ID1 = '.$_SESSION['ID'].' OR ID2 = '.$_SESSION['ID'].' ORDER BY Tour DESC LIMIT 1';
$reponse = mysql_query($requete);

if(mysql_affected_rows() == 0)
{
	echo '<p>Le tournoi n\'a pas encore commencé !<br />
	Vous serez informé par email dès que ce sera le cas. En attendant, entraînez-vous bien !</p>';
}
else
{
	while($donnees = mysql_fetch_array($reponse))
	{
		$IDMatch = $donnees['ID'];
		$circuit1 = $donnees['Circuit1'];
		$circuit2 = $donnees['Circuit2'];
		$circuit3 = $donnees['Circuit3'];
		$circuit4 = $donnees['Circuit4'];
		$date = $donnees['Date'];
		$ID1 = $donnees['ID1'];
		$ID2 = $donnees['ID2'];
		$IDGagnant = $donnees['IDGagnant'];
		$reponseP1 = $donnees['ReponseP1'];
		$reponseP2 = $donnees['ReponseP2'];
		$dateRepport = $donnees['DateReport'];
		$IDRepport = $donnees['QuiRepporte'];
		$tour = $donnees['Tour'];
	}
	
	if($ID1 == $_SESSION['ID'])
	{
		$IDAdversaire = $ID2;
		$reponseAdversaire = $reponseP2;
		$P = 1;
	}
	else
	{
		$IDAdversaire = $ID1;
		$reponseAdversaire = $reponseP1;
		$P = 2;
	}
		
	$requete = 'SELECT * FROM participants WHERE ID = '.$IDAdversaire.'';
	$reponse = mysql_query($requete);
	
	while($donnees = mysql_fetch_array($reponse))
	{
		$pseudoAdversaire = $donnees['Pseudo'];
		$emailAdversaire = $donnees['Email'];
		$codeAmiAdversaire = $donnees['CodeAmi'];
		$nbVictoiresAdversaire = $donnees['NbVictoires'];
	}
	
	$requete = 'SELECT * FROM participants WHERE ID = '.$_SESSION['ID'].'';
	$reponse = mysql_query($requete);
	
	while($donnees = mysql_fetch_array($reponse))
	{
		$nbVictoiresParticipant = $donnees['NbVictoires'];
	}
		
	if(isset($_GET['action']))
	{
		if($_GET['action'] == 'reporter')
		{
			require('reporter.php');
		}
		elseif($_GET['action'] == 'renseigner')
		{
			require('renseigner.php');
		}
		elseif($_GET['action'] == 'message')
		{
			require('message.php');
		}
	}
	else
	{
		if($IDGagnant == '0')
		{
			$statut = '<span class="pasencore">Match pas encore joué</span>';
		}
		elseif($IDGagnant == '-1')
		{
			$statut = '<span class="joue">Égalité</span>';
		}
		elseif($IDGagnant == $_SESSION['ID'])
		{
			$statut = '<span class="joue">Match joué (remporté)</span>';		
		}
		else
		{
			$statut = '<span class="joue">Match joué (perdu)</span>';
		}
		
		require('circuits.php');
		echo '
		<table width="80%" border="1">
		<tr>
			<th>Votre adversaire</th>
			<td>'.$pseudoAdversaire.' (code ami : '.$codeAmiAdversaire.')</td>
		</tr>
		<tr>
			<th>Circuits (français)</th>
			<td>'.$circuits[$circuit1].' - '.$circuits[$circuit2].' - '.$circuits[$circuit3].' - '.$circuits[$circuit4].'</td>
		</tr>
		<tr>
			<th>Circuits (anglais)</th>
			<td>'.$circuitsAnglais[$circuit1].' - '.$circuitsAnglais[$circuit2].' - '.$circuitsAnglais[$circuit3].' - '.$circuitsAnglais[$circuit4].'</td>
		</tr>
		<tr>
			<th>Date prévue (heure du serveur)</th>
			<td>'.str_replace($motsAnglais, $motsFrancais, date('l d F Y - G:i', $date)).'</td>
		</tr>
		<tr>
			<th>Date prévue (heure locale)</th>
			<td>'.str_replace($motsAnglais, $motsFrancais, date('l d F Y - G:i', $date+$_SESSION['decalage']*3600)).'</td>
		</tr>
		<tr>
			<th>Statut</th>
			<td>'.$statut.'</td>
		</tr>
		</table>';
		mysql_close();
		?>
		
		<p><a href="profil.php?menu=voirmatch&amp;action=reporter">Proposer une autre date pour le match</a></p>
		<p><a href="profil.php?menu=voirmatch&amp;action=message">Envoyer un message à votre adversaire</a></p>
		<p><a href ="profil.php?menu=voirmatch&amp;action=renseigner">Renseigner les résultats du match</a></p>
	
	<?php
	}
}
	?>
