<?php
session_start();
if(!isset($_SESSION["username"])){
    header("Location: /");
    die();
}
require_once("../connectdb.php");
$sth_user = $db->prepare("SELECT * FROM users WHERE username = :u");
$sth_user->bindValue(":u",htmlspecialchars($_SESSION["username"]));
$sth_user->execute();
$res_user = $sth_user->fetchAll();
if(sizeof($res_user) === 0)
{
    header("Location: /disconnect");
    die();
}
$sth_ex = $db->prepare("SELECT numero,titre FROM exercice_python");
$sth_ex->execute();
$res_ex = $sth_ex->fetchAll();
$sth_all_ex = $db->prepare("SELECT COUNT(*) FROM exercice_python");
$sth_all_ex->execute();
$nb_all_ex = $sth_all_ex->fetchAll();
$python_solves = explode(",",$res_user[0]["solved_python"]);
$solved = [];
$todo = [];
$disable = [];
$lastI = 0;
if(substr_count($res_user[0][3],",") === 0)
{
    $avancement = 0;
    array_push($todo,[$res_ex[0][0],$res_ex[0][1]]);
    for($i=1;$i<sizeof($res_ex);$i++)
    {
        array_push($disable,[$res_ex[$i][0],$res_ex[$i][1]]);
    }
}else{
    $avancement = (sizeof($python_solves)-1)/intval($nb_all_ex[0][0])*100;
    for($i=0; $i<sizeof($python_solves); $i++)
    {
        if($python_solves[$i]){
            array_push($solved,[$res_ex[$i][0],$res_ex[$i][1]]);
            $lastI = $i+1;
        }
    }
    if($lastI < sizeof($res_ex)){
        array_push($todo,[$res_ex[$lastI][0],$res_ex[$lastI][1]]);
        for($i=$lastI+1; $i<sizeof($res_ex);$i++)
        {
            array_push($disable,[$res_ex[$i][0],$res_ex[$i][1]]);
        }
    }else $message = "Bravo ! Vous avez complété tous les exercices en python ! <br> Vous pouvez passer à une autre catégorie :)";
}
?>
<!DOCTYPE HTML>
<head>
    <meta charset="utf-8">  
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/assets/css/style.css">
    <title>ESNA - Playground</title>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>ESNA Bootcamp - Python</h3>
            </div>

            <ul class="list-unstyled components">
                <p>Salut <?=$res_user[0][1];?></p>
                <li class="active">
                    <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Documentation</a>
                    <ul class="collapse list-unstyled" id="homeSubmenu">
                        <li>
                            <a href="/doc/python">Python</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Exercices</a>
                    <ul class="collapse list-unstyled" id="pageSubmenu">
                        <?php 
                        if(sizeof($solved) > 0){
                            foreach($solved as $solv){
                                echo('<li><a href="/exercice/python/train?id='.$solv[0].'" style="color: lightgreen;">'.$solv[1].'</a></li>');
                            }
                        }
                        if(sizeof($todo) > 0) echo ('<li><a id="todo" onmouseleave="$(\'#todo\').css(\'color\',\'white\')" onmouseover="$(\'#todo\').css(\'color\',\'black\')" href="/exercice/python/train?id='.$todo[0][0].'" style="color: white;">'.$todo[0][1].'</a></li>');
                        if(sizeof($disable) > 0){
                            foreach($disable as $disab){
                                echo('<li><a href="#" style="pointer-events: none; color: red;">'.$disab[1].'</a></li>');
                            }
                        } 
                        ?>
                    </ul>
                </li>
            </ul>

            <ul class="list-unstyled CTAs">
                <li>
                    <a href="/playground" class="download">Back to Home</a>
                </li>
                <li>
                    <a href="/disconnect" class="article">Disconnect</a>
                </li>
            </ul>
        </nav>

        <!-- Page Content  -->
        <div id="content">

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">

                    <button type="button" id="sidebarCollapse" class="btn btn-info 1" onclick="changeText()">
                        <i class="fas fa-align-left"></i>
                        <span>Cacher le menu</span>
                    </button>
                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-align-justify"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto">
                            <li class="nav-item active">
                                <a class="nav-link" href="#">Python</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <h2>Partie Exercice - Python</h2>
            <p>Vous êtes sur la partie python du site internet, avec des exercices, que vous trouverez dans le menu à côté.</p>

            <div class="line"></div>
            <label for="avancement">Avancement en python : </label>
            <progress id="avancement" max="100" value="<?=$avancement;?>"><?=$avancement;?></progress>
            
            <div class="line"></div>
            <?php if(isset($message))  echo('<p class="lead" style="color: green">'.$message.'</p>'); ?>
            <div class="text-center">.:Happy Programming:.</div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });
        });

        function changeText()
        {
            var to_app = '<i class="fas fa-align-left"></i><span>change</span>'
            var cur_class = $("#sidebarCollapse").attr("class")
            if(cur_class.includes("1"))
            {
                $("#sidebarCollapse").empty()
                $("#sidebarCollapse").removeClass("1")
                $("#sidebarCollapse").addClass("2")
                $("#sidebarCollapse").append(to_app.replace("change","Ouvrir le menu"))
            }else{
                $("#sidebarCollapse").empty()
                $("#sidebarCollapse").removeClass("2")
                $("#sidebarCollapse").addClass("1")
                $("#sidebarCollapse").append(to_app.replace("change","Cacher le menu"))
            }
        }
    </script>
</body>
</html>