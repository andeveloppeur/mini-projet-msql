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
    </nav>
    <header></header>
    <section class="container cListe">
            <?php
                $monfichier = fopen('BDD.txt', 'r');
                $ligne = fgets($monfichier);
                $produits=explode('|',$ligne);
                fclose($monfichier);
            ?>
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
            function format($n){//permet d afficher le separateur de millier
                return strrev(wordwrap(strrev($n), 3, ' ', true));
            }
            $j=0;
            for($i=0;$i<substr_count($ligne,"|");$i+=3){
                $leProdruit=$produits[$i];
                $laQuantite=$produits[$i+1];
                $lePrix=$produits[$i+2];
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
                else{//si la quantité est inferieur à 10 la class rouge mettre les cellule en rouge
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
            echo
                '<tr class="row">
                    <td class="col-md-1 text-center gras"></td>
                    <td class="col-md-3 text-center gras">Total</td>
                    <td class="col-md-3 text-center gras">'.format($totalQuant).'</td>
                    <td class="col-md-2 text-center gras">'.$prixMoy.'</td>
                    <td class="col-md-3 text-center gras">'.format($totalMont).'</td>
                </tr>';
            ?>
        </table>
    </section>
    <?php
        include("piedDePage.php");
    ?>
</body>
</html>