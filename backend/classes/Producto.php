<?php

class Producto 
{

        //propiedades
        private $idProducto;
        private $producto;
        private $idMarca;
        private $stock;
        private $idCategoria;
        private $precioPublico;
        private $precioUnidad;
        private $precioKilo;
        

        //methods

        public function listarProducto()
        {       
                $link = Conexion::conectar();
                $sql = "SELECT idProducto,producto,p.idMarca,marcaNombre,p.idCategoria,categoriaNombre,precioPublico,precioUnidad,precioKilo,stock,stock_suelto,proveedor,porcentaje_ganancia,precio_costo,codigo_producto,stock_logistica
                FROM productos p, marcas m, categorias c, proveedor pr
                WHERE p.idMarca = m.idMarca AND p.idCategoria = c.idCategoria AND p.idProveedor = pr.idProveedor ";
                if(isset($_GET['desde']) && !is_null($_GET['desde']) && isset($_GET['hasta']) && !is_null($_GET['hasta'])){
                        $sql .= "LIMIT ".$_GET['hasta'];
                }
                $stmt = $link->prepare($sql);
                $stmt->execute();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $json = array();
                foreach ($resultado as $reg) {
                        $json[] = array(
                                'idProducto' => $reg['idProducto'],
                                'producto' => $reg['producto'],
                                'idMarca' => $reg['idMarca'],
                                'marcaNombre' => $reg['marcaNombre'],
                                'idCategoria'=>$reg['idCategoria'],
                                'categoriaNombre' => $reg['categoriaNombre'],
                                'precioPublico' => $reg['precioPublico'],
                                'precioUnidad' => $reg['precioUnidad'],
                                'precioKilo' => $reg['precioKilo'],
                                'stock' => $reg['stock'],
                                'stock_suelto' => $reg['stock_suelto'],
                                'proveedor' => $reg['proveedor'],
                                'porcentaje_ganancia' => $reg['porcentaje_ganancia'],
                                'precio_costo' => $reg['precio_costo'],
                                'codigo_producto' => $reg['codigo_producto'],
                                'stock_logistica' => $reg['stock_logistica']
                        );
                }
                $jsonString = json_encode($json);
                return $jsonString;
        }

