<?php

// retornar resposta da API em formato JSON
header("Content-Type: application/json");

$clientes = [
    [
        "id" => 1,
        "nome" => "João Silva",
        "cpf" => "123.456.789-01",
        "whatsapp" => "(11) 99999-0001",
        "email" => "joao.silva@email.com"
    ],
    [
        "id" => 2,
        "nome" => "Maria Santos",
        "cpf" => "234.567.890-12",
        "whatsapp" => "(11) 99999-0002",
        "email" => "maria.santos@email.com"
    ],
    [
        "id" => 3,
        "nome" => "Carlos Oliveira",
        "cpf" => "345.678.901-23",
        "whatsapp" => "(11) 99999-0003",
        "email" => "carlos.oliveira@email.com"
    ],
    [
        "id" => 4,
        "nome" => "Ana Costa",
        "cpf" => "456.789.012-34",
        "whatsapp" => "(11) 99999-0004",
        "email" => "ana.costa@email.com"
    ],
    [
        "id" => 5,
        "nome" => "Pedro Almeida",
        "cpf" => "567.890.123-45",
        "whatsapp" => "(11) 99999-0005",
        "email" => "pedro.almeida@email.com"
    ],
    [
        "id" => 6,
        "nome" => "Juliana Ferreira",
        "cpf" => "678.901.234-56",
        "whatsapp" => "(11) 99999-0006",
        "email" => "juliana.ferreira@email.com"
    ],
    [
        "id" => 7,
        "nome" => "Rafael Souza",
        "cpf" => "789.012.345-67",
        "whatsapp" => "(11) 99999-0007",
        "email" => "rafael.souza@email.com"
    ],
    [
        "id" => 8,
        "nome" => "Fernanda Lima",
        "cpf" => "890.123.456-78",
        "whatsapp" => "(11) 99999-0008",
        "email" => "fernanda.lima@email.com"
    ],
    [
        "id" => 9,
        "nome" => "Lucas Rodrigues",
        "cpf" => "901.234.567-89",
        "whatsapp" => "(11) 99999-0009",
        "email" => "lucas.rodrigues@email.com"
    ],
    [
        "id" => 10,
        "nome" => "Camila Martins",
        "cpf" => "012.345.678-90",
        "whatsapp" => "(11) 99999-0010",
        "email" => "camila.martins@email.com"
    ]
];

// Verificar qual método está sendo requisitado
$method = $_SERVER["REQUEST_METHOD"];

// GET = consulta de dados
if ($method === "GET") {
    // verificar se é busca por NOME
    if (isset($_GET['name'])) {
        $name = $_GET['name'];

        foreach ($clientes as $key => $item) {
            // if = Tem o nome pesquisado
            if (stripos($item['nome'], $name) !== false) {
                // echo $key . ' - ' . $item['nome'] . ' - Tem ' . $name;
                continue;
            } else {
                // echo $key . ' - ' . $item['nome'] . ' - Não tem ' . $name;
                // unset = remove o item do array
                unset($clientes[$key]);
            }

        }
    }

    // count = contar quantos itens existem no array
    if (count($clientes) === 0) {
        // 204 = No content
        http_response_code(204);
        exit;
    } else {
        $response = [
            'status' => 'success',
            'data' => $clientes,
            'total' => count($clientes),
        ];
        // json_encode = converte array em JSON
        echo json_encode($response);
        exit;
    }
}
