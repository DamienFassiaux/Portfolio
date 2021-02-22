<?php 
require_once('include/init_inc.php');

//echo '<pre>'; print_r($_POST); echo '</pre>';


//si l'internaute a cliqué sur le bouton 'AJOUTER AU PANIER', donc l'attribut name 'ajout_panier' du formulaire est détecté dans $_POST, on rentre dand la condition IF
if(isset($_POST['ajout_panier']))

{    //on selectionne tout en BDD par rapport a l'id_produit ajouté dans le panier
    $data = $bdd->prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
    $data->bindValue(':id_produit', $_POST['id_produit'], PDO::PARAM_INT);
    $data->execute();

     //on récupère les infos du produit ajouté au panier sous forme d'ARRAY
    $produitPanier = $data->fetch(PDO::FETCH_ASSOC);
      //echo '<pre>'; print_r($produitPanier); echo '</pre>';

    ajoutPanier($produitPanier['id_produit'],$produitPanier['photo'],$produitPanier['reference'],$produitPanier['titre'],$produitPanier['taille'],$_POST['quantite'],$produitPanier['prix']);

    header('location: panier.php');

   // echo '<pre>'; print_r($_SESSION); echo '</pre>';
}

require_once('include/header_inc.php');
require_once('include/nav_inc.php');
?>

<h1 class="display-4 text-center my-5">Mon Panier</h1>

   <table class="col-md-8 mx-auto table table-bordered text-center">
        <tr class="bg-dark text-white">
             <th>PHOTO</th>
             <th>REFERENCE</th>
             <th>TITRE</th>
             <th>TAILLE</th>
             <th>QUANTITE</th>
             <th>PRIX Unitaire</th>
             <th>Prix total/produit</th>
             <th>SUPP</th>
        </tr>

         <!-- Si dans la session le tableau id_produit est vide ou non défini alors il n'y a aucun produit ajouté dans le panier on entre dans le IF -->
        <?php if(empty($_SESSION['panier']['id_produit'])): ?>
           <tr>
                 <td colspan="8" class="text-danger">Votre panier est vide.</td>
           </tr>

        <?php else: //sinon le tableau 'id_produit' dans la session contient des id_produit, donc ily a bien des produits ajoutés dans le panier, on entre dans le ELSE ?>  

           <!-- la boucle FOR tourne autant de fois qu'il y a de produits dans la session donc dans le panier -->
           <!--               $i  < 3    -->
           <?php for ($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++):  ?>
                 
                 <tr>
                      <td>  
                             <!-- On sert de l'indice $i pour aller crocheter aux différents indices des tablaux ARRAY dans la session panier, la variable $i a les mêmes valeurs que les indices des tableaux ARRAY  -->
                            <img src="<?=$_SESSION['panier']['photo'][$i] ?>" alt="" style="width: 100px;">
                      </td>
                      <td class="align-middle"> <?=$_SESSION['panier']['reference'][$i] ?> </td>
                      <td class="align-middle"> <?=$_SESSION['panier']['titre'][$i] ?> </td>
                      <td class="align-middle"> <?= strtoupper($_SESSION['panier']['taille'][$i]) ?> </td>
                      <td class="align-middle"> <?=$_SESSION['panier']['quantite'][$i] ?> </td>
                      <td class="align-middle"> <?=$_SESSION['panier']['prix'][$i] ?>€ </td>
                      <td class="align-middle"> 
                        <strong>
                           <?= $_SESSION['panier']['quantite'][$i]*$_SESSION['panier']['prix'][$i] ?>€
                        </strong>
                      </td>
                        <td class="align-middle">
                            <a href="" class="btn btn-danger"><i class="far fa-trash-alt"></i></a>
                        </td>
                 
                 </tr>
           
           <?php endfor;  ?>

                  <tr>
                       <th>MONTANT TOTAL</th>
                       <td colspan="5"></td>
                       <th class="bg-dark text-white"> <?= montantTotal(); ?>€ </th>
                  </tr>

                  <?php if(connect()): ?>

                    <tr>
                        <td colspan="8">
                           <form method="post" class="">
                               <input type="submit" class="btn btn-success" value="ACCEDER AU PAIEMENT">
                           </form>
                        </td>
                    </tr>

                  <?php else: ?>
                    <tr>
                       <td colspan="8">
                            <a href="connexion.php" class="btn btn-success">IDENTIFIEZ-VOUS POUR VALIDER LA COMMANDE</a>
                       </td>
                    </tr>

                  <?php endif; ?>

          <?php endif; ?>
      </table>

    


<?php
require_once('include/footer_inc.php');