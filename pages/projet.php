<?php
include '../template/header.php';
?>

<style>

/* The Modal (background) */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

/* The Close Button */
.close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
</style>

<button id="btn_new_projet">Nouveau Projet</button>

<!-- The Modal -->
<div id="new_projet_modal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <h1>Nouveau Projet :</h1>
    <form method="POST" id="projet">
    	<label for="nom_projet">Nom du projet</label>
    	<input type="text" name="nom_projet"><br>
    	<label for="desc_projet">Description du projet</label>
    	<textarea name="desc_projet" form="projet"></textarea><br>
    	<input type="submit" name="new_projet">
    </form>
  </div>

</div>

<div id="projet_modal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span id="span_projet" class="close">&times;</span>
    <h1>Modifier le projet :</h1>
    <form method="POST" id="modif_projet">
    	<input type="search" name="add_friend">

    	<input type="submit" name="new_projet">
    </form>
  </div>

</div>

<div>
<?php

/*------------------------------------- Requete SQL afficher les projets -------------------------------------------*/

	$select_projet = $pdo->prepare("SELECT * FROM `projets` INNER JOIN `membres_projet` ON  `projets`.`id_projet` =  `membres_projet`.`id_projet` AND `membres_projet`.`id_membres` = :id_membres");
	$select_projet->execute(array(
				    ':id_membres' => $_SESSION['id'],				    
				));
	$result['projet'] = $select_projet->fetchAll();
	foreach ($result['projet'] as $value) {
		$nom = $value['nom_projet'];
		$desc = $value['description'];
		$id_projet = $value['id_projet'];
		echo '<br><button value='.$id_projet.' id="btn_projet">'.$nom.'</button><br>';
	}
	
/*------------------------------------- Ajout d'un nouveau projet -------------------------------------------*/

	if(isset($_POST['new_projet'])) {
		if(!empty($_POST['nom_projet']) && !empty($_POST['desc_projet'])) {
			$nom_projet = $_POST['nom_projet'];
			$desc_projet = $_POST['desc_projet'];

			$ajout_projet = $pdo->prepare("INSERT INTO `projets` (`nom_projet`, `description`) VALUES (:nom_projet, :desc_projet)");
			$ajout_projet->execute(array(
				    ':nom_projet' => $nom_projet,
				    ':desc_projet' => $desc_projet,
				));
			$ajout_projet_membre = $pdo->prepare("INSERT INTO `membres_projet` (`id_projet`, `id_membres`, `id_status`) VALUES (:id_projet, :id_membres, :id_status);");
			$ajout_projet_membre->execute(array(
				    ':id_projet' => $id_projet + 1,
				    ':id_membres' => $_SESSION['id'],
				    ':id_status' => 0,
				));
			header('Location: projet.php');
			exit;
		}
	}

/*------------------------------------- Modification d'un projet / ajout d'un membre -------------------------------------------*/
	//$membre = array();
    $select_membres = $pdo->prepare("SELECT * FROM `membres`");
    $select_membres->execute();

    $result_membre['membres'] = $select_membres->fetchAll();
    foreach ($result_membre['membres'] as $value) {
        $membre[] = $value['pseudo'];
    }
    $membre_array = implode(',', $membre);
    echo $membre_array;
?>
</div>


<script>

var modal_projet = document.getElementById('projet_modal');
var btn_projet = document.getElementById("btn_projet");
var span_projet = document.getElementsByClassName("close")[0];

btn_projet.onclick = function() {
    modal_projet.style.display = "block";
}
span_projet.onclick = function() {
    modal_projet.style.display = "none";
}

var modal_new_projet = document.getElementById('new_projet_modal');
var btn_new_projet = document.getElementById("btn_new_projet");
var span_new_projet = document.getElementsByClassName("close")[0];

btn_new_projet.onclick = function() {
    modal_new_projet.style.display = "block";
}
span_new_projet.onclick = function() {
    modal_new_projet.style.display = "none";
}

</script>