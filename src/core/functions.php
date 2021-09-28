<?php
function generateRandomString($length = 10) {
    $characters = 'abcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function run_python_code($filename)
{
    $res = shell_exec("cd /var/www/html/exercice/ && sudo ./run_docker_python.sh ".$filename." ".generateRandomString());
    return $res;
}

function run_java_code($filename,$exo_name)
{
    $output = "";
    $err = shell_exec("cd /var/www/html/exercice/users/ && javac ".$filename.".java");
    if(!$err)
    {
        shell_exec("rm /var/www/html/exercice/users/".$filename);
        $res = shell_exec("cd /var/www/html/exercice/ && sudo ./run_docker_java.sh \"".$filename.".java\" "." \"".$exo_name."\" \"".$filename."\" ".generateRandomString());
        $output = $res;
    }else $output = $err;
    shell_exec("rm /var/www/html/exercice/users/".$filename.".java");
    return $output;
}

function run_c_code($filename,$exo_name)
{
    $res = shell_exec("cd /var/www/html/exercice/ && sudo ./run_docker_c.sh ".$filename.".c \"".$exo_name."\" ".generateRandomString());
    return $res;
}

function create_secure_code($code)
{
    $rand_filename = rand();
    file_put_contents("/var/www/html/exercice/users/".$rand_filename.".py",$code);
    return run_python_code($rand_filename.".py");
}

function create_secure_code_java($code,$exo_name)
{
    $rand_filename = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"),0,15);
    $className = explode("{",explode("public ",$code)[1])[0];
    $code = str_replace($className,"class ".$rand_filename,$code);
    file_put_contents("/var/www/html/exercice/users/".$rand_filename.".java",$code);
    return run_java_code($rand_filename,$exo_name);
}

function create_secure_code_c($code,$exo_name)
{
    $rand_filename = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"),0,15);
    file_put_contents("/var/www/html/exercice/users/".$rand_filename.".c",$code);
    return run_c_code($rand_filename,$exo_name);
}