<?php require_once '../inc/header.inc.php';

if (!admin()){

    header('location:../');
    exit();
}



$r=executeRequete("SELECT * FROM category");

$categories=$r->fetchAll(PDO::FETCH_ASSOC);


// condition de soumission de formulaire
if (!empty($_POST))
{

    //debug($_POST);
    //debug($_FILES);
    // condition d'upload d'un fichier photo
    if (!empty($_FILES) && !empty($_FILES['picture']['name']) && !isset($_POST['id'])){
        // d ou D pour jour
        // m ou M pour mois
        // Y ou yyyy pour l'année en 4 chiffre
        // H pour heure
        // i pour minute
        // s pour seconde

        // date_format() est fonction prédéfini qui attend en premier argument un objet DateTime() et en second le formatage attendu. l'objet dateTime vide contient toute les informations de date et heure à l'instant où il est appelé.
        $picture_bdd=date_format(new DateTime(),'dmYHis').uniqid().$_FILES['picture']['name'];
        // ici on vient de renommer le nom du fichier uploadé en concaténant le nom d'origine avec la date formatée et une clés aléatoire de 13 caractères aléatoires générée par uniquid()

        //  on doit  copier le fichier temporaire dans un dossier d'upload

        // si les dossiers n'existe pas
        if (!file_exists('../assets') && !file_exists('../assets/upload'))
        {
            // on les créés grace à mkdir
            mkdir('../assets', 0777, true);
            mkdir('../assets/upload', 0777, true);

        }

        // la fonction copy permet de déplacer le fichier temporaire dans un dossier d'upload.
        // elle attend en 1er argument l'emplacement du fichier temporer présent dans le sous tableau tmp_name et en 2nd argument l'emplacement où il faut copier le fichier et son nom
        copy($_FILES['picture']['tmp_name'], '../assets/upload/'.$picture_bdd);

       $req= executeRequete("INSERT INTO product (title, price, description , picture, id_category) VALUES (:title, :price, :description, :picture, :id_category)", array(
               ':title'=>$_POST['title'],
               ':price'=>$_POST['price'],
                ':description'=>$_POST['description'],
                 ':picture'=>'assets/upload/'.$picture_bdd,
           ':id_category'=>$_POST['id_category']




       ));

       // $requ renvoi false ou un objet pdoStatement
        // si $req renvoi false alors on renvoie un message d'erreur et on le redirige sur la même page pour afficher le message d'erreur
       if (!$req){

           $_SESSION['messages']['danger'][]='Une serreur s\'est produite, veuillez recommencer';
           header('location:formulaireProduit.php');
           exit();

       }else{ // sinon on redirige sur la page gestion avec un message de succes

           $_SESSION['messages']['success'][]='Produit ajouté';
           header('location:gestionProduit.php');
           exit();

       }




    } // fin condition de présence de fichier photo à l'ajout

    // condition pour s'assurer d'être en modification
    if (isset($_POST['id'])){

        // on verifie si il y a un fichier en cours d'upload sur l'input type file editPicture
        // alors on souhaite modifier la photo
        if (!empty($_FILES) && !empty($_FILES['editPicture']['name'])){

            // on renomme le nouveau fichier
            $picture_bdd='assets/upload/'.date_format(new DateTime(),'dmYHis').uniqid().$_FILES['editPicture']['name'];

            //on upload le fichier temporaire
            copy($_FILES['editPicture']['tmp_name'], '../'.$picture_bdd);
            // on supprime l'ancienne photo
            unlink('../'.$_POST['picture']);


        }else{ // on ne modifie pas la photo

            $picture_bdd=$_POST['picture'];

        }

        $req=executeRequete("UPDATE product SET title=:title, description=:description, price=:price, picture=:picture, id_category=:id_category WHERE id=:id", array(
                ':title'=>$_POST['title'],
                 ':description'=>$_POST['description'],
                 ':price'=>$_POST['price'],
                  ':picture'=>$picture_bdd,
                  ':id'=>$_POST['id'],
            ':id_category'=>$_POST['id_category']
        ));

        if (!$req){

            $_SESSION['messages']['danger'][]='Une serreur s\'est produite, veuillez recommencer';
            header('location:formulaireProduit.php');
            exit();

        }else{ // sinon on redirige sur la page gestion avec un message de succes

            $_SESSION['messages']['success'][]='Produit modifié';
            header('location:gestionProduit.php');
            exit();

        }








    }




}// fin de condition de formulaire

// on récupère le produit en modification car l'id est présent dans l'url déclaré en get
if(isset($_GET['id'])){

    $requete=executeRequete('SELECT * FROM product WHERE id=:id', array(
            ':id'=>$_GET['id']
    ));
    $product=$requete->fetch(PDO::FETCH_ASSOC);


}






?>


<form method="post" enctype="multipart/form-data" >
    <fieldset>

        <div class="form-group">
            <label for="exampleInputPassword1" class="form-label mt-4">Nom du produit</label>
            <input name="title" value="<?=  $product['title'] ?? '' ; ?>" type="text" class="form-control" id="exampleInputPassword1" placeholder="Saisissez le nom du produit">
        </div>
        <div class="form-group mt-4 ">
            <label >Selectionnez votre note</label>
            <select name="id_category" class=" form-select  " id="floatingSelect"
                    aria-label="Floating label select example">
            <?php foreach ($categories as $category): ?>

                <option <?php if (isset($product) && $product['id_category']==$category['id'] ): echo 'selected'; endif;?> value="<?=  $category['id'] ; ?>"><?=  $category['title'] ; ?></option>
            <?php endforeach; ?>
            </select>

        </div>
        <div class="form-group">
            <label for="exampleInputPassword2" class="form-label mt-4">Prix</label>
            <input name="price" value="<?=  $product['price'] ?? '' ; ?>" type="text" class="form-control" id="exampleInputPassword2" placeholder="Saisissez le prix du produit">
        </div>
        <div class="form-group">
            <label for="exampleTextarea" class="form-label mt-4">Description</label>
            <textarea name="description" class="form-control" id="exampleTextarea" rows="3"><?=  $product['description'] ?? 'présenter produit' ; ?>
            </textarea>
        </div>
        <?php if (isset($product)): ?>
        <div class="form-group text-center">
            <label for="formFile" class="form-label mt-4">Modifier Photo</label>
            <input name="editPicture" class="form-control mb-2" type="file" id="formFile">
                <img src="<?=  BASE.$product['picture'] ; ?>" width="300" alt="">
            <input type="hidden" name="picture" value="<?=  $product['picture'] ; ?>">
            <input type="hidden" name="id" value="<?=  $product['id'] ; ?>">

        </div>
        <?php else: ?>
            <div class="form-group text-center">
                <label for="formFile" class="form-label mt-4">Photo</label>
                <input name="picture" class="form-control mb-2" type="file" id="formFile">
            </div>
     <?php endif; ?>

        <button type="submit" class="btn btn-warning mt-3">Enregistrer</button>
    </fieldset>
</form>





<?php require_once '../inc/footer.inc.php';  ?>






