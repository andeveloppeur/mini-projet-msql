<?php

    session_start();
    $existeDeja = 0;
    $adminprincipal = 0;
    $nouv_statut="";
    $changementStat=0;
    $_SESSION["existeDeja"] = false;
    $_SESSION["erreur"] = false;
    $serveur = "localhost";
    $Monlogin = "root";
    $Monpass = "101419";
    function securisation($donnees)
    {
        $donnees = trim($donnees); //trim supprime les espaces (ou d'autres caractères) en début et fin de chaîne
        $donnees = stripslashes($donnees); //Supprime les antislashs d'une chaîne
        $donnees = strip_tags($donnees); //neutralise le code html et php
        return $donnees;
    }
    $login="";
    if (isset($_POST["valider"])) {
        $nom = securisation($_POST["nom"]);
        $login = securisation($_POST["login"]);
        $telephone = securisation($_POST["telephone"]);
        $Adresse = securisation($_POST["Adresse"]);
        $email = securisation($_POST["email"]);
        $MDP = securisation($_POST["MDP"]);
        $MDPconf = securisation($_POST["MDPconf"]);
        $profil = securisation($_POST["profil"]);
        $tatut= "actif";
    }
    if (isset($_GET["login"])) {
        $aChanger = securisation($_GET["login"]);
        if ($aChanger != "Abdou" && !empty($aChanger)) {
            $changementStat = 1;
        } 
        elseif ($aChanger == "Abdou") {
            $adminprincipal = 1;
        }
    }
    try {
        $connexion = new PDO("mysql:host=$serveur;dbname=mini-projet-php;charset=utf8", $Monlogin, $Monpass); //se connecte au serveur mysquel
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //setAttribute — Configure l'attribut PDO $connexion
        /////////////----existe deja---///////////////////
        $codemysql="SELECT Login FROM utilisateurs";
        $requete=$connexion->prepare($codemysql);
        $requete->execute();
        $log_utilisateurs=$requete->fetchAll();
        for($i=0;$i<count($log_utilisateurs);$i++){
            if( $log_utilisateurs[$i][0]== $login){
                $existeDeja = 1;
                $_SESSION["existeDeja"] = true; 
            }
        }
        
        /////////////----Fin existe deja---///////////////////
       

        /////////////////////////////----Ajout ---///////////////////
        if( isset($_POST["valider"]) && $MDP == $MDPconf && $existeDeja == 0){//ajout utilisateur
            $codemysql = "INSERT INTO utilisateurs (Nom,Login,MDP,Email,Adresse,Telephone,Profil,Statut)
                        VALUES(:Nom,:Login,:MDP,:Email,:Adresse,:Telephone,:Profil,:Statut)"; //le code mysql
            $requete = $connexion->prepare($codemysql); //Prépare la requête $codemysql à l'exécution 
            $requete->bindParam( ":Nom", $nom); //bindParam lie un paramètre (:nom) à un nom de variable spécifique ($nom)
            $requete->bindParam( ":Login", $login);
            $requete->bindParam( ":MDP", $MDP);
            $requete->bindParam( ":Email", $email);
            $requete->bindParam( ":Adresse", $Adresse);
            $requete->bindParam( ":Telephone", $telephone);
            $requete->bindParam( ":Profil", $profil);
            $requete->bindParam( ":Statut", $tatut);
            $requete->execute(); //excecute la requete qui a été preparé
        }
        /////////////////////////////----Fin Ajout ---///////////////////

        /////////////////////////////----Bloquer-Debloquer ---///////////////////

        if ($changementStat == 1) {
            $codemysql = "SELECT Statut FROM utilisateurs WHERE Login='$aChanger'";
            $requete = $connexion->prepare($codemysql);
            $requete->execute();
            $statut_utilisateur=$requete->fetchAll();
            if ( $statut_utilisateur[0]["Statut"] == "actif") { //si son profil est actif on le bloque
                $nouv_statut="Bloquer";
            } elseif ( $statut_utilisateur[0]["Statut"] == "Bloquer") { //inversement
                $nouv_statut="actif";
            }

            $codemysql ="UPDATE utilisateurs SET Statut='$nouv_statut' WHERE Login='$aChanger'";
            $requete=$connexion->prepare($codemysql);
            $requete->execute();
            
            header('Location:  Utilisateur.php#' . $aChanger);
        }
        /////////////////////////////----Fin Bloquer-Debloquer ---///////////////////

        if ($adminprincipal == 1) {
            header('Location: Utilisateur.php#Abdou');
        }
        if (isset($_POST["valider"]) && $MDP != $MDPconf || isset($_POST["valider"]) && $existeDeja == 1) { //si errer lors de la creation de l utilisateur
            $_SESSION["erreur"] = true;
            header('Location: Utilisateur.php');
        }

    } catch (PDOException $e) {
        echo "ECHEC : " . $e->getMessage(); //en cas d erreur lors de la connexion à la base de données mysql
        exit(); //arreter le code
    }

    
    

?>