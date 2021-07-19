<?php

    class Usuario
    {
        public function login(){
            $usuario = $_POST['username'];
            $pw = $_POST['pw'];
            $link = Conexion::conectar();
            $sql = "SELECT nombre,usuario,pw,superUser,idLocal FROM usuarios WHERE usuario = :usuario";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $cantidad = $stmt->rowCount();
            if ($cantidad == 0) {
                return json_encode(array(
                    "ok" => false,
                    "info" => "Usuario inexistente"
                ));
            }
            $user = $result[0];
            $verificarPass = password_verify($pw,$user['pw']);
            if(!$verificarPass){
                return json_encode(array(
                    "ok"=>false,
                    "info"=>"Contraseña incorrecta"
                ));
            }
            $_SESSION['logueado'] = 1;
            $_SESSION['user'] = array(
                "user_name" => $user['usuario'],
                "name" => $user['nombre'],
                "admin" => 0,
                "idLocal" => $user['idLocal']
            );
    
            if ($user['superUser'] == 1) {
                $_SESSION['user']['admin'] = 1;   
            }
            
            return json_encode(array(
                "ok" => true,
                "user" => $user['usuario']
            ));
        }

        public function listarUsuario(){
            $link = Conexion::conectar();
            $usuario = $_SESSION['user']['user_name'];
            $sql = "SELECT nombre,usuario FROM usuarios WHERE usuario = :usuario";
            if(isset($_GET['tipo']) && $_GET['tipo'] == 'todos'){
                $sql = "SELECT usu.idUsuario AS idUsuario,
                    usu.nombre AS nombre,
                    usu.usuario AS usuario,
                    usu.superUser AS superUser,
                    usu.idLocal as idLocal,
                CASE 
                    WHEN usu.idLocal is null THEN 'Sin local'
                    ELSE  loc.nombre  
                END AS local
                FROM usuarios AS usu
                LEFT JOIN locales AS loc ON usu.idLocal = loc.idLocal";
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
                        'superUser' => $user['superUser'],
                        'idLocal' => $user['idLocal'],
                        'local_usuario' => $user['local']
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

        public function listarUsuarioPorId(){
            $link = Conexion::conectar();
            $idUsuario = $_GET['idUsuario'];
            $sql = "SELECT idUsuario,nombre,idLocal,superUser FROM usuarios WHERE idUsuario = :idUsuario";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':idUsuario',$idUsuario,PDO::PARAM_INT);
            $stmt->execute();
            $json = array();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($resultado as $user) {
                $json[] = array(
                    'idUsuario' => $user['idUsuario'],
                    'nombre' => $user['nombre'],
                    'idLocal' => $user['idLocal'], 
                    'superUser' => $user['superUser']
                );
            };
            return json_encode($json); 
        }

        public function logout(){
            session_unset(); //limpia las variables de session
            session_destroy(); //borra la sesion
            return json_encode(true);
        }

        public function agregarUsuario(){
            $link = Conexion::conectar();
            $nombre = $_POST['nombre'];
            $usuario = $_POST['usuario'];
            $pw = password_hash($_POST['pw'],PASSWORD_DEFAULT);
            $superUser = $_POST['superUser'];
            $idLocal = $_POST['idLocal'];
            $sql = "INSERT INTO usuarios (nombre,usuario,pw,superUser,idLocal) VALUES (:nombre,:usuario,:pw,:superUser,:idLocal)";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':nombre',$nombre,PDO::PARAM_STR);
            $stmt->bindParam(':usuario',$usuario,PDO::PARAM_STR);
            $stmt->bindParam(':pw',$pw,PDO::PARAM_STR);
            $stmt->bindParam(':superUser',$superUser,PDO::PARAM_INT);
            $stmt->bindParam(':idLocal',$idLocal,PDO::PARAM_INT);
            if ($stmt->execute()) {
                return json_encode(array('status'=>200,'info'=>'Se agrego el usuario con éxito'));
            }else{
                return json_encode(array('status'=>400,'info'=>'Problemas al cargar el usuario'));
            }
        }

        public function eliminarUsuario(){
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

        public function modificarUsuario(){
            $nombre = $_POST['nombre'];
            $superUser = $_POST['superUser'];
            $idUsuario = $_POST['idUsuario'];
            $idLocal = $_POST['idLocal'];
            $link = Conexion::conectar();
            $sql = "UPDATE usuarios SET nombre = :nombre,
                                        superUser = :superUser,
                                        idLocal = :idLocal
                    WHERE idUsuario = :idUsuario";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':nombre',$nombre,PDO::PARAM_STR);
            $stmt->bindParam(':superUser',$superUser,PDO::PARAM_INT);
            $stmt->bindParam(':idLocal',$idLocal,PDO::PARAM_INT);
            $stmt->bindParam(':idUsuario',$idUsuario,PDO::PARAM_INT);
            if($stmt->execute()){
                return json_encode(array('status'=>200,'info'=>'Usuario modificado'));
            }
            return json_encode(array('status'=>400,'info'=>'Problemas al modificar el usuario'));
        }
    }
