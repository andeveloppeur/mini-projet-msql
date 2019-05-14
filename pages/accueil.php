<?php
session_start();
if($_SESSION["profil"]!="admin" && $_SESSION["profil"]!="user" ){
    echo'<h1>Connectez-vous</h1>';
    header('Location: ../index.php');
    exit();
} 
$_SESSION['ouvert']=1;
?>
<!DOCTYPE html>
<html lang="FR-fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="../css/MonStyle.css">
    <title>Site</title>
</head>
<body>
    <nav class="container nav nav-pills nav-fill">
        <a class="nav-link active nav-item" href="accueil.php">Accueil</a>
        <a class="nav-link nav-item" href="listerProduits.php">Liste</a>
        <a class="nav-link nav-item" href="rechercherProduits.php">Recherche</a>
        <a class="nav-link nav-item" href="ajouterProduit.php">Ajouter</a>
        <a class="nav-link nav-item" href="updateProduit.php">Modifier</a>
        <a class="nav-link nav-item" href="supprimerProduit.php">Supprimer</a>
        <?php
            if($_SESSION["profil"]=="admin"){
                echo'<a class="nav-link nav-item" href="Utilisateur.php">Utilisateurs</a>';
            }        
        ?>
        <a class="nav-link nav-item" href="deconnexion.php">Déconnection</a>
    </nav>
    <header></header>
    <section class="container">
        <?php
            echo"<h2 class='bienv'>Bienvenue ".$_SESSION["nom"]."</h2>";//affiche bienvenue suivi du nom de l'utilisateur
        ?>
    </section>
    <?php
        include("piedDePage.php");
    ?>
</body>
</html>