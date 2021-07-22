<?php

    class Proveedor 
    {
        //attributtes
        public $idProveedor;
        public $proveedor;
        public $email;
        public $telefono;

        public function listarProveedor()
        {
            $link = Conexion::conectar();
            $sql = "SELECT * FROM proveedor WHERE estado = 1 ORDER BY idProveedor DESC";
            $stmt = $link->prepare($sql);
            $stmt->execute();
            $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $json = array();
            foreach ($registros as $proveedor) {
                $json[] = array(
                    'idProveedor' => $proveedor['idProveedor'],
                    'proveedor' => $proveedor['proveedor'],
                    'email' => $proveedor['email'],
                    'telefono' => $proveedor['telefono']
                );
            }
            return json_encode($json);
        }

        public function verProveedorPorId()
        {
            $idProveedor = $_GET['idProveedor'];
            $link = Conexion::conectar();
            $sql = "SELECT * FROM proveedor WHERE idProveedor = :idProveedor";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':idProveedor',$idProveedor,PDO::PARAM_INT);
            $stmt->execute();
            $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $json = array();
            foreach ($registros as $proveedor) {
                $json[] = array(
                    'idProveedor' => $proveedor['idProveedor'],
                    'proveedor' => $proveedor['proveedor'],
                    'email' => $proveedor['email'],
                    'telefono' => $proveedor['telefono'],
                    'estado' => $proveedor['estado']
                );   
            }
            return json_encode($json);
        }

        public function agregarProveedor()
        {
            $proveedor = $_POST['proveedor'];
            $email = null;
            $telefono = null;
            $estado = $_POST['estado'];
            if ($_POST['email']!='' && $_POST['email']!='null') {
                $email = $_POST['email'];
            }
            if ($_POST['telefono']!='' && $_POST['telefono']!='null') {
                $telefono = $_POST['telefono'];
            }
            $link = Conexion::conectar();
            $sql = "INSERT INTO proveedor (proveedor,email,telefono,estado)
                    VALUES (:proveedor,:email,:telefono,:estado)";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':proveedor',$proveedor,PDO::PARAM_STR);
            $stmt->bindParam(':email',$email,PDO::PARAM_STR);
            $stmt->bindParam(':telefono',$telefono,PDO::PARAM_STR);
            $stmt->bindParam(':estado',$estado,PDO::PARAM_INT);
            if ($stmt->execute()) {
                return json_encode(array('status'=>200,'info'=>'Se inserto el proveedor con éxito'));
            }
            return json_encode(array('status'=>400,'info'=>'Problemas al insertar el proveedor'));
        }

        public function modificarProveedor()
        {
            $idProveedor = $_POST['idProveedor'];
            $proveedor = $_POST['proveedor'];
            $email = null;
            $telefono = null;
            $estado = $_POST['estado'];
            if ($_POST['email']!='' && $_POST['email']!='null') {
                $email = $_POST['email'];
            }
            if ($_POST['telefono']!='' && $_POST['telefono']!='null') {
                $telefono = $_POST['telefono'];
            }
            $link = Conexion::conectar();
            $sql = "UPDATE proveedor SET proveedor = :proveedor,
                                         email = :email,
                                         telefono = :telefono,
                                         estado = :estado
                    WHERE idProveedor = :idProveedor";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':proveedor',$proveedor,PDO::PARAM_STR);
            $stmt->bindParam(':email',$email,PDO::PARAM_STR);
            $stmt->bindParam(':telefono',$telefono,PDO::PARAM_STR);
            $stmt->bindParam(':estado',$estado,PDO::PARAM_INT);
            $stmt->bindParam(':idProveedor',$idProveedor,PDO::PARAM_INT);
            if ($stmt->execute()) {
                return json_encode(array('status'=>200,'info'=>'Se modificó el proveedor con éxito'));
            }
            return json_encode(array('status'=>400,'info'=>'Problemas al insertar el proveedor'));
        }

        public function eliminarProveedor()
        {
            $idProveedor = $_GET['idProveedor'];
            $link = Conexion::conectar();
            $sql = "UPDATE proveedor SET estado = 0 WHERE idProveedor = :idProveedor";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':idProveedor',$idProveedor,PDO::PARAM_INT);
            if ($stmt->execute()) {
                return json_encode(array('status'=>200,'info'=>'Se inserto el proveedor con éxito'));
            }
            return json_encode(array('status'=>400,'info'=>'Problemas al insertar el proveedor'));
        }

        public function obtenerMontoPagos($fecha, $estado = null){
            $link = Conexion::conectar();
            $propiedadASumar = "total";
            if(!is_null($estado)){
                $propiedadASumar = "monto";
            }
            $sql = "SELECT SUM(". $propiedadASumar .") AS total_pagos FROM pagoProveedores WHERE fecha LIKE '". $fecha ."%'";
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
    