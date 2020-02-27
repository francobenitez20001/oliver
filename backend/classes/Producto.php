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
                $inicio = $_GET['inicio'];
                $fin = $_GET['fin'];
                $link = Conexion::conectar();
                $sql = "SELECT idProducto,producto,marcaNombre,categoriaNombre,precioPublico,precioUnidad,precioKilo,stock
                        FROM productos p, marcas m, categorias c
                        WHERE p.idMarca = m.idMarca AND p.idCategoria = c.idCategoria AND idProducto between :inicio AND :fin";
                $stmt = $link->prepare($sql);
                $stmt->bindParam(':inicio',$inicio,PDO::PARAM_INT);
                $stmt->bindParam(':fin',$fin,PDO::PARAM_INT);
                $stmt->execute();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $json = array();
                foreach ($resultado as $reg) {
                        $json[] = array(
                                'idProducto' => $reg['idProducto'],
                                'producto' => $reg['producto'],
                                'marcaNombre' => $reg['marcaNombre'],
                                'categoriaNombre' => $reg['categoriaNombre'],
                                'precioPublico' => $reg['precioPublico'],
                                'precioUnidad' => $reg['precioUnidad'],
                                'precioKilo' => $reg['precioKilo'],
                                'stock' => $reg['stock']
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
            $precioPublico = $_POST['precioPublico'];
            $precioUnidad = $_POST['precioUnidad'];
            $precioKilo = $_POST['PrecioKilo'];
            $stock = $_POST['stock'];
            $sql = "INSERT INTO productos (producto,idMarca,idCategoria,precioPublico,precioUnidad,preciokilo,stock)
                    VALUES (:producto,:idMarca,:idCategoria,:precioPublico,:precioUnidad,:precioKilo,:stock)";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':producto',$producto,PDO::PARAM_STR);
            $stmt->bindParam(':idMarca',$idMarca,PDO::PARAM_INT);
            $stmt->bindParam(':idCategoria',$idCategoria,PDO::PARAM_INT);
            $stmt->bindParam(':precioPublico',$precioPublico,PDO::PARAM_INT);
            $stmt->bindParam(':precioUnidad',$precioUnidad,PDO::PARAM_INT);
            $stmt->bindParam(':precioKilo',$precioKilo,PDO::PARAM_INT);
            $stmt->bindParam(':stock',$stock,PDO::PARAM_INT);
            $resultado = $stmt->execute();
            if ($resultado) {
                return json_encode('true');
            }
            return json_encode('false');
        }

        public function modificarProducto()
        {
                $link = Conexion::conectar();
                // $this->cargarDatosDesdeForm();
                $idProducto = $_POST['idProducto'];
                $producto = $_POST['producto'];
                $idMarca = $_POST['idMarca'];
                $idCategoria = $_POST['idCategoria'];
                $precioPublico = $_POST['precioPublico'];
                $precioUnidad = $_POST['precioUnidad'];
                $precioKilo = $_POST['PrecioKilo'];
                $stock = $_POST['stock'];
                $sql = "UPDATE productos SET producto = :producto,
                                                idMarca = :idMarca,
                                                idCategoria = :idCategoria,
                                                precioPublico = :precioPublico,
                                                precioUnidad = :precioUnidad,
                                                precioKilo = :precioKilo,
                                                stock = :stock
                                        WHERE idProducto = :idProducto";
                $stmt = $link->prepare($sql);
                $stmt->bindParam(':idProducto', $idProducto , PDO::PARAM_INT);
                $stmt->bindParam(':producto', $producto , PDO::PARAM_STR);
                $stmt->bindParam(':idMarca', $idMarca , PDO::PARAM_INT);
                $stmt->bindParam(':idCategoria', $idCategoria , PDO::PARAM_INT);
                $stmt->bindParam(':precioPublico', $precioPublico , PDO::PARAM_INT);
                $stmt->bindParam(':precioUnidad', $precioUnidad , PDO::PARAM_INT);
                $stmt->bindParam(':precioKilo', $precioKilo , PDO::PARAM_INT);
                $stmt->bindParam(':stock', $stock , PDO::PARAM_INT);
                $bool = $stmt->execute();
                if ($bool) {
                        return json_encode('true');
                }
                return json_encode('false');
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
                        return json_encode('true');
                }
                return json_encode('false');
        }

        public function verProductoPorId()
        {
                $link = Conexion::conectar();
                $idProducto = $_GET['idProducto'];
                $sql = "SELECT idProducto,producto,marcaNombre, p.idMarca,categoriaNombre,p.idCategoria,        precioPublico,precioUnidad,precioKilo,stock     
                        FROM productos p, marcas m, categorias c
                        WHERE p.idMarca = m.idMarca AND p.idCategoria = c.idCategoria AND idProducto = :idProducto";
                $stmt = $link->prepare($sql);
                $stmt->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
                $stmt->execute();
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                //por ahora devuelo el array asociativo nomas
                // $json = array();
                // $json[] = array(
                //         'idProducto' => $reg['idProducto'],
                //         'producto' => $reg['producto'],
                //         'marcaNombre' => $reg['marcaNombre'],
                //         'categoriaNombre' => $reg['categoriaNombre'],
                //         'precioPublico' => $reg['precioPublico'],
                //         'precioUnidad' => $reg['precioUnidad'],
                //         'precioKilo' => $reg['precioKilo']
                // );
                // $jsonString = json_encode($json);
                // return $jsonString;
                return $resultado;
        }

        public function buscarProducto()
        {
                $link = Conexion::conectar();
                $producto = $_POST['productoSearch'];
                $sql = "SELECT idProducto,producto,marcaNombre,categoriaNombre,precioPublico,precioUnidad,precioKilo,stock
                        FROM productos p, marcas m, categorias c
                        WHERE p.idMarca = m.idMarca AND p.idCategoria = c.idCategoria
                        AND producto LIKE '%$producto%'";
                $stmt = $link->prepare($sql);
                $stmt->execute();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $json = array();
                foreach ($resultado as $reg) {
                        $json[] = array(
                                'idProducto' => $reg['idProducto'],
                                'producto' => $reg['producto'],
                                'marcaNombre' => $reg['marcaNombre'],
                                'categoriaNombre' => $reg['categoriaNombre'],
                                'precioPublico' => $reg['precioPublico'],
                                'precioUnidad' => $reg['precioUnidad'],
                                'precioKilo' => $reg['precioKilo'],
                                'stock' => $reg['stock']
                        );
                }
                $jsonString = json_encode($json);
                return $jsonString;
        }

        public function aumentarPorMarca()
        {
                $link = Conexion::conectar();
                $idMarca = $_POST['idMarca'];
                $precioPublico = $_POST['precioPublico'];
                $precioUnidad = $_POST['precioUnidad'];
                $precioKilo = $_POST['PrecioKilo'];
                $sql = "UPDATE productos SET precioPublico = :precioPublico,
                                                precioUnidad = :precioUnidad,
                                                precioKilo = :precioKilo
                                        WHERE idMarca = :idMarca";
                $stmt = $link->prepare($sql);
                $stmt->bindParam(':precioPublico',$precioPublico,PDO::PARAM_INT);
                $stmt->bindParam(':precioUnidad',$precioUnidad,PDO::PARAM_INT);
                $stmt->bindParam(':precioKilo',$precioKilo,PDO::PARAM_INT);
                $stmt->bindParam(':idMarca',$idMarca,PDO::PARAM_INT);
                $bool = $stmt->execute();
                if ($bool) {
                        return json_encode('true');
                }
                return json_encode('false');
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
    