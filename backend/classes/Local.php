<?php

    class Local {
        private $idLocal;
        private $local;
        private $estado;

        public function __construct($idLocal=null,$local=null,$estado=null){
            $this->setIdLocal($idLocal);
            $this->setLocal($local);
            $this->setEstado($estado);
        }

        public function addLocal(){
            $local = $this->getLocal();
            $estado = $this->getEstado();
            $con = Conexion::conectar();
            $sql = "INSERT INTO locales (nombre,estado) VALUES (:nombre,:estado)";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(':nombre',$local,PDO::PARAM_STR);
            $stmt->bindParam(':estado',$estado,PDO::PARAM_INT);
            if($stmt->execute()){
                return true;
            }
            return false;
        }

        public function updateLocal(){
            $idLocal = $this->getIdLocal();
            $local = $this->getLocal();
            $estado = $this->getEstado();
            $con = Conexion::conectar();
            $sql = "UPDATE locales SET nombre = :nombre, estado = :estado WHERE idLocal = :id";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(':nombre',$local,PDO::PARAM_STR);
            $stmt->bindParam(':estado',$estado,PDO::PARAM_INT);
            $stmt->bindParam(':id',$idLocal,PDO::PARAM_INT);
            if($stmt->execute()){
                return true;
            }
            return false;
        }

        public function get(){
            $idLocal = $this->getIdLocal();
            $con = Conexion::conectar();
            $sql = "SELECT idLocal,nombre,estado FROM locales";
            if(!is_null($idLocal)){
                $sql .= " WHERE idLocal = :id";
            }
            $sql .= " ORDER BY idLocal DESC";
            $stmt = $con->prepare($sql);
            if(!is_null($idLocal)){
                $stmt->bindParam(':id',$idLocal,PDO::PARAM_INT);
            }
            if(!$stmt->execute()){
                return false;
            }
            $locales = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $locales;
        }


        //GETTERS AND SETTERS

        public function getIdLocal(){
            return $this->idLocal;
        }

        public function setIdLocal($idLocal){
            $this->idLocal = $idLocal;
        }

        public function getLocal(){
            return $this->local;
        }

        public function setLocal($local){
            $this->local = $local;
        }

        public function getEstado(){
            return $this->estado;
        }

        public function setEstado($estado){
            $this->estado = $estado;
        }
    }
    