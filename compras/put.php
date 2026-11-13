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

    // Verificar STATUS da COMPRA
    $sql = "
    SELECT
        STATUS
    FROM
        COMPRAS
    WHERE
        ID_COMPRA = :ID
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':ID', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Verifica se encontrou a COMPRA
    if ($stmt->rowCount() > 0) {
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        // Consultar STATUS
        if ($data['STATUS'] === 'Cancelado' || $data['STATUS'] === 'Finalizado') {
            http_response_code(400);
            $response = [
                'status' => 'error',
                'message' => 'Não é possível atualizar COMPRA com o STATUS "Finalizado" ou "Cancelado"'
            ];
            echo json_encode($response);
            exit;
        }
    } else {
        http_response_code(400);
        $response = [
            'status' => 'error',
            'message' => 'COMPRA não encontrada'
        ];
        echo json_encode($response);
        exit;
    }

    // Recuperar informações via JSON e transformar em ARRAY
    $json = json_decode(file_get_contents('php://input'), true);

    // Listar campos
    $data = !empty($json['data']) ? trim($json['data']) : null;
    $forma_pgto = !empty($json['forma_pgto']) ? trim($json['forma_pgto']) : null;
    $status = !empty($json['status']) ? trim($json['status']) : null;
    $fornecedor = !empty($json['fornecedor']) ? $json['fornecedor'] : null;
    $produtos = !empty($json['produtos']) ? $json['produtos'] : [];

    // Verificar campos obrigatórios
    if (is_null($data) || empty($data)) {
        http_response_code(422);
        $response = [
            'status' => 'error',
            'message' => 'Campo DATA é obrigatório'
        ];
        echo json_encode($response);
        exit;
    } elseif (is_null($forma_pgto) || empty($forma_pgto)) {
        http_response_code(422);
        $response = [
            'status' => 'error',
            'message' => 'Campo FORMA DE PAGAMENTO é obrigatório'
        ];
        echo json_encode($response);
        exit;
    } elseif (is_null($fornecedor) || empty($fornecedor)) {
        http_response_code(422);
        $response = [
            'status' => 'error',
            'message' => 'Campo FORNECEDOR é obrigatório'
        ];
        echo json_encode($response);
        exit;
    } elseif (is_null($produtos) || empty($produtos)) {
        http_response_code(422);
        $response = [
            'status' => 'error',
            'message' => 'Campo PRODUTOS é obrigatório'
        ];
        echo json_encode($response);
        exit;
    }

    // Iniciando uma sequência de Querys
    $conn->beginTransaction();

    // Montar sintaxe SQL para gravar no banco de dados
    $sql = "
    UPDATE COMPRAS SET
        DATA = :DATA,
        FORMA_PGTO = :FORMA_PGTO,
        STATUS = :STATUS,
        ID_FORNECEDOR = :ID_FORNECEDOR
    WHERE
        ID_COMPRA = :ID
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':DATA', $data, PDO::PARAM_STR);
    $stmt->bindValue(':FORMA_PGTO', $forma_pgto, PDO::PARAM_STR);
    $stmt->bindValue(':STATUS', $status, PDO::PARAM_STR);
    $stmt->bindValue(':ID_FORNECEDOR', $fornecedor['id_fornecedor'], PDO::PARAM_INT);
    $stmt->bindValue(':ID', $id, PDO::PARAM_INT);
    $stmt->execute();

    $sql = "
    DELETE FROM
        RL_COMPRAS_PRODUTOS
    WHERE
        ID_COMPRA = :ID
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':ID', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Montar sintaxe SQL para inserir produtos a compra
    $sql = "
    INSERT INTO RL_COMPRAS_PRODUTOS
    VALUES (:ID_COMPRA, :ID_PRODUTO, :PRECO_UNIT, :QTDE)
    ";

    foreach ($produtos as $key => $item) {
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':ID_COMPRA', $id, PDO::PARAM_INT);
        $stmt->bindValue(':ID_PRODUTO', $produtos[$key]['id_produto'], PDO::PARAM_INT);
        $stmt->bindValue(':PRECO_UNIT', $produtos[$key]['preco_unit'], PDO::PARAM_STR);
        $stmt->bindValue(':QTDE', $produtos[$key]['qtde'], PDO::PARAM_INT);
        $stmt->execute();
    }

    // Finaliza as sequências de Querys
    $conn->commit();


    $response = [
        'status' => 'success',
        'message' => 'Compra salva com sucesso!'
    ];
} catch (PDOException $e) {
    // Desfazer sequência de Querys
    $conn->rollBack();

    $response = [
        'status' => 'error',
        'message' => $e->getMessage()
    ];
} finally {
    // Retornar resposta API
    echo json_encode($response ?? []);
}
