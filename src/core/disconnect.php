<?php
session_start();
if(isset($_SESSION["username"])){
    require_once("connectdb.php");
    $sth_user = $db->prepare("SELECT id FROM users WHERE username = :u");
    $sth_user->bindValue(":u",$_SESSION["username"]);
    $sth_user->execute();
    $id = $sth_user->fetchAll()[0][0];
    $sth = $db->prepare("DELETE FROM en_cours WHERE users = :u");
    $sth->bindValue(":u",$id);
    $sth->execute();
}
session_destroy();
header("Location: /");
die();