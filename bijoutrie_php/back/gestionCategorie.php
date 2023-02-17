<?php require_once '../inc/header.inc.php';

if (!admin()){

    header('location:../');
    exit();
}


$resultat=executeRequete("SELECT * FROM category");

$categories=$resultat->fetchAll(PDO::FETCH_ASSOC);


// ici on s'assure de receptionner toutes les informations transmises en GET sur notre bouton supprimer dans le cas d'une saisie dans l'url par un utilisateur malveillant
// existe t-il l'entrée action dans $_GET, est ce que $_GET['action'] a pour valeur delete (param de suppression ) et y a t'il présence d'une entrée 'id' dans $_GET
if (isset($_GET['action']) && $_GET['action']=='delete' && isset($_GET['id']))
{


    $req=executeRequete("DELETE FROM category WHERE id=:id", array(
        ':id'=>$_GET['id']
    ));



//     redirection sur la même page pour recharger la page
    header('location:./gestionCategorie.php');
    exit();


}

if (isset($_GET['action']) && $_GET['action']=='edit' && isset($_GET['id']))
{


    $req=executeRequete("SELECT * FROM category WHERE id=:id", array(
        ':id'=>$_GET['id']
    ));
    $category=$req->fetch(PDO::FETCH_ASSOC);



}

if (!empty($_POST)){

    $requ=executeRequete("REPLACE INTO category (id, title) VALUES (:id, :title)", array(
        ':id'=>$_POST['id'],
        'title'=>$_POST['title']
    ));

    header('location:./gestionCategorie.php');
    exit();


}






?>

<form method="post" action="">
    <div class="form-group  mt-3">

            <label >Titre</label>
            <input class="form-control"  type="text" name="title" value="<?=  $category['title'] ?? '' ; ?>">
        <input type="hidden" name="id" value="<?=  $category['id'] ?? 0; ?>">

    </div>

    <button type="submit" class="mt-1 mb-5  btn btn-info">Valider</button>
</form>


<table class="table table-hover table-dark table-striped">
    <thead>
    <tr>
        <th class="text-center align-middle" scope="col">#</th>
        <th class="text-center align-middle" scope="col">Titre</th>
        <th class="text-center align-middle" scope="col">Actions</th>
    </tr>
    </thead>
    <tbody>

    <?php foreach($categories as $category ):  ?>
        <tr>
            <th class="text-center align-middle" scope="row"><?=  $category['id'] ; ?></th>
            <td class="text-center align-middle"><?=  $category['title'] ; ?></td>
          
            <td class="text-center align-middle">
                <a href="?action=edit&id=<?=  $category['id'] ; ?>" class="btn btn-info me-2">Modifier</a>
                <a onclick="return confirm('Etes-vous sûr de vouloir supprimer')" href="?action=delete&id=<?=  $category['id'] ; ?>" class="btn btn-danger ms-2">Supprimer</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>







<?php require_once '../inc/footer.inc.php';   ?>
