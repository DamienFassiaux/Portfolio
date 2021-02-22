<?php
require_once('include/init_inc.php');

//echo '<pre>'; print_r($_GET); echo '</pre>';

//Si l'indice 'id_produit' est définie dans l'URL et que sa valeur est différent de vide
if(isset($_GET['id_produit']) && !empty($_GET['id_produit']))
{
   //echo 'indice est présent et différent de vide';

   //SELECTION EN BDD DU PRODUIT EN FONCTION DE L INDICE TRANSMIS DANS L'URL
   $data = $bdd->prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
     $data->bindValue(':id_produit', $_GET['id_produit'], PDO::PARAM_INT);
     $data->execute();

     //si la requete de selection retourne 1 résultat cela veut dire que l'id_produit dans l'url est existant en BDD
     if($data->rowCount())
     {
         // echo 'id_produit connu en BDD';
         $product = $data->fetch(PDO::FETCH_ASSOC);
          //echo'<pre>'; print_r($product); echo '</pre>';
     }

     else  //sinon la requete rowCount ne retourne aucun résultat on est redirigé vers la page boutique.php
     {
        header('location: boutique.php');
     }


}

//ou l'indice n'est pas définie ou vide
else
{
    //echo 'indice non présent ou vide ';
    header('location: boutique.php');
}




require_once('include/header_inc.php');
require_once('include/nav_inc.php');
?>
 <!-- Page Content -->
 <div class="container">

        <div class="row">

        <div class="col-lg-3">

            <h1 class="my-4">Que du lourd!!! Viendez-voir!!</h1>

            <?php
            $data = $bdd->query("SELECT DISTINCT(categorie) FROM produit")
            ?>
            <div class="list-group">
                <p class="list-group-item p-0 m-0 bg-info text-center text-white">CATEGORIES</p>
                <?php while($cat = $data->fetch(PDO::FETCH_ASSOC)):

                //echo '<pre>'; print_r($cat); echo'</pre>';
                ?>

                    <a href="boutique.php?categorie=<?= $cat['categorie'] ?>" class="list-group-item text-center text-dark"><?= $cat['categorie'] ?></a>
                
                <?php endwhile; ?>
            </div>

            </div>
            <!-- /.col-lg-3 -->

        <div class="col-lg-9">

            <div class="card mt-4">
           <p class="text-center"> <img class="card-img-top img-fluid" src="<?= $product['photo']?>" alt="<?= $product['titre']?>" style="width: 500px;"></p>
            <div class="card-body">
                <h3 class="card-title"><?= $product['titre']?></h3>
                <h4><?= $product['prix']?>€</h4>
                <p class="card-text"><?= $product['description']?></p><hr>

                <h5><strong>Détails du produit</strong></h5>

                <p class="card-text"><strong>Catégorie :</strong>  <a href="boutique.php?categorie=<?= $product['categorie']?>">  <?= $product['categorie'] ?> </a></p>

                <p class="card-text"><strong>Numéro du Modèle :</strong> <?= $product['reference'] ?></p>

                <p class="card-text"><strong>Taille :</strong> <?= $product['taille'] ?></p>

                <p class="card-text"><strong>Couleur :</strong> <?= $product['couleur'] ?></p>

                <p class="card-text"><strong>Service :</strong> <?= $product['public'] ?></p>
                 
                <p class="card-text">

                <?php if($product['stock'] <10 && $product['stock'] !=0): ?>
                <p class="card-text font-italic text-danger">Il en reste que <?=$product['stock'] ?></p>

                <?php elseif($product['stock'] >10): ?>
                 <p class="card-text font-italic text-success">En stock !! </p>

                <?php endif; ?>

                <?php if($product['stock'] >  0): ?>

                   <hr>

                <form method='post' action='panier.php' class='form-inline mb-0'>
                <input type="hidden" id="id_produit" name="id_produit" value="<?=$product['id_produit'] ?>">
             <div class='form-group'>
               <select name='quantite' id='quantite' class='form-control'>

               <?php for($i = 1;$i<= $product['stock'] && $i <= 30; $i++): ?>

               <option value="<?=$i ?>"> <?=$i ?></option>

               <?php endfor; ?>
               
               </select>
               </div>
               <input type='submit' class='btn btn-dark ml-2' name='ajout_panier' value='AJOUTER AU PANIER'></p>
               </form>

               <?php else: ?>
                   <p class='font-italic text-danger'>Rupture de stock!!!</p>

                   <?php endif; ?>
            
            </div>
        </div>
    <!-- /.card -->

            <div class="card card-outline-secondary my-4">
                <div class="card-header">
                    Product Reviews
                </div>
                <div class="card-body">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
                    <small class="text-muted">Posted by Anonymous on 3/1/17</small>
                    <hr>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
                    <small class="text-muted">Posted by Anonymous on 3/1/17</small>
                    <hr>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
                    <small class="text-muted">Posted by Anonymous on 3/1/17</small>
                    <hr>
                    <a href="#" class="btn btn-success">Leave a Review</a>
                </div>
            </div>
    <!-- /.card -->

      </div>
  <!-- /.col-lg-9 -->

  </div>

</div>
<!-- /.container -->


<?php
require_once('include/footer_inc.php');