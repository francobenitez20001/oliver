<?php
    class Venta 
    {
        public function agregarVenta()
        {
            $producto = $_POST['producto'];
            $idProducto = $_POST['producto'];
            $idMarca = $_POST['idMarca'];
            $idCategoria = $_POST['idCategoria'];
            $precio = $_POST['precio'];
            $fecha = $_POST['fecha'];
            $dia = $_POST['dia'];
            $cantidad = $_POST['cantidad'];
            $total = $precio * $cantidad;
            $link = Conexion::conectar();
            $sql = "INSERT INTO ventas (producto,cantidad,idMarca,idCategoria,
                                        total,fecha,dia)
                    VALUES (:producto,:cantidad,:idMarca,:idCategoria,:total,
                            :fecha,:dia)";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':producto', $producto,PDO::PARAM_STR);
            $stmt->bindParam(':cantidad', $cantidad,PDO::PARAM_INT);
            // $stmt->bindParam(':idProducto', $idProducto ,PDO::PARAM_INT);
            $stmt->bindParam(':idMarca', $idMarca,PDO::PARAM_INT);
            $stmt->bindParam(':idCategoria', $idCategoria,PDO::PARAM_INT);
            $stmt->bindParam(':total', $total,PDO::PARAM_INT);
            $stmt->bindParam(':fecha', $fecha,PDO::PARAM_STR);
            $stmt->bindParam(':dia', $dia,PDO::PARAM_STR);
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
    }
    