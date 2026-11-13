<?php
// Cabeçalhos de permissão de acesso
header("Access-Control-Allow-Origin: *");
// Permitir métodos HTTP específicos
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
// Permitir cabeçalhos específicos
header("Access-Control-Allow-Headers: Content-Type, Authorization");
// Permitir resposta em JSON com UTF-8
header("Content-Type: application/json; charset=UTF-8");

// Verificar qual método está sendo requisitado
$method = $_SERVER["REQUEST_METHOD"];
// Se o método for OPTIONS, apenas retorna 200 OK e encerra a execução
if ($method === "OPTIONS") {
    http_response_code(200);
    exit;
}

// database host
$db_host = "localhost";
// database name
$db_name = "tnet03";
// database username
$db_user = "root";
// database password
$db_pass = "root";

try {
    // CRIA UMA CONEXÃO PDO COM O BANCO DE DADOS
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    // CONFIGURA O MODO DE ERRO DO PDO PARA EXCEÇÃO
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Conexão realizada com sucesso!";
} catch (PDOException $e) {
    // RESPONSE CODE 500 = ERRO INTERNO DO SERVIDOR
    http_response_code(500);
    // SE OCORRER UM ERRO NA CONEXÃO, EXIBE A MENSAGEM
    $response = [
        'status' => 'error',
        'message' => $e->getMessage()
    ];
    // json_encode = converte array em JSON
    echo json_encode($response);
    exit;
}
