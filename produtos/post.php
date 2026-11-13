<?php

try {
    // Recuperar informações via JSON e transformar em ARRAY
    $json = json_decode(file_get_contents('php://input'), true);

    // Listar campos
    $PRODUTO = !empty($json['PRODUTO']) ? trim($json['PRODUTO']) : null;
    $DESCRICAO = !empty($json['DESCRICAO']) ? trim($json['DESCRICAO']) : null;
    $PRECO_UNIT = !empty($json['PRECO_UNIT']) ? trim($json['PRECO_UNIT']) : null;
    $QTDE = !empty($json['QTDE']) ? trim($json['QTDE']) : null;
    $MARCA = !empty($json['MARCA']) ? $json['MARCA'] : null;

    // Verificar campos obrigatórios
    if (is_null($PRODUTO) || empty($PRODUTO)) {
        http_response_code(422);
        $response = [
            'status' => 'error',
            'message' => 'Campo PRODUTO é obrigatório'
        ];
        echo json_encode($response);
        exit;
    } elseif (is_null($PRECO_UNIT) || empty($PRECO_UNIT)) {
        http_response_code(422);
        $response = [
            'status' => 'error',
            'message' => 'Campo PREÇO UNITÁRIO é obrigatório'
        ];
        echo json_encode($response);
        exit;
    } elseif (is_null($QTDE) || empty($QTDE)) {
        http_response_code(422);
        $response = [
            'status' => 'error',
            'message' => 'Campo QUANTIDADE é obrigatório'
        ];
        echo json_encode($response);
        exit;
    } elseif (is_null($MARCA) || empty($MARCA)) {
        http_response_code(422);
        $response = [
            'status' => 'error',
            'message' => 'Campo MARCA é obrigatório'
        ];
        echo json_encode($response);
        exit;
    }

    // Formatar campo PRECO_UNIT
    if (!strpos($PRECO_UNIT, ',') === false) {
        $PRECO_UNIT = str_replace('.', '', $PRECO_UNIT); // 9.999,99 => 9999,99
        $PRECO_UNIT = str_replace(',', '.', $PRECO_UNIT); // 9999,99 => 9999.99
    }

    // Montar sintaxe SQL para gravar no banco de dados
    $sql = "
    INSERT INTO PRODUTOS (PRODUTO, DESCRICAO, PRECO_UNIT, QTDE, ID_MARCA)
    VALUES (:PRODUTO, :DESCRICAO, :PRECO_UNIT, :QTDE, :ID_MARCA)
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':PRODUTO', $PRODUTO, PDO::PARAM_STR);
    $stmt->bindValue(':DESCRICAO', $DESCRICAO, is_null($DESCRICAO) ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':PRECO_UNIT', $PRECO_UNIT, PDO::PARAM_STR);
    $stmt->bindValue(':QTDE', $QTDE, PDO::PARAM_INT);
    $stmt->bindValue(':ID_MARCA', $MARCA['ID_MARCA'], PDO::PARAM_INT);
    $stmt->execute();

    $response = [
        'status' => 'success',
        'message' => 'Produto salvo com sucesso!',
        'id' => $conn->lastInsertId()
    ];
} catch (PDOException $e) {
            $response = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
} finally {
    // Retornar resposta API
    echo json_encode($response ?? []);
}
