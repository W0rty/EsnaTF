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

function get_data($name,$db)
{
    $sth = $db->prepare("SELECT * FROM ".$name);
    $sth->execute();
    return $sth->fetchAll();
}

$allExos = [];
$exercice_python = [];
$exercice_java = [];
$exercice_c = [];

$java_data = get_data("exercice_java",$db);
$python_data = get_data("exercice_python",$db);
$c_data = get_data("exercice_c",$db);

foreach($java_data as $jd)
{
    array_push($allExos,$jd);
    array_push($exercice_java,$jd);
}

foreach($python_data as $pd)
{
    array_push($allExos,$pd);
    array_push($exercice_python,$pd);
}

foreach($c_data as $cd)
{
    array_push($allExos,$cd);
    array_push($exercice_c,$cd);
}

if($_SERVER["REQUEST_METHOD"] === "POST")
{
    if(isset($_FILES["exercice"]))
    {
        $exercice = json_decode(file_get_contents($_FILES["exercice"]["tmp_name"]));
        $success = True;
        if($exercice !== NULL){
            if(!empty($exercice->id) && is_numeric($exercice->id)){
                if(!empty($exercice->langage)){
                    if(in_array($exercice->langage,$languages,true)){
                        $table = real_escape_string($exercice->langage);
                        $sth = $db->prepare("UPDATE ".$table." SET enonce = :e, reponse = :resp, titre = :t, ajout_script = :aj, remote = :remote, flag = :flag WHERE id = :i");
                        $sth->bindValue(":i",$exercice->id);
                        if(!empty($exercice->enonce) && !empty($exercice->titre))
                        {
                            $sth->bindValue(":e",real_escape_string($exercice->enonce));
                            $sth->bindValue(":t",real_escape_string($exercice->titre));
                            if(!empty($exercice->reponse))
                            {
                                $sth->bindValue(":resp",real_escape_string($exercice->reponse));
                                $sth->bindValue(":aj",real_escape_string($exercice->ajout));
                                $sth->bindValue(":remote","");
                                $sth->bindValue(":flag","");
                                $sth->execute();
                            }else{
                                if(!empty($exercice->remote))
                                {
                                    if(!empty($exercice->flag))
                                    {
                                        $sth->bindValue(":resp","");
                                        $sth->bindValue(":aj","");
                                        $sth->bindValue(":remote",real_escape_string($exercice->remote));
                                        $sth->bindValue(":flag",real_escape_string($exercice->flag));
                                        $sth->execute();
                                    }else{
                                        $msgError = "Il manque le flag permettant d'attester que l'utilisateur a réussi l'exercice.";
                                        $success = False;
                                    }
                                }else{
                                    $msgError = "Plusieurs champs requis sont vides, veuillez vérifier votre fichier JSON.";
                                    $success = False;
                                }
                            }
                        }else{
                            $msgError = "Un des champs parmis 'description, titre' est vide.";
                            $success = False;
                        }
                    }else{
                        $msgError =  "Le langage utilisé n'est pas connu dans la base de données, contactez Worty ou remplacer le si vous vous êtes trompé.";
                        $success = False;
                    }
                }else{
                    $msgError = "Le langage de programmation utilisé est manquant.";
                    $success = False;
                }
            }else{
                $msgError = "L'id est manquant ou a été modifié.";
                $success = False;
            }
            if($success) $msg = "L'exercice a été modifié avec succès !";
        }else $msgError = "Impossible de parser le fichier JSON fourni.";
    }
}
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
    <script src="/core/admin94386917542/assets/js/modify.js"></script>
    <?php echo('<script>storeExosJava('.json_encode($exercice_java).')</script>'); ?>
    <?php echo('<script>storeExosPython('.json_encode($exercice_python).')</script>'); ?>
    <?php echo('<script>storeExosC('.json_encode($exercice_c).')</script>'); ?>
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
                            <a href="/admin/users/show">Afficher tous les utilisateurs</a>
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
                            <a href="#">Modifier un exercice</a>
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
            <p>Modifier un exercice de la plateforme</p>
            <select id="selectUser" onchange="downloadExos()" class="form-select">
                <option selected>Choisissez un exercice</option>
                <?php foreach($allExos as $exo){
                    echo('<option value="'.$exo[4].'">'.$exo[4].'</option>');
                } ?>
            </select>
            <div class="container">
                <div class="row">
                    <div class="col d-flex justify-content-center">
                        <div id="exosInformations" >
                            <form method="POST" enctype="multipart/form-data" id="form">
                                <?php if(isset($msgError)) echo('<p class="lead" style="color: red;" id="msgerror">'.$msgError.'</p>'); ?>
                                <?php if(isset($msg)) echo('<p class="lead" style="color: green;" id="msgconfirm">'.$msg.'</p>'); ?>
                                <div class="form-group">
                                    <label for="exercice">Modifier un exercice</label>
                                    <input type="file" accept=".json" id="exercice" name="exercice" class="form-control-file">
                                </div>
                                <button class="btn btn-info 1" id="import"><i class="fas fa-arrow-circle-right"></i>Modifier l'exercice</button>
                            </form>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
    <?php if(!isset($msg) && !isset($msgError)){ ?>
        <script>$("#exosInformations").hide();</script>
    <?php } ?>
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