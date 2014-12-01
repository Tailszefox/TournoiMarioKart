<?php
	echo '<p><strong>Gérer ses disponibilités</strong></p>';
		
		require('config.php');
		if(isset($_GET['action']) && $_GET['action'] == 'update')
		{
			if($_POST['semaine'] == 'on')
				$semaine = 1;
			else
				$semaine = 0;
				
			if($_POST['mercredi'] == 'on')
				$mercredi = 1;
			else
				$mercredi = 0;
				
			if($_POST['samedi'] == 'on')
				$samedi = 1;
			else
				$samedi = 0;
				
			if($_POST['dimanche'] == 'on')
				$dimanche = 1;
			else
				$dimanche = 0;
				
			if($_POST['soiree'] == 'on')
				$soiree = 1;
			else
				$soiree = 0;
				
			$continent = $_POST['continent'];
			$decalageParticipant = htmlspecialchars($_POST['decalage']);
			$_SESSION['decalage'] = htmlspecialchars($_POST['decalage']);

			
			$requete = 'UPDATE disponibilite SET semaine = '.$semaine.', mercredi = '.$mercredi.', samedi = '.$samedi.', dimanche = '.$dimanche.', soiree = '.$soiree.', continent="'.$continent.'", decalage="'.$decalageParticipant.'" WHERE IDParticipant = "'.$_SESSION['ID'].'"';
			$reponse = mysql_query($requete);
			echo '<p><strong>Disponibilités mises à jour</strong></p>';
			
		}
		?>
			<p>Cette page vous permet de sélectionner les moments de la semaine où vous êtes le plus susceptible d'être disponible. Les duels se feront ainsi contre des membres qui ont les mêmes
			disponibilités que vous, dans la mesure du possible.<br />
			Renseigner votre continent vous permettra également d'affronter des gens du même continent au départ, évitant ainsi les problèmes du décalage horaire. N'oubliez pas de le renseigner lui aussi.<br />
			Notez qu'il est toujours possible de repousser un match prévu si vous n'êtes pas disponible à la date choisie, mais remplir cette page ne fera qu'optimiser
			le tournoi.</p>
		<?php
		$requete = 'SELECT * FROM disponibilite WHERE IDparticipant = "'.$_SESSION['ID'].'"';
		$reponse = mysql_query($requete);
		
		while($donnees = mysql_fetch_array($reponse))
		{
			$semaine = $donnees['semaine'];
			$mercredi = $donnees['mercredi'];
			$samedi = $donnees['samedi'];
			$dimanche = $donnees['dimanche'];
			$soiree = $donnees['soiree'];
			$continent = $donnees['continent'];
			$decalageParticipant = $donnees['decalage'];
			
		}
		
		mysql_close();
		?>
		<div class="formulaire"><form method="post" action="profil.php?menu=disponibilite&amp;action=update">
		<p><strong>Je suis disponible...</strong></p>
		<p class="alignerformulaire">
		<input type="checkbox" name="semaine" <?php if($semaine == 1) echo 'checked="checked"'; ?> /> En semaine (lundi, mardi, jeudi, vendredi)<br />
		<input type="checkbox" name="mercredi" <?php if($mercredi == 1) echo 'checked="checked"'; ?> /> Le mercredi après-midi<br />
		<input type="checkbox" name="samedi" <?php if($samedi == 1) echo 'checked="checked"'; ?> />Le samedi<br />
		<input type="checkbox" name="dimanche" <?php if($dimanche == 1) echo 'checked="checked"'; ?> />Le dimanche<br />
		<input type="checkbox" name="soiree" <?php if($soiree == 1) echo 'checked="checked"'; ?> /> En soirée
		</p>
		
		<p><strong>J'habite...</strong></p>
		<p class="alignerformulaire">
		<input type="radio" name="continent" value="europe" <?php if($continent == 'europe') echo 'checked="checked"' ?> /> En Europe<br />
		<input type="radio" name="continent" value="amerique" <?php if($continent == 'amerique') echo 'checked="checked"' ?> /> En Amérique du Nord
		</p>
		
		<p><strong>Mon décalage horaire est de...</strong></p>
		<p>
		<input type="text" name="decalage" size="2" maxlength="3" value="<?php echo $decalageParticipant; ?>" /> heure(s)<br /><br />
		L'heure du serveur est <strong><?php echo date('G:i'); ?></strong>. 
		D'après votre décalage horaire, il est <strong><?php echo date('G:i', time()+$decalageParticipant*3600); ?></strong> chez vous. Si ce n'est pas le cas, modifiez-le.<br />
		Pour le Québec, le décalage est -6. Pour le Nouveau-Brunswick, le décalage est -5.<br />
		
		<br /><input type="submit" value="Confirmer" />
		<input type="hidden" name="PHPSESSID" value="<?php echo session_id();?>" />
		</p>
		</form></div>

		
