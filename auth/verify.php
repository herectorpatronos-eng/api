<?php

// Permitir resposta em JSON com UTF-8
header("Content-Type: application/json; charset=UTF-8");

try {
    session_start();
    // Renova o ID da sessão para evitar ataques fixos
    session_regenerate_id(true);
    // Verifica se todas as variáveis de sessão estão definidas
    if (
        !isset($_SESSION['ID_ADMINISTRADOR']) ||
        !isset($_SESSION['NOME_ADMINISTRADOR']) ||
        !isset($_SESSION['PERMISSAO_ADMINISTRADOR'])
    ) {
        http_response_code(401);
        throw new Exception('Usuário não autenticado');
    }

} catch (Exception $e) {
    http_response_code(401);
    $response = [
        'status' => 'error',
        'message' => $e->getMessage()
    ];
    echo json_encode($response);
    exit;
}