<?php
session_start();

if(!isset($_SESSION["username"])){
    header("Location: /");
    die();
}
require_once("../core/connectdb.php");
$sth_user = $db->prepare("SELECT * FROM users WHERE username = :u");
$sth_user->bindValue(":u",htmlspecialchars($_SESSION["username"]));
$sth_user->execute();
$res_user = $sth_user->fetchAll();
if(sizeof($res_user) === 0)
{
    header("Location: /disconnect");
    die();
}
$id_user = $res_user[0][0];
$python_solved = $res_user[0][3];
if(isset($_POST["hash"]) && isset($_POST["flag"]))
{
    $sth = $db->prepare("SELECT * FROM en_cours WHERE token = :h");
    $sth->bindValue(":h",htmlspecialchars($_POST["hash"]));
    $sth->execute();
    $res = $sth->fetchAll();
    if($res)
    {
        if($res[0][1] === $id_user){
            if($res[0][2] !== "0"){
                $num_exo = $res[0][2];
                $table = "exercice_python";
                $solved = "solved_python";
            }elseif($res[0][3] !== "0") {
                $num_exo = $res[0][3];
                $table = "exercice_java";
                $solved = "solved_java";
            }
            $sth = $db->prepare("SELECT flag FROM ".$table." WHERE id = :n");
            $sth->bindValue(":n",$num_exo);
            $sth->execute();
            if($sth->fetchAll()[0][0] === $_POST["flag"])
            {
                $sth = $db->prepare("UPDATE users SET ".$solved." = :s WHERE id = :id");
                $sth->bindValue(":id",$id_user);
                if($solved === "solved_python") $sth->bindValue(":s",$python_solved.strval(sizeof(explode(",",$python_solved))).",");
                $sth->execute();
                header('Content-type: application/json');
                echo '{"ok":"Flag correct, vous pouvez passer à l\'exercice suivant !"}'; 
            }else{
                header('Content-type: application/json');
                echo '{"error":"Flag incorrect, courage!"}'; 
            }
        }else{
            header('Content-type: application/json');
            echo '{"error":"Erreur, veuillez réessayer."}';    
        }
    }else{
        header('Content-type: application/json');
        echo '{"error":"Impossible de trouver l\'exercice associé. Veuillez réessayer."}';    
    }

}else{
    header('Content-type: application/json');
    echo '{"error":"Paramètre(s) manquant(s)"}';
}