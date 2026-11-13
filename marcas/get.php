<?php

try {
    // Verifica se está vindo filtro por ID
    if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) {
        $id = (int)trim($_GET['id']);

        $sql = "
        SELECT
            *
        FROM
            MARCAS
        WHERE
            ID_MARCA = :ID
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':ID', $id, PDO::PARAM_INT);
        $stmt->execute();
    } 
    // Verifica se está vindo filtro por MARCA
    elseif (isset($_GET['marca']) && is_string($_GET['marca']) && !empty($_GET['marca'])) {
        $marca = trim($_GET['marca']);

        $sql = "
        SELECT
            *
        FROM
            MARCAS
        WHERE
            MARCA LIKE :MARCA
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':MARCA', "%$marca%", PDO::PARAM_STR);
        $stmt->execute();
    } 
    // BUSCA GERAL DE CLIENTES 
    else {
        $sql = "
        SELECT
            *
        FROM
            MARCAS
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