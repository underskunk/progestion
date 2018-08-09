<?php
$hote = '127.0.0.1';
$port = "";
$nom_bdd = 'progestion';
$utilisateur = 'root';
$mot_de_passe = '';

try {
    //On test la connexion à la base de donnée
    $pdo = new PDO('mysql:host='.$hote.';port='.$port.';dbname='.$nom_bdd, $utilisateur, $mot_de_passe);
} catch(Exception $e) {
    echo "Connection failed";
}

session_start();

if(!empty($_SESSION['login'])) {
	echo '<h1>Connection en cours...</h1>';
	header('Location: pages/projet.php');
	exit;
} else {
?>
<h1>Veuillez-vous connectez :</h1>

<form method="POST">
	<label for="login">Login</label>
	<input type="text" name="login" id="login">
	<label for="mdp">Mdp</label>
	<input type="password" name="mdp" id="mdp">
	<input type="submit" name="connexion" id="connexion" value="Connexion">
</form>

<h1>Si vous n'avez pas de compte, inscrivez-vous :</h1>

<form method="POST">
	<label for="nom">Nom</label>
	<input type="text" name="nom" id="nom" value="<?php if(!empty($_POST['nom'])){echo $_POST['nom'];} ?>" required><br><br>
	<label for="prenom">Prenom</label>
	<input type="text" name="prenom" id="prenom" value="<?php if(!empty($_POST['prenom'])){echo $_POST['prenom'];} ?>" required><br><br>
	<label for="mail">Mail</label>
	<input type="email" name="mail" id="mail" value="<?php if(!empty($_POST['mail'])){echo $_POST['mail'];} ?>" required><br><br>
	<label for="insc_login">Pseudo</label>
	<input type="text" name="insc_login" id="insc_login" value="<?php if(!empty($_POST['insc_login'])){echo $_POST['insc_login'];} ?>" required><br><br>
	<label for="insc_mdp">Mdp</label>
	<input type="password" name="insc_mdp" id="insc_mdp" required><br><br>
	<label for="insc_mdp2">Mdp Confirm</label>
	<input type="password" name="insc_mdp2" id="insc_mdp2" required><br><br>
	<input type="submit" name="inscription" id="inscription" value="Inscription">
</form>

<?php
	if(isset($_POST['connexion'])) {
		if(!empty($_POST['login']) && !empty($_POST['mdp'])) {
			$pseudo = $_POST['login'];
			$mdp = $_POST['mdp'];

			$verif_membre = $pdo->prepare("SELECT * FROM `membres` WHERE `pseudo` LIKE :pseudo");
			$verif_membre->execute(array(
				    ':pseudo' => $pseudo,
				));
			$result = $verif_membre->fetchAll();
			if(count($result) == 0) {
				echo 'Le mot de passe et le login ne correspondent pas.';
			} elseif (password_verify($mdp, $result[0]['mdp'])) {
			    $_SESSION['login'] =  $_POST['login'];
			    $_SESSION['id'] = $result[0]['id_membre'];
				header('Location: pages/projet.php');
				exit;
			}
		} else {
			echo "Le mot de passe et le login ne correspondent pas.";
		}
		
	}

	if(isset($_POST['inscription'])) {
			$mail = $_POST['mail'];
			$pseudo = $_POST['insc_login'];
			//$mdp = password_hash($_POST['mdp'], PASSWORD_BCRYPT);
			$nom = $_POST['nom'];
			$prenom = $_POST['prenom'];
			$insc_mdp = $_POST['insc_mdp'];
			$insc_mdp2 = $_POST['insc_mdp2'];
		
			$verif_membre = $pdo->prepare("SELECT COUNT(*) FROM `membres` WHERE `pseudo` LIKE :pseudo OR `mail` LIKE :mail");
			$verif_membre->execute(array(
				    ':pseudo' => $pseudo,
				    ':mail' => $mail,
				));
			$result = $verif_membre->fetchAll();

		switch (true) {
			case ($result[0][0] > 0):
				echo 'Ce pseudo ou cet email est déjà utilisé.';
				break;
			case ($insc_mdp != $insc_mdp2):
				echo 'Les deux mot de passe de correspondent pas.';
				break;
			case (!empty($mail) && !empty($pseudo) && !empty($nom) && !empty($prenom) && !empty($insc_mdp));
				$insert_membre = $pdo->prepare("INSERT INTO `membres` (`nom`, `prenom`, `pseudo`, `mdp`, `mail`) VALUES (:nom, :prenom, :pseudo, :mdp, :mail);");
				$insert_membre->execute(array(
					    ':pseudo' => $pseudo,
					    ':nom' => $nom,
					    ':mail' => $mail,
					    ':prenom' => $prenom,
					    ':mdp' => password_hash($insc_mdp, PASSWORD_BCRYPT)
					));
				//$_SESSION['login'] =  $_POST['insc_login'];
				header('Location: pages/projet.php');
				exit;
		}
	}
}
?>