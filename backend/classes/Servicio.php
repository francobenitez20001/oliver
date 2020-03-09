<?php

    class Servicio 
    {
        private $idServicio;
        private $servicioNombre;
        private $fecha;
        private $total;

        public function listarServicio()
        {
            $link = Conexion::conectar();
            $sql = "SELECT * FROM servicios ORDER BY idServicio DESC";
            $stmt = $link->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $json = array();
            foreach ($result as $reg) {
                $json[] = array(
                    'idServicio' => $reg['idServicio'],
                    'servicioNombre' => $reg['servicioNombre'],
                    'fecha' => $reg['fecha'],
                    'estado' => $reg['estado'],
                    'total' => $reg['total']
                );
            };
            $jsonString = json_encode($json);
            return $jsonString;
        }

        public function saldarServicio()
        {
            $link = Conexion::conectar();
            $idServicio = $_GET['idServicio'];
            $sql = "UPDATE servicios SET estado = 'Pago' WHERE idServicio = :idServicio";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':idServicio',$idServicio,PDO::PARAM_INT);
            $result = $stmt->execute();
            if ($result) {
                return json_encode(true);
            }
            return json_encode(false);
        }

        public function eliminarServicio()
        {
            $link = Conexion::conectar();
            $idServicio = $_GET['idServicio'];
            $sql = "DELETE FROM servicios WHERE idServicio = :idServicio";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':idServicio',$idServicio,PDO::PARAM_INT);
            $result = $stmt->execute();
            if ($result) {
                return json_encode(true);
            }
            return json_encode(false);
        }

        public function agregarServicio()
        {
            $servicioNombre = $_POST['servicioNombre'];
            $fecha = $_POST['fecha'];
            $estado = $_POST['estado'];
            $total = $_POST['total'];
            $link = Conexion::conectar();
            $sql = "INSERT INTO servicios (servicioNombre,fecha,estado,total)
                    VALUES (:servicioNombre,:fecha,:estado,:total)";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':servicioNombre',$servicioNombre,PDO::PARAM_STR);
            $stmt->bindParam(':fecha',$fecha,PDO::PARAM_STR);
            $stmt->bindParam(':estado',$estado,PDO::PARAM_STR);
            $stmt->bindParam(':total',$total,PDO::PARAM_INT);
            $response = $stmt->execute();
            if ($response) {
                return json_encode(true);
            }
            return json_encode(false);
        }

    }
    