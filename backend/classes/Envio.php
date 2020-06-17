<?php
    class Envio 
    {
        public function listarEnvios()
        {
            $link = Conexion::conectar();
            $sql = "SELECT idEnvio,env.cliente,ubicacion,telefono,env.estado,fecha,descripcionUbicacion,producto,email 
                    FROM envios AS env, ventas AS ven 
                    WHERE env.idVenta = ven.idVenta ";
            if(isset($_GET['inicio']) && isset($_GET['fin'])){
                $sql .= "AND fecha BETWEEN :inicio AND :fin ";
            }
            $sql .= "ORDER BY fecha DESC";
            $stmt = $link->prepare($sql);
            if(isset($_GET['inicio']) && !is_null($_GET['inicio']) && isset($_GET['fin']) && !is_null($_GET['fin'])){
                $stmt->bindParam(':inicio',$_GET['inicio'],PDO::PARAM_STR);
                $stmt->bindParam(':fin',$_GET['fin'],PDO::PARAM_STR);
            }
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $json = array();
            foreach ($result as $reg) {
                $json[] = array(
                    'idEnvio' => $reg['idEnvio'],
                    'cliente' => $reg['cliente'],
                    'ubicacion' => $reg['ubicacion'],
                    'telefono' => $reg['telefono'],
                    'estado' => $reg['estado'],
                    'fecha' => $reg['fecha'],
                    'descripcionUbicacion' => $reg['descripcionUbicacion'],
                    'producto' => $reg['producto'],
                    'email' => $reg['email'] 
                );
            }
            $jsonString = json_encode($json);
            return $jsonString;
        }

        public function agregarEnvio()
        {
            $link = Conexion::conectar();
            $idVenta = $_POST['idVenta'];
            $cliente = $_POST['cliente'];
            $ubicacion = $_POST['ubicacion'];
            $descripcionUbicacion = $_POST['descripcionUbicacion'];
            $telefono = $_POST['telefono'];
            $estado = $_POST['estado'];
            $email = 'No registrado';
            if (isset($_POST['email']) && !is_null($_POST['email']) && $_POST['email']!='') {
                $email = $_POST['email'];
            }
            $sql = "INSERT INTO envios (cliente,ubicacion,descripcionUbicacion,telefono,estado,idVenta,email)
                    VALUES (:cliente,:ubicacion,:descripcionUbicacion,:telefono,:estado,:idVenta,:email)";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':cliente',$cliente,PDO::PARAM_STR);
            $stmt->bindParam(':ubicacion',$ubicacion,PDO::PARAM_STR);
            $stmt->bindParam(':descripcionUbicacion',$descripcionUbicacion,PDO::PARAM_STR);
            $stmt->bindParam(':telefono',$telefono,PDO::PARAM_STR);
            $stmt->bindParam(':estado',$estado,PDO::PARAM_STR);
            $stmt->bindParam(':idVenta',$idVenta,PDO::PARAM_INT);
            $stmt->bindParam(':email',$email,PDO::PARAM_STR);
            $bool = $stmt->execute();
            if ($bool) {
                return json_encode(array('status'=>200,'info'=>'Envío registrado correctamente'));
            }
            return json_encode(array('status'=>400,'info'=>'Problemas al registrar el envío'));
        }

        public function eliminarEnvio()
        {
            $link = Conexion::conectar();
            $idEnvio = $_GET['idEnvio'];
            $sql = "DELETE FROM envios WHERE idEnvio = :idEnvio";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':idEnvio',$idEnvio,PDO::PARAM_INT);
            $bool = $stmt->execute();
            if ($bool) {
                return json_encode(true);
            }
            return json_encode(false);
        }

        public function entregarEnvio()
        {
            $link = Conexion::conectar();
            $idEnvio = $_GET['idEnvio'];
            $sql = "UPDATE envios SET estado = 'Entregado' WHERE idEnvio = :idEnvio";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':idEnvio',$idEnvio,PDO::PARAM_INT);
            $bool = $stmt->execute();
            if ($bool) {
                return json_encode(true);
            }
            return json_encode(false);
        }

        ########################### BALANCE ###########################

        public function obtenerEnviosSinEntregar()
        {
            $link = Conexion::conectar();
            $sql = "SELECT count(idEnvio) AS envios_sin_entregar 
                    FROM envios WHERE estado = 'Sin entregar'";
            $stmt = $link->prepare($sql);
            if ($stmt->execute()) {
                $json = array();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($resultado as $envios) {
                    $json[] = array(
                        'envios_sin_entregar' => $envios['envios_sin_entregar']
                    );
                }
                return json_encode($json);
            }
            return json_encode(array('status'=>400,'info'=>'problemas al ejecutar la consulta'));
        }
    }
    
?>