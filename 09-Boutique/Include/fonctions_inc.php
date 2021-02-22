<?php 

//FONCTION INTERNAUTE IDENTIFIE / CONNECTE SUR LE SITE
function connect()
{
    //si la session n'est pas définie l'internaute n' est pas connecté  on retourne FALSE
    if(!isset($_SESSION['user']))
    {
        return false;
    }
    else //sinon l'user est connecté session existe on retourne TRUE
    {
        return true;
    }
}

// FONCTION INTERNAUTE IDENTIFIE / CONNECTE SUR LE SITE ET ADMINISTRATEUR
function adminConnect()
{
    //si l'internaute est identifié sur le site (connect()) et que le statut de l'internaute est admin on retourne TRUE
    if(connect() && $_SESSION['user']['statut'] =='admin')
    {
        return true;
    }
    else //sinon l'utilisateur n' est pas admin ou pas identifié sur le site on retourne FALSE
    {
        return false;
    }
}


// FONCTION CREATION DU PANIER DANS LA SESSION UTILISATEUR 

function creationPanier()

{         //si l'indice 'panier' dans la session n'est pas défini alors on le crée dans le fichier session de l'utilisateur on ne va pas recréer un panier à chaque fois que l'utilisateur rajoute un produit on crée le panier qu'une seule fois dans la session

    if(!isset($_SESSION['panier']))


    {       // création des différents tableaux ARRAY ds la session qui permettront de stocker les infos des produits ajoutés au panier
        $_SESSION['panier'] = array();
        $_SESSION['panier']['id_produit'] = array();
        $_SESSION['panier']['photo'] = array();
        $_SESSION['panier']['reference'] = array();
        $_SESSION['panier']['titre'] = array();
        $_SESSION['panier']['taille'] = array();
        $_SESSION['panier']['quantite'] = array();
        $_SESSION['panier']['prix'] = array();

    }
}
// FONCTION PERMETTANT D'AJOUTER DES PRODUITS DANS LA SESSION '[panier]'

// Les paramètres définis en argument de la fonction permettront de réceptionner les infos du produit ajouté au panier afin de les stocker dans les différents tableaux du panier dans la session
function ajoutPanier($id_produit, $photo, $reference, $titre, $taille, $quantite, $prix)
{
        creationPanier();

         //array_search() permet de trouver à quel indice se trouve un id_produit dans la session
        $positionProduit = array_search($id_produit, $_SESSION['panier']['id_produit']);

        echo '<p class="text-center">'; var_dump($positionProduit); echo '</p>';

          //Si $positionProduit retourne une indice du tableau $_SESSION['panier']['id_produit'], cela veut dire que l'id_produit ajouté au panier existe dans la session alors on modifie seulement la quantité à l'indice du produit
        if($positionProduit !== false)
        {
            $_SESSION['panier']['quantite'][$positionProduit] += $quantite;
        }
        else //sinon l'id_produit n'existe pas dans la session panier alors on génère de nouveaux indices numériques, des nouvelles lignes dans les tableaux ARRAY 
        {
        //on remplie chaque tableau ARRAY ds la session panier avec les différentes infos du produit ajouté au panier
        //Les crochets vides [] permettent de générer des indices numériques dans les tableaux ARRAY 
        $_SESSION['panier']['id_produit'][] = $id_produit;
        $_SESSION['panier']['photo'][] = $photo;
        $_SESSION['panier']['reference'][] = $reference;
        $_SESSION['panier']['titre'][] = $titre;
        $_SESSION['panier']['taille'][] = $taille;
        $_SESSION['panier']['quantite'][] = $quantite;
        $_SESSION['panier']['prix'][] = $prix;
        }
}


/*
    SESSION

    array
    (
        [user] => array(
            données utilisateurs
        )

        [panier] => array(

            [id_produit] => array (
                [0] => 15
                [1] => 42
            )   
            
            [titre] => array (
                [0] => tee-shirt rouge
                [1] => sweat vert
            )   

            [reference] => array (
                [0] => 45T12
                [1] => 59I78
            )   
        )
    )
*/

// FONCTION CALCUL DU MONTANT DU PANIER

function montantTotal()
{
    $total = 0;

    for($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++)
    {
        $total += $_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix'][$i];
    }

    return round($total,2);
}
