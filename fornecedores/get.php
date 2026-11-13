<?php

try {
    // Verifica se está vindo filtro por ID
    if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) {
        $id = (int)trim($_GET['id']);

        $sql = "
        SELECT
            *
        FROM
            FORNECEDORES
        WHERE
            ID_FORNECEDOR = :ID
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
            *
        FROM
            FORNECEDORES
        WHERE
            RAZAO_SOCIAL LIKE :NAME
            OR NOME_FANTASIA LIKE :NAME
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':NAME', "%$name%", PDO::PARAM_STR);
        $stmt->execute();
    } 
    // Verifica se está vindo filtro por CIDADE
    elseif (isset($_GET['cnpj']) && is_string($_GET['cnpj']) && !empty($_GET['cnpj'])) {
        $cnpj = trim($_GET['cnpj']);

        $sql = "
        SELECT
            *
        FROM
            FORNECEDORES
        WHERE
            CNPJ LIKE :CNPJ
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':CNPJ', "%$cnpj%", PDO::PARAM_STR);
        $stmt->execute();
    }
    // Verifica se está vindo filtro GERAL
    elseif (isset($_GET['search']) && is_string($_GET['search']) && !empty($_GET['search'])) {
        $search = trim($_GET['search']);

        $sql = "
        SELECT
            *
        FROM
            FORNECEDORES
        WHERE
            RAZAO_SOCIAL LIKE :SEARCH
            OR NOME_FANTASIA LIKE :SEARCH
            OR CNPJ LIKE :SEARCH
            OR TELEFONE LIKE :SEARCH
            OR EMAIL LIKE :SEARCH
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':SEARCH', "%$search%", PDO::PARAM_STR);
        $stmt->execute();
    } 
    // BUSCA GERAL DE CLIENTES 
    else {
        $sql = "
        SELECT
            *
        FROM
            FORNECEDORES
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }

    // Verificar se encontrou registro
    if ($stmt->rowCount() > 0) {
        // Organizando registro como array
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

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