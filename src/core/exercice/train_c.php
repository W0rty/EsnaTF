<?php
session_start();

if(!isset($_SESSION["username"])){
    header("Location: /");
    die();
}
if(!isset($_GET["id"])){
    header("Location: /exercice/c");
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
$sth_ex = $db->prepare("SELECT * FROM exercice_c WHERE numero = :n");
$sth_ex->bindValue(":n",htmlspecialchars($_GET["id"]));
$sth_ex->execute();
$res_ex = $sth_ex->fetchAll();
if($res_ex){
    $hash = hash("sha256",rand());
    $sth_encours = $db->prepare("INSERT INTO en_cours(id,users,exercice_python,exercice_java,exercice_c,token) VALUES (0,:u,null,null,:e,:t)");
    $sth_encours->bindValue(":u",$res_user[0][0]);
    $sth_encours->bindValue(":e",$res_ex[0][0]);
    $sth_encours->bindValue(":t",$hash);
    $sth_encours->execute();
    $c_solves = explode(",",$res_user[0][8]);
    if(substr_count($res_user[0][8],",") > 0){
        $enable = [];
        $disable = [];
        $isNext = False;
        foreach($c_solves as $solve)
        {
            if($solve === $_GET["id"]){
                $message = "Vous avez déjà résolu cet exercice !";
            }
            if(intval($solve)+1 === intval($_GET["id"]))
            {
                $isNext = True;
            }
        }
        if(!isset($message)){
            if($isNext)
            {
                if($res_ex[0][6] != ""){
                    $host = explode(":",$res_ex[0][6])[0];
                    $port = explode(":",$res_ex[0][6])[1];
                }
                $ex_name = $res_ex[0][4];
                $ex_enonce = $res_ex[0][2];
                $ex_id = $res_ex[0][0];
            }else{
                header("Location: /exercice/c");
                die();
            }
        }
    }else{
        if(intval($_GET["id"]) !== 1)
        {
            header("Location: /exercice/c");
            die();
        }else{
            $ex_name = $res_ex[0][4];
            $ex_enonce = $res_ex[0][2];
            $ex_id = $res_ex[0][0];
        }
    }
}else{
    header("Location: /exercice/c");
    die();  
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
    <title>ESNA - Playground</title>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>ESNA Bootcamp - C</h3>
            </div>

            <ul class="list-unstyled components">
                <p>Salut <?=$res_user[0][1];?></p>
                <li class="active">
                    <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Documentation</a>
                    <ul class="collapse list-unstyled" id="homeSubmenu">
                        <li>
                            <a href="/doc/c">C</a>
                        </li>
                    </ul>
                </li>
            </ul>

            <ul class="list-unstyled CTAs">
                <li>
                    <a href="/exercice/c" class="download">Back to C</a>
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
                                <a class="nav-link" href="#">C</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <?php if(!isset($message)){ ?>
                <h2><?=$ex_name;?></h2>
                <p><?php echo str_replace("\\","",$ex_enonce);?></p>
                <?php if(!isset($host)){ ?>
                <div class="text-center" id="divcode">
                    <button class="btn btn-info 1" id="runcode" onclick="execute()"><i class="fas fa-arrow-circle-right"></i>Exécuter le code</button><br/>
                    <textarea id="code" name="code" cols="70" rows="15" style="background-color: #2a2a2e; color: white;"></textarea>
                </div>
                <?php }else{ ?>
                <p class="lead" style="color: black;">Ce challenge se passe en remote, vous allez devoir utiliser une connexion TCP pour le réaliser.</p>
                <p>Paramètres de connexion au challenge:<p>
                    <ul>
                        <li>Host: <?=$host;?></li>
                        <li>Port: <?=$port;?></li>
                    </ul>
                <div class="text-center" id="divcenter">
                    <input type="text" name="hash" id="hash" value="<?=$hash;?>" hidden required readonly>
                    <div class="form-group">
                        <label for="flag">Flag:</label>
                        <input type="text" name="flag" id="flag" placeholder="ESNA{...}" size="30" required>
                    </div>
                    <button class="btn btn-info 1" onclick="sendFlag()"><i class="fas fa-arrow-circle-right"></i>Submit le flag</button>
                </div>
                <?php } ?>
            </div>
        </div>
        <script type="text/javascript">

            $(document).delegate('#code', 'keydown', function(e) {
            var keyCode = e.keyCode || e.which;

            if (keyCode == 9) {
                e.preventDefault();
                var start = this.selectionStart;
                var end = this.selectionEnd;

                $(this).val($(this).val().substring(0, start)
                            + "\t"
                            + $(this).val().substring(end));

                this.selectionStart =
                this.selectionEnd = start + 1;
            }
            });

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
            
            function sendFlag()
            {
                var hash = $("#hash").val();
                var flag = $("#flag").val();
                $("#msg").remove();
                $.ajax({
                    type: "POST",
                    url: "/api/flag",
                    data: {"hash":hash,"flag":flag},
                    success: function(data)
                    {
                        if(data["ok"]) $("#divcenter").append("<div id='msg'><br><br><p style='color: green;'>"+data["ok"]+"</p></div>")
                        if(data["error"]) $("#divcenter").append("<div id='msg'><br><br><p style='color: red;'>"+data["error"]+"</p></div>")
                    }
                })
            }
            function back_error()
            {
                $("#backtocode").remove()
                $("#code").show()
                $("#errorcode").remove()
                $("#runcode").show()
            }
            function execute()
            {
                var code = btoa($("#code").val())
                $("#msg").remove()
                $("#runcode").hide()
                $("#code").before('<div id="running" class="fa-1x"><p class="lead"><i class="fas fa-circle-notch fa-spin"></i> le code est en train d\'être exécuté...</p></div>')
                $.ajax({
                    method: "POST",
                    url: "/api/c/exercice",
                    data: {code: code, hash: "<?=$hash;?>"},
                    success: function(data){
                        $("#running").remove()
                        $("#runcode").show();
                        if(data["error"]) $("#runcode").after('<p id="msg" class="lead" style="color: red";>'+data["error"]+'</p>')
                        if(data["code_error"]){
                            $("#code").hide()
                            $("#runcode").hide()
                            $("#runcode").before('<button class="btn btn-info 1" id="backtocode" onclick="back_error()"><i class="fas fa-arrow-circle-right"></i>Retourner au code</button><br/>')
                            $("#code").after('<textarea id="errorcode" name="errorcode" cols="70" rows="15" style="background-color: #2a2a2e; color: white;"></textarea>')
                            $("#errorcode").val(atob(data["code_error"]))
                        }
                        if(data["valid"]) $("#runcode").after('<p id="msg" class="lead" style="color: green";>'+data["valid"]+'</p>')
                    },
                    error: function(data){
                        $("#running").remove();
                        $("#runcode").show();
                        $("#runcode").after('<p id="msg" class="lead" style="color: red";>Unexcepted error.</p>')
                    }
                })
            }
        </script>
        <?php }else{ ?>
        <p class="lead" style="color: green;"><?=$message;?></p>
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
        <?php } ?>
</body>
</html>
