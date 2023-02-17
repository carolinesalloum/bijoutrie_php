<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bijouterie</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/5.2.3/quartz/bootstrap.min.css"
          integrity="sha512-3qm29Ouc1OmoMoeJlbg5vEOYakc9MqIWAgDVuB/TJeuqFGftnZyE9S+AP+3TGeYIkPYt6CWz5JdiKkvcZ2qHPg=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
</head>
<body>
<?php require_once 'init.inc.php'; ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= BASE; ?>">Bijouterie</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor02"
                aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarColor02">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="<?= BASE; ?>">Accueil

                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Features</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Pricing</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About</a>
                </li>

               <?php if (admin()): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                       aria-haspopup="true" aria-expanded="false">ADMIN</a>
                    <div class="dropdown-menu text-dark">
                        <a class="dropdown-item text-dark" href="<?= BASE . 'back/formulaireProduit.php'; ?>">Ajouter
                            produit</a>
                        <a class="dropdown-item text-dark" href="<?= BASE . 'back/gestionProduit.php'; ?>">Gestion
                            produits</a>
                        <a class="dropdown-item text-dark" href="<?=  BASE.'back/gestionCategorie.php' ; ?>">Gestion Catégories</a>
                        <div class="dropdown-divider bg-dark"></div>
                        <a class="dropdown-item text-dark" href="#">Separated link</a>
                    </div>
                </li>
                <?php endif; ?>
            </ul>
<!--            <form class="d-flex">-->
<!--                <input class="form-control me-sm-2" type="search" placeholder="Search">-->
<!--                <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>-->
<!--            </form>-->
            <?php if (!connect()): ?>
            <a href="<?=  BASE.'security/inscription.php' ; ?>" class="btn btn-secondary me-2">Inscription</a>
            <a href="<?=  BASE.'security/connexion.php' ; ?>" class="btn btn-info">Connexion</a>
            <?php else:  ?>
                <a href="<?=  BASE.'?action=deco' ; ?>" class="btn btn-info">Deconnexion</a>
            <?php endif; ?>

        </div>
    </div>
</nav>

<div class="container mt-4 pb-4">

    <?php if (isset($_SESSION['messages']) && !empty($_SESSION['messages'])):
        foreach ($_SESSION['messages'] as $type => $messages  ):
         foreach ($messages as $key=>$message):
    ?>
    <div class="alert alert-<?=  $type ; ?> text-center">
        <p><?=  $message ; ?></p>
    </div>

<?php unset($_SESSION['messages'][$type][$key]);

endforeach;endforeach;  endif;

?>