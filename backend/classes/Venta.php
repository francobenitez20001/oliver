<?php
    class Venta 
    {
        public function agregarVenta()
        {
            $producto = $_POST['producto'];
            $idProducto = $_POST['producto'];
            $idMarca = $_POST['idMarca'];
            $idCategoria = $_POST['idCategoria'];
            $estado = $_POST['estado'];
            $precio = $_POST['precio'];
            $fecha = $_POST['fecha'];
            $dia = $_POST['dia'];
            $cantidad = $_POST['cantidad'];
            if (isset($_POST['cantidadSuelto']) && !is_null($_POST['cantidadSuelto']) && $_POST['cantidadSuelto']!='') {
                $cantidad = $_POST['cantidadSuelto'];
            }
            $tipo_pago = $_POST['tipo_pago'];
            $cliente = 'No registrado';
            if (isset($_POST['cliente']) && $_POST['cliente']!='') {
                $cliente = $_POST['cliente'];
            }
            $total = $_POST['total'];
            $link = Conexion::conectar();
            $sql = "INSERT INTO ventas (producto,cantidad,idMarca,idCategoria,
                                        total,fecha,dia,estado,tipo_pago,cliente)
                    VALUES (:producto,:cantidad,:idMarca,:idCategoria,:total,
                            :fecha,:dia,:estado,:tipo_pago,:cliente)";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':producto', $producto,PDO::PARAM_STR);
            $stmt->bindParam(':cantidad', $cantidad,PDO::PARAM_INT);
            // $stmt->bindParam(':idProducto', $idProducto ,PDO::PARAM_INT);
            $stmt->bindParam(':idMarca', $idMarca,PDO::PARAM_INT);
            $stmt->bindParam(':idCategoria', $idCategoria,PDO::PARAM_INT);
            $stmt->bindParam(':total', $total,PDO::PARAM_INT);
            $stmt->bindParam(':fecha', $fecha,PDO::PARAM_STR);
            $stmt->bindParam(':dia', $dia,PDO::PARAM_STR);
            $stmt->bindParam(':estado',$estado,PDO::PARAM_STR);
            $stmt->bindParam(':tipo_pago',$tipo_pago,PDO::PARAM_STR);
            $stmt->bindParam(':cliente',$cliente,PDO::PARAM_STR);
            $resultado = $stmt->execute();
            if ($resultado) {
                $actualizarStock = $this->actualizarStock();
                if ($actualizarStock) {
                    return json_encode(array('status'=>200,'info'=>'Venta agregada', 'idVenta'=>$link->lastInsertId()));//trae el ultimo id registrado, es el id de la venta cargada.   
                }
                return json_encode(array('status'=>400,'info'=>'Problemas al actualizar el stock'));
            }
            return json_encode(array('status'=>400,'info'=>'Problemas al agregar la venta'));
        }

        public function actualizarStock()
        {
            $producto = $_POST['producto'];
            $stockParcial = $_POST['stockParcial'];
            $stockSuelto = $_POST['stockSuelto'];
            $cantidad = $_POST['cantidad'];
            $stock = $stockParcial - $cantidad;
            $link = Conexion::conectar();
            $sql = "UPDATE productos SET stock = :stock WHERE producto = :producto";
            if ($_POST['cantidadSuelto']!='') {
                $cantidad = $_POST['cantidadSuelto'];
                $stock = $stockSuelto - $cantidad;
                $sql = 'UPDATE productos SET stock_suelto = :stock WHERE producto = :producto';
            }
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':stock',$stock,PDO::PARAM_INT);
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
            $sql = "SELECT * FROM ventas ";
            if (isset($_GET['tipo-pago']) && $_GET['tipo-pago']!='null') {
                $tipo_pago = $_GET['tipo-pago'];
                $sql .= "WHERE tipo_pago = :tp ";
            }
            $sql .= "ORDER BY idVenta DESC";
            $stmt = $link->prepare($sql);
            if (isset($_GET['tipo-pago']) && $_GET['tipo-pago']!='null') {
                $stmt->bindParam(':tp',$tipo_pago,PDO::PARAM_STR);
            }
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
            $sql = "DELETE FROM ventas WHERE idVenta = :idVenta";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':idVenta',$idVenta,PDO::PARAM_INT);
            $response = $stmt->execute();
            if ($response) {
                return json_encode(true);
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
            $sql = "SELECT SUM(total) AS total_ventas
                    FROM ventas WHERE estado = 'Pago' AND fecha = '". $dia ."'";
            if (!is_null($criterio) && $criterio!='') {
                $sql = "SELECT SUM(total) AS total_ventas
                        FROM ventas WHERE estado = 'Pago' AND fecha LIKE '". $dia ."%'";
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
    