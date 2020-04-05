<?php

    class Pedido 
    {
        public function listarPedidos()
        {
            $link = Conexion::conectar();
            $sql = "SELECT idPedido,descripcion,cantidad,p.estado,total,proveedor 
                    FROM pedidos p, proveedor pr where p.idProveedor = pr.idProveedor ORDER BY idPedido DESC";
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
                    'total' => $reg['total'],
                    'proveedor' => $reg['proveedor']
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
            $idProveedor = $_POST['idProveedor'];
            $estado = $_POST['estado'];
            $total = $_POST['total'];
            if (!is_null($_POST['total'] && $_POST['total']!='')) {
                $total = $_POST['total'];
            }
            $sql = "INSERT INTO pedidos (descripcion,cantidad,estado,total,idProveedor) VALUES (:descripcion,:cantidad,:estado,:total,:idProveedor)";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':descripcion',$descripcion,PDO::PARAM_STR);
            $stmt->bindParam(':cantidad',$cantidad,PDO::PARAM_INT);
            $stmt->bindParam(':estado',$estado,PDO::PARAM_STR);
            $stmt->bindParam(':total',$total,PDO::PARAM_INT);
            $stmt->bindParam(':idProveedor',$idProveedor,PDO::PARAM_INT);
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
            $total = $_GET['total'];
            $sql = "UPDATE pedidos SET estado = 'Recibido',
                                       total = :total
                    WHERE idPedido = :idPedido";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':total',$total,PDO::PARAM_INT);
            $stmt->bindParam(':idPedido',$idPedido,PDO::PARAM_INT);
            $bool = $stmt->execute();
            if ($bool) {
                return json_encode(array('status'=>200,'info'=>'Pedido actualizado'));
            }
            return json_encode(array('status'=>400,'info'=>'Problemas al actualizar los datos del pedido'));
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
    