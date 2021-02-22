<?php
require_once('../Include/init_inc.php');

// echo '<pre>'; print_r($_SESSION); echo'<pre>';

// echo '<pre>'; print_r($_POST); echo'<pre>';

// Toutes les infos liés au fichier uploadé sont stockés dans la superglobale $_FILES en PHP
// echo '<pre>'; print_r($_FILES); echo'<pre>';


// si l'internaute n'est pas administrateur ou pas identifié le statut de la session n'est pas 'admin' il n'a rien à faire sur la page gestion_boutique on le redirige vers l'authentification
if(!adminConnect())
{
    header('location: ' . URL . 'connexion.php');
}

 // SELECTION EN BDD DU PRODUIT A MODIFIE  
 if(isset($_GET['id_produit']) && !empty($_GET['id_produit']))    
 {
    // On selectionne tout en BDD à condition que l'id_produit en BDD corresponde à l'id_produit transmis dans l'URL dans le cas d'une modification
    $produitActuel = $bdd->prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
    $produitActuel->bindValue(':id_produit', $_GET['id_produit'], PDO::PARAM_INT);
    $produitActuel->execute();

    // si la requete retourne un résultat id_prdouit transmis dans l'URL est connu en BDD 
     if($produitActuel->rowCount() > 0)
        {
            // On récupère le résultat sous forme de tableau ARRAY
            $product = $produitActuel->fetch(PDO::FETCH_ASSOC);

            //echo '<pre>'; print_r($product); echo '</pre>';

        }
     else //Sinon id_produit n'est pas connu dans la BDD rowcount() retourne un boolean FALSE on redirige l'utilisateur vers l'affichage des produits
        {
            header('location: ' . URL . 'admin/gestion_boutique.php?action=affichage');
        }

 }      
   //si la référence en BDD est bien défini on stock la référence dans une variable sinon ':' on stock  une chaine de caractères vide dans la variable (condition ternaire)
   //On stock chaque valeur du produit récupéré en BDD dans des variables distinctes afin d'envoyer chaque valeur dans les attributs "value" des champs correspondant
 $reference = (isset($product['reference'])) ? $product['reference'] : '';
 $categorie = (isset($product['categorie'])) ? $product['categorie'] : '';
 $titre = (isset($product['titre'])) ? $product['titre'] : '';
 $description = (isset($product['description'])) ? $product['description'] : '';
 $taille = (isset($product['taille'])) ? $product['taille'] : '';
 $public = (isset($product['public'])) ? $product['public'] : '';
 $couleur = (isset($product['couleur'])) ? $product['couleur'] : '';
 $prix = (isset($product['prix'])) ? $product['prix'] : '';
 $photo = (isset($product['photo'])) ? $product['photo'] : '';
 $stock = (isset($product['stock'])) ? $product['stock'] : '';

// SUPPRESSION PRODUIT
if(isset($_GET['action']) && $_GET['action'] =='suppression')
{
   // echo "je veux supprimer le produit";
   $supp = $bdd->prepare("DELETE FROM produit WHERE id_produit = :id_produit");
        
        $supp->bindValue(':id_produit', $_GET['id_produit'], PDO:: PARAM_INT);
        $supp->execute();

        // Après avoir supprimé le produit dans l'URL est stocké 'action=suppression' nous ne sommes donc pas redirigé vers l'affichage des produits
        //On redéfinie donc la valeur de l'indice 'action' dans l'URL par 'affichage' pour entrer dans la condition qui execute l'affichage des produits (condition ci-dessous dans le code)
        $_GET['action'] = 'affichage';

        $vd = "<div class='col-md-3 mx-auto alert alert-success text-center'>Le produit <strong>ID$_GET[id_produit]</strong> a été supprimé avec succès !</div>";
}


