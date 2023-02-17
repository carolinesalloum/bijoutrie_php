<?php require_once '../inc/header.inc.php';

if (!empty($_GET['id'])) {
    $req = executeRequete("SELECT * FROM product WHERE id=:id", array(
        ':id' => $_GET['id']
    ));

    $product = $req->fetch(PDO::FETCH_ASSOC);

    $requete=executeRequete("SELECT u.username as username, r.* FROM rating r INNER JOIN user u ON u.id=r.id_user WHERE id_product=:id", array(
            ':id'=>$_GET['id']
    ));

    $comments=$requete->fetchAll(PDO::FETCH_ASSOC);


} else {

    header('location:../');
    exit();
}

if (!empty($_POST)){

    $r=executeRequete("INSERT INTO rating (comment, rate, id_product,publish_date, id_user) VALUES (:comment, :rate, :id_product,:publish_date, :id_user)", array(
       ':comment'=>$_POST['comment'],
       ':rate'=>$_POST['rate'],
       ':publish_date'=>date_format(new DateTime(), 'Y-m-d H:i:s'),
       ':id_product'=>$_POST['id_product'],
       ':id_user'=>$_SESSION['user']['id']
    ));

    header("location:./detailProduit.php?id=$_POST[id_product]" );
    exit();
}







?>

<div class="row">
    <div class="card col-md-7 p-0 border-primary m-3">
        <div class="card-header rounded p-0 text-center bg bg-info">
            <img src="<?= BASE . $product['picture']; ?>" width="240" alt="" class=" rounded img-fluid m-2" alt="">
        </div>
        <div class="card-body">
            <h4 class="card-title text-center"><?= $product['title']; ?></h4>
            <h5 class="card-title text-center"><?= $product['price'] . ' €'; ?></h5>
            <p class="card-text text-center"><?= $product['description']; ?></p>
        </div>
    </div>
    <div class="col-md-4">
        <?php foreach ($comments as $comment): ?>
            <div class="card  border-primary m-3">
                <div class="card-body">
                    <h6 class="card-title text-center"><?= $comment['rate']; ?>/5</h6>
                    <p class="card-text text-center"><?= $comment['comment']; ?></p>
                    <h6 class="card-title text-center"><?= $comment['username'] ; ?></h6>
                    <span class="card-title text-center"><?= date_format(new DateTime($comment['publish_date']), 'd-m-Y H:i:s' )  ; ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    </div>
    <!-- <div class="form-floating m-3">
        <textarea class="form-control border-primary" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px"></textarea>
        <label for="floatingTextarea2">Laissez un commentaire ici</label>
    </div>

    <div class="form-floating m-3">
        <select class="form-select border-primary" id="floatingSelect" aria-label="Floating label select example">
            <option selected>Notez cet article</option>
            <option value="1">One</option>
            <option value="2">Two</option>
            <option value="3">Three</option>
        </select>
        <label for="floatingSelect">Works with selects</label>
    </div> -->
     <?php if (connect()): ?>
    <form method="post" action="">
        <div class="input-group text-center col-11 mt-3">
            <div class="col-7 ms-3 me-4 text-center">
                <label for="floatingTextarea2">Laissez un commentaire</label>
                <textarea name="comment" class="bg-light text-dark form-control border-primary my-1" id="floatingTextarea2"
                          style="height: 100px">Votre commentaire</textarea>
            </div>
            <input type="hidden" name="id_product" value="<?=  $product['id'] ; ?>">
            <div class="col-4 mx-0 text-center ">
                <label for="floatingSelect">Selectionnez votre note</label>
                <select name="rate" class="bg-light text-dark form-select border-primary mt-1" id="floatingSelect"
                        aria-label="Floating label select example">
                    <option selected>Notes de 1 à 5</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>

            </div>
        </div>

            <button type="submit" class=" ms-5  btn btn-info">Valider</button>
    </form>
<?php endif; ?>





<?php require_once '../inc/footer.inc.php'; ?>