<?php

try {
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
    INSERT INTO 
        MARCAS (MARCA)
    VALUES
        (:MARCA)
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':MARCA', $marca, PDO::PARAM_STR);
    $stmt->execute();

    $response = [
        'status' => 'success',
        'message' => 'Marca salva com sucesso!',
        'id' => $conn->lastInsertId()
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
