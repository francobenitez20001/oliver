<?php

    class Pedido 
    {
        public function listarPedidos($limit=null){
            $link = Conexion::conectar();
            $mes = isset($_GET['mes']) ? $_GET['mes'] : null;
            $sql = "SELECT idPedido,descripcion,cantidad,p.estado,p.idProveedor,proveedor,fecha,comprobante 
                    FROM pedidos p, proveedor pr where p.idProveedor = pr.idProveedor ";
            if(isset($_GET['inicio']) && !is_null($_GET['inicio']) && isset($_GET['fin']) && !is_null($_GET['fin']
                    && !is_null($_GET['idProveedor']))){
                $sql .= "AND p.idProveedor = :idProveedor AND fecha BETWEEN :inicio AND :fin ORDER BY idPedido DESC ";
            }else{
                if(!is_null($mes)){
                    $sql .= "AND fecha LIKE '".$mes."%' ORDER BY idPedido DESC "; 
                };
            };

            if(!is_null($limit)){
                $sql .= "LIMIT :limit";
            }

            $stmt = $link->prepare($sql);
            if(isset($_GET['inicio']) && !is_null($_GET['inicio']) && isset($_GET['fin']) && !is_null($_GET['fin']) && !is_null($_GET['idProveedor'])){
                $stmt->bindParam(':inicio',$_GET['inicio'],PDO::PARAM_STR);
                $stmt->bindParam(':fin',$_GET['fin'],PDO::PARAM_STR);
                $stmt->bindParam(':idProveedor',$_GET['idProveedor'],PDO::PARAM_INT);
            }
            if(!is_null($limit)){
                $stmt->bindParam(':limit',$limit,PDO::PARAM_INT);
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
                    'idProveedor'=>$reg['idProveedor'],
                    'proveedor' => $reg['proveedor'],
                    'fecha' => $reg['fecha'],
                    'comprobante' => $reg['comprobante']
                );
            }
            $jsonString = json_encode($json);
            return $jsonString;
        }

        public function agregarPedido(){
            $link = Conexion::conectar();
            $descripcion = $_POST['descripcion'];
            $cantidad = $_POST['cantidad'];
            $idProveedor = $_POST['idProveedor'];
            $estado = $_POST['estado'];
            $sql = "INSERT INTO pedidos (descripcion,cantidad,estado,idProveedor) VALUES (:descripcion,:cantidad,:estado,:idProveedor)";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':descripcion',$descripcion,PDO::PARAM_STR);
            $stmt->bindParam(':cantidad',$cantidad,PDO::PARAM_INT);
            $stmt->bindParam(':estado',$estado,PDO::PARAM_STR);
            // $stmt->bindParam(':total',$total,PDO::PARAM_INT);
            $stmt->bindParam(':idProveedor',$idProveedor,PDO::PARAM_INT);
            $bool = $stmt->execute();
            if ($bool) {
                return json_encode(true);
            }
            return json_encode(false);
        }

        public function modificarPedido()
        {
            $cantidad = $_POST['cantidadMonto'];
            $idPedido = $_POST['idPedido'];
            $link = Conexion::conectar();
            $sql = "UPDATE pedidos SET cantidad = :cantidad WHERE idPedido = :id";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':id',$idPedido,PDO::PARAM_INT);
            $stmt->bindParam(':cantidad',$cantidad,PDO::PARAM_INT);
            if($stmt->execute()){
                return json_encode(array('status'=>200,'info'=>'Pedido modificado'));
            }
            return json_encode(array('status'=>400,'info'=>'Problemas al modificar el pedido'));
        }

        public function recibirPedido()
        {
            $link = Conexion::conectar();
            $idPedido = $_POST['idPedido'];
            $cantidad = $_POST['cantidadFinal'];
            $sql = "UPDATE pedidos SET estado = 'Recibido',
                                       cantidad = :cantidad
                    WHERE idPedido = :idPedido";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':idPedido',$idPedido,PDO::PARAM_INT);
            $stmt->bindParam(':cantidad',$cantidad,PDO::PARAM_STR);
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

        public function saldarDeudaConPedido()
        {
            $link = Conexion::conectar();
            $idPedido = $_POST['idPedido'];
            $pago = $_POST['pago'];
            $sql = "UPDATE pedidos SET pagado = pagado + :pagado
                    WHERE idPedido = :idPedido";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':idPedido',$idPedido,PDO::PARAM_INT);
            $stmt->bindParam(':pagado',$pago,PDO::PARAM_INT);
            $bool = $stmt->execute();
            if($bool){
                return json_encode(array('status'=>200,'info'=>'Se actualizo la informacion del pedido'));
            }
            return json_encode(array('status'=>400,'info'=>'Operacion fallida'));
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

        public function verPedidoPorId()
        {
            $id = $_GET['idPedido'];
            $link = Conexion::conectar();
            $sql = "SELECT * FROM pedidos WHERE idPedido = :id";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':id',$id,PDO::PARAM_INT);
            if ($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return json_encode(array('status'=>200,'idPedido'=>$result[0]['idPedido'],'total'=>$result[0]['total'],'pagado'=>$result[0]['pagado']));
            }
            return json_encode(array('status'=>400,'data'=>'Problemas al obtener el recurso'));
        }

        ######################## BALANCE ########################
        public function obtenerPedidosSinEntregar(){
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

    }
