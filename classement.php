<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Tournoi Mario Kart</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body><div id="titre">
</div>
<p><strong>Classement du tournoi</strong></p>
<p>Cette page vous permet de consulter le classement actuel même si vous ne participez pas au tournoi. Si vous êtes inscrit, des informations plus détaillées sont présentes dans votre profil.</p>

<table class="participants" border="2">
<tr>
	<th>Classement</th>
	<th>Victoires</th>
	<th>Pseudo</th>
</tr>
<?php
		require('config.php');
		
		$requete = 'SELECT * FROM participants ORDER BY NbVictoires DESC, Elimine ASC, Pseudo ASC';
		$reponse = mysql_query($requete);
		
		$place = 0;
		$precedente = -1;
		
		while($donnees = mysql_fetch_array($reponse))
		{
			if($donnees['NbVictoires'] !== $precedente)
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
				</tr>';
			}
			else
			{
				echo '
				<tr>
					<td class="dix">'.$place.'</td>
					<td class="dix">'.$donnees['NbVictoires'].'</td>
					<td>'.$donnees['Pseudo'].'</td>
				</tr>';
			}
		}
		
		mysql_close();
		?>
</table>
<p><a href="index.php">Retour à l'accueil</a></p>
</body>
</html>
