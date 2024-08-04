<?php
header('Content-Type: application/json');

include_once "../config/conexao.php";
include_once "../dao/servicoDao.php";

include_once "../model/servico.php";


try {
    $servicoDao = new ServicoDao();
    $servicos = $servicoDao->readAll();
    if ($servicos === null) {
        throw new Exception('Erro ao buscar serviços.');
    }

    $servicosArray = array_map(function ($servico) {
        return [
            'idServico' => $servico->getIdServico(),
            'nome' => $servico->getNomeServico(),
            'preco' => $servico->getPreco()
        ];
    }, $servicos);

    echo json_encode($servicosArray);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
