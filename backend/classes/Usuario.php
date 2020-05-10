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
            if(isset($_GET['tipo']) && $_GET['tipo'] == 'todos'){
                $sql = "SELECT idUsuario,nombre,usuario,pw,superUser FROM usuarios";
            }
            $stmt = $link->prepare($sql);
            if(!isset($_GET['tipo'])){
                $stmt->bindParam(':usuario',$usuario,PDO::PARAM_STR);   
            }
            $stmt->execute();
            $json = array();
            if(isset($_GET['tipo'])){
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($resultado as $user) {
                    $json[] = array(
                        'idUsuario' => $user['idUsuario'],
                        'nombre'=>$user['nombre'],
                        'usuario' => $user['usuario'],
                        'pw' => $user['pw'],
                        'superUser' => $user['superUser']
                    );
                }
                return json_encode($json);
            }
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            $json[] = array(
                'nombre' => $resultado['nombre'],
                'usuario' => $resultado['usuario']
            );
            $jsonString = json_encode($json);
            return $jsonString;
        }

        public function listarUsuarioPorId()
        {
            $link = Conexion::conectar();
            $idUsuario = $_GET['idUsuario'];
            $sql = "SELECT * FROM usuarios WHERE idUsuario = :idUsuario";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':idUsuario',$idUsuario,PDO::PARAM_INT);
            $stmt->execute();
            $json = array();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($resultado as $user) {
                $json[] = array(
                    'idUsuario' => $user['idUsuario'],
                    'nombre'=>$user['nombre'],
                    'usuario' => $user['usuario'],
                    'pw' => $user['pw'],
                    'superUser' => $user['superUser']
                );
            };
            return json_encode($json); 
        }

        public function logout()
        {
            session_unset(); //limpia las variables de session
            session_destroy(); //borra la sesion
            return json_encode(true);
        }

        public function agregarUsuario()
        {
            $link = Conexion::conectar();
            $nombre = $_POST['nombre'];
            $usuario = $_POST['usuario'];
            $pw = $_POST['pw'];
            $superUser = $_POST['superUser'];
            $sql = "INSERT INTO usuarios (nombre,usuario,pw,superUser) VALUES (:nombre,:usuario,:pw,:superUser)";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':nombre',$nombre,PDO::PARAM_STR);
            $stmt->bindParam(':usuario',$usuario,PDO::PARAM_STR);
            $stmt->bindParam(':pw',$pw,PDO::PARAM_STR);
            $stmt->bindParam(':superUser',$superUser,PDO::PARAM_INT);
            if ($stmt->execute()) {
                return json_encode(array('status'=>200,'info'=>'Se agrego el usuario con éxito'));
            }else{
                return json_encode(array('status'=>400,'info'=>'Problemas al cargar el usuario'));
            }
        }

        public function eliminarUsuario()
        {
            $link = Conexion::conectar();
            $idUsuario = $_GET['idUsuario'];
            $sql = "DELETE FROM usuarios WHERE idUsuario = :idUsuario";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':idUsuario',$idUsuario,PDO::PARAM_INT);
            if ($stmt->execute()) {
                return json_encode(true);
            }else{
                return json_encode(false);
            }
        }

        public function modificarUsuario()
        {
            $nombre = $_POST['nombre'];
            $usuario = $_POST['usuario'];
            $pw = $_POST['pw'];
            $superUser = $_POST['superUser'];
            $idUsuario = $_POST['idUsuario'];
            $link = Conexion::conectar();
            $sql = "UPDATE usuarios SET nombre = :nombre,
                                        usuario = :usuario,
                                        pw = :pw,
                                        superUser = :superUser
                    WHERE idUsuario = :idUsuario";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':nombre',$nombre,PDO::PARAM_STR);
            $stmt->bindParam(':usuario',$usuario,PDO::PARAM_STR);
            $stmt->bindParam(':pw',$pw,PDO::PARAM_STR);
            $stmt->bindParam(':superUser',$superUser,PDO::PARAM_INT);
            $stmt->bindParam(':idUsuario',$idUsuario,PDO::PARAM_INT);
            if($stmt->execute()){
                return json_encode(array('status'=>200,'info'=>'Usuario modificado'));
            }
            return json_encode(array('status'=>400,'info'=>'Problemas al modificar el usuario'));
        }
    }
