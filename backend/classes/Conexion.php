<?php

  /**
   *
   */
   class Conexion
   {
     static function conectar(){
       $opciones = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'); //cartacteres especiales

       $link = new PDO(
             //completar con los datos reales de xrargentina
             'mysql:host=localhost;dbname=oliver',
             'root',
             'villromance22',
             $opciones
             );
       $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); //reporte de errores
       return $link;
     }
   }


?>