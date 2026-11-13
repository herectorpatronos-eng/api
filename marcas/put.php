<?php

try {
    // Recuperar ID do registro a ser atualizado
    if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
        $id = (int)$_GET['id'];
    } else {
        http_response_code(400);
        $response = [
            'status' => 'error',
            'message' => 'Campo ID é obrigatório'
        ];
        echo json_encode($response);
        exit;
    }

    // Recuperar informações via JSON e transformar em ARRAY
    $json = json_decode(file_get_contents('php://input'), true);

    // Listar campos
    $marca = !empty($json['marca']) ? trim($json['marca']) : null;

    // Verificar campos obrigatórios
    if (is_null($marca) || empty($marca)) {
        http_response_code(422);
        $response = [
            'status' => 'error',
            'message' => 'Campo MARCA é obrigatório'
        ];
        echo json_encode($response);
        exit;
    }

    // Montar sintaxe SQL para gravar no banco de dados
    $sql = "
    UPDATE MARCAS SET
        MARCA = :MARCA
    WHERE
        ID_MARCA = :ID
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':ID', $id, PDO::PARAM_INT);
    $stmt->bindValue(':MARCA', $marca, PDO::PARAM_STR);
    $stmt->execute();

    $response = [
        'status' => 'success',
        'message' => 'Marca salva com sucesso!'
    ];
} catch (PDOException $e) {
    switch ($e->getCode()) {
        case 23000:
            $response = [
                'status' => 'error',
                'message' => 'MARCA já está cadastrada em outro registro.'
            ];
            break;
        default:
            $response = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
            break;
    }
} finally {
    // Retornar resposta API
    echo json_encode($response ?? []);
}
