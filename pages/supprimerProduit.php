<?php
session_start();
if($_SESSION["profil"]!="admin" && $_SESSION["profil"]!="user" ){
    echo'<h1>Connectez-vous</h1>';
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
        <a class="nav-link nav-item" href="updateProduit.php">Modifier</a>
        <a class="nav-link active nav-item" href="supprimerProduit.php">Supprimer</a>
        <?php
            if($_SESSION["profil"]=="admin"){
                echo'<a class="nav-link nav-item" href="Utilisateur.php">Utilisateurs</a>';
            }        
        ?>
        <a class="nav-link nav-item" href="../index.php">Déconnection</a>
    </nav>
    <header></header>
    <section class="container corps">
        <form action="supprimerProduit.php" method="POST" class="tab row">
            <div class="col-md-3"></div>
            <div class="col-md-6 bor">
                    <?php
                    $monfichier = fopen('BDD.txt', 'r');
                    $ligne = fgets($monfichier);
                    $produits=explode('|',$ligne);
                    fclose($monfichier);
                    $prodExiste=0;

                    $supPro=$_POST["produit"];//recupere le nom du produit saisi dans le formulaire via le tableau $_POST
                    for($i=0;$i<substr_count($ligne,"|");$i+=3){//casse
                        if(!strcasecmp($supPro,$produits[$i])){//pour gerer la casse
                            $supPro=$produits[$i];
                        }
                    }
                    for($i=0;$i<substr_count($ligne,"|");$i+=3){//permet de parcourir tout le tableau (count renvoi la taille du tableau)
                        if($produits[$i]==$supPro){//verifie si le produit à ajouter n existe pas deja
                            $prodExiste=1;//si existe deja le variable $prodExiste=1 cela nous permettra de bloquer l'ajout
                            }
                    }
                    echo'<div class="row">
                        <div class="col-md-2"></div>
                        <input class="form-control col-md-8 espace ';if(isset($_POST["valider"]) && $supPro=="" || isset($_POST["valider"]) && $prodExiste==0){echo'rougMoins';}echo'" type="text" id="produit" name="produit"';
                        if(isset($_POST["valider"]) && $supPro==""){echo'placeholder="Remplir le nom du produit à supprimer"';}//si on envoi ajoute un produit sans remplir le nom du produit
                        elseif($prodExiste==0 && isset($_POST["valider"])){echo' placeholder="Le produit '.$supPro.' n\'existe pas" value=""';}//si le produit existe deja
                        elseif($prodExiste==1){echo' placeholder="Nom produit" value=""';}//si modif reussi
                        elseif($prodExiste==0 ){echo' placeholder="Produit à supprimer" value=""';}echo'>';//lors du chargement de la page
                    echo'</div>';
                ?>

                <div class="row">
                    <div class="col-md-3"></div>
                    <input type="submit" class="form-control col-md-6 espace" value="Supprimer" name="valider">
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
                function format($n){//permet d afficher le separateur de millier
                    return strrev(wordwrap(strrev($n), 3, ' ', true));
                }
                
                
                if($supPro!="" && $prodExiste==1){
                    for($i=0;$i<substr_count($ligne,"|");$i+=3){
                        if($supPro==$produits[$i]){
                            $aSupprimer=$produits[$i]."|".$produits[$i+1]."|".$produits[$i+2]."|";
                            $nouvelleChaine=str_replace($aSupprimer,"",$ligne);
                        }
                    }
                    $monfichier = fopen('BDD.txt', 'w+');
                    fwrite($monfichier,$nouvelleChaine);
                    fclose($monfichier);
                }

                $monfichier = fopen('BDD.txt', 'r');
                $ligne = fgets($monfichier);
                $produits=explode('|',$ligne);
                fclose($monfichier);
                $j=0;
                for($i=0;$i<substr_count($ligne,"|");$i+=3){
                    $leProdruit=$produits[$i];
                    $laQuantite=$produits[$i+1];
                    $lePrix=$produits[$i+2];
                    
                    if($leProdruit!=""){
                        if($laQuantite>=10){
                            $j++;
                            echo
                            '<tr class="row">
                                <td class="col-md-1 text-center">'.$j.'</td>
                                <td class="col-md-3 text-center">'.$leProdruit.'</td>
                                <td class="col-md-3 text-center">'.format($laQuantite).'</td>
                                <td class="col-md-2 text-center">'.format($lePrix).'</td>
                                <td class="col-md-3 text-center">'.format($laQuantite*$lePrix).'</td>
                            </tr>';
                        }
                        else{//si la quantité est inferieur à 10 la class rouge met les cellules en rouge
                            $j++;
                            echo
                            '<tr class="row">
                                <td class="col-md-1 text-center rouge">'.$j.'</td>
                                <td class="col-md-3 text-center rouge">'.$leProdruit.'</td>
                                <td class="col-md-3 text-center rouge">'.format($laQuantite).'</td>
                                <td class="col-md-2 text-center rouge">'.format($lePrix).'</td>
                                <td class="col-md-3 text-center rouge">'.format($laQuantite*$lePrix).'</td>
                            </tr>';  
                        }   
                        $totalQuant+=$laQuantite;//calcul le total des quantités
                        $Totprix+=$lePrix;//calcul le total des prix pour calculer la moyenne
                        $prixMoy=$Totprix/(($i/3)+1);
                        $totalMont+=$laQuantite*$lePrix; 
                    }      
                }
                echo
                    '<tr class="row">
                        <td class="col-md-1 text-center gras"></td>
                        <td class="col-md-3 text-center gras">Total</td>
                        <td class="col-md-3 text-center gras">'.format($totalQuant).'</td>
                        <td class="col-md-2 text-center gras">'.$prixMoy.'</td>
                        <td class="col-md-3 text-center gras">'.format($totalMont).'</td>
                    </tr>';
                /*echo"<pre>"; //pour verifier si les elements sont reelement supprimés
                    print_r($produits);
                echo"<pre>";*/
            ?>
        </table>
    </section>
    <?php
        include("piedDePage.php");
    ?>
</body>
</html>