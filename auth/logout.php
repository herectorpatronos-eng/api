<?php

// Permitir resposta em JSON com UTF-8
header("Content-Type: application/json; charset=UTF-8");

try {
    session_start();
    unset($_SESSION);
    session_destroy();

    $response = [
        'status' => 'success',
        'message' => 'Logout realizado com sucesso'
    ];
} catch (Exception $e) {
    $response = [
        'status' => 'error',
        'message' => $e->getMessage()
    ];
} finally {
    // Retornar resposta API
    echo json_encode($response ?? []);
}
