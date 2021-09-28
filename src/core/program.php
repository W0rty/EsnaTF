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
                <h3>ESNA Bootcamp - Programme</h3>
            </div>

            <ul class="list-unstyled components">
                <p>Salut <?=$res_user[0][1];?></p>
            </ul>

            <ul class="list-unstyled CTAs">
                <li>
                    <a href="/playground" class="download">Back to home</a>
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
                                <a class="nav-link" href="#">Programme des journées</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <h2>Programme des journées</h2>
            <div class="line"></div>
            <div class="text-center">
                <button class="btn btn-primary" id="mardi" onclick="changeDay('mardi')">Mardi</button>
                <button class="btn btn-primary" id="mercredi" onclick="changeDay('mercredi')">Mercredi</button>
                <div class="line"></div>
                <p id="msg" style="color: black;">Planning de Mardi</p>
            </div>
            <div class="line"></div>
            <div id="planningMardi">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Exercice</th>
                            <th scope="col">Intervenant(s)</th>
                            <th scope="col">8h30 -> 12h30</th>
                            <th scope="col">12h30 -> 14h</th>
                            <th scope="col">14h -> 17h</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">Principes Fondamentaux d'algorithmie</th>
                            <td>Noiche & Floczii</td>
                            <td>Cours</td>
                            <td>Repas</td>
                            <td>/</td>
                        </tr>
                        <tr>
                            <th scope="row">Le langage C</th>
                            <td>iHuggsy</td>
                            <td>Cours</td>
                            <td>Repas</td>
                            <td>Cours</td>
                        </tr>
                        <tr>
                            <th scope="row">Python & Java</th>
                            <td>Worty</td>
                            <td>Cours - Python</td>
                            <td>Repas</td>
                            <td>Cours- Java</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="planningMercredi">
            <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Exercice</th>
                            <th scope="col">Intervenant(s)</th>
                            <th scope="col">8h30 -> 12h30</th>
                            <th scope="col">12h30 -> 14h</th>
                            <th scope="col">14h -> 17h</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">Le langage C</th>
                            <td>iHuggsy</td>
                            <td>Cours</td>
                            <td>Repas</td>
                            <td>Cours</td>
                        </tr>
                        <tr>
                            <th scope="row">JavaScript POO</th>
                            <td>Sean & Sonata</td>
                            <td>Cours</td>
                            <td>Repas</td>
                            <td>Cours</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="line"></div>
            <div class="text-center">.:Happy Programming:.</div>
        </div>
    </div>
    <script type="text/javascript">
        $("#mardi").hide()
        $("#planningMercredi").hide()
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

        function changeDay(day)
        {
            if(day == "mardi")
            {
                                
                $("#mardi").hide()
                $("#mercredi").show();
                $("#planningMardi").show();
                $("#planningMercredi").hide();
                $("#msg").text("Planning de Mardi")
            }
            else
            {
                $("#mercredi").hide()
                $("#mardi").show();
                $("#planningMercredi").show()
                $("#planningMardi").hide();
                $("#msg").text("Planning de Mercredi")
            }
        }
    </script>
</body>
</html>
