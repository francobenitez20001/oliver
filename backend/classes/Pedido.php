<?php

    class Pedido 
    {
        public function listarPedidos()
        {
            $link = Conexion::conectar();
            $sql = "SELECT idPedido,descripcion,cantidad,p.estado,total,proveedor,fecha 
                    FROM pedidos p, proveedor pr where p.idProveedor = pr.idProveedor ";
            if(isset($_GET['inicio']) && !is_null($_GET['inicio']) && isset($_GET['fin']) && !is_null($_GET['fin'])){
                $sql .= "AND fecha BETWEEN :inicio AND :fin ORDER BY fecha DESC";
            }else{
                $sql .= "ORDER BY idPedido DESC";
            };
            $stmt = $link->prepare($sql);
            if(isset($_GET['inicio']) && !is_null($_GET['inicio']) && isset($_GET['fin']) && !is_null($_GET['fin'])){
                $stmt->bindParam(':inicio',$_GET['inicio'],PDO::PARAM_STR);
                $stmt->bindParam(':fin',$_GET['fin'],PDO::PARAM_STR);
            }
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
                    'proveedor' => $reg['proveedor'],
                    'fecha' => $reg['fecha']
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
            $fecha = $_POST['fecha'];
            if (!is_null($_POST['total'] && $_POST['total']!='')) {
                $total = $_POST['total'];
            }
            $sql = "INSERT INTO pedidos (descripcion,cantidad,estado,total,idProveedor,fecha) VALUES (:descripcion,:cantidad,:estado,:total,:idProveedor,:fecha)";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':descripcion',$descripcion,PDO::PARAM_STR);
            $stmt->bindParam(':cantidad',$cantidad,PDO::PARAM_INT);
            $stmt->bindParam(':estado',$estado,PDO::PARAM_STR);
            $stmt->bindParam(':total',$total,PDO::PARAM_INT);
            $stmt->bindParam(':idProveedor',$idProveedor,PDO::PARAM_INT);
            $stmt->bindParam(':fecha',$fecha,PDO::PARAM_STR);
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
                //obtener la descripcion del producto recientemente recibido para actualizar el stock
                $sql = "SELECT descripcion,cantidad FROM pedidos WHERE idPedido = ".$idPedido;
                $stmt = $link->prepare($sql);
                if ($stmt->execute()) {
                    $json = array();
                    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($resultado as $reg) {
                        $json[] = array(
                            'producto' => $reg['descripcion'],
                            'cantidad' => $reg['cantidad']
                        );
                    };
                    return json_encode(array('status'=>200,'info'=>'Pedido actualizado','producto'=>$json[0]['producto'],'cantidad'=>$json[0]['cantidad']));
                }
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

        ######################## BALANCE ########################

        public function obtenerMontoPedidos($criterio = null)//criterio es si filtra por dia o por mes. Si no es null, busca por mes
        {
            $link = Conexion::conectar();
            $fecha = $_GET['fecha'];
            $sql = "SELECT SUM(total) AS total_pedidos 
                    FROM pedidos WHERE estado = 'Recibido' and fecha = '". $fecha ."%'";
            if (!is_null($criterio) && $criterio!='') {
                $sql = "SELECT SUM(total) AS total_pedidos
                        FROM pedidos WHERE estado = 'Recibido' AND fecha LIKE '". $fecha ."%'";
            }
            $stmt = $link->prepare($sql);
            // $stmt->bindParam(':fecha',$fecha,PDO::PARAM_STR);
            if ($stmt->execute()) {
                $json = array();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($resultado as $pedido) {
                    $json[] = array(
                        'pedidos_total' => $pedido['total_pedidos']
                    );
                }
                return json_encode($json);
            };
            return json_encode(array('status'=>400,'info'=>'problemas al ejecutar la consulta'));
        }

        public function obtenerPedidosSinEntregar()
        {
            $link = Conexion::conectar();
            $sql = "SELECT count(idPedido) AS pedidos_sin_entregar 
                    FROM pedidos WHERE estado = 'No recibido'";
            $stmt = $link->prepare($sql);
            if ($stmt->execute()) {
                $json = array();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($resultado as $pedidos) {
                    $json[] = array(
                        'pedidos_sin_entregar' => $pedidos['pedidos_sin_entregar']
                    );
                }
                return json_encode($json);
            }
            return json_encode(array('status'=>400,'info'=>'problemas al ejecutar la consulta'));
        }

        public function listarPedidosLimit()
        {
            $link = Conexion::conectar();
            $sql = "SELECT descripcion,cantidad,total FROM pedidos order by fecha DESC LIMIT 3";
            $stmt = $link->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $json = array();
            foreach ($result as $venta) {
                $json[] = array(
                    'descripcion' => $venta['descripcion'],
                    'cantidad' => $venta['cantidad'],
                    'total' => $venta['total']
                );
            }
            return json_encode($json);
        }

    }
    