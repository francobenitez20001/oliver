<?php

  session_start();

  function autocarga($clase)
  {
    if (!isset($_SESSION['logueado'])){
      echo json_encode(array('status'=>403,'info'=>'Authentication Error'));
      return false;
    }
    require_once 'classes/'.$clase.'.php';
  }

  spl_autoload_register('autocarga');