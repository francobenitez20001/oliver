<?php

    class Servicio 
    {
        private $idServicio;
        private $servicioNombre;
        private $fecha;
        private $total;

        public function listarServicio()
        {
            $link = Conexion::conectar();
            $sql = "SELECT * FROM servicios ORDER BY idServicio DESC";
            $stmt = $link->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $json = array();
            foreach ($result as $reg) {
                $json[] = array(
                    'idServicio' => $reg['idServicio'],
                    'servicioNombre' => $reg['servicioNombre'],
                    'fecha' => $reg['fecha'],
                    'estado' => $reg['estado'],
                    'total' => $reg['total'],
                    'comprobante' => $reg['comprobante']
                );
            };
            $jsonString = json_encode($json);
            return $jsonString;
        }

        public function saldarServicio()
        {
            $link = Conexion::conectar();
            $idServicio = $_GET['idServicio'];
            $comprobante = $_GET['comprobante'];
            $sql = "UPDATE servicios SET estado = 'Pago',
                                         comprobante = :comprobante
                    WHERE idServicio = :idServicio";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':comprobante',$comprobante,PDO::PARAM_STR);
            $stmt->bindParam(':idServicio',$idServicio,PDO::PARAM_INT);
            $result = $stmt->execute();
            if ($result) {
                return json_encode(array(
                    'status'=>200,
                    'info'=>'Servicio Actualizado'
                ));
            }
            return json_encode(array(
                'status'=>400,
                'info'=>'Problemas al actualizar el servicio'
            ));
        }

        public function eliminarServicio()
        {
            $link = Conexion::conectar();
            $idServicio = $_GET['idServicio'];
            $sql = "DELETE FROM servicios WHERE idServicio = :idServicio";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':idServicio',$idServicio,PDO::PARAM_INT);
            $result = $stmt->execute();
            if ($result) {
                return json_encode(true);
            }
            return json_encode(false);
        }

        public function agregarServicio()
        {
            $servicioNombre = $_POST['servicioNombre'];
            $fecha = $_POST['fecha'];
            $estado = $_POST['estado'];
            $total = $_POST['total'];
            $comprobante = 'NO';
            if (isset($_FILES['comprobante'])) {
                $comprobante = $this->cargarComprobante('agregar');
            }
            $link = Conexion::conectar();
            $sql = "INSERT INTO servicios (servicioNombre,fecha,estado,total,comprobante)
                    VALUES (:servicioNombre,:fecha,:estado,:total,:comprobante)";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':servicioNombre',$servicioNombre,PDO::PARAM_STR);
            $stmt->bindParam(':fecha',$fecha,PDO::PARAM_STR);
            $stmt->bindParam(':estado',$estado,PDO::PARAM_STR);
            $stmt->bindParam(':total',$total,PDO::PARAM_INT);
            $stmt->bindParam(':comprobante',$comprobante,PDO::PARAM_STR);
            $response = $stmt->execute();
            if ($response) {
                return json_encode(array('status'=>200,'info'=>$comprobante));
            }
            return json_encode(array('status'=>400,'info'=>'Problemas al insertar servicio'));
        }

        public function cargarComprobante($accion=null)
        {
            $idServicio = '';
            if ($accion==null) {
                $idServicio = $_POST['idServicio'];
            }
            // $comprobante = $_FILES['comprobante'];
            $ruta = '../../comprobantes/';
            $imagen = $_FILES['comprobante']['name'];
            if ($_FILES['comprobante']['error']==0) {
                $imagenTMP = $_FILES['comprobante']['tmp_name'];
                $bool = move_uploaded_file($imagenTMP,$ruta.$imagen);
                if ($bool) {
                    if ($accion!=null) {
                        return $imagen;
                    }
                    return json_encode(array(
                        'status'=>200,
                        'data'=>array('idServicio'=>$idServicio,'comprobante'=>$imagen),
                        'info'=>'Comprobante cargado'));
                };
                if ($accion!=null){
                    return $imagen;
                }
                return json_encode(array(
                    'status'=>400,
                    'data'=>array('nombre'=>$imagen,'tmp'=>$imagenTMP,'ruta'=>$ruta,'error'=>$_FILES['comprobante']['error'],'size'=>$_FILES['comprobante']['size']),
                    'info'=>'Problemas al cargar el componente'
                ));
            }
        }

        public function verComprobante()
        {
            $idServicio = $_GET['idServicio'];
            $link = Conexion::conectar();
            $sql = "SELECT comprobante FROM servicios WHERE idServicio = :idServicio";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':idServicio',$idServicio,PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll();
            return $result;
        }

         ######################## BALANCE ########################

        public function obtenerMontoServicio($criterio = null)//criterio es si filtra por dia o por mes. Si no es null, busca por mes
        {
             $link = Conexion::conectar();
             $fecha = $_GET['fecha'];
             $sql = "SELECT SUM(total) AS total_servicios 
                     FROM servicios WHERE fecha LIKE '". $fecha ."%'";
             if (!is_null($criterio) && $criterio=='nopago') {
                 $sql = "SELECT SUM(total) AS total_servicios
                         FROM servicios WHERE estado = 'No pago' AND fecha LIKE '". $fecha ."%'";
             }else if((!is_null($criterio) && $criterio=='pago')){
                //pedidos del dia pagos
                $sql = "SELECT SUM(total) AS total_servicios
                         FROM servicios WHERE estado = 'Pago' AND fecha LIKE '". $fecha ."'";
             }
             $stmt = $link->prepare($sql);
             // $stmt->bindParam(':fecha',$fecha,PDO::PARAM_STR);
             if ($stmt->execute()) {
                 $json = array();
                 $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                 foreach ($resultado as $servicio) {
                    $json[] = array(
                        'servicio_total' => $servicio['total_servicios']
                    );
                 }
                 return json_encode($json);
             };
             return json_encode(array('status'=>400,'info'=>'problemas al ejecutar la consulta'));
        }

        public function obtenerServiciosSinPagar()
        {
            $link = Conexion::conectar();
            $sql = "SELECT count(idServicio) AS servicios_sin_pagar 
                    FROM servicios WHERE estado = 'No pago'";
            $stmt = $link->prepare($sql);
            if ($stmt->execute()) {
                $json = array();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($resultado as $servicio) {
                    $json[] = array(
                        'servicios_sin_pagar' => $servicio['servicios_sin_pagar']
                    );
                }
                return json_encode($json);
            }
        }

    }
    
