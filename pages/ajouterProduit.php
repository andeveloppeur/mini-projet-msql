<?php
session_start();
if ($_SESSION["profil"] != "admin" && $_SESSION["profil"] != "user") {
    echo '<h1>Connectez-vous</h1>';
    header('Location: ../index.php');
    exit();
}
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
        <a class="nav-link  nav-item" href="accueil.php">Accueil</a>
        <a class="nav-link nav-item" href="listerProduits.php">Liste</a>
        <a class="nav-link nav-item" href="rechercherProduits.php">Recherche</a>
        <a class="nav-link active nav-item" href="ajouterProduit.php">Ajouter</a>
        <a class="nav-link nav-item" href="updateProduit.php">Modifier</a>
        <a class="nav-link nav-item" href="supprimerProduit.php">Supprimer</a>
        <?php
            if ($_SESSION["profil"] == "admin") {
                echo '<a class="nav-link nav-item" href="Utilisateur.php">Utilisateurs</a>';
            }
        ?>
        <a class="nav-link nav-item" href="../index.php">Déconnection</a>
    </nav>
    <header></header>
    <section class="container corps">
        <?php
        // $monfichier = fopen('BDD.txt', 'r');
        // $ligne = fgets($monfichier);
        // $produits = explode('|', $ligne);
        // fclose($monfichier);
        ?>
        <form action="ajouterProduit.php" method="POST" class="tab row">
            <div class="col-md-3"></div>
            <div class="col-md-6 bor">
                <?php
                    $prodExiste = 0;
                    $ajout_reussi = 0;
                    $serveur = "localhost";
                    $Monlogin = "root";
                    $Monpass = "101419";
                    $nouvPro = $_POST["produit"];

                    try {
                        $connexion = new PDO("mysql:host=$serveur;dbname=mini-projet-php;charset=utf8", $Monlogin, $Monpass); //se connecte au serveur mysquel
                        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //setAttribute — Configure l'attribut PDO $connexion
                        $codemysql = "SELECT * FROM `Liste-produits`";
                        $requete = $connexion->prepare($codemysql);
                        $requete->execute();
                        $produits = $requete->fetchAll();
                        for ($i = 0; $i < count($produits); $i++) { //casse
                            if (!strcasecmp($nouvPro, $produits[$i]["Nom"])) { //pour gerer la casse
                                $nouvPro = $produits[$i]["Nom"];
                            }
                            if ($produits[$i]["Nom"] == $nouvPro) { //verifie si le produit à ajouter n existe pas deja
                                $prodExiste = 1; //si existe deja le variable $prodExiste=1 cela nous permettra de bloquer l'ajout
                            }
                        }
                        if ($prodExiste == 0 && $nouvPro != "" && $_POST["quantite"] >= 0 && $_POST["prix"] >= 100) { //pour reinitialiser les placeholders
                            $ajout_reussi = 1;
                        }
                    } catch (PDOException $e) {
                        echo "ECHEC : " . $e->getMessage(); //en cas d erreur lors de la connexion à la base de données mysql
                        exit(); //arreter le code
                    }
                    //////////////////////////////////----validiter des données et gestion des inputs----////////////////////
                    echo '<div class="row">
                            <div class="col-md-2"></div>
                            <input class="form-control col-md-8 espace ';
                    if (isset($_POST["valider"]) && $nouvPro == "" || $prodExiste == 1) {
                        echo 'rougMoins';
                    }
                    echo '" type="text" id="produit" name="produit"';
                    if (isset($_POST["valider"]) && $nouvPro == "") {
                        echo 'placeholder="Remplir le nom du produit"';
                    } //si on envoi ajoute un produit sans remplir le nom du produit
                    elseif ($prodExiste == 1) {
                        echo ' placeholder="' . $nouvPro . ' existe déja" value=""';
                    } //si le produit existe deja
                    elseif ($ajout_reussi == 1) {
                        echo ' placeholder="Nom produit" value=""';
                    } //si ajout reussi
                    elseif (isset($_POST["valider"]) && $nouvPro != "" && $prodExiste == 0) {
                        echo 'value="' . $nouvPro . '"';
                    } //si il y a une erreur dans la quantité ou le prix garder le nom du produit saisi
                    elseif ($prodExiste == 0) {
                        echo ' placeholder="Nom produit" value=""';
                    }
                    echo '>'; //lors du chargement de la page
                    echo '</div>';
                    $nouvQuant = $_POST["quantite"];

                    echo '<div class="row">
                                <div class="col-md-2"></div>
                                <input class="form-control col-md-8 espace ';
                    if (isset($_POST["valider"]) && $nouvQuant < 0 || isset($_POST["valider"]) && $nouvQuant == "") {
                        echo 'rougMoins';
                    }
                    echo '" type="number" id="quantite" name="quantite"';
                    if ($nouvQuant == "" && isset($_POST["valider"])) {
                        echo ' placeholder="Remplir la quantité"';
                    } elseif ($nouvQuant < 0 && $nouvQuant != "") {
                        echo ' placeholder="Impossible car ' . $nouvQuant . ' est inférieur à 0"';
                    } elseif ($ajout_reussi == 1) {
                        echo ' placeholder="Quantité" value=""';
                    } //si ajout reussi
                    elseif ($nouvQuant >= 0 && isset($_POST["valider"])) {
                        echo ' value="' . $nouvQuant . '"';
                    } elseif ($nouvQuant == "") {
                        echo ' placeholder="Quantité" value=""';
                    }
                    echo '>'; //lors du chargement de la page
                    echo '</div>';

                    $nouvPrix = $_POST["prix"];
                    echo '<div class="row">
                                <div class="col-md-2"></div><input class="form-control col-md-8 espace ';
                    if (isset($_POST["valider"]) && $nouvPrix < 100 || isset($_POST["valider"]) && $nouvPrix == "") {
                        echo 'rougMoins';
                    }
                    echo '" type="number" id="prix" name="prix"';
                    if ($nouvPrix == "" && isset($_POST["valider"])) {
                        echo ' placeholder="Remplir le prix"';
                    } elseif ($nouvPrix < 100 && $nouvPrix != "") {
                        echo ' placeholder="Impossible car ' . $nouvPrix . ' est inférieur à 100"';
                    } elseif ($ajout_reussi == 1) {
                        echo ' placeholder="Prix" value=""';
                    } //si ajout reussi
                    elseif ($nouvPrix >= 100 && isset($_POST["valider"])) {
                        echo ' value="' . $nouvPrix . '"';
                    } elseif ($nouvPrix == "") {
                        echo ' placeholder="Prix" value=""';
                    }
                    echo '>'; //lors du chargement de la page
                    echo '</div>';
                    //////////////////////////////////----Fin validiter des données et gestion des inputs----////////////////////
                ?>
                <div class="row">
                    <div class="col-md-3"></div>
                    <input type="submit" class="form-control col-md-6 espace" value="Ajouter" name="valider">
                </div>
            </div>
        </form>
        <table class="col-12 liste mb5 table">
            <thead class="thead-dark">
                <tr class="row PourHover">
                    <!--creation d'une ligne-->
                    <td class="col-md-1 text-center gras">N°</td>
                    <!--creation d'une colonne-->
                    <td class="col-md-3 text-center gras">Produit</td>
                    <td class="col-md-3 text-center gras">Quantité</td>
                    <td class="col-md-2 text-center gras">Prix</td>
                    <td class="col-md-3 text-center gras">Montant</td>
                </tr>
            </thead>
            <?php
            function format($n)
            { //permet d afficher le separateur de millier ex : 10 000
                return strrev(wordwrap(strrev($n), 3, ' ', true)); //strrev permet de renverser une chaine de caractere et wordwrap permet d inserer l espace
            }
            if ($nouvPro != "" && $nouvQuant >= 0 && $nouvPrix >= 100 && $prodExiste == 0) { //pour eviter d'ajouter un element vide dans le tableau lorsque la page sera actualiser et que $nouvPro sera vide
                $codemysql = "INSERT INTO `Liste-produits` (Nom,Quantite,Prix,Montant)
                        VALUES(:Nom,:Quantite,:Prix,:Montant)"; //le code mysql
                $requete = $connexion->prepare($codemysql); //Prépare la requête $codemysql à l'exécution 
                $requete->bindParam(":Nom", $nouvPro); //bindParam lie un paramètre (:nom) à un nom de variable spécifique ($nom)
                $requete->bindParam(":Quantite", $nouvQuant);
                $requete->bindParam(":Prix", $nouvPrix);
                $Montant = $nouvQuant * $nouvPrix;
                $requete->bindParam(":Montant", $Montant);
                $requete->execute(); //excecute la requete qui a été preparé
                header("location: #finTab");
            }
            //////////////////////////////////------Debut affichage-----//////////////////////////
            try {
                $connexion = new PDO("mysql:host=$serveur;dbname=mini-projet-php;charset=utf8", $Monlogin, $Monpass); //se connecte au serveur mysquel
                $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //setAttribute — Configure l'attribut PDO $connexion
                $codemysql = "SELECT * FROM `Liste-produits`";
                $requete = $connexion->prepare($codemysql);
                $requete->execute();
                $produits = $requete->fetchAll();

                $j = 0;
                for ($i = 0; $i < count($produits); $i++) { //permet de parcourir tout le tableau (count renvoi la taille du tableau)
                    $leProdruit = $produits[$i]["Nom"];
                    $laQuantite = $produits[$i]["Quantite"];
                    $lePrix = $produits[$i]["Prix"];
                    $leMontant = $produits[$i]["Montant"];
                    if ($laQuantite >= 10) { //pour savoir les produit à renouveller
                        echo
                            '<tr class="row">';
                    } else {
                        echo '<tr class="row rouge">';
                    }
                    $j++;
                    echo '<td class="col-md-1 text-center">' . $j . '</td>
                        <td class="col-md-3 text-center">' . $leProdruit . '</td>
                        <td class="col-md-3 text-center">' . format($laQuantite) . '</td>
                        <td class="col-md-2 text-center">' . format($lePrix) . '</td>
                        <td class="col-md-3 text-center">' . format($leMontant) . '</td>
                    </tr>';

                    $totalQuant += $laQuantite; //calcul le total des quantités
                    $Totprix += $lePrix; //calcul le total des prix pour calculer la moyenne
                    $prixMoy = $Totprix / ($i + 1);
                    $totalMont += $leMontant;
                }
                echo //permet d afficher le total des elements
                    '<tr class="row" id="finTab">
                    <td class="col-md-1 text-center gras"></td>
                    <td class="col-md-3 text-center gras">Total</td>
                    <td class="col-md-3 text-center gras">' . format($totalQuant) . '</td>
                    <td class="col-md-2 text-center gras">' . $prixMoy . '</td>
                    <td class="col-md-3 text-center gras">' . format($totalMont) . '</td>
                    </tr>';
            } catch (PDOException $e) {
                echo "ECHEC : " . $e->getMessage(); //en cas d erreur lors de la connexion à la base de données mysql
            }
            //////////////////////////////////------Debut affichage-----//////////////////////////
            ?>
        </table>
    </section>
    <?php
    include("piedDePage.php");
    ?>
</body>

</html>