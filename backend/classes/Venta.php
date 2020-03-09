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
            $total = $precio * $cantidad;
            $link = Conexion::conectar();
            $sql = "INSERT INTO ventas (producto,cantidad,idMarca,idCategoria,
                                        total,fecha,dia,estado)
                    VALUES (:producto,:cantidad,:idMarca,:idCategoria,:total,
                            :fecha,:dia,:estado)";
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
            $resultado = $stmt->execute();
            if ($resultado) {
                $actualizarStock = $this->actualizarStock();
                if ($actualizarStock) {
                    return json_encode(true);   
                }
                return json_encode('Problemas al actualizar el stock del producto');
            }
            return json_encode(false);
        }

        public function actualizarStock()
        {
            $producto = $_POST['producto'];
            $stockParcial = $_POST['stockParcial'];
            $cantidad = $_POST['cantidad'];
            $link = Conexion::conectar();
            $stock = $stockParcial - $cantidad;
            $sql = "UPDATE productos SET stock = :stock WHERE producto = :producto";
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
            $sql = "SELECT * FROM ventas ORDER BY idVenta DESC";
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
                    'estado' => $reg['estado']
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
                    'estado' => $reg['estado']
                );
            }
            $jsonString = json_encode($json);
            return $jsonString;
        }

    }
    