if($_POST)
{
    $photoBdd = "";

    if(isset($_GET['action']) && $_GET['action'] == 'modification')
    {
        // En cas de modification, si nous en changeons pas de photo, le champ $photo reste vide, il n'est poas possible d'affecter un attribut sur un champ de type 'file', ici nous recupérons l'URL de l'image actuelle du produit stockée en BDD afin de la réaffecter à la variable $photoBdd et la ré-insérer en base de donnée, cela évite d'envoyer une valeur vide dans la champ photo en BDD

        $photoBdd = $photo; //$photoBdd = http://localhost/php/09-boutique/photo/id_produit/
    }
    //TRAITEMENT DE LA PHOTO UPLOADE
    if(!empty($_FILES['photo']['name']))

    //  controle de l extension de l'image

    {     // splFileInfo() : classe prédéfinie en PHP permettant de traiter un fichier uploadé
        $info = new SplFileInfo($_FILES['photo']['name']);
       // echo '<pre>'; var_dump($info); echo'<pre>';

        // echo '<pre>'; print_r(get_class_methods($info)); echo'<pre>';

         // on stocke l'extension du fichier uploadé grace à la méthode getExtension() de la classe splFileInfo()
        $extFichier = $info->getExtension();
       // print_r($extFichier);


         // déclaration du tableau contenant les extensions autorisées
        $arrayExt = ['jpg', 'png', 'jpeg'];
        // echo '<pre>'; print_r($arrayExt); echo'<pre>';

        //arraySearch() fonction prédéfinie permettant de trouver la position de l'extension uploadé dans le tableau ARRAY des extensions autorisées

        $positionExt = array_search($extFichier, $arrayExt);
        // echo '<pre>'; var_dump($positionExt); echo'<pre>';


         // Si $positionExt retourne un boolean FALSE cela veut dire que l'extension uploadé n'est pas présente dans le tableau ARRAY des extensions autorisées
        if($positionExt === false)
        {
              $errorfile = "<small class='font-italic text-danger'>extension non autorisée (jpg, png, jpeg).</small>";  

              // cette variable est déclarée seulement dans le cas d'une mauvaise extension du fichier
              $error = true;
        }
        else
        {
            // on renomme la photo en concaténant la référence saisie dans le formulaire avec le nom de l'image recupérée dans la suerglobale $_FILES
            $nomPhoto = $_POST['reference'] . '-' . $_FILES['photo']['name'];
           // echo $nomPhoto . '<hr>';

           // permet de définir l'URL de la photo qui est conservé en BDD
           // ex:  http://localhost/PHP/09-Boutique/photo/22B78-image5.jpg
           $photoBdd = URL . "photo/$nomPhoto";
          // echo $photoBdd . '<hr>';

           // on défini le chemin physique de l'image sur le serveur
           //ex : C:/xampp/htdocs/PHP/09-Boutique/Photos/22B78-image5.jpg
           $photoDossier = RACINE_SITE . "photo/$nomPhoto";
          // echo $photoDossier . '<hr>';

           // copy() : fonction prédéfinie permettant de copier un fichier sur le serveur
            // arguements : 
            // 1. le nom temporaire de l'image accessible via la superglobale $_FILES
            // 2. le chemin physique de la photo dans le dossier photo sur le serveur
           copy($_FILES['photo']['tmp_name'], $photoDossier);
        }
    }

     //si la variable $error n'est pas défini cela veut dire que l'internaute n'a pas fait d'erreur sur l'extension du fichier uploadé nous pouvons executer la requete d'insertion en BDD 
    if(!isset($error))
    {    
        //si action est definie dans l'URL et qu il a pour valeur ajout on execute une requete d' insertion (l'internaute a cliqué sur ajout d'un produit)
        if(isset($_GET['action']) && $_GET['action'] == 'ajout')

        {
                $data = $bdd->prepare("INSERT INTO produit (reference, categorie, titre, description, couleur, taille, photo, public, prix, stock) VALUES (:reference, :categorie, :titre, :description, :couleur, :taille, :photo, :public, :prix, :stock)");

                // après l'insertion on redéfinie l'indice 'action' dans l'URL afin d'etre redirigé vers l'affichage des produits
                $_GET['action'] = 'affichage';

                $v = "<div class='col-md-3 mx-auto alert alert-success text-center'>Le produit référence <strong>$_POST[reference]</strong> a été enregistré avec succès !</div>";
        }
        else  // sinon ce n' est pas une insertion mais une modification on execute une requete d'update
        {       
                // A la validation du formulaire en cas de modification, on execute une requete UPDATE en BDD par rapport à l'id_produit transmis dans l'URL

                $data = $bdd->prepare("UPDATE produit SET reference = :reference, categorie = :categorie, titre = :titre, description = :description, couleur = :couleur, taille = :taille, photo = :photo, public = :public , prix = :prix, stock = :stock WHERE id_produit = :id_produit ");

                $data->bindValue(':id_produit', $_GET['id_produit'], PDO:: PARAM_INT);

                $_GET['action'] = 'affichage';

                $v = "<div class='col-md-3 mx-auto alert alert-success text-center'>Le produit référence <strong>$_POST[reference]</strong> a été modifié avec succès !</div>";
        }   
                $data->bindValue('reference', $_POST['reference'], PDO:: PARAM_STR);
                $data->bindValue('categorie', $_POST['categorie'], PDO:: PARAM_STR);
                $data->bindValue('titre', $_POST['titre'], PDO:: PARAM_STR);
                $data->bindValue('description', $_POST['description'], PDO:: PARAM_STR);
                $data->bindValue('couleur', $_POST['couleur'], PDO:: PARAM_STR);
                $data->bindValue('taille', $_POST['taille'], PDO:: PARAM_STR);
                $data->bindValue('photo', $photoBdd, PDO:: PARAM_STR);
                $data->bindValue('public', $_POST['public'], PDO:: PARAM_STR);
                $data->bindValue('prix', $_POST['prix'], PDO:: PARAM_INT);
                $data->bindValue('stock', $_POST['stock'], PDO:: PARAM_INT);
                $data->execute();
    }
}



