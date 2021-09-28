<?php
session_start();
?>
<!DOCTYPE HTML>
<head>
    <meta charset="utf-8">  
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <title>ESNA - Playground</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col text-center" style="margin-top: 30%">
                <p class="lead">Bienvenue sur le playground de l'ESNA</p>
                <?php if(!isset($_SESSION["username"])) { ?>
                <a href="/login" class="btn btn-primary">Se connecter</a>
                <a href="/register" class="btn btn-primary">S'enregister</a>
                <?php }else{ ?>
                <a href="/playground" class="btn btn-primary">Accéder au playground</a>
                <?php } ?>
            </div>
        </div>
    </div>
</body>
</html>