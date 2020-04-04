<?php
    class Envio 
    {
        public function listarEnvios()
        {
            $link = Conexion::conectar();
            $sql = "SELECT * FROM envios";
            $stmt = $link->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $json = array();
            foreach ($result as $reg) {
                $json[] = array(
                    'idEnvio' => $reg['idEnvio'],
                    'cliente' => $reg['cliente'],
                    'producto' => $reg['producto'],
                    'ubicacion' => $reg['ubicacion'],
                    'telefono' => $reg['telefono'],
                    'estado' => $reg['estado'],
                    'fecha' => $reg['fecha'],
                    'descripcionUbicacion' => $reg['descripcionUbicacion'] 
                );
            }
            $jsonString = json_encode($json);
            return $jsonString;
        }

        public function agregarEnvio()
        {
            $link = Conexion::conectar();
            $cliente = $_POST['cliente'];
            $fecha = $_POST['fecha'];
            $producto = $_POST['producto'];
            $ubicacion = $_POST['ubicacion'];
            $descripcionUbicacion = $_POST['descripcionUbicacion'];
            $telefono = $_POST['telefono'];
            $estado = $_POST['estado'];
            $sql = "INSERT INTO envios (cliente,fecha,producto,ubicacion,descripcionUbicacion,telefono,estado)
                    VALUES (:cliente,:fecha,:producto,:ubicacion,:descripcionUbicacion,:telefono,:estado)";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':cliente',$cliente,PDO::PARAM_STR);
            $stmt->bindParam(':fecha',$fecha,PDO::PARAM_STR);
            $stmt->bindParam(':producto',$producto,PDO::PARAM_STR);
            $stmt->bindParam(':ubicacion',$ubicacion,PDO::PARAM_STR);
            $stmt->bindParam(':descripcionUbicacion',$descripcionUbicacion,PDO::PARAM_STR);
            $stmt->bindParam(':telefono',$telefono,PDO::PARAM_STR);
            $stmt->bindParam(':estado',$estado,PDO::PARAM_STR);
            $bool = $stmt->execute();
            if ($bool) {
                return json_encode(true);
            }
            return json_encode(false);
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
    }
    
?>