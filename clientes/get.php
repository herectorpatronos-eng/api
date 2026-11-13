<?php

try {
    // Verifica se está vindo filtro por ID
    if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) {
        $id = (int)trim($_GET['id']);

        $sql = "
        SELECT
            ID_CLIENTE, NOME, CPF, DATA_NASCIMENTO, WHATSAPP, EMAIL,
            JSON_OBJECT(
                'LOGRADOURO', LOGRADOURO,
                'NUMERO', NUMERO,
                'COMPLEMENTO', COMPLEMENTO,
                'BAIRRO', BAIRRO,
                'CIDADE', CIDADE,
                'ESTADO', ESTADO,
                'CEP', CEP
            ) AS ENDERECO
        FROM
            CLIENTES
        WHERE
            ID_CLIENTE = :ID
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':ID', $id, PDO::PARAM_INT);
        $stmt->execute();
    } 
    // Verifica se está vindo filtro por NOME
    elseif (isset($_GET['name']) && is_string($_GET['name']) && !empty($_GET['name'])) {
        $name = trim($_GET['name']);

        $sql = "
        SELECT
            ID_CLIENTE, NOME, CPF, DATA_NASCIMENTO, WHATSAPP, EMAIL,
            JSON_OBJECT(
                'LOGRADOURO', LOGRADOURO,
                'NUMERO', NUMERO,
                'COMPLEMENTO', COMPLEMENTO,
                'BAIRRO', BAIRRO,
                'CIDADE', CIDADE,
                'ESTADO', ESTADO,
                'CEP', CEP
            ) AS ENDERECO
        FROM
            CLIENTES
        WHERE
            NOME LIKE :NAME
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':NAME', "%$name%", PDO::PARAM_STR);
        $stmt->execute();
    } 
    // Verifica se está vindo filtro por CIDADE
    elseif (isset($_GET['city']) && is_string($_GET['city']) && !empty($_GET['city'])) {
        $city = trim($_GET['city']);

        $sql = "
        SELECT
            ID_CLIENTE, NOME, CPF, DATA_NASCIMENTO, WHATSAPP, EMAIL,
            JSON_OBJECT(
                'LOGRADOURO', LOGRADOURO,
                'NUMERO', NUMERO,
                'COMPLEMENTO', COMPLEMENTO,
                'BAIRRO', BAIRRO,
                'CIDADE', CIDADE,
                'ESTADO', ESTADO,
                'CEP', CEP
            ) AS ENDERECO
        FROM
            CLIENTES
        WHERE
            CIDADE LIKE :CITY
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':CITY', "%$city%", PDO::PARAM_STR);
        $stmt->execute();
    }
    // Verifica se está vindo filtro GERAL
    elseif (isset($_GET['search']) && is_string($_GET['search']) && !empty($_GET['search'])) {
        $search = trim($_GET['search']);

        $sql = "
        SELECT
            ID_CLIENTE, NOME, CPF, DATA_NASCIMENTO, WHATSAPP, EMAIL,
            JSON_OBJECT(
                'LOGRADOURO', LOGRADOURO,
                'NUMERO', NUMERO,
                'COMPLEMENTO', COMPLEMENTO,
                'BAIRRO', BAIRRO,
                'CIDADE', CIDADE,
                'ESTADO', ESTADO,
                'CEP', CEP
            ) AS ENDERECO
        FROM
            CLIENTES
        WHERE
            NOME LIKE :SEARCH
            OR CPF LIKE :SEARCH
            OR LOGRADOURO LIKE :SEARCH
            OR NUMERO LIKE :SEARCH
            OR COMPLEMENTO LIKE :SEARCH
            OR BAIRRO LIKE :SEARCH
            OR CIDADE LIKE :SEARCH
            OR ESTADO LIKE :SEARCH
            OR CEP LIKE :SEARCH
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':SEARCH', "%$search%", PDO::PARAM_STR);
        $stmt->execute();
    } 
    // BUSCA GERAL DE CLIENTES 
    else {
        $sql = "
        SELECT
            ID_CLIENTE, NOME, CPF, DATA_NASCIMENTO, WHATSAPP, EMAIL,
            JSON_OBJECT(
                'LOGRADOURO', LOGRADOURO,
                'NUMERO', NUMERO,
                'COMPLEMENTO', COMPLEMENTO,
                'BAIRRO', BAIRRO,
                'CIDADE', CIDADE,
                'ESTADO', ESTADO,
                'CEP', CEP
            ) AS ENDERECO
        FROM
            CLIENTES
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }

    // Verificar se encontrou registro
    if ($stmt->rowCount() > 0) {
        // Organizando registro como array
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as $key => $item) {
            // Transformar ENDERECO em objeto JSON
            $data[$key]['ENDERECO'] = !empty($item['ENDERECO']) ?
                json_decode($item['ENDERECO'], true) : [];
        }

        http_response_code(200);
        // Criar a resposta da API
        $response = [
            'status' => 'success',
            'data' => $data,
            'total' => count($data),
        ];
    } else {
        // Código para lista vazia
        http_response_code(204);
        exit;
    }
} catch (PDOException $e) {
    http_response_code(500);
    $response = [
        'status' => 'error',
        'message' => $e->getMessage()
    ];
} finally {
    // Retornar resposta API
    if (!empty($response)) {
        echo json_encode($response);
    }
}
?>