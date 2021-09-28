<?php
session_start();
if(!isset($_SESSION["username"])){
    header("Location: /");
    die();
}
require_once("connectdb.php");
$sth_user = $db->prepare("SELECT * FROM users WHERE username = :u");
$sth_user->bindValue(":u",htmlspecialchars($_SESSION["username"]));
$sth_user->execute();
$res_user = $sth_user->fetchAll();
if(sizeof($res_user) === 0)
{
    header("Location: /disconnect");
    die();
}
$sth_ex = $db->prepare("SELECT * FROM exercice");
$sth_ex->execute();
$res_ex = $sth_ex->fetchAll();
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
                            <a href="/myscore">Mon score</a>
                        </li>
                    </ul>
                </li>
            </ul>

            <ul class="list-unstyled CTAs">
                <li>
                    <a href="/program" class="download">Programme des journées</a>
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
                                <a class="nav-link" href="#">Explications</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <h2>Bienvenue au bootcamp de l'ESNA !</h2>
            <p>Le bootcamp de l'ESNA a pour but de vous faire découvrir la programmation via des documentations, des interventions, des cours ainsi que des exercices.</p>
            <p>Ce bootcamp a lieu du 28 au 29 septembre inclus.</p>
            <div class="line"></div>

            <h2>Comment ça marche ?</h2>
            <p>Le fonctionnement est simple, vous avez de la documentation dans le menu, pour les langages de programmation que nous avons choisi.</p>
            <p>Une fois la documentation lue et que vous avez assisté au cours, vous pouvez vous lancer dans les exercices mis à votre disposition.</p>
            <p>Un exercice fonctionne de telle manière:</p>
            <ul>
                <li>Un énoncé vous ai donné avec des consignes précises</li>
                <li>Vous avez des données d'entrées (à mettre dans votre programme) et la sortie attendue</li>
                <li>Une fois que vous pensez avoir un programme qui répond à l'énoncé, vous n'avez qu'à soumettre votre code dans l'espace prévu à cet effet</li>
                <li>Le code sera exécuté sur le serveur, avec des données d'entrées aléatoire, et si la sortie est bonne, vous obtiendrez votre flag !</li>
            </ul>
            <div class="line"></div>
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