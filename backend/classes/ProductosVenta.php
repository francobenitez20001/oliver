<?php

class ProductosVenta{
    public function agregarProductos(){
        // Obtenemos el json enviado
        $data = file_get_contents('php://input');
        // Los convertimos en un array
        $data = json_decode( $data, true );
        $idVenta = $data['idVenta'];
        $prd_count = count($data['carrito']['productos']);//cantidad de productos que cargo el usuario;
        $logResponse = array();
        $link = Conexion::conectar();
        for ($i=0; $i < $prd_count; $i++) {
            $sql = "INSERT INTO productosVenta (idProducto,idMarca,idCategoria,tipoVenta,total,idVenta,cantidad) VALUES (:idProducto,:idMarca,:idCategoria,:tipoVenta,:total,:idVenta,:cantidad)";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':idProducto',$data['carrito']['productos'][$i]['idProducto'],PDO::PARAM_INT);
            $stmt->bindParam(':idMarca',$data['carrito']['productos'][$i]['idMarca'],PDO::PARAM_INT);
            $stmt->bindParam(':idCategoria',$data['carrito']['productos'][$i]['idCategoria'],PDO::PARAM_INT);
            $stmt->bindParam(':tipoVenta',$data['carrito']['productos'][$i]['tipoDeVenta'],PDO::PARAM_STR);
            $stmt->bindParam(':total',$data['carrito']['productos'][$i]['total'],PDO::PARAM_STR);
            $stmt->bindParam(':idVenta',$idVenta,PDO::PARAM_INT);
            $stmt->bindParam(':cantidad',$data['carrito']['productos'][$i]['cantidad'],PDO::PARAM_STR);            
            if ($stmt->execute()) {
                array_push($logResponse,true);   
            }else{
                array_push($logResponse,false);
            }
        }
        if(in_array(false,$logResponse)){
            return json_encode(array('status'=>400,'info'=>'Problemas al cargar los productos'));
        }
        return json_encode(array('status'=>200,'info'=>'Venta agregada'));
    }

    public function listarProductosPorVenta()
    {
        $idVenta = $_GET['idVenta'];
        $link = Conexion::conectar();
        $sql = "SELECT producto,tipoVenta,total,cantidad FROM productosVenta AS pv, productos AS pr WHERE pv.idProducto = pr.idProducto AND idVenta = :idVenta";
        $stmt = $link->prepare($sql);
        $stmt->bindParam(':idVenta',$idVenta,PDO::PARAM_INT);
        if($stmt->execute()){
            $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $json = array();
            foreach ($productos as $producto) {
                $json[] = array(
                    'producto' => $producto['producto'],
                    'tipoVenta' => $producto['tipoVenta'],
                    'total' => $producto['total'],
                    'cantidad' => $producto['cantidad']
                );
            }
            $jsonString = json_encode($json);
            return $jsonString;
        }
    }
}