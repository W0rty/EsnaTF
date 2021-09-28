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

$sth = $db->prepare("SELECT * FROM users");
$sth->execute();
$res_users = $sth->fetchAll();

$sth = $db->prepare("SELECT COUNT(*) FROM exercice_python");
$sth->execute();
$res_ex_python = $sth->fetchAll()[0][0];

$sth = $db->prepare("SELECT COUNT(*) FROM exercice_java");
$sth->execute();
$res_ex_java = $sth->fetchAll()[0][0];
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
    <title>Admin - Playground</title>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>Bootcamp - Admin</h3>
            </div>

            <ul class="list-unstyled components">
                <p>Admin <?=$res[0][1];?></p>
                <li class="active">
                    <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Utilisateurs</a>
                    <ul class="collapse list-unstyled" id="homeSubmenu">
                        <li>
                            <a href="#">Afficher tous les utilisateurs</a>
                        </li>
                        <li>
                            <a href="/admin/users/modify">Modifier un utilisateur</a>
                        </li>
                        <li>
                            <a href="/admin/users/ban">Bannir/Débannir un utilisateur</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Exercices</a>
                    <ul class="collapse list-unstyled" id="pageSubmenu">
                        <li>
                            <a href="/admin/exercice/add">Ajouter un exercice</a>
                        </li>
                        <li>
                            <a href="/admin/exercice/modify">Modifier un exercice</a>
                        </li>
                        <li>
                            <a href="/admin/exercice/delete">Supprimer un exercice</a>
                        </li>
                    </ul>
                </li>
            </ul>

            <ul class="list-unstyled CTAs">
                <li>
                    <a href="/admin" class="article">Back to admin panel</a>
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
                                <a class="nav-link" href="#">Administration Panel</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <h2>Administration Panel - ESNA Bootcamp</h2>
            <p>Utilisateurs de la plateforme</p>
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">id</th>
                        <th scope="col">Pseudo</th>
                        <th scope="col">Est il banni?</th>
                        <th scope="col">Python</th>
                        <th scope="col">Java</th>
                        <th scope="col">C</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for($i=0; $i<sizeof($res_users) ;$i++) { 
                        echo('<tr>');
                        echo('<th scope="row">'.$res_users[$i][0].'</th>');
                        echo('<td>'.$res_users[$i][1].'</th>');
                        if($res_users[$i][5] === "1") echo('<td onmouseover="alert(\'Raison du bannissement:\n'.htmlspecialchars($res_users[$i][6]).'\')">Oui</td>');
                        else echo('<td>Non</td>');
                        echo('<td>'.(sizeof(explode(",",$res_users[$i][3]))-1).'/'.$res_ex_python.'</td>');
                        echo('<td>'.(sizeof(explode(",",$res_users[$i][7]))-1).'/'.$res_ex_java.'</td>');
                        echo('<td>0/0</td>');
                    }
                    ?>
                </tbody>
            </table>
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