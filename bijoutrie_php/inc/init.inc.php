<?php


// paramètre connexion à la BDD
$pdo= new PDO('mysql:host=localhost;dbname=bijouterie', 'root', '', array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES utf8'));

// initiation de la session
session_start();

function debug($var)
{
    echo '<pre>';
    var_dump($var);
    echo '</pre>';

}

define('BASE', '/PHP/08-projet/');

function executeRequete($requete, $param  =array())
{
    // le paramètre $requête recoit une requête sql. Le paramètre $param recoit un tableau avec les marqueurs associés à leur valeur

    // Echappement des données avec htmlspecialchars() qui va convertir les caractères spéciaux en entité HTML
    foreach ($param as $marqueur => $valeur)
    {

            $param[$marqueur]=htmlspecialchars($valeur);





        // principalement afin de transformer les chevrons en entité HTML pour eviter les injections de balise <style>  ou <scritp>. On evite ainsi les failles XSS et CSS
    }

    global $pdo; // permet d'accéder à la variable $pdo de manière globale

             $resultat=$pdo->prepare($requete); // on prépare la requête

         $success= $resultat->execute($param); // on execute en lui passant le tableau de marqueurs

    // execute() renvoie toujours un boolean.

    if ($success){

        return $resultat;
    }else{

        return false;

    }

}


function password_strength_check($password, $min_len = 4, $max_len = 15, $req_digit = 1, $req_lower = 1, $req_upper = 1, $req_symbol = 1) {
    // Build regex string depending on requirements for the password
    $regex = '/^';
    if ($req_digit == 1) { $regex .= '(?=.*\d)'; }              // Match at least 1 digit
    if ($req_lower == 1) { $regex .= '(?=.*[a-z])'; }           // Match at least 1 lowercase letter
    if ($req_upper == 1) { $regex .= '(?=.*[A-Z])'; }           // Match at least 1 uppercase letter
    if ($req_symbol == 1) { $regex .= '(?=.*[^a-zA-Z\d])'; }    // Match at least 1 character that is none of the above
    $regex .= '.{' . $min_len . ',' . $max_len . '}$/';

    if(preg_match($regex, $password)) {
        return TRUE;
    } else {
        return FALSE;
    }
}


function connect()
{
    if (isset($_SESSION['user'])){

        return true;
    }else{

        return false;
    }
}

function admin()
{
    if (connect() && $_SESSION['user']['role']=='ROLE_ADMIN'){

        return true;
    }else{

        return false;
    }

}
