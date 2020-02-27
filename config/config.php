<?php

  session_start();

  function autocarga($clase)
  {
    require_once 'backend/classes/'.$clase.'.php';
    if (!isset($_SESSION['login'])){
        header('location: adminProductos.html');
    }
  }

  spl_autoload_register('autocarga');
