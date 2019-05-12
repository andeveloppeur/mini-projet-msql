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
        <a class="nav-link nav-item" href="accueil.php">Accueil</a>
        <a class="nav-link nav-item" href="listerProduits.php">Liste</a>
        <a class="nav-link active nav-item" href="rechercherProduits.php">Recherche</a>
        <a class="nav-link nav-item" href="ajouterProduit.php">Ajouter</a>
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
        <form action="rechercherProduits.php" method="POST" class="tab row">
            <div class="col-md-3"></div>
            <div class="col-md-6 bor">
                <?php
                $recherch_reussi = 0;
                if ($_POST["quantite"] >= 0 && $_POST["quantite"] != "" && $_POST["prixMin"] >= 0 && $_POST["prixMin"] != "" && $_POST["prixMax"] > 0 && $_POST["prixMax"] > $_POST["prixMin"] || $_POST["quantite"] >= 0 && $_POST["quantite"] != "" && $_POST["prixMin"] == "" && $_POST["prixMax"] == "" || $_POST["quantite"] == "" && $_POST["prixMin"] >= 0 && $_POST["prixMin"] != "" && $_POST["prixMax"] > 0 && $_POST["prixMax"] > $_POST["prixMin"]) {
                    $recherch_reussi = 1;
                }
                $seuilQuantite = $_POST["quantite"]; //recuperation du seuil (quantité)

                echo '<div class="row">
                            <div class="col-md-2"></div>
                            <input class="form-control col-md-8 espace ';
                if (isset($_POST["valider"]) && $seuilQuantite < 0) {
                    echo 'rougMoins';
                }
                echo '" type="number" id="quantite" name="quantite"';
                if ($seuilQuantite < 0 && $seuilQuantite != "") {
                    echo ' placeholder="Impossible car ' . $seuilQuantite . ' est inférieur à 0"';
                } elseif ($seuilQuantite >= 0 && isset($_POST["valider"]) && $seuilQuantite != "") {
                    echo ' placeholder="Quantité supérieur ou égal à :" value="' . $seuilQuantite . '"';
                } elseif ($seuilQuantite == "") {
                    echo ' placeholder="Quantité supérieur ou égal à :" value=""';
                }
                echo '>'; //lors du chargement de la page
                echo '</div>';

                $seuilPrixMin = $_POST["prixMin"]; //recuperation du seuil (prixMin)
                echo '<div class="row">
                            <div class="col-md-2"></div><input class="form-control col-md-8 espace ';
                if (isset($_POST["valider"]) && $seuilPrixMin < 0 && $seuilPrixMin != "" || isset($_POST["valider"]) && $_POST["prixMax"] >= 0 && $_POST["prixMax"] != 0 && $seuilPrixMin == "") {
                    echo 'rougMoins';
                }
                echo '" type="number" id="prixMin" name="prixMin"';
                if ($seuilPrixMin < 0 && $seuilPrixMin != "") {
                    echo ' placeholder="Impossible car ' . $seuilPrixMin . ' est inférieur à 0"';
                } elseif ($_POST["prixMax"] >= 0 && $_POST["prixMax"] != 0 && $seuilPrixMin == "") {
                    echo ' placeholder="Remplir la borne supérieur "';
                } elseif ($seuilPrixMin >= 0 && isset($_POST["valider"]) && $seuilPrixMin != "") {
                    echo ' placeholder="Prix (Bonre inférieur) :" value="' . $seuilPrixMin . '"';
                } elseif ($seuilPrixMin == "") {
                    echo ' placeholder="Prix (Bonre inférieur) :" value=""';
                }
                echo '>'; //lors du chargement de la page
                echo '</div>';
                $seuilPrixMax = $_POST["prixMax"]; //recuperation du seuil (prixMax)
                echo '<div class="row">
                            <div class="col-md-2"></div><input class="form-control col-md-8 espace ';
                if (isset($_POST["valider"]) && $seuilPrixMax < 0 && $seuilPrixMax != "" || isset($_POST["valider"]) && $seuilPrixMax < $seuilPrixMin && $seuilPrixMax != "" || isset($_POST["valider"]) && $seuilPrixMin >= 0 && $seuilPrixMin != 0 && $seuilPrixMax == "") {
                    echo 'rougMoins';
                }
                echo '" type="number" id="prixMax" name="prixMax"';
                if ($seuilPrixMax < 0 && $seuilPrixMax != "") {
                    echo ' placeholder="Impossible car ' . $seuilPrixMax . ' est inférieur à 0"';
                } elseif ($seuilPrixMax < $seuilPrixMin && $seuilPrixMax != "") {
                    echo ' placeholder="Impossible car ' . $seuilPrixMax . ' est inférieur à ' . $seuilPrixMin . '"';
                } elseif ($seuilPrixMin >= 0 && $seuilPrixMin != 0 && $seuilPrixMax == "") {
                    echo ' placeholder="Remplir la borne supérieur "';
                } elseif ($seuilPrixMax >= 0 && isset($_POST["valider"]) && $seuilPrixMax != "") {
                    echo ' placeholder="Prix (Bonre supérieur) :" value="' . $seuilPrixMax . '"';
                } elseif ($seuilPrixMax == "") {
                    echo ' placeholder="Prix (Bonre supérieur) :" value=""';
                }
                echo '>'; //lors du chargement de la page
                echo '</div>';
                ?>
                <div class="row">
                    <div class="col-md-3"></div>
                    <input type="submit" class="form-control col-md-6 espace" value="Rechercher" name="valider">
                </div>
            </div>
        </form>
        <table class="col-12 liste table">
            <?php
            $serveur = "localhost";
            $Monlogin = "root";
            $Monpass = "101419";
            try {
                $connexion = new PDO("mysql:host=$serveur;dbname=mini-projet-php;charset=utf8", $Monlogin, $Monpass); //se connecte au serveur mysquel
                $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //setAttribute — Configure l'attribut PDO $connexion
                $codemysql = "SELECT * FROM `Liste-produits`";
                $requete = $connexion->prepare($codemysql);
                $requete->execute();
                $produits = $requete->fetchAll();


                if ($recherch_reussi == 1) { //pour cacher le tableau lors du chargement de la page
                    echo '<thead class="thead-dark">
                            <tr class="row">
                                <td class="col-md-1 text-center gras">N°</td>
                                <td class="col-md-3 text-center gras">Produit</td>
                                <td class="col-md-3 text-center gras">Quantité</td>
                                <td class="col-md-2 text-center gras">Prix</td>
                                <td class="col-md-3 text-center gras">Montant</td>
                            </tr>
                        </thead>';
                    function format($n)
                    { //permet d afficher le separateur de millier
                        return strrev(wordwrap(strrev($n), 3, ' ', true));
                    }
                    $j = 0;
                    for ($i = 0; $i < count($produits); $i++) {
                        $leProdruit = $produits[$i]["Nom"];
                        $laQuantite = $produits[$i]["Quantite"];
                        $lePrix = $produits[$i]["Prix"];
                        $leMontant = $produits[$i]["Montant"];
                        if ($laQuantite >= $seuilQuantite && $lePrix >= $seuilPrixMin && $lePrix <= $seuilPrixMax && $seuilQuantite >= 0 && $seuilPrixMin >= 0 && $seuilPrixMax >= 0) { //permet d'afficher les produits qui ont une quantité et un prix superieurs au seuil
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
                            $prixMoy = $Totprix / ($i +1);
                            $totalMont += $leMontant;
                        } 
                        elseif ($seuilQuantite == "" && $lePrix >= $seuilPrixMin && $lePrix <= $seuilPrixMax && $seuilPrixMin >= 0 && $seuilPrixMax >= 0) {
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
                            $prixMoy = $Totprix / ($i +1);
                            $totalMont += $leMontant;
                        } 
                        elseif ($seuilQuantite == "" && $lePrix >= $seuilPrixMin && $seuilPrixMax == "" && $seuilPrixMin >= 0) {
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
                            $prixMoy = $Totprix / ($i +1);
                            $totalMont += $leMontant;
                        } 
                        elseif ($seuilQuantite == "" && $seuilPrixMin == "" && $lePrix <= $seuilPrixMax && $seuilPrixMax >= 0) {
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
                            $prixMoy = $Totprix / ($i +1);
                            $totalMont += $leMontant;
                        } 
                        elseif ($laQuantite >= $seuilQuantite && $seuilPrixMin == "" && $seuilPrixMax == "" && $seuilQuantite >= 0) { //permet d'afficher les produits qui ont une quantité et un prix superieurs au seuil
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
                            $prixMoy = $Totprix / ($i +1);
                            $totalMont += $leMontant;
                        }

                    }
                    echo
                        '<tr class="row">
                        <td class="col-md-1 text-center gras"></td>
                        <td class="col-md-3 text-center gras">Total</td>
                        <td class="col-md-3 text-center gras">' . format($totalQuant) . '</td>
                        <td class="col-md-2 text-center gras">' . $prixMoy . '</td>
                        <td class="col-md-3 text-center gras">' . format($totalMont) . '</td>
                    </tr>';
                }
            } catch (PDOException $e) {
                echo "ECHEC : " . $e->getMessage(); //en cas d erreur lors de la connexion à la base de données mysql
            }
            ?>
        </table>
    </section>
    <?php
    include("piedDePage.php");
    ?>
</body>

</html>