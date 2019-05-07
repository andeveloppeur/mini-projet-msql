<?php
session_start();
/*if($_SESSION["profil"]!="admin"){
    echo'<h1>Reserver à l\'admin</h1>';
    exit();
} */
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
        <a class="nav-link nav-item" href="../index.php">Déconnection</a>
    </nav>
    <header></header>
    <section class="container cAuth">
        <form method="POST" action="Utilisateur.php" class="MonForm row insc">
            <div class="col-md-3"></div>
            <div class="col-md-6 bor">
                <div class="row">
                    <div class="col-md-2"></div>
                    <input class="form-control col-md-8 espace" type="text" id="nom" name="nom" placeholder="Nom et prénom" require="require">
                </div>
                <div class="row">
                    <div class="col-md-2"></div>
                    <input class="form-control col-md-8 espace" type="text" id="login" name="login" placeholder="Login" require="require">
                </div>
                <div class="row">
                    <div class="col-md-2"></div>
                    <input class="form-control col-md-8 espace" type="number" id="telephone" name="telephone" placeholder="telephone" require="require">
                </div>
                <div class="row">
                    <div class="col-md-2"></div>
                    <input class="form-control col-md-8 espace" type="text" id="Adresse" name="Adresse" placeholder="Adresse" require="require">
                </div>
                <div class="row">
                    <div class="col-md-2"></div>
                    <input class="form-control col-md-8 espace" type="email" id="email" name="email" placeholder="Email" require="require">
                </div>
                <div class="row">
                    <div class="col-md-2"></div>
                    <input class="form-control col-md-8 espace" type="password" id="MDP" name="MDP" placeholder="Mot de passe" require="require">
                </div>
                <div class="row">
                    <div class="col-md-2"></div>
                    <input class="form-control col-md-8 espace" type="password" id="MDPconf" name="MDPconf" placeholder="Confirmez votre mot de passe" require>
                </div>
                <div class="row">
                    <div class="col-md-3"></div>
                    <input type="submit" class="form-control col-md-6 espace" value="Connexion" name="valider">
                    <div class="col-md-1"></div>
                    <div class="col-md-2">
                        <div class="row">
                            <input type="radio" name="profil" id="admin" value="admin" checked="checked">
                            <label for="admin">Admin</label>
                        </div>
                        <div class="row">
                            <input type="radio" name="profil" value="user" id="user">
                            <label for="user">User</label>
                        </div>
                    </div>
                </div>


                <?php
                    $nom=$_POST["nom"];
                    $login=$_POST["login"];
                    $telephone=$_POST["telephone"];
                    $Adresse=$_POST["Adresse"];
                    $email=$_POST["email"];
                    $MDP=$_POST["MDP"];
                    $MDPconf=$_POST["MDPconf"];
                    $profil=$_POST["profil"];

                    //gerer le cas ou le login existe deja

                    
                    if(isset($_POST["valider"]) && $MDP==$MDPconf){
                        $monfichier = fopen('../Aut.txt', 'a+');
                        $nouvU=$nom."|".$login."|".$telephone."|".$Adresse."|".$email."|".$MDP."|".$profil."|";
                        fwrite($monfichier,$nouvU);
                        fclose($monfichier);
                    }
                    elseif(isset($_POST["valider"]) && $MDP!=$MDPconf){
                        echo"
                        <div class='row'>
                            <div class=col-md-1></div>
                            <p class='blocAcc'>Erreur sur sur l'une des données saisies !!</p>
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
                    $monfichier = fopen('../Aut.txt', 'r');
                    
                    while(!feof($monfichier)){
                        $ligne = fgets($monfichier);
                        $utilisateurs=explode('|',$ligne);
                            echo
                            '<tr class="row">
                                <td class="col-md-2 text-center">'.$utilisateurs[1].'</td>
                                <td class="col-md-2 text-center">'.$utilisateurs[0].'</td>
                                <td class="col-md-2 text-center">'.$utilisateurs[2].'</td>
                                <td class="col-md-2 text-center">'.$utilisateurs[3].'</td>
                                <td class="col-md-2 text-center">'.$utilisateurs[6].'</td>
                                <td class="col-md-2 text-center"><a class="" href="">'.$utilisateurs[7].'</a></td>
                            </tr>';
                    }
                    fclose($monfichier);
                    ?>
                    </table>
                    
            
    </section>
    <?php
        include("piedDePage.php");
    ?>
</body>
</html>