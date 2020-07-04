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
             'mysql:host=127.0.0.1;dbname=oliver',
             'root',
             'oliverpetshop2020',
             $opciones
             );
       $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); //reporte de errores
       return $link;
     }
   }


?>