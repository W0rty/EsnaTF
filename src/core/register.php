<?php
session_start();
if(isset($_SESSION["username"])){
    header("Location: /playground");
    die();
}
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
    if(isset($_POST["username"]) && $_POST["password"] && !empty($_POST["username"]) && !empty($_POST["password"]))
    {
        require_once("connectdb.php");
        $sth = $db->prepare("SELECT * FROM users WHERE username = :u");
        $sth->bindValue(":u",htmlspecialchars($_POST["username"]));
        $sth->execute();
        if(sizeof($sth->fetchAll()) === 0){
            if(strlen($_POST["username"]) <= 50){
                if(strlen($_POST["password"]) >= 8)
                {
                        $sth = $db->prepare("INSERT INTO users(id,username,password,solved_python,isAdmin,isBan,reasonBan,solved_java,solved_c) VALUES(0,:u,:p,'',0,0,'','','')");
                        $sth->bindValue(":u",htmlspecialchars($_POST["username"]));
                        $sth->bindValue(":p",get_secured_password($_POST["password"]));
                        $sth->execute();
                        $msgConfirm = "Votre compte a bien été créé.";
                }else $msgError = "Le mot de passe doit faire plus de 8 caracètres.";
            }else $msgError = "Le nom d'utilisateur doit être inférueur à 50 caractères.";
        }else $msgError = "Un compte existe déjà avec ce nom d'utilisateur.";
    }else $msgError = "Paramètres manquants.";
}
?>
<!DOCTYPE HTML>
<head>
    <meta charset="utf-8">  
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <title>ESNA - Playground</title>
    <link rel="stylesheet" href="/assets/css/form.css"> 
</head>
<body>
    <div style="text-align: center; margin-top: 20px;"></div>
        <div class="wrapper fadeInDown">
        <p class="lead">Créez-vous un compte!</p>
            <?php if(isset($msgError)) echo '<p class="lead" style="color: red;">'.$msgError.'</p>';
                  if(isset($msgConfirm)) echo '<p class="lead" style="color: green;">'.$msgConfirm.'</p>'; ?>
            <div id="formContent">
                <form method="POST">
                    <input type="text" name="username" placeholder="Wortax" required>
                    <input type="password" name="password" placeholder="********" required>
                    <input type="submit" class="fourth btn" value="S'enregistrer">
                </form>
            </div>
            <p class="lead">Vous avez déjà un compte? <a href="/login">Connectez-vous.</a></p>
        </div>
    </div>
</body>
</html>
