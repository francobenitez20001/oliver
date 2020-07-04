<?php

    class PagoProveedor 
    {
        public function listarPagos($mes=null)
        {   
            $link=Conexion::conectar();
            $sql = "SELECT id,pp.idProveedor,proveedor,total,monto,fecha FROM pagoProveedores as pp,proveedor AS pr
            WHERE pp.idProveedor = pr.idProveedor ";
            if(!is_null($mes)){
                $sql .= "AND fecha LIKE '".$mes."%'";
            }
            $stmt = $link->prepare($sql);
            if($stmt->execute()){
                $json = array();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($result as $pago) {
                    $json[] = array(
                        'id'=>$pago['id'],
                        'idProveedor' => $pago['idProveedor'],
                        'proveedor'=>$pago['proveedor'],
                        'total' => $pago['total'],
                        'monto' => $pago['monto'],
                        'fecha' => $pago['fecha']
                    );
                };
                return json_encode($json);
            }
        }

        public function listarPagosPorProveedor()
        {
            $idProveedor = $_GET['idProveedor'];
            $link = Conexion::conectar();
            $sql = "SELECT id,proveedor,monto,total FROM pagoProveedores AS pp, proveedor AS pr
                    WHERE pp.idProveedor = pr.idProveedor AND pp.idProveedor = :id ORDER BY id DESC";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':id',$idProveedor,PDO::PARAM_INT);
            if($stmt->execute()){
                $json = array();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($result as $pago) {
                    $json[] = array(
                        'id'=>$pago['id'],
                        'proveedor'=>$pago['proveedor'],
                        'total' => $pago['total'],
                        'monto' => $pago['monto']
                    );
                };
                return json_encode($json);
            }
        }

        public function agregarPago()
        {
            $idProveedor = $_POST['idProveedor'];
            $total = $_POST['total'];
            $monto = $_POST['monto'];
            $fecha = $_POST['fecha'];
            $link = Conexion::conectar();
            $sql = "INSERT INTO pagoProveedores (idProveedor,total,monto,fecha) VALUES (:idProveedor,:total,:monto,:fecha)";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':idProveedor',$idProveedor,PDO::PARAM_INT);
            $stmt->bindParam(':total',$total,PDO::PARAM_STR);
            $stmt->bindParam(':monto',$monto,PDO::PARAM_STR);
            $stmt->bindParam(':fecha',$fecha,PDO::PARAM_STR);
            if($stmt->execute()){
                return json_encode(array('status'=>200,'info'=>'pago agregado'));
            }
            return json_encode(array('status'=>400,'info'=>'Problemas al agregar el pago'));
        }

        public function eliminarPago()
        {
            $id = $_GET['id'];
            $link = Conexion::conectar();
            $sql = "DELETE FROM pagoProveedores WHERE id = :id";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':id',$id,PDO::PARAM_INT);
            if($stmt->execute()){
                return json_encode(array('status'=>200,'info'=>'Pago eliminado'));
            }
            return json_encode(array('status'=>400,'info'=>'Problemas al eliminar el pago'));
        }

        public function actualizarPago()
        {
            $id = $_POST['id'];
            $monto = $_POST['monto'];
            $link = Conexion::conectar();
            $sql = "UPDATE pagoProveedores SET monto = monto + :monto WHERE id = :id";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':id',$id,PDO::PARAM_INT);
            $stmt->bindParam(':monto',$monto,PDO::PARAM_STR);
            if($stmt->execute()){
                return json_encode(array('status'=>200,'info'=>'Pago actualizado'));
            }
            return json_encode(array('status'=>400,'info'=>'Problemas al actualizar el pago'));
        }


        // balance
        public function obtenerMontoPagos($criterio = null)
        {
            $link = Conexion::conectar();
            $fecha = $_GET['fecha'];
            $sql = "SELECT SUM(total) AS total_pagos 
                    FROM pagoProveedores WHERE fecha = '". $fecha ."%'";
            if (!is_null($criterio) && $criterio!='') {
                $sql = "SELECT SUM(total) AS total_pagos
                        FROM pagoProveedores WHERE fecha LIKE '". $fecha ."%'";
            }
            $stmt = $link->prepare($sql);
            if ($stmt->execute()) {
                $json = array();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($resultado as $pagos) {
                    $json[] = array(
                        'pagos_total' => $pagos['total_pagos']
                    );
                }
                return json_encode($json);
            };
            return json_encode(array('status'=>400,'info'=>'problemas al ejecutar la consulta'));
        }
    }
    