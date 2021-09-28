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
$exos = ["python","java","c"];
$nb_ex = [];
$nb_total = 0;
foreach($exos as $exo)
{
    $sth_all_ex = $db->prepare("SELECT COUNT(*) FROM exercice_".$exo);
    $sth_all_ex->execute();
    $aux_nb = $sth_all_ex->fetchAll()[0][0];
    $nb_ex[$exo] = $aux_nb;
    $nb_total += intval($aux_nb);
}
$python_solves = sizeof(explode(",",$res_user[0]["solved_python"]))-1;
$java_solves = sizeof(explode(",",$res_user[0]["solved_java"]))-1;
$c_solves = sizeof(explode(",",$res_user[0]["solved_c"]))-1;
$nb_solve_by_user = $python_solves+$java_solved+$c_solves;
if($nb_total === 0) $avancement_general = 100;
else $avancement_general = ($nb_solve_by_user / $nb_total)*100;
if($nb_ex["python"] === "0") $avancement_python = 100;
else $avancement_python = ($python_solves/$nb_ex["python"])*100;
if($nb_ex["java"] === "0") $avancement_java = 100;
else $avancement_java = ($java_solves/$nb_ex["java"])*100;
if($nb_ex["c"] === "0") $avancement_c = 100;
else $avancement_c = ($c_solves/$nb_ex["c"])*100;
?>
<!DOCTYPE HTML>
<head>
    <meta charset="utf-8">  
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
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
                <h3>ESNA Bootcamp</h3>
            </div>

            <ul class="list-unstyled components">
                <p>Salut <?=$res_user[0][1];?></p>
                <li class="active">
                    <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Documentations</a>
                    <ul class="collapse list-unstyled" id="homeSubmenu">
                        <li>
                            <a href="/doc/python">Python</a>
                        </li>
                        <li>
                            <a href="/doc/java">Java</a>
                        </li>
                        <li>
                            <a href="/doc/c">C</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Exercices</a>
                    <ul class="collapse list-unstyled" id="pageSubmenu">
                        <li>
                            <a href="/exercice/python">Python</a>
                        </li>
                        <li>
                            <a href="/exercice/java">Java</a>
                        </li>
                        <li>
                            <a href="/exercice/c">C</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#pageSubmenuScore" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Scores</a>
                    <ul class="collapse list-unstyled" id="pageSubmenuScore">
                        <li>
                            <a href="/leaderboard">Leaderboard</a>
                        </li>
                        <li>
                            <a href="#">Mon score</a>
                        </li>
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
                                <a class="nav-link" href="#">Mon score</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <h2>Bootcamp - Votre score</h2>
            <div class="line"></div>
            <label for="avancement_general">Avancement général : </label>
            <progress id="avancement_general" max="100" value="<?=$avancement_general;?>"><?=$avancement_general;?></progress>
            <p><?=$nb_solve_by_user;?> exercices résolus sur <?=$nb_total;?></p>
            <div class="line"></div>
            <label for="avancement_python">Avancement en python : </label>
            <progress id="avancement_python" max="100" value="<?=$avancement_python;?>"><?=$avancement_python;?></progress>
            <p><?=$python_solves;?> exercices résolus sur <?=$nb_ex["python"];?></p>
            <div class="line"></div>
            <label for="avancement_java">Avancement en java : </label>
            <progress id="avancement_java" max="100" value="<?=$avancement_java;?>"><?=$avancement_java;?></progress>
            <p><?=$java_solves;?> exercices résolus sur <?=$nb_ex["java"];?></p>
            <div class="line"></div>
            <label for="avancement_c">Avancement en c : </label>
            <progress id="avancement_c" max="100" value="<?=$avancement_c;?>"><?=$avancement_c;?></progress>
            <p><?=$c_solves;?> exercices résolus sur <?=$nb_ex["c"];?></p>
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