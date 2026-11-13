<?php

try {
    // Caminho das imagens
    $url = "http://$_SERVER[HTTP_HOST]/produtos/imagens/";
    // Verifica se está vindo filtro por ID
    if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) {
        $id = (int)trim($_GET['id']);

        $sql = "
        SELECT
            P.ID_PRODUTO, P.PRODUTO, P.DESCRICAO, P.PRECO_UNIT, P.QTDE, 
            IF(P.IMAGEM <> '', CONCAT('$url', P.IMAGEM), NULL) AS IMAGEM,
            JSON_OBJECT (
                'ID_MARCA', M.ID_MARCA, 'MARCA', M.MARCA
            ) AS MARCA
        FROM
            PRODUTOS AS P
        LEFT JOIN
            MARCAS AS M ON P.ID_MARCA = M.ID_MARCA
        WHERE
            P.ID_PRODUTO = :ID
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
            P.ID_PRODUTO, P.PRODUTO, P.DESCRICAO, P.PRECO_UNIT, P.QTDE,
            IF(P.IMAGEM <> '', CONCAT('$url', P.IMAGEM), NULL) AS IMAGEM,
            JSON_OBJECT (
                'ID_MARCA', M.ID_MARCA, 'MARCA', M.MARCA
            ) AS MARCA
        FROM
            PRODUTOS AS P
        LEFT JOIN
            MARCAS AS M ON P.ID_MARCA = M.ID_MARCA
        WHERE
            P.PRODUTO LIKE :PRODUTO
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
            P.ID_PRODUTO, P.PRODUTO, P.DESCRICAO, P.PRECO_UNIT, P.QTDE,
            IF(P.IMAGEM <> '', CONCAT('$url', P.IMAGEM), NULL) AS IMAGEM,
            JSON_OBJECT (
                'ID_MARCA', M.ID_MARCA, 'MARCA', M.MARCA
            ) AS MARCA
        FROM
            PRODUTOS AS P
        LEFT JOIN
            MARCAS AS M ON P.ID_MARCA = M.ID_MARCA
        WHERE
            M.MARCA LIKE :MARCA
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':MARCA', "%$marca%", PDO::PARAM_STR);
        $stmt->execute();
    }
    // Verifica se está vindo filtro GERAL
    elseif (isset($_GET['search']) && is_string($_GET['search']) && !empty($_GET['search'])) {
        $search = trim($_GET['search']);

        $sql = "
        SELECT
            P.ID_PRODUTO, P.PRODUTO, P.DESCRICAO, P.PRECO_UNIT, P.QTDE,
            IF(P.IMAGEM <> '', CONCAT('$url', P.IMAGEM), NULL) AS IMAGEM,
            JSON_OBJECT (
                'ID_MARCA', M.ID_MARCA, 'MARCA', M.MARCA
            ) AS MARCA
        FROM
            PRODUTOS AS P
        LEFT JOIN
            MARCAS AS M ON P.ID_MARCA = M.ID_MARCA
        WHERE
            P.PRODUTO LIKE :SEARCH
            OR P.DESCRICAO LIKE :SEARCH
            OR P.PRECO_UNIT LIKE :SEARCH
            OR P.QTDE LIKE :SEARCH
            OR M.MARCA LIKE :SEARCH
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':SEARCH', "%$search%", PDO::PARAM_STR);
        $stmt->execute();
    }
    // BUSCA GERAL DE CLIENTES 
    else {
        $sql = "
        SELECT
            P.ID_PRODUTO, P.PRODUTO, P.DESCRICAO, P.PRECO_UNIT, P.QTDE,
            IF(P.IMAGEM <> '', CONCAT('$url', P.IMAGEM), NULL) AS IMAGEM,
            JSON_OBJECT (
                'ID_MARCA', M.ID_MARCA, 'MARCA', M.MARCA
            ) AS MARCA
        FROM
            PRODUTOS AS P
        LEFT JOIN
            MARCAS AS M ON P.ID_MARCA = M.ID_MARCA
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }

    // Verificar se encontrou registro
    if ($stmt->rowCount() > 0) {
        // Organizando registro como array
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Transformar coluna MARCA em objeto JSON
        foreach($data as $key => $item) {
            $data[$key]['MARCA'] = json_decode($data[$key]['MARCA']);
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
