<?php

class Comprobante 
{
    public function insertarComprobante()
    {
        $link = Conexion::conectar();
        $comprobante = $_GET['comprobante'];
        $idProveedor = $_GET['idProveedor'];
        $descripcion = $_GET['descripcion'];
        $sql = 'INSERT INTO comprobantes (idProveedor,comprobante,descripcion) VALUES (:idProveedor,:comprobante,:descripcion)';
        $stmt = $link->prepare($sql);
        $stmt->bindParam(':idProveedor',$idProveedor,PDO::PARAM_INT);
        $stmt->bindParam(':comprobante',$comprobante,PDO::PARAM_STR);
        $stmt->bindParam(':descripcion',$descripcion,PDO::PARAM_STR);
        if ($stmt->execute()) {
            return json_encode(array('status'=>200,'info'=>'Comprobante insertado'));
        }
        return json_encode(array('status'=>400,'info'=>'Problemas al insertar el comprobante'));
    }

    public function verComprobantePorProveedor()
    {
        $idProveedor = $_GET['idProveedor'];
        $link = Conexion::conectar();
        $sql = "SELECT idComprobante,proveedor,comprobante,descripcion FROM comprobantes AS c, proveedor AS p WHERE c.idProveedor = :id
                AND c.idProveedor = p.idProveedor
                ORDER BY idComprobante DESC";
        $stmt = $link->prepare($sql);
        $stmt->bindParam(':id',$idProveedor,PDO::PARAM_INT);
        if ($stmt->execute()) {
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $json = array();
            foreach ($resultado as $reg) {
                $json[] = array(
                    'idComprobante'=>$reg['idComprobante'],
                    'proveedor'=>$reg['proveedor'],
                    'comprobante'=>$reg['comprobante'],
                    'descripcion'=>$reg['descripcion'],
                );
            };
            return json_encode($json);
        }
        return json_encode(array('status'=>400,'info'=>'Problemas al obtener registros'));
    }

    public function verComprobantePorId()
    {
        $idComprobante = $_GET['idComprobante'];
        $link = Conexion::conectar();
        $sql = "SELECT comprobante FROM comprobantes WHERE idComprobante = :id";
        $stmt = $link->prepare($sql);
        $stmt->bindParam(':id',$idComprobante,PDO::PARAM_INT);
        if ($stmt->execute()) {
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $json = array();
            foreach ($resultado as $reg) {
                $json[] = array(
                    'comprobante'=>$reg['comprobante'],
                );
            };
            return json_encode($json);
        }
        return json_encode(array('status'=>400,'info'=>'Problemas al obtener registros'));
    }

    public function cargarComprobante()
        {
            $link = Conexion::conectar();
            $idProveedor = $_POST['idProveedor'];
            $descripcion = $_POST['descripcion'];
            // $comprobante = $_FILES['comprobante'];
            $ruta = '../../comprobantes/';
            $imagen = $_FILES['comprobante']['name'];
            if ($_FILES['comprobante']['error']==0) {
                $imagenTMP = $_FILES['comprobante']['tmp_name'];
                $bool = move_uploaded_file($imagenTMP,$ruta.$imagen);
                if ($bool) {
                    return json_encode(array(
                        'status'=>200,
                        'data'=>array('idProveedor'=>$idProveedor,'comprobante'=>$imagen,'descripcion'=>$descripcion),
                        'info'=>'Comprobante cargado'));
                };
                return json_encode(array(
                    'status'=>400,
                    'error'=>$link->error,
                    'data'=>array('nombre'=>$imagen,'tmp'=>$imagenTMP,'ruta'=>$ruta,'size'=>$_FILES['comprobante']['error']),
                    'info'=>'Problemas al cargar el componente'
                ));
            }
        }
}
