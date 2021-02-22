<?php
require_once('include/init_inc.php');

//Contrôle du formulaire :
//echo'<pre>'; print_r($_POST); echo'<pre>';



//DECONNEXION
//Si l'indice 'action' est défini dans l'URL et qu'il a pour valeur 'deconnexion', cela veut dire que l'internaute a cliqué sur Déconnexion, alors on entre dans la condition if et on supprime le tableau ARRAY à l'indice 'user'
if(isset($_GET['action'])  && $_GET['action'] =='deconnexion')
{
    unset($_SESSION['user']);
}

if(connect())
{
    //si l'internaute est déjà identifié sur le site il est redirigé directement vers la page votre profil
   header('location: profil.php');
}



if($_POST)
{
    // On sélectionne tout en BDD à condition que le pseudo ou l'email saisi par l'internaute dans le formulaire correspondent à des données en BDD
    $data = $bdd->prepare("SELECT * FROM membre WHERE pseudo = :pseudo OR email = :email");
    $data->bindValue(':pseudo', $_POST['pseudo_email'], PDO::PARAM_STR);
    $data->bindValue(':email', $_POST['pseudo_email'], PDO::PARAM_STR);
    $data->execute();
    // Si la requete de sélection retourne 1 résultat , cela veut dire que l'internaute a saisi le bon mail/pseudo
    if ($data->rowCount())
    {
        //echo 'pseudo ou email ok !';

        //on execute fetch() afin d'obtenir un ARRAY contenant les données en BDD de l'internaute qui a saisi le bon pseudo/email
        $user = $data->fetch(PDO::FETCH_ASSOC);
       // echo '<pre>'; print_r($user); echo '</pre>';
        
        //si le mot de passe de la BDD est égal au mot de passe que l'internaute a saisi on entre dans le IF
        //$user['mdp'] == $_POST['mdp'] Comparaison de mot de passe en clair

        //Controle de mot de passe haché :
        //password_verify() : fonction prédéfinie permettant de comparer un mdp en clair à une clé de hachage stockées en BDD
        // arguments : password_verify('mot de passe saisie', 'clé de hachage BDD')

        if(password_verify($_POST['mdp'], $user['mdp']))
        {
             // on entre dans le if si l'internaute a correctement rempli le formulaire de connexion (pseudo ou mail et mdp)
             //echo 'mot de passe ok !';

             //enregistrement des données de l'internaute dans son fichier de session afin qu'il puisse se connecter et etre identifié sur le site quelque soit la page ou il se trouve

             //on boucle les infos de l'utilisateur recupérer en BDD afin de créer un tableau multi dans la session, contenant un autre ARRAY

             foreach($user as $key => $value)
             {
                 if($key != 'mdp')
                 {
                     $_SESSION['user'][$key] = $value;
                 }
             }
                //Une fois les données enregistrées en session on redirige l'internaute vers sa page profil
                header('location: profil.php');
                //echo '<pre>'; print_r($_SESSION); echo '</pre>';
        }
        else //Sinon l'internaute n'a pas saisi le bon mot de passe on affiche un message d'erreur
        {
            //echo 'erreur mot de passe !';
            $error = "<div class= 'col-md-2 alert alert-danger text-center mx-auto'>Identifiants erronés.</div>";
        }
    }
    else //sinon la requete de selection ne retourne aucun résultat de la BDD
    {
       // echo 'pseudo ou email ERREUR !';
       $error = "<div class= 'col-md-2 alert alert-danger text-center mx-auto'>Identifiants erronés.</div>";
    }
}

require_once('include/header_inc.php');
require_once('include/nav_inc.php');
?>

<h1 class="display text-center py-5">Identifiez-vous</h1>
<?php 
// Affichage message erreur utilisateur
 if(isset($error)) echo $error; 
 ?>

<form method="post" class="col-md-3 mx-auto">
  <div class="form-group">
    <label for="pseudo_email">Email ou Pseudo</label>
    <input type="text" class="form-control" id="pseudo_email" name="pseudo_email" value="<?php if(isset($_POST['pseudo_email'])) echo $_POST['pseudo_email'];?>">
  </div>
  <div class="form-group">
    <label for="mdp">Mot de passe</label>
    <input type="password" class="form-control" id="mdp" name="mdp">
  </div>
   <button type="submit" class="btn btn-secondary">CONTINUEZ</button>
</form>


<?php
require_once('include/footer_inc.php');
