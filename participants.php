<p><strong>Liste des matchs et participants</strong></p>
<p>Vous trouverez ici la liste des matchs, qu'ils soient prévus ou qu'ils aient déjà eu lieu. En bas de la page se trouve la liste complète des participants.</p>
<p><strong>Liste des matchs</strong></p>
<?php
		require('config.php');
		require('circuits.php');
		include('traduction.php');
		
		$requete = 'SELECT * FROM participants';
		$reponse = mysql_query($requete);
		
		while($donnees = mysql_fetch_array($reponse))
		{
			$Pseudo[$donnees['ID']] = $donnees['Pseudo'];
		}
		
		$requete = 'SELECT * FROM matchs ORDER BY Tour DESC';
		$reponse = mysql_query($requete);
		
		$tour = 0;
		$nbTours = 0;
		
		while($donnees = mysql_fetch_array($reponse))
		{
			if($tour != $donnees['Tour'])
			{
				$tour = $donnees['Tour'];
				echo '<p>Tour '.$tour.'</p>';
				$nbTours++;
			}
			
			if($donnees['IDGagnant'] == 0)
			{
				$gagnant = '<span class="pasencore">Pas encore joué</span>';
			}
			elseif($donnees['IDGagnant'] == -1)
			{
				$gagnant = '<span class="joue">Égalité</span>';
			}
			else
			{
				$gagnant = '<span class="joue">'.$Pseudo[$donnees['IDGagnant']].' a remporté le match</span>';
			}
			
			echo '
			<table class="matchs" border="2">
			<tr>
				<td class="cinquante"><strong>'.$Pseudo[$donnees['ID1']].'</strong></td>
				<td class="cinquante"><strong>'.$Pseudo[$donnees['ID2']].'</strong></td>
			</tr>
			<tr>
				<td colspan="2">'.str_replace($motsAnglais, $motsFrancais, date('l d F Y - G:i', $donnees['Date'])).'</td> 
			</tr>
			<tr>
				<td colspan="2">'.$circuits[$donnees['Circuit1']].', '.$circuits[$donnees['Circuit2']].', '.$circuits[$donnees['Circuit3']].', '.$circuits[$donnees['Circuit4']].'</td>
			</tr>
			<tr>
				<td colspan="2">'.$gagnant.'</td>
			</tr>
			</table>
			';
		}
		if(mysql_affected_rows() == 0)
		{
			echo '<p>Aucun match n\'est encore prévu.</p>';
		}
?>
<p><strong>Liste des participants</strong></p>
<table class="participants" border="2">
<tr>
	<th>Classement</th>
	<th>Victoires</th>
	<th>Pseudo</th>
	<th>Code ami</th>
</tr>
<?php	
		$requete = 'SELECT * FROM participants ORDER BY NbVictoires DESC, Elimine ASC, Pseudo ASC';
		$reponse = mysql_query($requete);
		
		$place = 0;
		$precedente = -1;
		
		while($donnees = mysql_fetch_array($reponse))
		{
			if($donnees['NbVictoires'] != $precedente)
			{
				$place++;
			}
			$precedente = $donnees['NbVictoires'];
			
			if($donnees['Elimine'] == 1)
			{
				echo '
				<tr>
					<td class="dix"><span style="color: red;">Éliminé</span></td>
					<td class="dix">'.$donnees['NbVictoires'].'</td>
					<td>'.$donnees['Pseudo'].'</td>
					<td>'.$donnees['CodeAmi'].'</td>
				</tr>';
			}
			else
			{
				echo '
				<tr>
					<td class="dix">'.$place.'</td>
					<td class="dix">'.$donnees['NbVictoires'].'</td>
					<td>'.$donnees['Pseudo'].'</td>
					<td>'.$donnees['CodeAmi'].'</td>
				</tr>';
			}
		}
		
		mysql_close();
		?>
</table>
