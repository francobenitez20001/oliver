<?php

    class Pedido 
    {
        public function listarPedidos()
        {
            $link = Conexion::conectar();
            $sql = "SELECT * FROM pedidos";
            $stmt = $link->prepare($sql);
            $stmt->execute();
            $json = array();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($resultado as $reg) {
                $json[] = array(
                    'idPedido' => $reg['idPedido'],
                    'descripcion' => $reg['descripcion'],
                    'cantidad' => $reg['cantidad'],
                    'estado' => $reg['estado'],
                    'total' => $reg['total']
                );
            }
            $jsonString = json_encode($json);
            return $jsonString;
        }

        public function agregarPedido()
        {
            $link = Conexion::conectar();
            $descripcion = $_POST['descripcion'];
            $cantidad = $_POST['cantidad'];
            $estado = $_POST['estado'];
            $total = $_POST['total'];
            $sql = "INSERT INTO pedidos (descripcion,cantidad,estado,total) VALUES (:descripcion,:cantidad,:estado,:total)";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':descripcion',$descripcion,PDO::PARAM_STR);
            $stmt->bindParam(':cantidad',$cantidad,PDO::PARAM_INT);
            $stmt->bindParam(':estado',$estado,PDO::PARAM_STR);
            $stmt->bindParam(':total',$total,PDO::PARAM_INT);
            $bool = $stmt->execute();
            if ($bool) {
                return json_encode(true);
            }
            return json_encode(false);
        }

        public function recibirPedido()
        {
            $link = Conexion::conectar();
            $idPedido = $_GET['idPedido'];
            $sql = "UPDATE pedidos SET estado = 'Recibido' WHERE idPedido = :idPedido";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':idPedido',$idPedido,PDO::PARAM_INT);
            $bool = $stmt->execute();
            if ($bool) {
                return json_encode(true);
            }
            return json_encode(false);
        }

        public function eliminarPedido()
        {
            $idPedido = $_GET['idPedido'];
            $link = Conexion::conectar();
            $sql = "DELETE FROM pedidos WHERE idPedido = :idPedido";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':idPedido',$idPedido,PDO::PARAM_INT);
            $bool = $stmt->execute();
            if ($bool) {
                return json_encode(true);
            }
            return json_encode(false);
        }

    }
    