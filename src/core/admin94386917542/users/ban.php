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

$sth = $db->prepare("SELECT id,username,isBan FROM users WHERE isAdmin=0");
$sth->execute();
$res_users = $sth->fetchAll();

function get_secured_password($mdp)
{
    for($i=0; $i<1000; $i++)
    {
        $mdp = hash("sha256",$mdp);
    }
    return $mdp;
}

if($_SERVER["REQUEST_METHOD"] === "POST")
{
    if(isset($_POST["id"]) && isset($_POST["username"]) && !empty($_POST["id"]) && !empty($_POST["username"]))
    {
        $key = array_search($_POST["id"],array_column($res_users,'id'));
        $save_username = "";
        $save_id = "";
        if($key !== False){
            $user_to_modify = $res_users[$key];
            $save_username = $user_to_modify[1];
            $save_id = $user_to_modify[0];
            if($user_to_modify[1] === $_POST["username"])
            {
                if(isset($_POST["deban"]))
                {
                    $sth = $db->prepare("UPDATE users SET isBan = 0, reasonBan = '' WHERE id = :id");
                    $sth->bindValue(":id",htmlspecialchars($_POST["id"]));
                    $sth->execute();
                    $deban = True;
                    $msg = "Le bannissement de ".$save_username." a été révoqué.";
                    $sth = $db->prepare("SELECT id,username,isBan FROM users WHERE isAdmin=0");
                    $sth->execute();
                    $res_users = $sth->fetchAll();
                }else{
                    if(isset($_POST["ban"]))
                    {
                        if(isset($_POST["reason"]) && !empty($_POST["reason"]))
                        {
                            $sth = $db->prepare("UPDATE users SET isBan=1, reasonBan = :rb WHERE id = :id");
                            $sth->bindValue(":rb",htmlspecialchars($_POST["reason"]));
                            $sth->bindValue(":id",htmlspecialchars($_POST["id"]));
                            $sth->execute();
                            $ban = True;
                            $msg = "L'utilisateur ".$save_username." a été banni.";
                            $sth = $db->prepare("SELECT id,username,isBan FROM users WHERE isAdmin=0");
                            $sth->execute();
                            $res_users = $sth->fetchAll();
                        }
                    }else{
                        unset($save_id);
                        unset($save_username);
                        $msgError = "Des champs n'ont pas été spécifié";
                    }
                }
            }else{
                unset($save_id);
                unset($save_username);
                $msgError = "Impossible de trouver l'utilisateur avec le couple id/username.";
            }
        }else{
            unset($save_id);
            unset($save_username);
            $msgError = "Impossible de trouver l'utilisateur avec cet id.";
        }
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
    <?php echo('<script>storeUsers('.json_encode($res_users).')</script>'); ?>
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
            <p>Choisissez l'utilisateur que vous souhaitez bannir / débannir.</p>
            <select id="selectUser" onchange="updateInfos()" class="form-select">
                <option selected>Choisissez un utilisateur</option>
                <?php foreach($res_users as $user){
                    echo('<option value="'.$user[0].'">'.$user[1].'</option>');
                } ?>
            </select>
            <div class="container">
                <div class="row">
                    <div class="col d-flex justify-content-center">
                    <?php if(isset($msgError)) echo('<p class="lead" style="color: red;" id="msgerror">'.$msgError.'</p>'); ?>
                        <div id="userInformations" >
                            <form method="POST">
                                <?php if(isset($msg)) echo('<p class="lead" style="color: green;">'.$msg.'</p>'); ?>
                                <div class="form-group">
                                    <?php if(isset($save_id)) { ?>
                                        <input type="text" class="form-control" name="id" id="id" value="<?=$save_id;?>" hidden readonly required>
                                    <?php }else{ ?>
                                        <input type="text" class="form-control" name="id" id="id" hidden readonly required>
                                    <?php } ?>
                                </div>
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <?php if(isset($save_username)) { ?>
                                        <input type="text" class="form-control" name="username" id="username" value="<?=$save_username;?>" readonly required>
                                    <?php }else{ ?>
                                        <input type="text" class="form-control" name="username" id="username" readonly required>
                                    <?php } ?>
                                </div>
                                <div class="form-check" id="bandiv">
                                    <input type="checkbox" class="form-check-input" id="ban" name="ban">
                                    <label class="form-check-label" for="ban">Bannir l'utilisateur</label>
                                </div>
                                <div class="form-group" id="reasondiv">
                                    <textarea rows="10" cols="30" id="reason" name="reason" aria-describedby="banHelp" placeholder="Utilisateur banni car..."></textarea>
                                    <small id="banHelp" class="form-text text-muted">Indiquez la raison du bannissement.</small>
                                </div>
                                <div class="form-check" id="debandiv">
                                    <input type="checkbox" class="form-check-input" id="deban" name="deban">
                                    <label class="form-check-label" for="deban">Annuler le bannissement de l'utilisateur</label>
                                </div>
                                <button class="btn btn-info 1" id="runcode"><i class="fas fa-arrow-circle-right"></i>Enregistrer les modifications</button><br/>
                            </form>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
    <?php if(!isset($save_username)){ ?>
        <script>$("#userInformations").hide(); $("#debandiv").hide(); $("#reasondiv").hide();</script>
    <?php } ?>
    <?php if(isset($ban)){ ?> <script>$("#bandiv").hide(); $("#reasondiv").hide()</script> <?php } ?>
    <?php if(isset($deban)){ ?> <script>$("#debandiv").hide();</script> <?php } ?>
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