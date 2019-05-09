<?php
session_start();
if($_SESSION["profil"]!="admin"){
    echo'<h1>Reserver à l\'admin</h1>';
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
            if($_SESSION["profil"]=="admin"){
                echo'<a class="nav-link nav-item active" href="Utilisateur.php">Utilisateurs</a>';
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
                    $existeDeja=0;
                    $monfichier = fopen('../Aut.txt', 'r');
                    while(!feof($monfichier)){
                        $ligne = fgets($monfichier);
                        $utilisateurs=explode('|',$ligne);
                        if(isset($_POST[$utilisateurs[1]]) && $utilisateurs[1]!="Abdou"){//vu que les boutons pour bloquer on un name egal au login on cherche si un des bouton a été activé et si le login est different de abdou qui est l admin par defaut
                            $changementStat=1;
                            $aChanger=$utilisateurs[1];//stockera le nom de l utilisateur à bloquer ou debloquer
                        }
                        if($utilisateurs[1]==$_POST["login"]){
                            $existeDeja=1;//verifie si on a pas le meme login
                        }
                    }
                    fseek($monfichier,0);//remettre le curseur au debut
                    if($changementStat==1){
                        
                        while(!feof($monfichier)){
                            $ligne = fgets($monfichier);
                            $element=explode('|',$ligne);
                            if($element[1]==$aChanger){
                                if($element[7]=="actif"){//si son profil est actif on le bloque
                                    $nouv=$nouv.$element[0]."|".$element[1]."|".$element[2]."|".$element[3]."|".$element[4]."|".$element[5]."|".$element[6]."|Bloquer|";
                                }
                                elseif($element[7]=="Bloquer"){//inversement
                                    $nouv=$nouv.$element[0]."|".$element[1]."|".$element[2]."|".$element[3]."|".$element[4]."|".$element[5]."|".$element[6]."|actif|";
                                }
                                $nouv=$nouv."\n";//pour gerer le retour à la ligne qui n est pas gerer par les 2 cas d en haut mais les autre de la variable $ligne le gere
                            }
                            else{
                                $nouv=$nouv.$ligne;//on ne change pas la ligne si le login ne correspond pas à celui de la ligne
                            }
                        }
                    }
                    fclose($monfichier);

                    if($nouv!=""){
                        $monfichier = fopen('../Aut.txt', 'w+');
                        fwrite($monfichier,trim($nouv));//on ecrit le fichier pour enregister la modification du statut de l utilisateur
                        fclose($monfichier);
                    }
                    
                    $nom=$_POST["nom"];
                    $login=$_POST["login"];
                    $telephone=$_POST["telephone"];
                    $Adresse=$_POST["Adresse"];
                    $email=$_POST["email"];
                    $MDP=$_POST["MDP"];
                    $MDPconf=$_POST["MDPconf"];
                    $profil=$_POST["profil"];

                    //gerer le cas ou le login existe deja
                    
                    if(isset($_POST["valider"]) && $MDP==$MDPconf && $existeDeja==0){
                        $monfichier = fopen('../Aut.txt', 'a+');
                        $nouvU="\n".$nom."|".$login."|".$telephone."|".$Adresse."|".$email."|".$MDP."|".$profil."|actif|";//ajout d un nouvel utilisateur
                        fwrite($monfichier,$nouvU);//ajout 
                        fclose($monfichier);
                    }
                    elseif(isset($_POST["valider"]) && $MDP!=$MDPconf || isset($_POST["valider"]) && $existeDeja==1){//si errer lors de la creation de l utilisateur
                        echo"
                        <div class='row'>";
                            
                            if($existeDeja==1){echo" 
                                <div class=col-md-4></div>
                                <p class='blocAcc'>Ce login existe déja ";}
                            else{echo"<div class=col-md-3></div>
                                <p class='blocAcc'>Erreur sur l'une des données saisies ";}
                            echo"!!</p>
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
                            if($utilisateurs[0]!=""){
                                echo
                                '<tr class="row">
                                    <td class="col-md-2 text-center">'.$utilisateurs[1].'</td>
                                    <td class="col-md-2 text-center">'.$utilisateurs[0].'</td>
                                    <td class="col-md-2 text-center">'.$utilisateurs[2].'</td>
                                    <td class="col-md-2 text-center">'.$utilisateurs[3].'</td>
                                    <td class="col-md-2 text-center">'.$utilisateurs[6].'</td>
                                    <td class="col-md-2 text-center">
                                        <form method="POST" action="Utilisateur.php" class="">
                                            <input type="submit" class="mesBout ';
                                            if($utilisateurs[7]=="Bloquer"){echo "rougMoins";}
                                            echo'" name="'.$utilisateurs[1].'" value="'.$utilisateurs[7].'">
                                        </form>
                                    </td>
                                </tr>';
                            }
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