<?php
require_once '../models/material.php';


if (isset($_GET['idmaterial']) && isset($_GET['idturma'])) {
    $idmaterial = $_GET['idmaterial'];
    $idturma = $_GET['idturma'];

    $materialModel = new Material();
    $resultado = $materialModel->vincularTurma($idmaterial, $idturma);

    if ($resultado) {
        header("Location: ../views/teacher/link_material.php?idmaterial=" . $idmaterial);
        exit;
    } else {
        echo "Erro ao vincular material à turma.";
    }
} else {
    echo "Parâmetros inválidos.";
}
?>

