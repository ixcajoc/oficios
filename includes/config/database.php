<?php

function conectarBD():mysqli{
    // $db = mysqli_connect('localhost','root','root','bienesraices_crud');
    $db = mysqli_connect('localhost','root','root','oficios');

    if(!$db){
        echo 'error, no se puede conectar a la base de datos';
        exit;
    }

    return $db;
}