<?php
session_start();
if(!isset($_SESSION["username"])){
    header("Location: /");
    die();
}
require_once("../core/connectdb.php");

$sth = $db->prepare("SELECT * FROM users WHERE isAdmin=0");
$sth->execute();
$all_users = $sth->fetchAll();

$languages = ["exercice_python","exercice_java","exercice_c"];
$lang = ["python","java","c"];
$exos_count = [];
$i = 0;
$totalExos = 0;
foreach($languages as $language)
{
    $sth = $db->prepare("SELECT COUNT(*) FROM ".$language);
    $sth->execute();
    $aux_nb = $sth->fetchAll()[0][0];
    $exos_count[$lang[$i]] = $aux_nb;
    $totalExos += intval($aux_nb);
    $i++;
}

$leaderboard = [];
foreach($all_users as $user)
{
    $username = $user[1];
    $nb_solved_python = sizeof(explode(",",$user[3]))-1;
    $nb_solved_java = sizeof(explode(",",$user[7]))-1;
    $nb_solved_c = sizeof(explode(",",$user[8]))-1;
    $nb_solved_total = $nb_solved_python+$nb_solved_java+$nb_solved_c;
    array_push($leaderboard,["username" => $username,"python" => $nb_solved_python, "java" => $nb_solved_java, "total" => $nb_solved_total, "c" => $nb_solved_c]);
}
if(isset($_GET["lang"]))
{
    if($_GET["lang"] === "python") array_multisort(array_column($leaderboard,"python"),SORT_NUMERIC,SORT_DESC,$leaderboard);
    if($_GET["lang"] === "java") array_multisort(array_column($leaderboard,"java"),SORT_NUMERIC,SORT_DESC,$leaderboard);
    if($_GET["lang"] === "c") array_multisort(array_column($leaderboard,"c"),SORT_NUMERIC,SORT_DESC,$leaderboard);
}else  array_multisort(array_column($leaderboard,"total"),SORT_NUMERIC,SORT_DESC,$leaderboard);
header('Content-type: application/json');
echo json_encode($leaderboard);