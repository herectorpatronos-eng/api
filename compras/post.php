<?php

try {
    // Recuperar informações via JSON e transformar em ARRAY
    $json = json_decode(file_get_contents('php://input'), true);

    // Listar campos
    $DATA = !empty($json['DATA']) ? trim($json['DATA']) : null;
    $FORMA_PGTO = !empty($json['FORMA_PGTO']) ? trim($json['FORMA_PGTO']) : null;
    $STATUS = !empty($json['STATUS']) ? trim($json['STATUS']) : 'Pendente';
    $FORNECEDOR = !empty($json['FORNECEDOR']) ? $json['FORNECEDOR'] : null;
    $PRODUTOS = !empty($json['PRODUTOS']) ? $json['PRODUTOS'] : [];

    // Verificar campos obrigatórios
    if (is_null($DATA) || empty($DATA)) {
        http_response_code(422);
        $response = [
            'STATUS' => 'error',
            'message' => 'Campo DATA é obrigatório'
        ];
        echo json_encode($response);
        exit;
    } elseif (is_null($FORMA_PGTO) || empty($FORMA_PGTO)) {
        http_response_code(422);
        $response = [
            'STATUS' => 'error',
            'message' => 'Campo FORMA DE PAGAMENTO é obrigatório'
        ];
        echo json_encode($response);
        exit;
    } elseif (is_null($FORNECEDOR) || empty($FORNECEDOR)) {
        http_response_code(422);
        $response = [
            'STATUS' => 'error',
            'message' => 'Campo FORNECEDOR é obrigatório'
        ];
        echo json_encode($response);
        exit;
    } elseif (is_null($PRODUTOS) || empty($PRODUTOS)) {
        http_response_code(422);
        $response = [
            'STATUS' => 'error',
            'message' => 'Campo PRODUTOS é obrigatório'
        ];
        echo json_encode($response);
        exit;
    }

    // Iniciando uma sequência de Querys
    $conn->beginTransaction();

    // Montar sintaxe SQL para gravar a Compra no banco de dados
    $sql = "
    INSERT INTO COMPRAS (DATA, FORMA_PGTO, STATUS, ID_FORNECEDOR)
    VALUES (:DATA, :FORMA_PGTO, :STATUS, :ID_FORNECEDOR)
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':DATA', $DATA, PDO::PARAM_STR);
    $stmt->bindValue(':FORMA_PGTO', $FORMA_PGTO, PDO::PARAM_STR);
    $stmt->bindValue(':STATUS', $STATUS, PDO::PARAM_STR);
    $stmt->bindValue(':ID_FORNECEDOR', $FORNECEDOR['id_FORNECEDOR'], PDO::PARAM_INT);
    $stmt->execute();

    // Recuperar ID_COMPRA para tabela rl_compras_PRODUTOS
    $id_compra = $conn->lastInsertId();

    // Montar sintaxe SQL para inserir PRODUTOS na compra
    $sql = "
    INSERT INTO RL_COMPRAS_PRODUTOS 
    VALUES (:ID_COMPRA, :ID_PRODUTO, :PRECO_UNIT, :QTDE)
    ";

    foreach ($PRODUTOS as $key => $item) {
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':ID_COMPRA', $id_compra, PDO::PARAM_INT);
        $stmt->bindValue(':ID_PRODUTO', $item['id_produto'], PDO::PARAM_INT);
        $stmt->bindValue(':PRECO_UNIT', $item['preco_unit'], PDO::PARAM_STR);
        $stmt->bindValue(':QTDE', $item['qtde'], PDO::PARAM_INT);
        $stmt->execute();
    }

    // Finaliza as sequências de Querys
    $conn->commit();

    $response = [
        'STATUS' => 'success',
        'message' => 'Compra salva com sucesso!',
        'id' => $id_compra
    ];
} catch (PDOException $e) {
    // Desfazer sequência de Querys em caso de erro
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }

    $response = [
        'STATUS' => 'error',
        'message' => $e->getMessage()
    ];
} finally {
    // Retornar resposta API
    echo json_encode($response ?? []);
}