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
        <a class="nav-link active nav-item" href="listerProduits.php">Liste</a>
        <a class="nav-link nav-item" href="rechercherProduits.php">Recherche</a>
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
    <section class="container cListe">
        <table class="col-12 tabliste table">

            <thead class="thead-dark">
                <tr class="row">
                    <td class="col-md-1 text-center gras">N°</td>
                    <td class="col-md-3 text-center gras">Produit</td>
                    <td class="col-md-3 text-center gras">Quantité</td>
                    <td class="col-md-2 text-center gras">Prix</td>
                    <td class="col-md-3 text-center gras">Montant</td>
                </tr>
            </thead>
            <?php
            $totalQuant =0;
            $Totprix =0;
            $prixMoy = 0;
            $totalMont =0;
            $serveur = "localhost";
            $Monlogin = "root";
            $Monpass = "101419";

            function format($n)
            { //permet d afficher le separateur de millier
                return strrev(wordwrap(strrev($n), 3, ' ', true));
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
            //////////////////////////////////------Fin affichage-----//////////////////////////
            ?>
        </table>
    </section>
    <?php
    include("piedDePage.php");
    ?>
</body>

</html>