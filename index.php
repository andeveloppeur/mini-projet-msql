<?php
session_start();
?>
<!DOCTYPE html>
<html lang="FR-fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/MonStyle.css">
    <title>Authentification</title>
</head>
<body>
    <nav class="container nav nav-pills nav-fill">
        <a class="nav-link active nav-item" href="#">Authentification</a>
    </nav>
    <header></header>
    <section class="container cAuth">
        <form method="POST" action="index.php" class="MonForm row">
            <div class="col-md-3"></div>
            <div class="col-md-6 bor">
                <div class="row">
                    <div class="col-md-2"></div>
                    <input class="form-control col-md-8 espace" type="text" id="login" name="login" placeholder="Login">
                </div>
                <div class="row">
                    <div class="col-md-2"></div>
                    <input class="form-control col-md-8 espace" type="password" id="MDP" name="MDP" placeholder="Mot de passe">
                </div>
                <div class="row">
                    <div class="col-md-3"></div>
                    <input type="submit" class="form-control col-md-6 espace" value="Connexion">
                </div>
                <?php
                    $monfichier = fopen('Aut.csv', 'r');
                    $ligne = fgets($monfichier);
                    $utilisateurs=explode('|',$ligne);
                    fclose($monfichier);

                    $login=$_POST["login"];//recuperation du login 
                    $mDp=$_POST["MDP"];//recuperation du MDP
                    $reussi=0;
                    if($login!="" && $mDp!=""){
                        for($i=0;$i<substr_count($ligne,"|");$i+=3){
                            if($utilisateurs[$i+1]==$login){
                                if($utilisateurs[$i+2]==$mDp){
                                    header('Location: pages/accueil.php');
                                    $_SESSION["nom"]=$utilisateurs[$i];
                                    $reussi=1;
                                }
                            }
                        }
                        if($reussi==0){//verification du login et du MDP
                            echo"
                            <div class='row'>
                            <div class=col-md-3></div>
                            <p class='blocAcc'>Erreur sur le login ou le mot de passe !!</p>
                            </div>";
                        }
                        
                    }
                ?>
            </div>
        </form>
    </section>
    <?php
        include("pages/piedDePage.php");
    ?>
</body>
</html>