<?php
    class Venta 
    {
        public function agregarVentaJson()
        {
            /// Obtenemos el json enviado
            $data = file_get_contents('php://input');
            // Los convertimos en un array
            $data = json_decode( $data, true );
            $total = $data['total'];
            $estado = $data['estado'];
            $tipo_pago = $data['tipo_pago'];
            $cliente = $data['cliente'];
            $descuento = $data['descuento'];
            $subtotal = $data['subtotal'];
            $idUsuario = $data['idUsuario'];
            $idLocal = $data['idLocal'];
            
            $link = Conexion::conectar();
            $sql = "INSERT INTO ventas (total,estado,tipo_pago,cliente,descuento,subtotal,idUsuario,idLocal)
                    VALUES (:total,:estado,:tipo_pago,:cliente,:descuento,:subtotal,:idUsuario,:idLocal)";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':total',$total,PDO::PARAM_STR);
            $stmt->bindParam(':estado',$estado,PDO::PARAM_STR);
            $stmt->bindParam(':tipo_pago',$tipo_pago,PDO::PARAM_STR);
            $stmt->bindParam(':cliente',$cliente,PDO::PARAM_STR);
            $stmt->bindParam(':descuento',$descuento,PDO::PARAM_STR);
            $stmt->bindParam(':subtotal',$subtotal,PDO::PARAM_STR);
            $stmt->bindParam(':idUsuario',$idUsuario,PDO::PARAM_INT);
            $stmt->bindParam(':idLocal',$idLocal,PDO::PARAM_INT);
            if($stmt->execute()){
                $prd_count = count($data['productos']);//cantidad de productos que cargo el usuario;
                $logResponse = array();
                for ($i=0; $i < $prd_count; $i++) {
                    $actualizarStock = $this->actualizarStock($data['productos'][$i]['tipoDeVenta'],$data['productos'][$i]['cantidad'],$data['productos'][$i]['producto'],$idLocal);
                    if ($actualizarStock) {
                        array_push($logResponse,true);   
                    }else{
                        array_push($logResponse,false);
                    }
                }
                if(in_array(false,$logResponse)){
                    return json_encode(array('status'=>400,'info'=>'Problemas al actualizar el stock'));
                }
                return json_encode(array('status'=>200,'info'=>'Venta agregada','idVenta'=>$link->lastInsertId(),'produtos'=>$data['productos']));
            }
            return json_encode(array('status'=>400,'info'=>'No se pudo cargar la venta'));
        }

        public function actualizarStock($tipoVenta=null,$cantidad=null,$producto=null,$idLocal){
            $link = Conexion::conectar();
            $sql = "";
            if($tipoVenta == 'normal'){
                if($idLocal=="1"){
                    $sql = "UPDATE productos SET stock_local_1 = stock_local_1 - :cantidad WHERE producto = :producto";
                }else{
                    $sql = "UPDATE productos SET stock_local_2 = stock_local_2 - :cantidad WHERE producto = :producto";
                }
            }else{
                if($idLocal=="1"){
                    $sql = 'UPDATE productos SET stock_suelto_local_1 = stock_suelto_local_1 - :cantidad WHERE producto = :producto';
                }else{
                    $sql = 'UPDATE productos SET stock_suelto_local_2 = stock_suelto_local_2 - :cantidad WHERE producto = :producto';
                }
            };
            if(is_null($cantidad) && is_null($producto)){
                $producto = $_POST['producto'];
                $cantidad = $_POST['cantidad'];
            }
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':cantidad',$cantidad,PDO::PARAM_STR);
            $stmt->bindParam(':producto',$producto,PDO::PARAM_STR);
            $resultado = $stmt->execute();
            if ($resultado) {
                return true;
            }
            return false;
        }
        
        public function listarVenta($limit = null){
            $link = Conexion::conectar();
            $sql = "SELECT ven.idVenta,
                    ven.fecha,
                    ven.total,
                    ven.estado,
                    ven.tipo_pago,
                    ven.cliente,
                    ven.descuento,
                    ven.subtotal,
                    IFNULL(usu.nombre,'No registrado') as vendedor,
                    loc.nombre as local
            FROM ventas AS ven 
            LEFT JOIN usuarios AS usu ON ven.idUsuario = usu.idUsuario
            LEFT JOIN locales AS loc ON ven.idLocal = usu.idLocal
            WHERE 1=1 ";

            if (isset($_GET['tipo-pago']) && $_GET['tipo-pago']!='null') {
                $tipo_pago = $_GET['tipo-pago'];
                $sql .= "AND ven.tipo_pago = :tp ";
            }
            if(isset($_GET['inicio']) && !is_null($_GET['inicio']) && isset($_GET['fin']) && !is_null($_GET['fin'])){
                $sql .= "AND ven.fecha BETWEEN :inicio AND :fin ORDER BY fecha DESC ";
            }else{
                $sql .= "ORDER BY ven.idVenta DESC ";
            }

            if(!is_null($limit)){
                $sql .= "LIMIT :limit";
            }

            $stmt = $link->prepare($sql);
            if (isset($_GET['tipo-pago']) && $_GET['tipo-pago']!='null') {
                $stmt->bindParam(':tp',$tipo_pago,PDO::PARAM_STR);
            }
            if(isset($_GET['inicio']) && !is_null($_GET['inicio']) && isset($_GET['fin']) && !is_null($_GET['fin'])){
                $stmt->bindParam(':inicio',$_GET['inicio'],PDO::PARAM_STR);
                $stmt->bindParam(':fin',$_GET['fin'],PDO::PARAM_STR);
            }
            if(!is_null($limit)){
                $stmt->bindParam(':limit',$limit,PDO::PARAM_INT);
            }
            
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $json = array();
            foreach ($result as $reg) {
                $json[] = array(
                    'idVenta' => $reg['idVenta'],
                    'fecha' => $reg['fecha'],
                    'total' => $reg['total'],
                    'estado' => $reg['estado'],
                    'tipo_pago' => $reg['tipo_pago'],
                    'cliente' => $reg['cliente'],
                    'descuento' => $reg['descuento'],
                    'subtotal' => $reg['subtotal'],
                    'vendedor' => $reg['vendedor'],
                    'local' => $reg['local']
                );
            }
            $jsonString = json_encode($json);
            return $jsonString;
        }

        public function saldarVenta()
        {
            $idVenta = $_GET['idVenta'];
            $link = Conexion::conectar();
            $sql = "UPDATE ventas SET estado = 'Pago' WHERE idVenta = :idVenta";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':idVenta',$idVenta,PDO::PARAM_INT);
            $response = $stmt->execute();
            if ($response) {
                return json_encode(true);
            }
            return json_encode(false);
        }

        public function eliminarVenta(){
            $idVenta = $_GET['idVenta'];
            $link = Conexion::conectar();
            $sql = "DELETE FROM productosVenta WHERE idVenta = ".$idVenta;
            $stmt = $link->prepare($sql);
            $response = $stmt->execute();
            if ($response) {
                $sql = "DELETE FROM ventas WHERE idVenta = :idVenta";
                $stmt = $link->prepare($sql);
                $stmt->bindParam(':idVenta',$idVenta,PDO::PARAM_INT);
                if($stmt->execute()){
                    return json_encode(true);
                }
                return json_encode(false);
            }
            return json_encode(false);
        }

        public function listarVentasSaldadas(){
            $link = Conexion::conectar();
            $sql = "SELECT * FROM ventas WHERE estado = 'Pago' ORDER BY idVenta DESC";
            $stmt = $link->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $json = array();
            foreach ($result as $reg) {
                $json[] = array(
                    'idVenta' => $reg['idVenta'],
                    'fecha' => $reg['fecha'],
                    'dia' => $reg['dia'],
                    'total' => $reg['total'],
                    'estado' => $reg['estado'],
                    'tipo_pago' => $reg['tipo_pago'],
                    'cliente' => $reg['cliente']
                );
            }
            $jsonString = json_encode($json);
            return $jsonString;
        }


        ####################### Balance #######################
        public function obtenerMontoVenta($fecha=null,$estado=null,$tipo_pago=null){
            $link = Conexion::conectar();
            $sql = "SELECT IFNULL(SUM(total),0) AS total_ventas FROM ventas WHERE 1 = 1 ";
            
            if(!is_null($estado) && $estado != ""){
                $sql .= "AND estado = :estado ";
            }

            if(!is_null($tipo_pago)){
                $sql .= "AND tipo_pago = :tipo ";
            }

            $sql .= "AND fecha LIKE '". $fecha ."%' ";

            $stmt = $link->prepare($sql);
            if(!is_null($estado) && $estado != ""){
                $stmt->bindParam(':estado',$estado,PDO::PARAM_STR);
            }

            if(!is_null($tipo_pago)){
                $stmt->bindParam(':tipo',$tipo_pago,PDO::PARAM_STR);
            }
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $json = array();
            foreach ($result as $venta) {
                $json[] = array(
                    'ventas_total' => $venta['total_ventas']
                );
            }
            return json_encode($json);
        }

        public function getPagosPorMedioDePago($fecha){
            $link = Conexion::conectar();
            $sql = "SELECT (
                SELECT COUNT(*) AS TOTAL FROM ventas WHERE tipo_pago = 'Tarjeta' AND estado = 'Pago' AND fecha LIKE '". $fecha ."%'
            ) AS pagos_tarjeta,
            (
                SELECT COUNT(*) AS TOTAL FROM ventas WHERE tipo_pago = 'Tarjeta' AND estado = 'Debe' AND fecha LIKE '". $fecha ."%'
            ) AS deudor_tarjeta,
            (
                SELECT COUNT(*) AS TOTAL FROM ventas WHERE tipo_pago = 'Efectivo' AND estado = 'Pago' AND fecha LIKE '". $fecha ."%'
            ) AS pagos_efectivo,
            (
                SELECT COUNT(*) AS TOTAL FROM ventas WHERE tipo_pago = 'Efectivo' AND estado = 'Debe' AND fecha LIKE '". $fecha ."%'
            ) AS deudor_efectivo;";
            $stmt=$link->prepare($sql);
            if ($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $json = array();
                foreach ($result as $pagos_tarjeta) {
                    $json[] = array(
                        'pagos_tarjeta' => $pagos_tarjeta['pagos_tarjeta'],
                        'deudor_tarjeta' => $pagos_tarjeta['deudor_tarjeta'],
                        'pagos_efectivo' => $pagos_tarjeta['pagos_efectivo'],
                        'deudor_efectivo' => $pagos_tarjeta['deudor_efectivo']
                    );
                }
                return json_encode($json);
            }
        }

        public function getProductoMasVendido($fecha){
            $link = Conexion::conectar();
            $fecha = $_GET['fecha'];
            $sql = "SELECT producto, count(pv.idProducto) as cantidad FROM productosVenta AS pv,productos AS pr 
                    where pv.idProducto = pr.idProducto 
                    and fecha LIKE '".$fecha."%' 
                    GROUP BY pv.idProducto
                    LIMIT 1";
            $stmt = $link->prepare($sql);
            if ($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $json = array();
                foreach ($result as $prd) {
                    $json[] = array(
                        'producto' => $prd['producto'],
                        'cantidad' => $prd['cantidad']
                    );
                }
                return json_encode($json);
            }
        }
    }
    