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
             'mysql:host=us-cdbr-east-06.cleardb.net;dbname=heroku_04c4af04733e45a',
             'b603c64ae4d2ae',
             '90a42cd9',
             $opciones
             );
       $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); //reporte de errores
       return $link;
     }
   }


?>
