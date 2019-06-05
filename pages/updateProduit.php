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
        <a class="nav-link nav-item" href="rechercherProduits.php">Recherche</a>
        <a class="nav-link nav-item" href="ajouterProduit.php">Ajouter</a>
        <a class="nav-link active nav-item" href="updateProduit.php">Modifier</a>
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
        <form action="updateProduit.php" method="POST" class="tab row">
            <div class="col-md-3"></div>
            <div class="col-md-6 bor">
                <?php
                // $monfichier = fopen('BDD.txt', 'r');
                // $ligne = fgets($monfichier);
                // $produits=explode('|',$ligne);
                // fclose($monfichier);
                $prodExiste = 0;
                $Modif_reussi = 0;
                $nouvPro = "";
                $nouvQuant = 0;
                $nouvPrix = 0;
                $totalQuant = 0;
                $Totprix = 0;
                $totalMont = 0;
                $serveur = "localhost";
                $Monlogin = "root";
                $Monpass = "101419";
                if (isset($_POST["valider"])) {
                    function securisation($donnees)
                    {
                        $donnees = trim($donnees); //trim supprime les espaces (ou d'autres caractères) en début et fin de chaîne
                        $donnees = stripslashes($donnees); //Supprime les antislashs d'une chaîne
                        $donnees = strip_tags($donnees); //neutralise le code html et php
                        $donnees = mysql_real_escape_string($donnees); // elle neutralise tous les caractères susceptibles d'être à l'origine d'une injection SQL.
                        $donnees = addcslashes($donnees, '%_'); //pour gerer les injections sql qui visent notamment à surcharger notre serveur en alourdissant notre requête. Ce type d'injection utilise les caractères % et _.
                        return $donnees;
                    }
                    $nouvPro = securisation($_POST["produit"]);
                    $nouvQuant = securisation($_POST["quantite"]);
                    $nouvPrix = securisation($_POST["prix"]);
                }
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


                if (($prodExiste == 1 && $nouvPro != "" && $_POST["quantite"] >= 0 && $_POST["prix"] >= 100) || ($prodExiste == 1 && $nouvPro != "" && $_POST["quantite"] >= 0 && $_POST["prix"] == "") || ($prodExiste == 1 && $nouvPro != "" && $_POST["quantite"] == "" && $_POST["prix"] >= 100)) {
                    $Modif_reussi = 1;
                }
                echo '<div class="row">
                            <div class="col-md-2"></div>
                            <input class="form-control col-md-8 espace ';
                if (isset($_POST["valider"]) && $nouvPro == "" || isset($_POST["valider"]) && $prodExiste == 0) {
                    echo 'rougMoins';
                }
                echo '" type="text" id="produit" name="produit"';
                if (isset($_POST["valider"]) && $nouvPro == "") {
                    echo 'placeholder="Remplir le nom du produit"';
                } //si on envoi ajoute un produit sans remplir le nom du produit
                elseif ($prodExiste == 0 && isset($_POST["valider"])) {
                    echo ' placeholder="Le produit ' . $nouvPro . ' n\'existe pas" value=""';
                } //si le produit existe deja
                elseif ($Modif_reussi == 1) {
                    echo ' placeholder="Nom produit" value=""';
                } //si modif reussi
                elseif (isset($_POST["valider"]) && $nouvPro != "" && $prodExiste == 1) {
                    echo 'value="' . $nouvPro . '"';
                } //si il y a une erreur dans la quantité ou le prix garder le nom du produit saisi
                elseif ($prodExiste == 0) {
                    echo ' placeholder="Nom produit" value=""';
                }
                echo '>'; //lors du chargement de la page
                echo '</div>';


                echo '<div class="row">
                            <div class="col-md-2"></div>
                            <input class="form-control col-md-8 espace ';
                if (isset($_POST["valider"]) && $nouvQuant < 0) {
                    echo 'rougMoins';
                }
                echo '" type="number" id="quantite" name="quantite"';
                if ($nouvQuant < 0 && $nouvQuant != "") {
                    echo ' placeholder="Impossible car ' . $nouvQuant . ' est inférieur à 0"';
                } elseif ($Modif_reussi == 1) {
                    echo ' placeholder="Quantité à modifier :" value=""';
                } //si modif reussi
                elseif ($nouvQuant >= 0 && isset($_POST["valider"]) && $nouvQuant != "") {
                    echo ' value="' . $nouvQuant . '"';
                } elseif ($nouvQuant == "") {
                    echo ' placeholder="Quantité à modifier :" value=""';
                }
                echo '>'; //lors du chargement de la page
                echo '</div>';


                echo '<div class="row">
                            <div class="col-md-2"></div><input class="form-control col-md-8 espace ';
                if (isset($_POST["valider"]) && $nouvPrix < 100 && $nouvPrix != "") {
                    echo 'rougMoins';
                }
                echo '" type="number" id="prix" name="prix"';
                if ($nouvPrix < 100 && $nouvPrix != "") {
                    echo ' placeholder="Impossible car ' . $nouvPrix . ' est inférieur à 100"';
                } elseif ($Modif_reussi == 1) {
                    echo ' placeholder="Prix à modifier :" value=""';
                } //si modif reussi
                elseif ($nouvPrix >= 100 && isset($_POST["valider"])) {
                    echo ' value="' . $nouvPrix . '"';
                } elseif ($nouvPrix == "") {
                    echo ' placeholder="Prix à modifier :" value=""';
                }
                echo '>'; //lors du chargement de la page
                echo '</div>';
                ?>

                <div class="row">
                    <div class="col-md-3"></div>
                    <input type="submit" class="form-control col-md-6 espace" value="Modifier" name="valider">
                </div>
            </div>
        </form>
        <table class="col-12 liste mb5 table">
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
            function format($n)
            { //permet d afficher le separateur de millier
                return strrev(wordwrap(strrev($n), 3, ' ', true));
            }

            try {
                $connexion = new PDO("mysql:host=$serveur;dbname=mini-projet-php;charset=utf8", $Monlogin, $Monpass); //se connecte au serveur mysquel
                $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //setAttribute — Configure l'attribut PDO $connexion
                if (isset($_POST["valider"])) {
                    if ($_POST["quantite"] >= 0 && !empty($_POST["quantite"]) && $prodExiste == 1) { //pour ne pas ecraser l'ancienne valeur si on ne souhaite pas modifier la quantité (mais uniquement le prix)
                        $codemysql = "UPDATE `Liste-produits` SET Quantite='$nouvQuant' WHERE Nom='$nouvPro' "; //modification de la quantité
                        $requete = $connexion->prepare($codemysql);
                        $requete->execute();
                    }

                    if ($_POST["prix"] >= 100 && $prodExiste == 1) { //pour ne pas ecraser l'ancienne valeur si on ne souhaite pas modifier le prix (mais uniquement la quantité)
                        $codemysql = "UPDATE `Liste-produits` SET Prix='$nouvPrix' WHERE Nom='$nouvPro' "; //modification du prix
                        $requete = $connexion->prepare($codemysql);
                        $requete->execute();
                    }

                    if ($_POST["quantite"] >= 0 && !empty($_POST["quantite"]) && $prodExiste == 1 || $_POST["prix"] >= 100 && $prodExiste == 1) {
                        $codemysql = "SELECT Quantite,Prix FROM `Liste-produits`  WHERE Nom='$nouvPro' "; //recuperation du prix et de la quantité
                        $requete = $connexion->prepare($codemysql);
                        $requete->execute();
                        $quantite_prix = $requete->fetchAll();

                        $quantité_mod = $quantite_prix[0]["Quantite"]; //recuperation de la quantité
                        $prix_mod = $quantite_prix[0]["Prix"]; //recuperation du prix
                        $montant_mor = $quantité_mod * $prix_mod; //mise à jour du montant

                        $codemysql = "UPDATE `Liste-produits` SET Montant='$montant_mor' WHERE Nom='$nouvPro' "; //modification du montant
                        $requete = $connexion->prepare($codemysql);
                        $requete->execute();
                    }
                }
                //////////////////////////////////------Debut affichage-----//////////////////////////
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
                //////////////////////////////////------Fin affichage-----//////////////////////////
            } catch (PDOException $e) {
                echo "ECHEC : "  . $e->getMessage(); //en cas d erreur lors de la connexion à la base de données mysql
            }
            ?>
        </table>
    </section>
    <?php
    include("piedDePage.php");
    ?>
</body>

</html>