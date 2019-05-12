<?php
session_start();
if ($_SESSION["profil"] != "admin") {
    echo '<h1>Reserver à l\'admin</h1>';
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
    <title>Authentification</title>
</head>

<body>
    <nav class="container nav nav-pills nav-fill">
        <a class="nav-link  nav-item" href="accueil.php">Accueil</a>
        <a class="nav-link nav-item" href="listerProduits.php">Liste</a>
        <a class="nav-link nav-item" href="rechercherProduits.php">Recherche</a>
        <a class="nav-link nav-item" href="ajouterProduit.php">Ajouter</a>
        <a class="nav-link nav-item" href="updateProduit.php">Modifier</a>
        <a class="nav-link nav-item " href="supprimerProduit.php">Supprimer</a>
        <?php
        if ($_SESSION["profil"] == "admin") {
            echo '<a class="nav-link nav-item active" href="Utilisateur.php">Utilisateurs</a>';
        }
        ?>
        <a class="nav-link nav-item" href="../index.php">Déconnection</a>
    </nav>
    <header></header>
    <section class="container cAuth">
        <form method="POST" action="Utilisateur-trait.php" class="MonForm row insc">
            <div class="col-md-3"></div>
            <div class="col-md-6 bor">
                <div class="row">
                    <div class="col-md-2"></div>
                    <label class="form-control col-md-2 espace center" for="admin">Admin</label>
                    <input type="radio" name="profil" class="form-control col-md-2 espace" id="admin" value="admin" checked="checked">
                    <input class="form-control col-md-2 espace " type="radio" name="profil" value="user" id="user">
                    <label class="form-control col-md-2 espace center" for="user">User</label>
                </div>
                <div class="row">
                    <div class="col-md-2"></div>
                    <input class="form-control col-md-8 espace" type="text" id="nom" name="nom" placeholder="Nom et prénom" required="required">
                </div>
                <div class="row">
                    <div class="col-md-2"></div>
                    <input class="form-control col-md-8 espace" type="text" id="login" name="login" placeholder="Login" required="required">
                </div>
                <div class="row">
                    <div class="col-md-2"></div>
                    <input class="form-control col-md-8 espace" type="number" id="telephone" name="telephone" placeholder="telephone" required="required">
                </div>
                <div class="row">
                    <div class="col-md-2"></div>
                    <input class="form-control col-md-8 espace" type="text" id="Adresse" name="Adresse" placeholder="Adresse" required="required">
                </div>
                <div class="row">
                    <div class="col-md-2"></div>
                    <input class="form-control col-md-8 espace" type="email" id="email" name="email" placeholder="Email" required="required">
                </div>
                <div class="row">
                    <div class="col-md-2"></div>
                    <input class="form-control col-md-8 espace" type="password" id="MDP" name="MDP" placeholder="Mot de passe" required="required">
                </div>
                <div class="row">
                    <div class="col-md-2"></div>
                    <input class="form-control col-md-8 espace" type="password" id="MDPconf" name="MDPconf" placeholder="Confirmez votre mot de passe" required>
                </div>
                <div class="row">
                    <div class="col-md-3"></div>
                    <input type="submit" class="form-control col-md-6 espace" value="Connexion" name="valider">
                </div>
                <?php
                    if ($_SESSION["erreur"] == true) { //si errer lors de la creation de l utilisateur
                        echo "
                                <div class='row'>";
                        if ($_SESSION["existeDeja"] == true) {
                            echo "<div class=col-md-4></div>
                            <p class='blocAcc'>Ce login existe déja ";
                        } else {
                            echo "<div class=col-md-3></div>
                            <p class='blocAcc'>Erreur sur l'une des données saisies ";
                        }
                        echo "!!</p>
                                </div>";
                    }
                ?>
            </div>
        </form>
        <table class="col-12 tabliste table">
            <thead class="thead-dark">
                <tr class="row">
                    <td class="col-md-2 text-center gras">Login</td>
                    <td class="col-md-2 text-center gras">Nom</td>
                    <td class="col-md-2 text-center gras">Téléphone</td>
                    <td class="col-md-2 text-center gras">Adresse</td>
                    <td class="col-md-2 text-center gras">Profil</td>
                    <td class="col-md-2 text-center gras">Statut</td>
                </tr>
            </thead>
            <?php
                //////////////////////////////////////////-----AFFICHE TABLEAU----//////////////////
                $serveur = "localhost";
                $Monlogin = "root";
                $Monpass = "101419";

                try {
                    $connexion = new PDO("mysql:host=$serveur;dbname=mini-projet-php;charset=utf8", $Monlogin, $Monpass); //se connecte au serveur mysquel
                    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //setAttribute — Configure l'attribut PDO $connexion
                    $codemysql = "SELECT * FROM utilisateurs";
                    $requete = $connexion->prepare($codemysql);
                    $requete->execute();
                    $utilisateurs = $requete->fetchAll();
                } catch (PDOException $e) {
                    echo "ECHEC : " . $e->getMessage(); //en cas d erreur lors de la connexion à la base de données mysql
                    exit(); //arreter le code
                }

                for ($i = 0; $i < count($utilisateurs); $i++) {

                    if ($utilisateurs[0] != "") {
                        echo
                            '<tr class="row">
                                <td class="col-md-2 text-center">' . $utilisateurs[$i]["Login"] . '</td>
                                <td class="col-md-2 text-center">' . $utilisateurs[$i]["Nom"] . '</td>
                                <td class="col-md-2 text-center">' . $utilisateurs[$i]["Telephone"] . '</td>
                                <td class="col-md-2 text-center">' . $utilisateurs[$i]["Adresse"] . '</td>
                                <td class="col-md-2 text-center">' . $utilisateurs[$i]["Profil"] . '</td>
                                <td class="col-md-2 text-center">
                                    <a href="Utilisateur-trait.php?login=' . $utilisateurs[$i]["Login"] . '"><button class="mesBout ';
                        if ($utilisateurs[$i]["Statut"] == "Bloquer") {
                            echo "rougMoins";
                        }
                        echo '"id="' . $utilisateurs[$i]["Login"] . '">' . $utilisateurs[$i]["Statut"] . '</button>
                                    </a>
                                </td>
                            </tr>';
                    }
                }
                //////////////////////////////////////////-----AFFICHE TABLEAU----//////////////////
            ?>
        </table>
    </section>
    <?php
    include("piedDePage.php");
    ?>
</body>

</html>