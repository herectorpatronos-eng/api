<?php

try {
    // Verifica se está vindo filtro por ID
    if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) {
        $id = (int)trim($_GET['id']);

        $sql = "
        SELECT
            C.ID_COMPRA, DATE_FORMAT(C.DATA, '%d/%m/%Y') AS DATA, C.FORMA_PGTO, C.STATUS,
            JSON_OBJECT (
                'ID_FORNECEDOR', F.ID_FORNECEDOR, 
                'RAZAO_SOCIAL', F.RAZAO_SOCIAL,
                'NOME_FANTASIA', F.NOME_FANTASIA,
                'CNPJ', F.CNPJ,
                'TELEFONE', F.TELEFONE,
                'EMAIL', F.EMAIL
            ) AS FORNECEDOR,

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
            COMPRAS AS C
        JOIN
            FORNECEDORES AS F ON C.ID_FORNECEDOR = F.ID_FORNECEDOR
        JOIN
            RL_COMPRAS_PRODUTOS AS RL ON C.ID_COMPRA = RL.ID_COMPRA
        JOIN
            PRODUTOS AS P ON RL.ID_PRODUTO = P.ID_PRODUTO
        JOIN
            MARCAS AS M ON P.ID_MARCA = M.ID_MARCA
        WHERE
            C.ID_COMPRA = :ID
        GROUP BY
            C.ID_COMPRA
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
            C.ID_COMPRA, DATE_FORMAT(C.DATA, '%d/%m/%Y') AS DATA, C.FORMA_PGTO, C.STATUS,
            JSON_OBJECT (
                'ID_FORNECEDOR', F.ID_FORNECEDOR, 
                'RAZAO_SOCIAL', F.RAZAO_SOCIAL,
                'NOME_FANTASIA', F.NOME_FANTASIA,
                'CNPJ', F.CNPJ,
                'TELEFONE', F.TELEFONE,
                'EMAIL', F.EMAIL
            ) AS FORNECEDOR,
             
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
            COMPRAS AS C
        JOIN
            FORNECEDORES AS F ON C.ID_FORNECEDOR = F.ID_FORNECEDOR
        JOIN
            RL_COMPRAS_PRODUTOS AS RL ON C.ID_COMPRA = RL.ID_COMPRA
        JOIN
            PRODUTOS AS P ON RL.ID_PRODUTO = P.ID_PRODUTO
        JOIN
            MARCAS AS M ON P.ID_MARCA = M.ID_MARCA
        WHERE
            P.PRODUTO LIKE :PRODUTO
        GROUP BY
            C.ID_COMPRA
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
            C.ID_COMPRA, DATE_FORMAT(C.DATA, '%d/%m/%Y') AS DATA, C.FORMA_PGTO, C.STATUS,
            JSON_OBJECT (
                'ID_FORNECEDOR', F.ID_FORNECEDOR, 
                'RAZAO_SOCIAL', F.RAZAO_SOCIAL,
                'NOME_FANTASIA', F.NOME_FANTASIA,
                'CNPJ', F.CNPJ,
                'TELEFONE', F.TELEFONE,
                'EMAIL', F.EMAIL
            ) AS FORNECEDOR,

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
            COMPRAS AS C
        JOIN
            FORNECEDORES AS F ON C.ID_FORNECEDOR = F.ID_FORNECEDOR
        JOIN
            RL_COMPRAS_PRODUTOS AS RL ON C.ID_COMPRA = RL.ID_COMPRA
        JOIN
            PRODUTOS AS P ON RL.ID_PRODUTO = P.ID_PRODUTO
        JOIN
            MARCAS AS M ON P.ID_MARCA = M.ID_MARCA
        WHERE
            M.MARCA LIKE :MARCA
        GROUP BY
            C.ID_COMPRA
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':MARCA', "%$marca%", PDO::PARAM_STR);
        $stmt->execute();
    }
    // Verifica se está vindo filtro por FORNECEDOR
    elseif (isset($_GET['fornecedor']) && is_string($_GET['fornecedor']) && !empty($_GET['fornecedor'])) {
        $fornecedor = trim($_GET['fornecedor']);

        $sql = "
        SELECT
            C.ID_COMPRA, DATE_FORMAT(C.DATA, '%d/%m/%Y') AS DATA, C.FORMA_PGTO, C.STATUS,
            JSON_OBJECT (
                'ID_FORNECEDOR', F.ID_FORNECEDOR, 
                'RAZAO_SOCIAL', F.RAZAO_SOCIAL,
                'NOME_FANTASIA', F.NOME_FANTASIA,
                'CNPJ', F.CNPJ,
                'TELEFONE', F.TELEFONE,
                'EMAIL', F.EMAIL
            ) AS FORNECEDOR,

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
            COMPRAS AS C
        JOIN
            FORNECEDORES AS F ON C.ID_FORNECEDOR = F.ID_FORNECEDOR
        JOIN
            RL_COMPRAS_PRODUTOS AS RL ON C.ID_COMPRA = RL.ID_COMPRA
        JOIN
            PRODUTOS AS P ON RL.ID_PRODUTO = P.ID_PRODUTO
        JOIN
            MARCAS AS M ON P.ID_MARCA = M.ID_MARCA
        WHERE
            F.RAZAO_SOCIAL LIKE :FORNECEDOR
            OR F.NOME_FANTASIA LIKE :FORNECEDOR
            OR F.CNPJ LIKE :FORNECEDOR
        GROUP BY
            C.ID_COMPRA
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':FORNECEDOR', "%$fornecedor%", PDO::PARAM_STR);
        $stmt->execute();
    }
    // Verifica se está vindo filtro GERAL
    elseif (isset($_GET['search']) && is_string($_GET['search']) && !empty($_GET['search'])) {
        $search = trim($_GET['search']);

        $sql = "
        SELECT
            C.ID_COMPRA, DATE_FORMAT(C.DATA, '%d/%m/%Y') AS DATA, C.FORMA_PGTO, C.STATUS,
            JSON_OBJECT (
                'ID_FORNECEDOR', F.ID_FORNECEDOR, 
                'RAZAO_SOCIAL', F.RAZAO_SOCIAL,
                'NOME_FANTASIA', F.NOME_FANTASIA,
                'CNPJ', F.CNPJ,
                'TELEFONE', F.TELEFONE,
                'EMAIL', F.EMAIL
            ) AS FORNECEDOR,

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
            COMPRAS AS C
        JOIN
            FORNECEDORES AS F ON C.ID_FORNECEDOR = F.ID_FORNECEDOR
        JOIN
            RL_COMPRAS_PRODUTOS AS RL ON C.ID_COMPRA = RL.ID_COMPRA
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
            OR F.RAZAO_SOCIAL LIKE :SEARCH
            OR F.NOME_FANTASIA LIKE :SEARCH
            OR F.CNPJ LIKE :SEARCH
        GROUP BY
            C.ID_COMPRA
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':SEARCH', "%$search%", PDO::PARAM_STR);
        $stmt->execute();
    }
    // BUSCA GERAL DE CLIENTES 
    else {
        $sql = "
        SELECT
            C.ID_COMPRA, DATE_FORMAT(C.DATA, '%d/%m/%Y') AS DATA, C.FORMA_PGTO, C.STATUS,
            JSON_OBJECT (
                'ID_FORNECEDOR', F.ID_FORNECEDOR, 
                'RAZAO_SOCIAL', F.RAZAO_SOCIAL,
                'NOME_FANTASIA', F.NOME_FANTASIA,
                'CNPJ', F.CNPJ,
                'TELEFONE', F.TELEFONE,
                'EMAIL', F.EMAIL
            ) AS FORNECEDOR,

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
            COMPRAS AS C
        JOIN
            FORNECEDORES AS F ON C.ID_FORNECEDOR = F.ID_FORNECEDOR
        JOIN
            RL_COMPRAS_PRODUTOS AS RL ON C.ID_COMPRA = RL.ID_COMPRA
        JOIN
            PRODUTOS AS P ON RL.ID_PRODUTO = P.ID_PRODUTO
        JOIN
            MARCAS AS M ON P.ID_MARCA = M.ID_MARCA
        GROUP BY
            C.ID_COMPRA
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }

    // Verificar se encontrou registro
    if ($stmt->rowCount() > 0) {
        // Organizando registro como array
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($data as $key => $item) {
            // Transformar coluna FORNECEDOR em objeto JSON
            $data[$key]['FORNECEDOR'] = !empty($data[$key]['FORNECEDOR']) ?
                json_decode($data[$key]['FORNECEDOR']) : [];
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
