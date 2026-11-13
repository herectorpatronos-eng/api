<?php
// Importar arquivo de verificação do login
require __DIR__ . '/../auth/verify.php';

// Importar arquivo de conexão com banco de dados
require __DIR__ . '/../conn.php';

switch ($method) {
    case 'GET':
        require __DIR__ . '/get.php';
        break;
    case 'POST':
        require __DIR__ . '/post.php';
        break;
    case 'PUT':
        require __DIR__ . '/put.php';
        break;
    case 'DELETE':
        require __DIR__ . '/delete.php';
        break;
}
