<?php

    class Usuario
    {
        public function login()
        {
            $usuario = $_POST['username'];
            $pw = $_POST['pw'];
            $link = Conexion::conectar();
            $sql = "SELECT nombre,usuario,superUser FROM usuarios WHERE usuario = :usuario AND pw = :pw";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
            $stmt->bindParam(':pw', $pw, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $cantidad = $stmt->rowCount();
            $json = array();
            if ($cantidad == 0) {
                return json_encode(false);
            }else{
                $_SESSION['login']=0;
                foreach ($result as $usuario) {
                    $json[] = array(
                        'usuario' => $usuario['usuario'],
                        'superUser' => $usuario['superUser']
                    );
                }
                if ($json[0]['superUser'] == 1) {
                    $_SESSION['login']=1;   
                }
                $_SESSION['usuName'] = $json[0]['usuario'];
                return json_encode($_SESSION['usuName']);
            }
        }

        public function listarUsuario()
        {
            $link = Conexion::conectar();
            $usuario = $_SESSION['usuName'];
            $sql = "SELECT nombre,usuario FROM usuarios WHERE usuario = :usuario";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':usuario',$usuario,PDO::PARAM_STR);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            $json = array();
            $json[] = array(
                'nombre' => $resultado['nombre'],
                'usuario' => $resultado['usuario']
            );
            $jsonString = json_encode($json);
            return $jsonString;
        }

        public function logout()
        {
            session_unset(); //limpia las variables de session
            session_destroy(); //borra la sesion
            return json_encode(true);
        }
    }
