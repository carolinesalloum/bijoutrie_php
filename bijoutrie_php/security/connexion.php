<?php  require_once '../inc/header.inc.php';




if (!empty($_POST)){

    $requete=executeRequete("SELECT * FROM user WHERE email=:email", array(
        ':email'=>$_POST['email']
    ));

    if ($requete->rowCount() ==1){

        $user=$requete->fetch(PDO::FETCH_ASSOC);

        if (password_verify($_POST['password'], $user['password'])){

            $_SESSION['user']=$user;
            $_SESSION['messages']['success'][]='Bienvenue '.$user['username'];
            header('location:../');
            exit();

        }else{

            $_SESSION['messages']['danger'][]='Erreur sur le mot de passe';
            header('location:./connexion.php');
            exit();

        }






    }else{

        $_SESSION['messages']['danger'][]='Aucun compte à cette adresse mail';
        header('location:./connexion.php');
        exit();

    }



}


?>




<form method="post" action="">

    <section class="vh-100 " >
        <div class="mask d-flex align-items-center h-100 gradient-custom-3">
            <div class="container h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12 col-md-9 col-lg-7 col-xl-6">
                        <div class="card" style="border-radius: 15px;">
                            <div class="card-body p-5">
                                <h2 class="text-uppercase text-center mb-5">Connexion</h2>

                                <label for="inputEmail">Email</label>
                                <input type="email" value="" name="email" id="inputEmail"
                                       class="form-control" autocomplete="email" required autofocus>
                                <label for="inputPassword" class="mt-3">Mot de passe</label>
                                <input type="password" name="password" id="inputPassword" class="form-control"
                                       autocomplete="current-password" required>


                                <button class="btn mb-2 mt-3 mb-md-0 btn-outline-secondary btn-block" type="submit">
                                    Se connecter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
</form>






<?php  require_once '../inc/footer.inc.php';   ?>


