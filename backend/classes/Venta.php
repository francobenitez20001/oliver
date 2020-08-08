<?php
    class Venta 
    {
        public function agregarVentaJson()
        {
            /// Obtenemos el json enviado
            $data = file_get_contents('php://input');
            // Los convertimos en un array
            $data = json_decode( $data, true );
            $fecha = $data['fecha'];
            $dia = $data['dia'];
            $total = $data['total'];
            $estado = $data['estado'];
            $tipo_pago = $data['tipo_pago'];
            $cliente = $data['cliente'];
            $descuento = $data['descuento'];
            $subtotal = $data['subtotal'];
            
            $link = Conexion::conectar();
            $sql = "INSERT INTO ventas (fecha,dia,total,estado,tipo_pago,cliente,descuento,subtotal)
                    VALUES (:fecha,:dia,:total,:estado,:tipo_pago,:cliente,:descuento,:subtotal)";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':fecha',$fecha,PDO::PARAM_STR);
            $stmt->bindParam(':dia',$dia,PDO::PARAM_STR);
            $stmt->bindParam(':total',$total,PDO::PARAM_STR);
            $stmt->bindParam(':estado',$estado,PDO::PARAM_STR);
            $stmt->bindParam(':tipo_pago',$tipo_pago,PDO::PARAM_STR);
            $stmt->bindParam(':cliente',$cliente,PDO::PARAM_STR);
            $stmt->bindParam(':descuento',$descuento,PDO::PARAM_STR);
            $stmt->bindParam(':subtotal',$subtotal,PDO::PARAM_STR);
            if($stmt->execute()){
                $prd_count = count($data['productos']);//cantidad de productos que cargo el usuario;
                $logResponse = array();
                for ($i=0; $i < $prd_count; $i++) {
                    $actualizarStock = $this->actualizarStock($data['productos'][$i]['tipoDeVenta'],$data['productos'][$i]['cantidad'],$data['productos'][$i]['producto']);
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

        public function actualizarStock($tipoVenta=null,$cantidad=null,$producto=null)
        {
            $link = Conexion::conectar();
            if($tipoVenta == 'normal'){
                    $sql = "UPDATE productos SET stock = stock - :cantidad WHERE producto = :producto";
            }else{
                $sql = 'UPDATE productos SET stock_suelto = stock_suelto - :cantidad WHERE producto = :producto';
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

        public function listarVenta()
        {
            $link = Conexion::conectar();
            $sql = "SELECT * FROM ventas WHERE 1=1 ";
            if (isset($_GET['tipo-pago']) && $_GET['tipo-pago']!='null') {
                $tipo_pago = $_GET['tipo-pago'];
                $sql .= "AND tipo_pago = :tp ";
            }
            if(isset($_GET['inicio']) && !is_null($_GET['inicio']) && isset($_GET['fin']) && !is_null($_GET['fin'])){
                $sql .= "AND fecha BETWEEN :inicio AND :fin ORDER BY fecha DESC";
            }else{
                $sql .= "ORDER BY idVenta DESC";
            }
            $stmt = $link->prepare($sql);
            if (isset($_GET['tipo-pago']) && $_GET['tipo-pago']!='null') {
                $stmt->bindParam(':tp',$tipo_pago,PDO::PARAM_STR);
            }
            if(isset($_GET['inicio']) && !is_null($_GET['inicio']) && isset($_GET['fin']) && !is_null($_GET['fin'])){
                $stmt->bindParam(':inicio',$_GET['inicio'],PDO::PARAM_STR);
                $stmt->bindParam(':fin',$_GET['fin'],PDO::PARAM_STR);
            }
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
                    'cliente' => $reg['cliente'],
                    'subtotal' => $reg['subtotal'],
                    'descuento' => $reg['descuento']
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

        public function eliminarVenta()
        {
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

        public function listarVentasSaldadas()
        {
            $link = Conexion::conectar();
            $sql = "SELECT * FROM ventas WHERE estado = 'Pago' ORDER BY idVenta DESC";
            $stmt = $link->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $json = array();
            foreach ($result as $reg) {
                $json[] = array(
                    'idVenta' => $reg['idVenta'],
                    'producto' => $reg['producto'],
                    'cantidad' => $reg['cantidad'],
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
        public function obtenerMontoVenta($dia,$criterio=null)
        {
            $link = Conexion::conectar();
            switch ($criterio) {
                case !is_null($criterio) && $criterio=='mes':
                    $sql = "SELECT SUM(total) AS total_ventas
                    FROM ventas WHERE estado = 'Pago' AND fecha LIKE '". $dia ."%'";
                    break;
                case 'diaEfectivo':
                    $sql = "SELECT SUM(total) AS total_ventas
                    FROM ventas WHERE estado = 'Pago' AND fecha = '". $dia ."' AND tipo_pago = 'Efectivo'";
                    break;
                case 'diaTarjeta':
                    $sql = "SELECT SUM(total) AS total_ventas
                    FROM ventas WHERE estado = 'Pago' AND fecha = '". $dia ."' AND tipo_pago = 'Tarjeta'";
                    break;
                case 'mesEfectivo':
                    $sql = "SELECT SUM(total) AS total_ventas
                    FROM ventas WHERE estado = 'Pago' AND fecha LIKE '". $dia ."%' AND tipo_pago = 'Efectivo'";
                    break;
                case 'mesTarjeta':
                    $sql = "SELECT SUM(total) AS total_ventas
                    FROM ventas WHERE estado = 'Pago' AND fecha LIKE '". $dia ."%' AND tipo_pago = 'Tarjeta'";
                    break;
                default:
                    $sql = "SELECT SUM(total) AS total_ventas
                    FROM ventas WHERE estado = 'Pago' AND fecha = '". $dia ."'";
                    break;
            }
            $stmt = $link->prepare($sql);
            // $stmt->bindParam(':dia',$dia,PDO::PARAM_STR);
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

        public function listarVentaLimit()
        {
            $link = Conexion::conectar();
            $sql = "SELECT producto,cantidad,fecha,total FROM ventas order by fecha DESC LIMIT 3";
            $stmt = $link->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $json = array();
            foreach ($result as $venta) {
                $json[] = array(
                    'producto' => $venta['producto'],
                    'cantidad' => $venta['cantidad'],
                    'fecha' => $venta['fecha'],
                    'total' => $venta['total']
                );
            }
            return json_encode($json);
        }

        public function getPagosConTarjeta($deuda=null)
        {
            $link = Conexion::conectar();
            $fecha = $_GET['fecha'];
            $sql = "SELECT count(tipo_pago) as pagos_tarjeta 
                    FROM ventas WHERE tipo_pago = 'Tarjeta' AND fecha LIKE '". $fecha ."%'";
            if (!is_null($deuda)) {
                $sql = "SELECT count(tipo_pago) as pagos_tarjeta
                        FROM ventas WHERE tipo_pago = 'Tarjeta' AND estado = 'Debe' AND fecha LIKE '". $fecha ."%'";
            }
            $stmt=$link->prepare($sql);
            if ($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $json = array();
                foreach ($result as $pagos_tarjeta) {
                    $json[] = array(
                        'pagos_tarjeta' => $pagos_tarjeta['pagos_tarjeta']
                    );
                }
                return json_encode($json);
            }
        }

        public function getProductoMasVendido($criterio=null)
        {
            $link = Conexion::conectar();
            $fecha = $_GET['fecha'];
            $sql = "SELECT producto, count(producto) as cantidad FROM ventas 
                    WHERE fecha LIKE '". $fecha ."%' GROUP BY producto LIMIT 1";
            if (!is_null($criterio)) {
                $sql = "SELECT producto, count(producto) as cantidad FROM ventas 
                        WHERE fecha = '". $fecha ."' GROUP BY producto LIMIT 1";
            }
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
    