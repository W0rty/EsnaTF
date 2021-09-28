<?php
session_start();
if(!isset($_SESSION["username"])){
    header("Location: /");
    die();
}
require_once("../../connectdb.php");
$sth = $db->prepare("SELECT * FROM users WHERE username = :u");
$sth->bindValue(":u",htmlspecialchars($_SESSION["username"]));
$sth->execute();
$res = $sth->fetchAll();
if($res[0][4] !== "1"){
    header("Location: /playground");
    die();
}

function generateRandomString()
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < 10; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

if(isset($_GET["id"]) && isset($_GET["lang"]) && !empty($_GET["id"]) && !empty($_GET["lang"])){
    $languages = ["exercice_python","exercice_java","exercice_c"];
    if(in_array($_GET["lang"],$languages))
    {
        $sth = $db->prepare("SELECT * FROM ".$_GET["lang"]);
        $sth->execute();
        $res = $sth->fetchAll();
        foreach($res as $r)
        {
            if($r[0] === $_GET["id"])
            {
                $allExos = $r;
                break;
            }
        }
    }else{
        header('Content-Type: application/json');
        echo '{"error":"Langage introuvable"}';
        die();
    }
    if(isset($allExos))
    {
        $exo = ["langage" => $_GET["lang"], "id" => $allExos[0], "numero" => $allExos[1], "enonce" => $allExos[2], "reponse" => $allExos[3], "titre" => $allExos[4], "ajout" => $allExos[5], "remote" => $allExos[6], "flag" => $allExos[7]];
        $random = generateRandomString();
        file_put_contents("/var/www/html/auxfile95172851967286/".$random.".json",json_encode($exo));
        header('Content-Type: application/json');
        echo '{"ok":"'.$random.'.json"}';
    }else{
        header('Content-Type: application/json');
        echo '{"error":"Impossible de trouver l\'exercice spécifié."}';
    }
}else{
    header('Content-Type: application/json');
    echo '{"error":"Paramètre(s) manquant(s)."}';
}