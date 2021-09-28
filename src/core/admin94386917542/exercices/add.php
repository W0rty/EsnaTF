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
if($_SERVER["REQUEST_METHOD"] === "POST")
{
    if(isset($_FILES["exercices"]))
    {
        $data = json_decode(file_get_contents($_FILES["exercices"]["tmp_name"]));
        $i = 1;
        $success = True;
        if($data !== NULL){
            $authorize_table_name = ["python","java","c"];
            foreach($data->exercices as $exercice)
            {
                if(!empty($exercice->langage)){
                    if(in_array($exercice->langage,$authorize_table_name,true)){
                        $table = "exercice_".addslashes($exercice->langage);
                        $sth = $db->prepare("SELECT COUNT(*) FROM ".$table);
                        $sth->execute();
                        $res_numero_table = intval($sth->fetchAll()[0][0])+1;
                        $sth = $db->prepare("INSERT INTO ".$table."(id,numero,enonce,reponse,titre,ajout_script,remote,flag) VALUES(0,:n,:e,:resp,:t,:as,:remote,:f)");
                        if(!empty($exercice->description) && !empty($exercice->titre))
                        {
                            $sth->bindValue(":n",$res_numero_table);
                            $sth->bindValue(":e",addslashes($exercice->description));
                            $sth->bindValue(":t",addslashes($exercice->titre));
                            if($exercice->langage == "java" or !empty($exercice->reponse))
                            {
                                $sth->bindValue(":resp",addslashes($exercice->reponse));
                                $sth->bindValue(":as",addslashes($exercice->ajout));
                                $sth->bindValue(":remote","");
                                $sth->bindValue(":f","");
                                $sth->execute();
                            }else{
                                if(!empty($exercice->remote))
                                {
                                    if(!empty($exercice->flag))
                                    {
                                        $sth->bindValue(":resp","");
                                        $sth->bindValue(":as","");
                                        $sth->bindValue(":remote",addslashes($exercice->remote));
                                        $sth->bindValue(":f",addslashes($exercice->flag));
                                        $sth->execute();
                                    }
                                }
                            }
                            $i++;
                        }else{
                            $msgError = "Pour l'exercice n°".$i." (dans votre JSON), un des champs parmis 'langage, description, titre' est vide.";
                            $success = False;
                            break;
                        }
                    }else{
                        $msgError =  "Pour l'exercice n°".$i." (dans votre JSON), le langage utilisé n'est pas connu dans la base de données, contactez Worty ou remplacer le si vous vous êtes trompé.";
                        $success = False;
                        break;
                    }
                }else{
                    $msgError = "Pour l'exercice n°".$i." (dans votre JSON), le langage de programmation utilisé est manquant.";
                    $success = False;
                    break;
                }
            }
            if($success) $msg = "Tous vos exercices ont été importés et ajoutés dans la base de donnée avec succès!";
        }else $msgError = "Impossible de parser le fichier JSON fourni.";
    }
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
    <script src="/core/admin94386917542/assets/js/ban.js"></script>
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
                            <a href="#">Bannir/Débannir un utilisateur</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Exercices</a>
                    <ul class="collapse list-unstyled" id="pageSubmenu">
                        <li>
                            <a href="#">Ajouter un exercice</a>
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
            <p>Créer un / des exercice(s) !</p>
            <p>Pour upload des exercices, un template est disponible <a style="color: blue;" href="/admin/exercice/template" download>ici.</a></p>
            <p>Veuillez préter attention au respect du format du fichier JSON, sinon il sera impossible de le parser en backend.</p>
            <p>Note : Les champs spécifiés d'un 'optionnel' à la fin peuvent être laissés vides.</p>
            <div class="container">
                <div class="row">
                    <div class="col d-flex justify-content-center">
                            <form method="POST" enctype="multipart/form-data">
                                <?php if(isset($msgError)) echo('<p class="lead" style="color: red;" id="msgerror">'.$msgError.'</p>'); ?>
                                <?php if(isset($msg)) echo('<p class="lead" style="color: green;">'.$msg.'</p>'); ?>
                                <div class="form-group">
                                    <label for="exercices">Importer des exercices</label>
                                    <input type="file" accept=".json" id="exercices" name="exercices" class="form-control-file">
                                </div>
                                <button class="btn btn-info 1" id="import"><i class="fas fa-arrow-circle-right"></i>Impoter</button>
                            </form>
                    </div>
                </div>
            </div>
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
