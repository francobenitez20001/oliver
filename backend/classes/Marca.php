<?php

class Marca
{
    public function listarMarca()
    {
        $link = Conexion::conectar();
        $sql = "SELECT * FROM marcas";
        $stmt = $link->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $json = array();
        foreach ($resultado as $item) {
            $json[] = array(
                'idMarca' => $item['idMarca'],
                'marcaNombre' => $item['marcaNombre']
            );
        }
        $jsonString = json_encode($json);
        return $jsonString;
    }

    public function agregarMarca()
    {
        $link = Conexion::conectar();
        $marca = $_POST['marcaNombre'];
        $sql = "INSERT INTO marcas (marcaNombre) VALUES (:marcaNombre)";
        $stmt = $link->prepare($sql);
        $stmt->bindParam(':marcaNombre', $marca, PDO::PARAM_STR);
        $resultado = $stmt->execute();
        if ($resultado) {
            return json_encode('true');
        }
        return json_encode('false');
    }
}
