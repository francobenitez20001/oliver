<?php
    class Deudor 
    {
        public function listarDeudor()
        {
            $link = Conexion::conectar();
            $sql = "SELECT * FROM deudores";
            $stmt = $link->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $json = array();
            foreach ($result as $reg) {
                $json[] = array(
                    'idDeudor' => $reg['idDeudor'],
                    'cliente' => $reg['cliente'],
                    'fecha' => $reg['fecha'],
                    'descripcion' => $reg['descripcion'],
                    'total' => $reg['total'],
                    'estado' => $reg['estado']
                );
            }
            $jsonString = json_encode($json);
            return $jsonString;
        }

        public function agregarDeudor()
        {
            $link = Conexion::conectar();
            $cliente = $_POST['cliente'];
            $descripcion = $_POST['descripcion'];
            $total = $_POST['total'];
            $fecha = $_POST['fecha'];
            $estado = $_POST['estado'];
            $sql = "INSERT INTO deudores (cliente,fecha,descripcion,total,estado)
                    VALUES (:cliente,:fecha,:descripcion,:total,:estado)";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':cliente',$cliente,PDO::PARAM_STR);
            $stmt->bindParam(':fecha',$fecha,PDO::PARAM_STR);
            $stmt->bindParam(':descripcion',$descripcion,PDO::PARAM_STR);
            $stmt->bindParam(':total',$total,PDO::PARAM_INT);
            $stmt->bindParam(':estado',$estado,PDO::PARAM_STR);
            $bool = $stmt->execute();
            if ($bool) {
                return json_encode(true);
            }
            return json_encode(false);
        }

        public function eliminarDeudor()
        {
            $link = Conexion::conectar();
            $idDeudor = $_GET['idDeudor'];
            $sql = "DELETE FROM deudores WHERE idDeudor = :idDeudor";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':idDeudor',$idDeudor,PDO::PARAM_INT);
            $bool = $stmt->execute();
            if ($bool) {
                return json_encode(true);
            }
            return json_encode(false);
        }

        public function saldarDeudor()
        {
            $link = Conexion::conectar();
            $idDeudor = $_GET['idDeudor'];
            $sql = "UPDATE deudores SET estado = 'Pagado' WHERE idDeudor = :idDeudor";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':idDeudor',$idDeudor,PDO::PARAM_INT);
            $bool = $stmt->execute();
            if ($bool) {
                return json_encode(true);
            }
            return json_encode(false);
        }

        ###################### BALANCE ######################
        
        public function obtenerDeudores()
        {
            $link = Conexion::conectar();
            $sql = "SELECT count(idDeudor) AS deudores_por_venta 
                    FROM deudores WHERE estado = 'debe'";
            $stmt = $link->prepare($sql);
            if ($stmt->execute()) {
                $json = array();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($resultado as $deudores) {
                    $json[] = array(
                        'deudores' => $deudores['deudores_por_venta']
                    );
                }
                return json_encode($json);
            };
            return json_encode(array('status'=>400,'info'=>'problemas al ejecutar la consulta'));
        }
    }
    
?>