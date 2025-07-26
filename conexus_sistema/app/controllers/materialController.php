<?php
require_once __DIR__ . '/../models/material.php';

class MaterialController {
    private $model;

    public function __construct() {
        $this->model = new Material();
    }

    public function listar() {
        return $this->model->listar();
    }

    public function cadastrar($dados) {
        return $this->model->cadastrar($dados);
    }

    public function buscarMaterial($id) {
        return $this->model->buscarPorId($id);
    }

    public function atualizar($id, $dados) {
        return $this->model->atualizar($id, $dados);
    }

    public function excluir($id) {
        return $this->model->excluir($id);
    }
}
include __DIR__ . '/../views/admin/list_material.php';