require_once('../Include/header_inc.php');
require_once('../Include/nav_inc.php');
?>

<h1 class="display-4 text-center my-5">BACKOFFICE</h1>

<!-- Liens produits -->
<DIV class="col-md-3 mx-auto d-flex flex-column">
    <a href="?action=affichage" class="btn btn-success mb-2">AFFICHAGE DES PRODUITS</a>
    <a href="?action=ajout" class="btn btn-info">AJOUT D'UN PRODUIT</a>
</DIV>


<?php
// affichage des produits

//si l'indice action est défini dans l'URL et qu'il a pour valeur affichage cela veut dire que l'internaute a cliqué sur le lien 'AFFICHAGE DES PRODUITS' alors on entre dans la condition IF et on execute tout le code de l'affichage des produits
if(isset($_GET['action']) && $_GET['action'] == 'affichage')
{
    echo "<h1 class= 'display-4 text-center text-uppercase my-5'> $_GET[action] des produits </h1>";
 
    if(isset($vd)) echo $vd; //affichage message de suppression produit
    if(isset($v)) echo $v;  //affichage message insert ou update produit

    $result = $bdd->query("SELECT * FROM produit ");

    if($result->rowcount() < 2)
        $txt = 'produit enregistré';
    else
        $txt = 'produits enregistrés';

   echo"<h6 class='ml-3'><span class='badge badge-success'>" . $result->rowCount() . "</span> $txt </h6>";

       
          echo '<table class="table table-bordered table-striped text-center"> <tr>';


    for($i = 0  ;$i < $result->columnCount(); $i++)
    {
        $colonne = $result->getColumnMeta($i);
         //echo '<pre>';print_r($colonne); echo '</pre>';

         echo "<th>" . strtoupper($colonne['name']) . "</th>"; //strtoupper() permet de mettre en majuscules les noms des colonnes
    }
         echo"<th>EDIT</th>";
         echo"<th>SUPP</th>";
 
      echo'</tr>';

    while($arrayProduits = $result->fetch(PDO::FETCH_ASSOC))
    {
      // echo '<pre>';print_r($arrayProduits); echo '</pre>';

        echo"<tr>";
        foreach($arrayProduits as $key => $value)
          {

            if($key == 'photo')
            echo"<td><img src='$value' alt='' style='width: 150px;'></td>";
            else
             echo"<td class='align-middle'>$value</td>";
          }
             // on transmet l'id_produit dans l'URL dans le cas d'une modif ou suppression cela nous permettra soit de récupérer les infos du produit en cas de modif ou supprimer une ligne de la BDD via son
             echo "<td class='align-middle'><a href='?action=modification&id_produit=$arrayProduits[id_produit]' class='btn btn-primary'><i class='fas fa-edit'></i></a></td>";
             echo "<td class='align-middle'><a href='?action=suppression&id_produit=$arrayProduits[id_produit]' class='btn btn-danger' onclick='return(confirm(\"Voulez vous vraiment supprimer ce produit?\"));'><i class='fas fa-trash-alt'></i></a></td>";
             echo "</tr>";
    }

 echo'</table>';

}


?>

<!-- SI l'indice 'action' est définit dans l'URL et qu'il a pour valeur 'ajout', cela veut dire que l'internaute a cliqué sur le lien 'AJOUT D'UN PRODUIT', alors on entre dans la condition IF et on execute tout le code de l'affichage du formulaire des produits -->

