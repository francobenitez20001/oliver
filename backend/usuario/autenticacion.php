<?php
  require '../classes/Conexion.php';
  require '../classes/Usuario.php';
  session_start();//COMIENZA LA SESION
  if (!isset($_SESSION['login'])) {//SI NO EXISTE LA SESSION LOGIN..
    echo json_encode(false);
  }else{
    if(isset($_GET['admin']) && $_GET['admin']==true){
      if($_SESSION['login']==1){
        echo json_encode(array('login'=>true,'session'=>$_SESSION['login']));
      }else{
        echo json_encode(array('login'=>false,'info'=>'No tienes los permisos para acceder a esta pagina'));
      }
    }else{
      echo json_encode(array('login'=>true,'session'=>$_SESSION['login']));
    }
  }
?>