        public function agregarProducto()
        {
            $link = Conexion::conectar();
            $producto = $_POST['producto'];
            $idMarca = $_POST['idMarca'];
            $idCategoria = $_POST['idCategoria'];
            $stock = $_POST['stock'];
            $stockSuelto = $_POST['stockSuelto'];
            $idProveedor = $_POST['idProveedor'];
            $porcentaje_ganancia = $_POST['porcentaje_ganancia'];
            $precioCosto = $_POST['precioCosto'];
            $precioPublico = $precioCosto + ($precioCosto*$porcentaje_ganancia/100);//precio publico dinamico
            $cantidadUnitario = $_POST['cantidadUnitario'];
            $precioUnidad = $precioPublico/$cantidadUnitario;
            $precioKilo = 0;
            $cantidadPorKilo = 0;
            $porcentajeGananciaKilo = 0;
            if(isset($_POST['cantidadKilo']) && $_POST['cantidadKilo']!=='' && $_POST['cantidadKilo']!==0 &&
               isset($_POST['porcentajePorKilo']) && $_POST['porcentajePorKilo']!=='' && $_POST['porcentajePorKilo']!==0){
                $cantidadPorKilo = $_POST['cantidadKilo'];
                $porcentajeGananciaKilo = $_POST['porcentajePorKilo'];
                $costoKilo = $precioPublico/$cantidadPorKilo;
                $porcentajeGananciaKiloValor = $costoKilo*$porcentajeGananciaKilo/100;
                $precioKilo = $costoKilo + $porcentajeGananciaKiloValor;
            };
            $codigoProducto=null;
            if(isset($_POST['codigoProducto']) && $_POST['codigoProducto']!=='' && $_POST['codigoProducto']!==0){
                $codigoProducto = $_POST['codigoProducto'];
            }
            $sql = "INSERT INTO productos (producto,idMarca,idCategoria,precioPublico,precioUnidad,preciokilo,stock,idProveedor,porcentaje_ganancia,stock_suelto,precio_costo,cantidadUnitario,codigo_producto,cantidadPorKilo,porcentajeGananciaPorKilo) VALUES (:producto,:idMarca,:idCategoria,:precioPublico,:precioUnidad,:precioKilo,:stock,:idProveedor,:porcentaje_ganancia,:stockSuelto,:precioCosto,:cantidadUnitario,:codigoProducto,:cantidadPorKilo,:porcentajeGananciaPorKilo)";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':producto',$producto,PDO::PARAM_STR);
            $stmt->bindParam(':idMarca',$idMarca,PDO::PARAM_INT);
            $stmt->bindParam(':idCategoria',$idCategoria,PDO::PARAM_INT);
            $stmt->bindParam(':precioPublico',$precioPublico,PDO::PARAM_STR);
            $stmt->bindParam(':precioUnidad',$precioUnidad,PDO::PARAM_STR);
            $stmt->bindParam(':precioKilo',$precioKilo,PDO::PARAM_STR);
            $stmt->bindParam(':stock',$stock,PDO::PARAM_INT);
            $stmt->bindParam(':idProveedor',$idProveedor,PDO::PARAM_INT);
            $stmt->bindParam(':porcentaje_ganancia',$porcentaje_ganancia,PDO::PARAM_INT);
            $stmt->bindParam(':stockSuelto',$stockSuelto,PDO::PARAM_STR);
            $stmt->bindParam(':precioCosto',$precioCosto,PDO::PARAM_STR);
            $stmt->bindParam(':cantidadUnitario',$cantidadUnitario,PDO::PARAM_INT);
            $stmt->bindParam(':codigoProducto',$codigoProducto,PDO::PARAM_INT);
            $stmt->bindParam(':cantidadPorKilo',$cantidadPorKilo,PDO::PARAM_STR);
            $stmt->bindParam(':porcentajeGananciaPorKilo',$porcentajeGananciaKilo,PDO::PARAM_INT);
            $resultado = $stmt->execute();
            if ($resultado) {
                return json_encode(array('status'=>200,'info'=>'agregado'));
            }
            return json_encode(false);
        }

        public function modificarProducto()
        {
                $link = Conexion::conectar();
                // $this->cargarDatosDesdeForm();
                $idProducto = $_POST['idProducto'];
                $producto = $_POST['producto'];
                $idMarca = $_POST['idMarca'];
                $idCategoria = $_POST['idCategoria'];
                $precioCosto = $_POST['precio_costo'];
                $stock = $_POST['stock'] - $_POST['restaCostoUnitario'];
                $idProveedor = $_POST['idProveedor'];
                $porcentaje_ganancia = $_POST['porcentaje_ganancia'];
                $stockSuelto = $_POST['stockSuelto'];
                $precioPublico = $precioCosto + ($precioCosto*$porcentaje_ganancia/100);//precio publico dinamico
                $cantidadUnitario = $_POST['cantidadUnitario'];
                $precioUnidad = $precioPublico/$cantidadUnitario;
                $precioKilo = 0;
                $cantidadPorKilo = 0;
                $porcentajeGananciaKilo = 0;
                if(isset($_POST['cantidadKilo']) && $_POST['cantidadKilo']!=='' && $_POST['cantidadKilo']!==0 &&
                   isset($_POST['porcentajePorKilo']) && $_POST['porcentajePorKilo']!=='' && $_POST['porcentajePorKilo']!==0){
                        $cantidadPorKilo = $_POST['cantidadKilo'];
                        $porcentajeGananciaKilo = $_POST['porcentajePorKilo'];
                        $costoKilo = $precioPublico/$cantidadPorKilo;
                        $porcentajeGananciaKiloValor = $costoKilo*$porcentajeGananciaKilo/100;
                        $precioKilo = $costoKilo + $porcentajeGananciaKiloValor;
                };
                $codigoProducto = null;
                if (isset($_POST['codigoProducto']) && $_POST['codigoProducto']!=='' && $_POST['codigoProducto']!==0) {
                        $codigoProducto = $_POST['codigoProducto'];
                }
                $sql = "UPDATE productos SET producto = :producto,
                                                idMarca = :idMarca,
                                                idCategoria = :idCategoria,
                                                precioPublico = :precioPublico,
                                                precioUnidad = :precioUnidad,
                                                precioKilo = :precioKilo,
                                                precio_costo = :precioCosto,
                                                stock = :stock,
                                                idProveedor = :idProveedor,
                                                porcentaje_ganancia = :porcentaje_ganancia,
                                                stock_suelto = :stockSuelto,
                                                cantidadUnitario = :cantidadUnitario,
                                                codigo_producto = :codigoProducto,
                                                cantidadPorKilo = :cantidadPorKilo,
                                                porcentajeGananciaPorKilo = :porcentajeGananciaPorKilo
                                        WHERE idProducto = :idProducto";
                $stmt = $link->prepare($sql);
                $stmt->bindParam(':idProducto', $idProducto , PDO::PARAM_INT);
                $stmt->bindParam(':producto', $producto , PDO::PARAM_STR);
                $stmt->bindParam(':idMarca', $idMarca , PDO::PARAM_INT);
                $stmt->bindParam(':idCategoria', $idCategoria , PDO::PARAM_INT);
                $stmt->bindParam(':precioPublico', $precioPublico , PDO::PARAM_STR);
                $stmt->bindParam(':precioUnidad', $precioUnidad , PDO::PARAM_STR);
                $stmt->bindParam(':precioKilo', $precioKilo , PDO::PARAM_STR);
                $stmt->bindParam(':precioCosto',$precioCosto, PDO::PARAM_STR);
                $stmt->bindParam(':stock', $stock , PDO::PARAM_INT);
                $stmt->bindParam(':idProveedor', $idProveedor , PDO::PARAM_INT);
                $stmt->bindParam(':porcentaje_ganancia', $porcentaje_ganancia , PDO::PARAM_INT);
                $stmt->bindParam(':stockSuelto',$stockSuelto,PDO::PARAM_STR);
                $stmt->bindParam(':cantidadUnitario',$cantidadUnitario,PDO::PARAM_INT);
                $stmt->bindParam(':codigoProducto',$codigoProducto,PDO::PARAM_INT);
                $stmt->bindParam(':cantidadPorKilo',$cantidadPorKilo,PDO::PARAM_STR);
                $stmt->bindParam(':porcentajeGananciaPorKilo',$porcentajeGananciaKilo,PDO::PARAM_INT);
                $bool = $stmt->execute();
                if ($bool) {
                        return json_encode(true);
                }
                return json_encode(false);
        }

        public function eliminarProducto()
        {
                $link = Conexion::conectar();
                $idProducto = $_GET['idProducto'];
                $sql = "DELETE FROM productos WHERE idProducto = :idProducto";
                $stmt = $link->prepare($sql);
                $stmt->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
                $bool = $stmt->execute();
                if ($bool) {
                        return json_encode(true);
                }
                return json_encode(false);
        }

        public function verProductoPorId()
        {
                $link = Conexion::conectar();
                $idProducto = $_GET['idProducto'];
                $sql = "SELECT idProducto,producto,marcaNombre, p.idMarca,categoriaNombre,p.idCategoria,precioPublico,precioUnidad,precioKilo,precio_costo,stock,p.idProveedor,proveedor,porcentaje_ganancia,stock_suelto,cantidadUnitario,codigo_producto,cantidadPorKilo,porcentajeGananciaPorKilo FROM productos p, marcas m, categorias c, proveedor prov
                        WHERE p.idMarca = m.idMarca AND p.idCategoria = c.idCategoria AND p.idProveedor = prov.idProveedor AND idProducto = :idProducto";
                $stmt = $link->prepare($sql);
                $stmt->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
                $stmt->execute();
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                return $resultado;
        }

        public function buscarProducto()
        {
                $link = Conexion::conectar();
                $producto = $_POST['productoSearch'];
                $sql = "SELECT producto,precio_costo,porcentaje_ganancia,precioPublico,precioUnidad,precioKilo,stock,codigo_producto
                        FROM productos WHERE producto LIKE '%$producto%' OR codigo_producto LIKE '%$producto%'";
                $stmt = $link->prepare($sql);
                $stmt->execute();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $json = array();
                foreach ($resultado as $reg) {
                        $json[] = array(
                                'idProducto' => $reg['idProducto'],
                                'producto' => $reg['producto'],
                                'marcaNombre' => $reg['marcaNombre'],
                                'porcentaje_ganancia' => $reg['porcentaje_ganancia'],
                                'precio_costo' => $reg['precio_costo'],
                                'categoriaNombre' => $reg['categoriaNombre'],
                                'precioPublico' => $reg['precioPublico'],
                                'precioUnidad' => $reg['precioUnidad'],
                                'precioKilo' => $reg['precioKilo'],
                                'stock' => $reg['stock'],
                                'codigo_producto' => $reg['codigo_producto']
                        );
                }
                $jsonString = json_encode($json);
                return $jsonString;
        }

        public function aumentarPorProveedor()
        {
                $link = Conexion::conectar();
                $idProveedor = $_POST['idProveedor'];
                $porcentajeAumento = $_POST['porcentaje_aumento'];
                $aumentoNumero = $_POST['aumentoNumero'];
                $sql = "UPDATE productos SET precioPublico = precioPublico + (precioPublico*:porcentaje/100),
                                             precioUnidad = precioUnidad + (precioUnidad*:porcentaje/100),
                                             precioKilo = precioKilo + (precioKilo*:porcentaje/100),
                                             precio_costo = precio_costo + (precio_costo*:porcentaje/100)
                        WHERE idProveedor = :idProveedor";
                $stmt = $link->prepare($sql);
                $stmt->bindParam(':porcentaje',$porcentajeAumento,PDO::PARAM_INT);
                $stmt->bindParam(':idProveedor',$idProveedor,PDO::PARAM_INT);
                $bool = $stmt->execute();
                if ($bool) {
                        return json_encode(array('status'=>200,'info'=>'Se actualizaron los precios de manera correcta'));
                }
                return json_encode(array('status'=>400,'info'=>'Problemas al actualizar los precios'));
        }

        public function aumentarPorMarca()
        {
                $link = Conexion::conectar();
                $idMarca = $_POST['idMarca'];
                $porcentajeAumento = $_POST['porcentaje_aumento'];
                $aumentoNumero = $_POST['aumentoNumero'];
                $sql = "UPDATE productos SET precioPublico = precioPublico + (precioPublico*:porcentaje/100),
                                             precioUnidad = precioUnidad + (precioUnidad*:porcentaje/100),
                                             precioKilo = precioKilo + (precioKilo*:porcentaje/100),
                                             precio_costo = precio_costo + (precio_costo*:porcentaje/100)
                        WHERE idMarca= :idMarca";
                $stmt = $link->prepare($sql);
                $stmt->bindParam(':porcentaje',$porcentajeAumento,PDO::PARAM_INT);
                $stmt->bindParam(':idMarca',$idMarca,PDO::PARAM_INT);
                $bool = $stmt->execute();
                if ($bool) {
                        return json_encode(array('status'=>200,'info'=>'Se actualizaron los precios de manera correcta'));
                }
                return json_encode(array('status'=>400,'info'=>'Problemas al actualizar los precios'));
        }

        public function aumentarPorProducto()
        {
                $idProducto = $_POST['idProducto'];
                $link = Conexion::conectar();
                $porcentajeAumento = $_POST['porcentaje_aumento'];
                $sql = "UPDATE productos SET precioPublico = precioPublico + (precioPublico*:porcentaje/100),
                                             precioUnidad = precioUnidad + (precioUnidad*:porcentaje/100),
                                             precioKilo = precioKilo + (precioKilo*:porcentaje/100),
                                             precio_costo = precio_costo + (precio_costo*:porcentaje/100)
                        WHERE idProducto = :id";
                $stmt = $link->prepare($sql);
                $stmt->bindParam(':porcentaje',$porcentajeAumento,PDO::PARAM_STR);
                $stmt->bindParam(':id',$idProducto,PDO::PARAM_INT);
                $bool = $stmt->execute();
                if ($bool) {
                        return json_encode(array('status'=>200,'info'=>'Se actualizaron los precios de manera correcta'));
                }
                return json_encode(array('status'=>400,'info'=>'Problemas al actualizar los precios'));
        }

        public function modificarStock()
        {
                $link = Conexion::conectar();
                $producto = $_GET['producto'];
                $cantidad = $_GET['cantidad'];
                $sql = "UPDATE productos SET stock = stock + :nuevoStock
                        WHERE producto = :producto";
                $stmt = $link->prepare($sql);
                $stmt->bindParam(':nuevoStock',$cantidad,PDO::PARAM_INT);
                $stmt->bindParam(':producto',$producto,PDO::PARAM_STR);
                if ($stmt->execute()) {
                        return json_encode(array('status'=>200,'info'=>'Se actualizo el stock y el pedido correctamente','data'=>$producto,'cantidad'=>$cantidad));
                }
                return json_encode(array('status'=>400,'info'=>'Problemas al actualizar el stock'));
        }


        public function cargarDatosDesdeForm()
        {
                if (isset($_POST['idProducto'])) {
                        $this->setIdProducto($_POST['idProducto']);
                }
                $this->setProducto($_POST['producto']);
                $this->setIdMarca($_POST['idMarca']);
                $this->setIdCategoria($_POST['idCategoria']);
                $this->setPrecioPublico($_POST['precioPublico']);
                $this->setPrecioUnidad($_POST['precioUnidad']);
                $this->setPrecioKilo($_POST['PrecioKilo']);
        }




        
        //getter and setter
        
        public function getIdProducto()
        {
                return $this->idProducto;
        }
        public function setIdProducto($idProducto)
        {
                $this->idProducto = $idProducto;
        }

      
        public function getProducto()
        {
                return $this->producto;
        }
        public function setProducto($producto)
        {
                $this->producto = $producto;
        }

        
        public function getIdMarca()
        {
                return $this->idMarca;
        }
        public function setIdMarca($idMarca)
        {
                $this->idMarca = $idMarca;
        }

        
        public function getIdCategoria()
        {
                return $this->idCategoria;
        } 
        public function setIdCategoria($idCategoria)
        {
                $this->idCategoria = $idCategoria;
        }

        
        public function getPrecioPublico()
        {
                return $this->precioPublico;
        } 
        public function setPrecioPublico($precioPublico)
        {
                $this->precioPublico = $precioPublico;
        }

        
        public function getPrecioUnidad()
        {
                return $this->precioUnidad;
        }
        public function setPrecioUnidad($precioUnidad)
        {
                $this->precioUnidad = $precioUnidad;
        }

         
        public function getPrecioKilo()
        {
                return $this->precioKilo;
        } 
        public function setPrecioKilo($precioKilo)
        {
                $this->precioKilo = $precioKilo;
        }
}
    