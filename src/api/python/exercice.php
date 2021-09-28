<?php
session_start();
if(!isset($_SESSION["username"])){
    header("Location: /");
    die();
}
require_once("../../core/connectdb.php");
require_once("../../core/functions.php");
$sth_user = $db->prepare("SELECT * FROM users WHERE username = :u");
$sth_user->bindValue(":u",htmlspecialchars($_SESSION["username"]));
$sth_user->execute();
$res_user = $sth_user->fetchAll();
if(sizeof($res_user) === 0)
{
    header("Location: /disconnect");
    die();
}
if(isset($_POST["code"]) && isset($_POST["hash"]) && !empty($_POST["code"])){
    $sth_ec = $db->prepare("SELECT * FROM en_cours WHERE token = :t");
    $sth_ec->bindValue(":t",htmlspecialchars($_POST["hash"]));
    $sth_ec->execute();
    $res_ec = $sth_ec->fetchAll();
    if($res_ec){
        $sth_ex = $db->prepare("SELECT * FROM exercice_python WHERE id = :id");
        $sth_ex->bindValue(":id",$res_ec[0][2]);
        $sth_ex->execute();
        $res_ex = $sth_ex->fetchAll();
        $valid_result = $res_ex[0][3];
        $real_code = base64_decode($_POST["code"]);
        if($res_ex[0][5]) $real_code = $real_code.chr(10).str_replace("&quot;",'"',$res_ex[0][5]);
        $res = trim(create_secure_code($real_code),"\x00..\x1F");
        if($res === $valid_result){
            $sth_reussi = $db->prepare("UPDATE users SET solved_python = :sp WHERE username = :u");
            $sth_reussi->bindValue(":u",htmlspecialchars($_SESSION["username"]));
            if(substr_count($res_user[0][3],",") === 0) $sth_reussi->bindValue(":sp",$res_ec[0][2].",");
            else $sth_reussi->bindValue(":sp",$res_user[0][3].$res_ec[0][2].",");
            $sth_reussi->execute();
            header('Content-type: application/json');
            echo '{"valid":"Bien joué! Tu peux passer à l\'exercice suivant."}';
        }else{
            header('Content-type: application/json');
            echo '{"code_error":"'.base64_encode(htmlspecialchars($res)).'"}';
        }
    }else{
        header('Content-type: application/json');
        echo '{"error":"Token invalide"}';
    }
}else{
    header('Content-type: application/json');
    echo '{"error":"Paramètres manquants"}';
}
?>