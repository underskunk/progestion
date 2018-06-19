<?php

session_start();

if (empty($_SESSION['login'])) {
	header('Location: ../authentification.php');
	exit;
}

echo $_SESSION['login'];

?>

<br><br><a href="profile.php">Profile</a>
<a href="projet.php">Projet</a>

<br><br><form method="POST">
	<input type="submit" name="deco" id="deco" value="Deconnexion">
</form>

<?php

if (isset($_POST['deco'])) {
	session_destroy();
	session_unset();
	header('Location: ../authentification.php');
	exit;
}

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

?>