<?php if(isset($_GET['action']) && ($_GET['action'] =='ajout'  OR $_GET['action'] == 'modification')): 

    if(isset($_GET['action']) && ($_GET['action'] =='modification'))

            {echo "<h1 class='display-4 text-center text-uppercase my-5'>$_GET[action] du produit id $_GET[id_produit]</h1>"; }
    else
            {echo "<h1 class='display-4 text-center my-5 text-uppercase'>$_GET[action] d'un produit</h1>"; } 
            
            
            

    ?>


<!-- enctype="multipart/form-data" : si le formulaire contient un upload de fichier, il ne faut surtout pas oublier l'attribut 'enctype' et la valeur 'multipart/form-data' cela permet de récupérer les infos liés au fichier uploadé (nom' extension, nom tmp etc...) directement stocké dans la superglobale $_FILES -->
<form method="post" class="col-md-7 mx-auto mb-5" enctype="multipart/form-data">
               <div class= "form-row">
                 <div class="form-group col-md-6">
                    <label for="reference">Référence</label>
                    <input type="text" class="form-control" id="reference" name="reference" placeholder="reference" value="<?= $reference ?>">
                    
                 </div>
                 <div class="form-group col-md-6">
                    <label for="categorie">Catégorie</label>
                    <input type="text" class="form-control" id="categorie" name="categorie" placeholder="categorie" value="<?= $categorie ?>">
            
                 </div>
                 </div>
                 <div class= "form-row">
                 <div class="form-group col-md-12">
                 
                      <label for="titre">Titre</label>
                      <input type="text" class="form-control" id="titre" name="titre" placeholder="titre" value="<?= $titre ?>"> 
                </div>
                 </div>
                <div class="form-group">
                      <label for="description">Description</label>
                      <textarea class="form-control" id="description" name="description" rows="5"><?= $description ?></textarea>
        
                </div>
                <div class="form-row">
                <div class=" form-group col-md-6">
                <label for="taille">Taille</label>
                      <select class="form-control" name="taille" id="taille" value="<?= $taille ?>">
                      <option value="XS" <?php if ($taille == 'XS') echo 'selected'; ?> >XS</option>
                      <option value="S" <?php if($taille == 'S') echo 'selected'; ?> >S</option>
                      <option value="M" <?php if($taille == 'M') echo 'selected'; ?> >M</option>
                      <option value="L" <?php if($taille == 'L') echo 'selected'; ?> >L</option>
                      <option value="XL" <?php if($taille == 'XL') echo 'selected'; ?> >XL</option>
                      </select>
                      </div>
                      <div class="form-group col-md-6">
                      <label for="public"> Public</label>
                      <select class="form-control" name="public" id="public" value="<?= $public ?>">
                      <option value="femme" <?php if($public == 'femme') echo 'selected'; ?> >Femme</option>
                      <option value="homme" <?php if($public == 'homme') echo 'selected'; ?> >Homme</option>
                      <option value="mixte" <?php if($public == 'mixte') echo 'selected'; ?> >Mixte</option>
                      </select>
                      </div>
                      </div>
                <div class= "form-row">
                
                  <div class="form-group col-md-6">
                      <label for="couleur">Couleur</label>
                      <input type="text" class="form-control" id="couleur" name="couleur" placeholder="couleur" value="<?= $couleur ?>">
                      
                  </div>
                
                      <div class="form-group col-md-6">
                         <label for="prix"> Prix</label>
                         <input type="text" class="form-control" id="prix" name="prix" placeholder="saisir le prix" value="<?= $prix ?>">

                      </div>
                </div>
                <div class= "form-row">
                  <div class="form-group col-md-6">
                      <label for="photo"> Photo </label> 
                      <input type="file" class="form-control" id="photo" name="photo">
                      <?php if(isset($errorfile)) echo $errorfile;?> 
                    
                  </div>

                <?php if(!empty($photo)): ?>
                    <p class="text-center">
                        <em>Vous pouvez uploader une photo si vous souhaitez la changer</em><br>
                        <img src="<?= $photo ?>" alt=" <?=$titre ?>" style="width:150px;" >
                    </p>
                <?php endif ?>

                   <div class="form-group col-md-6">
                      <label for="stock">Stock</label>
                      <input type="text" class="form-control " id="stock" name="stock" placeholder="saisir le stock" value="<?= $stock ?>">
                  </div>
                </div>
                      <button type='submit' class='btn btn-info'><?= strtoupper($_GET['action']) ?> PRODUIT</button>
               </form>

                      
<?php
endif;


require_once('../Include/footer_inc.php');