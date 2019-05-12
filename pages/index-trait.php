<?php
            
    session_start();
    $reussi = 0;
    $bloquer = 0;
    $_SESSION["erreur-auth"] = false;
    $_SESSION["u-bloquer"] = false;

    $serveur="localhost";
    $Monlogin = "root";
    $Monpass = "101419";
    function securisation($donnees)
    {
        $donnees = trim($donnees); //trim supprime les espaces (ou d'autres caractères) en début et fin de chaîne
        $donnees = stripslashes($donnees); //Supprime les antislashs d'une chaîne
        $donnees = strip_tags($donnees); //neutralise le code html et php
        return $donnees;
    }
    $login = securisation($_POST["login"]); //recuperation du login 
    $mDp = securisation($_POST["MDP"]); //recuperation du MDP
    try{
        $connexion=new PDO("mysql:host=$serveur;dbname=mini-projet-php;charset=utf8",$Monlogin,$Monpass);
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $codemysql="SELECT * FROM utilisateurs WHERE Login='$login'";
        $requete = $connexion->prepare($codemysql);
        $requete->execute();
        $utilisateurs=$requete->fetchAll();
        
        if ($login != "" && $mDp != "") {
            if ($utilisateurs[0]["Login"] == $login) {
                if ($utilisateurs[0]["MDP"] == $mDp && $utilisateurs[0]["Statut"] != "Bloquer") {
                    header('Location: accueil.php');
                    $_SESSION["nom"] = $utilisateurs[0]["Nom"];
                    $_SESSION["login"] = $utilisateurs[0]["Login"];
                    $_SESSION["profil"] = $utilisateurs[0]["Profil"];
                    $reussi = 1;
                } elseif ( $utilisateurs[0]["Statut"] == "Bloquer") {
                    $bloquer = 1;
                }
            }
        }
        // echo"<pre>";
        //     print_r($utilisateurs);
        // echo "</pre>";
    } catch (PDOException $e) {
        echo"ECHEC: ".$e->getmessage();
    }

    if (isset($_POST["submit"])) {
        if ($reussi == 0) { //verification du login et du MDP
            $_SESSION["erreur-auth"] = true;
            if ($bloquer == 1) {
                 $_SESSION["u-bloquer"]=true;
            }
            header('Location: ../index.php'); 
        }
    }
    
?>