<?php

function obtenerBD(){
     
    $nombre_base_de_datos = "sensores";
    $usuario = "sensores";
    $contraseña = "4dm1n-5en5ores";

     
    try {

        $base_de_datos = new PDO('mysql:host=34.205.209.170:3306;dbname=' . $nombre_base_de_datos, $usuario, $contraseña);
        $base_de_datos->query("set names utf8;");
        $base_de_datos->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $base_de_datos->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        return $base_de_datos;
    } catch (Exception $e) {
        # Nota: ¡en la vida real no imprimas errores!
        exit("Error obteniendo BD: " . $e->getMessage());
        return null;
    }
}