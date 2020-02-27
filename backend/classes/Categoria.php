<?php

class Categoria
{
    public function listarCategoria()
    {
        $link = Conexion::conectar();
        $sql = "SELECT * FROM categorias";
        $stmt = $link->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $json = array();
        foreach ($resultado as $item) {
            $json[] = array(
                'idCategoria' => $item['idCategoria'],
                'categoriaNombre' => $item['categoriaNombre']
            );
        }
        $jsonString = json_encode($json);
        return $jsonString;
    }

    public function agregarCategoria()
    {
        $link = Conexion::conectar();
        $categoria = $_POST['categoriaNombre'];
        $sql = "INSERT INTO categorias (categoriaNombre) VALUES (:categoriaNombre)";
        $stmt = $link->prepare($sql);
        $stmt->bindParam(':categoriaNombre', $categoria, PDO::PARAM_STR);
        $resultado = $stmt->execute();
        if ($resultado) {
            return json_encode('true');
        }
        return json_encode('false');
    }
}