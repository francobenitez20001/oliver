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
                $sql = "SELECT idProducto,producto,p.idMarca,marcaNombre,p.idCategoria,categoriaNombre,precioPublico,precioUnidad,precioKilo,stock_local_1,stock_suelto_local_1,stock_local_2,stock_suelto_local_2,proveedor,porcentaje_ganancia,precio_costo,codigo_producto
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
                                'stock_local_1' => $reg['stock_local_1'],
                                'stock_suelto_local_1' => $reg['stock_suelto_local_1'],
                                'stock_local_2' => $reg['stock_local_2'],
                                'stock_suelto_local_2' => $reg['stock_suelto_local_2'],
                                'proveedor' => $reg['proveedor'],
                                'porcentaje_ganancia' => $reg['porcentaje_ganancia'],
                                'precio_costo' => $reg['precio_costo'],
                                'codigo_producto' => $reg['codigo_producto']
                        );
                }
                $jsonString = json_encode($json);
                return $jsonString;
        }

        public function listarProductoParaWeb()
        {
                $link = Conexion::conectar();
                $sql = "SELECT producto,stock_local_1,codigo_producto FROM productos";
                $stmt = $link->prepare($sql);
                $stmt->execute();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $json = array();
                foreach ($resultado as $reg) {
                        $json[] = array(
                                'producto' => $reg['producto'],
                                'stock' => $reg['stock_local_1'],
                                'codigo_producto' => $reg['codigo_producto']
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
            $stock_local_1 = $_POST['stock_local_1'];
            $stock_suelto_local_1 = $_POST['stock_suelto_local_1'];
            $stock_local_2 = $_POST['stock_local_2'];
            $stock_suelto_local_2 = $_POST['stock_suelto_local_2'];
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
            $sql = "INSERT INTO productos (producto,idMarca,idCategoria,precioPublico,precioUnidad,preciokilo,stock_local_1,stock_local_2,stock_suelto_local_1,stock_suelto_local_2,idProveedor,porcentaje_ganancia,precio_costo,cantidadUnitario,codigo_producto,cantidadPorKilo,porcentajeGananciaPorKilo) VALUES (:producto,:idMarca,:idCategoria,:precioPublico,:precioUnidad,:precioKilo,:stock_local_1,:stock_local_2,:stock_suelto_local_1,:stock_suelto_local_2,:idProveedor,:porcentaje_ganancia,:precioCosto,:cantidadUnitario,:codigoProducto,:cantidadPorKilo,:porcentajeGananciaPorKilo)";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':producto',$producto,PDO::PARAM_STR);
            $stmt->bindParam(':idMarca',$idMarca,PDO::PARAM_INT);
            $stmt->bindParam(':idCategoria',$idCategoria,PDO::PARAM_INT);
            $stmt->bindParam(':precioPublico',$precioPublico,PDO::PARAM_STR);
            $stmt->bindParam(':precioUnidad',$precioUnidad,PDO::PARAM_STR);
            $stmt->bindParam(':precioKilo',$precioKilo,PDO::PARAM_STR);
            $stmt->bindParam(':stock_local_1',$stock_local_1,PDO::PARAM_INT);
            $stmt->bindParam(':stock_local_2',$stock_local_2,PDO::PARAM_INT);
            $stmt->bindParam(':stock_suelto_local_1',$stock_suelto_local_1,PDO::PARAM_STR);
            $stmt->bindParam(':stock_suelto_local_2',$stock_suelto_local_2,PDO::PARAM_STR);
            $stmt->bindParam(':idProveedor',$idProveedor,PDO::PARAM_INT);
            $stmt->bindParam(':porcentaje_ganancia',$porcentaje_ganancia,PDO::PARAM_INT);
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

        public function modificarProducto(){
                $link = Conexion::conectar();
                // $this->cargarDatosDesdeForm();
                $idProducto = $_POST['idProducto'];
                $producto = $_POST['producto'];
                $idMarca = $_POST['idMarca'];
                $idCategoria = $_POST['idCategoria'];
                $cantidadUnitario = $_POST['cantidadUnitario'];
                $idProveedor = $_POST['idProveedor'];
                
                $precioKilo = null;
                $cantidadPorKilo = null;
                $porcentajeGananciaKilo = null;
                $costoKilo = null;

                $stock_local_1 = null;
                $stock_local_2 = null;
                $stock_suelto_local_1 = null;
                $stock_suelto_local_2 = null;
                $porcentaje_ganancia = $_POST['porcentaje_ganancia'];
                $precioCosto = $_POST['precioCosto'];
                $precioPublico = $precioCosto + ($precioCosto*$porcentaje_ganancia/100);//precio publico dinamico
                $precioUnidad = $precioPublico/$cantidadUnitario;

                if(isset($_POST['stock_local_1'])){
                        $stock_local_1 = $_POST['stock_local_1'];
                        $stock_local_2 = $_POST['stock_local_2'];
                        $stock_suelto_local_1 = $_POST['stock_suelto_local_1'];
                        $stock_suelto_local_2 = $_POST['stock_suelto_local_2'];
                }

                if(isset($_POST['cantidadKilo']) && isset($_POST['porcentajePorKilo'])){
                        $cantidadPorKilo = $_POST['cantidadKilo'];
                        $porcentajeGananciaKilo = $_POST['porcentajePorKilo'];
                        if($cantidadPorKilo==0 || $cantidadPorKilo=="0"){
                                $costoKilo = 0;
                                $porcentajeGananciaKiloValor = 0;
                                $precioKilo = 0;       
                        }else{
                                $costoKilo = $precioPublico/$cantidadPorKilo;
                                $porcentajeGananciaKiloValor = $costoKilo*$porcentajeGananciaKilo/100;
                                $precioKilo = $costoKilo + $porcentajeGananciaKiloValor;
                        }
                };

                $sql = "UPDATE productos SET producto = :producto,
                        idMarca = :idMarca,
                        idCategoria = :idCategoria,
                        cantidadUnitario = :cantidadUnitario,
                        porcentajeGananciaPorKilo = :porcentajeGananciaPorKilo, ";  
                
                if(!is_null($stock_local_1)){
                        $sql .= "stock_local_1 = :stock_local_1,
                                stock_local_2 = :stock_local_2,
                                stock_suelto_local_1 = :stock_suelto_local_1,
                                stock_suelto_local_2 = :stock_suelto_local_2,
                                porcentaje_ganancia = :porcentajeGanancia,
                                precio_costo = :precioCosto,
                                precioPublico = :precioPublico,
                                precioUnidad = :precioUnidad, ";
                }

                if(!is_null($cantidadPorKilo)){
                        $sql .= "cantidadPorKilo = :cantidadPorKilo,
                                porcentajeGananciaPorKilo = :gananciaPorKilo,
                                precioKilo = :precioKilo, ";
                }

                $sql .= "idProveedor = :idProveedor WHERE idProducto = :idProducto";
                
                $stmt = $link->prepare($sql);

                $stmt->bindParam(':idProducto', $idProducto , PDO::PARAM_INT);
                $stmt->bindParam(':producto', $producto , PDO::PARAM_STR);
                $stmt->bindParam(':idMarca', $idMarca , PDO::PARAM_INT);
                $stmt->bindParam(':idCategoria', $idCategoria , PDO::PARAM_INT);
                $stmt->bindParam(':cantidadUnitario',$cantidadUnitario,PDO::PARAM_INT);
                $stmt->bindParam(':porcentajeGananciaPorKilo',$porcentajeGananciaKilo,PDO::PARAM_INT);
                $stmt->bindParam(':idProveedor', $idProveedor , PDO::PARAM_INT);
                
                if(!is_null($stock_local_1)){
                        $stmt->bindParam(':stock_local_1',$stock_local_1,PDO::PARAM_INT);
                        $stmt->bindParam(':stock_local_2',$stock_local_2,PDO::PARAM_INT);
                        $stmt->bindParam(':stock_suelto_local_1',$stock_suelto_local_1,PDO::PARAM_STR);
                        $stmt->bindParam(':stock_suelto_local_2',$stock_suelto_local_2,PDO::PARAM_STR);
                        $stmt->bindParam(':porcentajeGanancia',$porcentaje_ganancia,PDO::PARAM_INT);
                        $stmt->bindParam(':precioCosto',$precioCosto,PDO::PARAM_STR);
                        $stmt->bindParam(':precioPublico',$precioPublico,PDO::PARAM_STR);
                        $stmt->bindParam(':precioUnidad',$precioUnidad,PDO::PARAM_STR);
                }

                if(!is_null($cantidadPorKilo)){
                        $stmt->bindParam(':cantidadPorKilo',$cantidadPorKilo,PDO::PARAM_STR);
                        $stmt->bindParam(':gananciaPorKilo',$porcentajeGananciaKilo,PDO::PARAM_INT);
                        $stmt->bindParam(':precioKilo', $precioKilo , PDO::PARAM_STR);
                }

                $bool = $stmt->execute();
                return json_encode(array('ok'=>$bool,'data'=>$cantidadPorKilo));
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
                $sql = "SELECT idProducto,producto,marcaNombre, p.idMarca,categoriaNombre,p.idCategoria,precioPublico,precioUnidad,precioKilo,precio_costo,stock_local_1,stock_local_2,p.idProveedor,proveedor,porcentaje_ganancia,stock_suelto_local_1,stock_suelto_local_2,cantidadUnitario,codigo_producto,cantidadPorKilo,porcentajeGananciaPorKilo FROM productos p, marcas m, categorias c, proveedor prov
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

        public function modificarStock(){
                $link = Conexion::conectar();
                $producto = $_GET['producto'];
                $cantidad = $_GET['cantidad'];
                $idLocal = $_GET['idLocal'];
                $sql = "UPDATE productos SET stock_local_1 = stock_local_1 + :nuevoStock WHERE producto = :producto";
                if($idLocal=="2"){
                        $sql = "UPDATE productos SET stock_local_2 = stock_local_2 + :nuevoStock WHERE producto = :producto";
                }
                $stmt = $link->prepare($sql);
                $stmt->bindParam(':nuevoStock',$cantidad,PDO::PARAM_INT);
                $stmt->bindParam(':producto',$producto,PDO::PARAM_STR);
                if ($stmt->execute()) {
                        return json_encode(array('status'=>200,'info'=>'Se actualizo el stock y el pedido correctamente','data'=>$producto,'cantidad'=>$cantidad));
                }
                return json_encode(array('status'=>400,'info'=>'Problemas al actualizar el stock'));
        }

        public function modificarStockDeposito()
        {
                /// Obtenemos el json enviado
                $data = file_get_contents('php://input');
                // Los convertimos en un array
                $data = json_decode( $data, true );
                $stockDepositoPrevio = $data['stockDepositoDelProducto'];
                $nuevoStockDeposito = $data['nuevoStockDeposito'];
                $nuevoStock = $stockDepositoPrevio - $nuevoStockDeposito;
                $idProducto = $data['idProducto'];
                $link = Conexion::conectar();
                $sql = "UPDATE productos SET stock_deposito = :nuevoStockDeposito,
                                                stock = stock + :nuevoStock
                        WHERE idProducto = :id";
                $stmt = $link->prepare($sql);
                $stmt->bindParam(':nuevoStockDeposito',$nuevoStockDeposito,PDO::PARAM_INT);
                $stmt->bindParam(':nuevoStock',$nuevoStock,PDO::PARAM_INT);
                $stmt->bindParam(':id',$idProducto,PDO::PARAM_INT);
                if ($stmt->execute()) {
                        return json_encode(array('status'=>200,'info'=>'Se actualizaron los stocks'));
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

        public function modificarCodigo()
        {
                $data = file_get_contents('php://input');
                $data = json_decode( $data, true );
                $idProducto = $data['idProducto'];
                $codigoProducto = $data['codigoProducto'];
                $link = Conexion::conectar();
                $sql = "UPDATE productos SET codigo_producto = :codigo WHERE idProducto = :id";
                $stmt = $link->prepare($sql);
                $stmt->bindParam(':codigo',$codigoProducto,PDO::PARAM_STR);
                $stmt->bindParam(':id',$idProducto,PDO::PARAM_INT);
                if($stmt->execute()){
                        return json_encode(array('status'=>200,'info'=>'Código actualizado'));
                }
                return json_encode(array('status'=>400,'info'=>'Problemas al actualizar el código'));
        }



        //interaccion con pagina web
        public function modifcarStockDesdePagina()
        {
                $data = file_get_contents('php://input');
                $data = json_decode($data,true);
                $codigoProducto = $data['codigo_producto'];
                $cantidad = $data['cantidad'];
                $link = Conexion::conectar();
                $sql = "UPDATE productos SET stock = (stock - :cantidad) WHERE codigo_producto = :codigo";
                $stmt = $link->prepare($sql);
                $stmt->bindParam(':cantidad',$cantidad,PDO::PARAM_INT);
                $stmt->bindParam(':codigo',$codigoProducto,PDO::PARAM_STR);
                if($stmt->execute()){
                        return json_encode(array('status'=>200,'info'=>'Stock modificado','cantidad'=>$cantidad));
                }
                return json_encode(array('status'=>400,'info'=>'Problemas al modificar el stock'));
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
    