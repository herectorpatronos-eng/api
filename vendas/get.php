<?php

try {
    // Verifica se está vindo filtro por ID
    if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) {
        $id = (int)trim($_GET['id']);

        $sql = "
        SELECT
            V.ID_VENDA, DATE_FORMAT(V.DATA, '%d/%m/%Y') AS DATA, V.FORMA_PGTO, V.STATUS,
            JSON_OBJECT (
                'ID_CLIENTE', C.ID_CLIENTE, 
                'NOME', C.NOME,
                'CPF', C.CPF,
                'ENDERECO', JSON_OBJECT(
                    'LOGRADOURO', C.LOGRADOURO,
                    'NUMERO', C.NUMERO,
                    'COMPLEMENTO', C.COMPLEMENTO,
                    'BAIRRO', C.BAIRRO,
                    'CIDADE', C.CIDADE,
                    'ESTADO', C.ESTADO,
                    'CEP', C.CEP
                )
            ) AS CLIENTE,

            JSON_ARRAYAGG(
                JSON_OBJECT (
                    'ID_PRODUTO', P.ID_PRODUTO, 
                    'PRODUTO', P.PRODUTO,
                    'DESCRICAO', P.DESCRICAO,
                    'MARCA', JSON_OBJECT(
                        'ID_MARCA', M.ID_MARCA,
                        'MARCA', M.MARCA
                    ),
                    'PRECO_UNIT', RL.PRECO_UNIT,
                    'QTDE', RL.QTDE
                )
            ) AS PRODUTOS
        FROM
            VENDAS AS V
        JOIN
            CLIENTES AS C ON V.ID_CLIENTE = C.ID_CLIENTE
        JOIN
            RL_VENDAS_PRODUTOS AS RL ON V.ID_VENDA = RL.ID_VENDA
        JOIN
            PRODUTOS AS P ON RL.ID_PRODUTO = P.ID_PRODUTO
        JOIN
            MARCAS AS M ON P.ID_MARCA = M.ID_MARCA
        WHERE
            V.ID_VENDA = :ID
        GROUP BY
            V.ID_VENDA
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':ID', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
    // Verifica se está vindo filtro por PRODUTO
    elseif (isset($_GET['product']) && is_string($_GET['product']) && !empty($_GET['product'])) {
        $product = trim($_GET['product']);

        $sql = "
        SELECT
            V.ID_VENDA, DATE_FORMAT(V.DATA, '%d/%m/%Y') AS DATA, V.FORMA_PGTO, V.STATUS,
            JSON_OBJECT (
                'ID_CLIENTE', C.ID_CLIENTE, 
                'NOME', C.NOME,
                'CPF', C.CPF,
                'ENDERECO', JSON_OBJECT(
                    'LOGRADOURO', C.LOGRADOURO,
                    'NUMERO', C.NUMERO,
                    'COMPLEMENTO', C.COMPLEMENTO,
                    'BAIRRO', C.BAIRRO,
                    'CIDADE', C.CIDADE,
                    'ESTADO', C.ESTADO,
                    'CEP', C.CEP
                )
            ) AS CLIENTE,
             
            JSON_ARRAYAGG(
                JSON_OBJECT (
                    'ID_PRODUTO', P.ID_PRODUTO, 
                    'PRODUTO', P.PRODUTO,
                    'DESCRICAO', P.DESCRICAO,
                    'MARCA', JSON_OBJECT(
                        'ID_MARCA', M.ID_MARCA,
                        'MARCA', M.MARCA
                    ),
                    'PRECO_UNIT', RL.PRECO_UNIT,
                    'QTDE', RL.QTDE
                )
            ) AS PRODUTOS
        FROM
            VENDAS AS V
        JOIN
            CLIENTES AS C ON V.ID_CLIENTE = C.ID_CLIENTE
        JOIN
            RL_VENDAS_PRODUTOS AS RL ON V.ID_VENDA = RL.ID_VENDA
        JOIN
            PRODUTOS AS P ON RL.ID_PRODUTO = P.ID_PRODUTO
        JOIN
            MARCAS AS M ON P.ID_MARCA = M.ID_MARCA
        WHERE
            P.PRODUTO LIKE :PRODUTO
        GROUP BY
            V.ID_VENDA
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':PRODUTO', "%$product%", PDO::PARAM_STR);
        $stmt->execute();
    }
    // Verifica se está vindo filtro por MARCA
    elseif (isset($_GET['marca']) && is_string($_GET['marca']) && !empty($_GET['marca'])) {
        $marca = trim($_GET['marca']);

        $sql = "
        SELECT
            V.ID_VENDA, DATE_FORMAT(V.DATA, '%d/%m/%Y') AS DATA, V.FORMA_PGTO, V.STATUS,
            JSON_OBJECT (
                'ID_CLIENTE', C.ID_CLIENTE, 
                'NOME', C.NOME,
                'CPF', C.CPF,
                'ENDERECO', JSON_OBJECT(
                    'LOGRADOURO', C.LOGRADOURO,
                    'NUMERO', C.NUMERO,
                    'COMPLEMENTO', C.COMPLEMENTO,
                    'BAIRRO', C.BAIRRO,
                    'CIDADE', C.CIDADE,
                    'ESTADO', C.ESTADO,
                    'CEP', C.CEP
                )
            ) AS CLIENTE,

            JSON_ARRAYAGG(
                JSON_OBJECT (
                    'ID_PRODUTO', P.ID_PRODUTO, 
                    'PRODUTO', P.PRODUTO,
                    'DESCRICAO', P.DESCRICAO,
                    'MARCA', JSON_OBJECT(
                        'ID_MARCA', M.ID_MARCA,
                        'MARCA', M.MARCA
                    ),
                    'PRECO_UNIT', RL.PRECO_UNIT,
                    'QTDE', RL.QTDE
                )
            ) AS PRODUTOS
        FROM
            VENDAS AS V
        JOIN
            CLIENTES AS C ON V.ID_CLIENTE = C.ID_CLIENTE
        JOIN
            RL_VENDAS_PRODUTOS AS RL ON V.ID_VENDA = RL.ID_VENDA
        JOIN
            PRODUTOS AS P ON RL.ID_PRODUTO = P.ID_PRODUTO
        JOIN
            MARCAS AS M ON P.ID_MARCA = M.ID_MARCA
        WHERE
            M.MARCA LIKE :MARCA
        GROUP BY
            V.ID_VENDA
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':MARCA', "%$marca%", PDO::PARAM_STR);
        $stmt->execute();
    }
    // Verifica se está vindo filtro por CLIENTE
    elseif (isset($_GET['cliente']) && is_string($_GET['cliente']) && !empty($_GET['cliente'])) {
        $cliente = trim($_GET['cliente']);

        $sql = "
        SELECT
            V.ID_VENDA, DATE_FORMAT(V.DATA, '%d/%m/%Y') AS DATA, V.FORMA_PGTO, V.STATUS,
            JSON_OBJECT (
                'ID_CLIENTE', C.ID_CLIENTE, 
                'NOME', C.NOME,
                'CPF', C.CPF,
                'ENDERECO', JSON_OBJECT(
                    'LOGRADOURO', C.LOGRADOURO,
                    'NUMERO', C.NUMERO,
                    'COMPLEMENTO', C.COMPLEMENTO,
                    'BAIRRO', C.BAIRRO,
                    'CIDADE', C.CIDADE,
                    'ESTADO', C.ESTADO,
                    'CEP', C.CEP
                )
            ) AS CLIENTE,

            JSON_ARRAYAGG(
                JSON_OBJECT (
                    'ID_PRODUTO', P.ID_PRODUTO, 
                    'PRODUTO', P.PRODUTO,
                    'DESCRICAO', P.DESCRICAO,
                    'MARCA', JSON_OBJECT(
                        'ID_MARCA', M.ID_MARCA,
                        'MARCA', M.MARCA
                    ),
                    'PRECO_UNIT', RL.PRECO_UNIT,
                    'QTDE', RL.QTDE
                )
            ) AS PRODUTOS
        FROM
            VENDAS AS V
        JOIN
            CLIENTES AS C ON V.ID_CLIENTE = C.ID_CLIENTE
        JOIN
            RL_VENDAS_PRODUTOS AS RL ON V.ID_VENDA = RL.ID_VENDA
        JOIN
            PRODUTOS AS P ON RL.ID_PRODUTO = P.ID_PRODUTO
        JOIN
            MARCAS AS M ON P.ID_MARCA = M.ID_MARCA
        WHERE
            C.NOME LIKE :CLIENTE
            OR C.NOME_FANTASIA LIKE :CLIENTE
            OR C.CPF LIKE :CLIENTE
        GROUP BY
            V.ID_VENDA
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':CLIENTE', "%$cliente%", PDO::PARAM_STR);
        $stmt->execute();
    }
    // Verifica se está vindo filtro GERAL
    elseif (isset($_GET['search']) && is_string($_GET['search']) && !empty($_GET['search'])) {
        $search = trim($_GET['search']);

        $sql = "
        SELECT
            V.ID_VENDA, DATE_FORMAT(V.DATA, '%d/%m/%Y') AS DATA, V.FORMA_PGTO, V.STATUS,
            JSON_OBJECT (
                'ID_CLIENTE', C.ID_CLIENTE, 
                'NOME', C.NOME,
                'CPF', C.CPF,
                'ENDERECO', JSON_OBJECT(
                    'LOGRADOURO', C.LOGRADOURO,
                    'NUMERO', C.NUMERO,
                    'COMPLEMENTO', C.COMPLEMENTO,
                    'BAIRRO', C.BAIRRO,
                    'CIDADE', C.CIDADE,
                    'ESTADO', C.ESTADO,
                    'CEP', C.CEP
                )
            ) AS CLIENTE,

            JSON_ARRAYAGG(
                JSON_OBJECT (
                    'ID_PRODUTO', P.ID_PRODUTO, 
                    'PRODUTO', P.PRODUTO,
                    'DESCRICAO', P.DESCRICAO,
                    'MARCA', JSON_OBJECT(
                        'ID_MARCA', M.ID_MARCA,
                        'MARCA', M.MARCA
                    ),
                    'PRECO_UNIT', RL.PRECO_UNIT,
                    'QTDE', RL.QTDE
                )
            ) AS PRODUTOS
        FROM
            VENDAS AS V
        JOIN
            CLIENTES AS C ON V.ID_CLIENTE = C.ID_CLIENTE
        JOIN
            RL_VENDAS_PRODUTOS AS RL ON V.ID_VENDA = RL.ID_VENDA
        JOIN
            PRODUTOS AS P ON RL.ID_PRODUTO = P.ID_PRODUTO
        JOIN
            MARCAS AS M ON P.ID_MARCA = M.ID_MARCA
        WHERE
            P.PRODUTO LIKE :SEARCH
            OR P.DESCRICAO LIKE :SEARCH
            OR P.PRECO_UNIT LIKE :SEARCH
            OR P.QTDE LIKE :SEARCH
            OR M.MARCA LIKE :SEARCH
            OR C.NOME LIKE :SEARCH
            OR C.NOME_FANTASIA LIKE :SEARCH
            OR C.CPF LIKE :SEARCH
        GROUP BY
            V.ID_VENDA
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':SEARCH', "%$search%", PDO::PARAM_STR);
        $stmt->execute();
    }
    // BUSCA GERAL DE CLIENTES 
    else {
        $sql = "
        SELECT
            V.ID_VENDA, DATE_FORMAT(V.DATA, '%d/%m/%Y') AS DATA, V.FORMA_PGTO, V.STATUS,
            JSON_OBJECT (
                'ID_CLIENTE', C.ID_CLIENTE, 
                'NOME', C.NOME,
                'CPF', C.CPF,
                'ENDERECO', JSON_OBJECT(
                    'LOGRADOURO', C.LOGRADOURO,
                    'NUMERO', C.NUMERO,
                    'COMPLEMENTO', C.COMPLEMENTO,
                    'BAIRRO', C.BAIRRO,
                    'CIDADE', C.CIDADE,
                    'ESTADO', C.ESTADO,
                    'CEP', C.CEP
                )
            ) AS CLIENTE,

            JSON_ARRAYAGG(
                JSON_OBJECT (
                    'ID_PRODUTO', P.ID_PRODUTO, 
                    'PRODUTO', P.PRODUTO,
                    'DESCRICAO', P.DESCRICAO,
                    'MARCA', JSON_OBJECT(
                        'ID_MARCA', M.ID_MARCA,
                        'MARCA', M.MARCA
                    ),
                    'PRECO_UNIT', RL.PRECO_UNIT,
                    'QTDE', RL.QTDE
                )
            ) AS PRODUTOS
        FROM
            VENDAS AS V
        JOIN
            CLIENTES AS C ON V.ID_CLIENTE = C.ID_CLIENTE
        JOIN
            RL_VENDAS_PRODUTOS AS RL ON V.ID_VENDA = RL.ID_VENDA
        JOIN
            PRODUTOS AS P ON RL.ID_PRODUTO = P.ID_PRODUTO
        JOIN
            MARCAS AS M ON P.ID_MARCA = M.ID_MARCA
        GROUP BY
            V.ID_VENDA
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }

    // Verificar se encontrou registro
    if ($stmt->rowCount() > 0) {
        // Organizando registro como array
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($data as $key => $item) {
            // Transformar coluna CLIENTE em objeto JSON
            $data[$key]['CLIENTE'] = !empty($data[$key]['CLIENTE']) ?
                json_decode($data[$key]['CLIENTE']) : [];
            // Transformar coluna PRODUTOS em objeto JSON
            $data[$key]['PRODUTOS'] = !empty($data[$key]['PRODUTOS']) ?
                json_decode($data[$key]['PRODUTOS']) : [];
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
