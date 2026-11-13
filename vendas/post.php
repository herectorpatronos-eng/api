<?php

try {
    // Recuperar informações via JSON e transformar em ARRAY
    $json = json_decode(file_get_contents('php://input'), true);

    // Listar campos
    $data = !empty($json['data']) ? trim($json['data']) : null;
    $forma_pgto = !empty($json['forma_pgto']) ? trim($json['forma_pgto']) : null;
    $status = !empty($json['status']) ? trim($json['status']) : 'Pendente';
    $cliente = !empty($json['cliente']) ? $json['cliente'] : null;
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
    } elseif (is_null($cliente) || empty($cliente)) {
        http_response_code(422);
        $response = [
            'status' => 'error',
            'message' => 'Campo CLIENTE é obrigatório'
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

    // Montar sintaxe SQL para gravar a Venda no banco de dados
    $sql = "
    INSERT INTO VENDAS (DATA, FORMA_PGTO, STATUS, ID_CLIENTE)
    VALUES (:DATA, :FORMA_PGTO, :STATUS, :ID_CLIENTE)
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':DATA', $data, PDO::PARAM_STR);
    $stmt->bindValue(':FORMA_PGTO', $forma_pgto, PDO::PARAM_STR);
    $stmt->bindValue(':STATUS', $status, PDO::PARAM_STR);
    $stmt->bindValue(':ID_CLIENTE', $cliente['id_cliente'], PDO::PARAM_INT);
    $stmt->execute();

    // Recuperar ID_VENDA para tabela rl_vendas_produtos
    $id_venda = $conn->lastInsertId();

    // Montar sintaxe SQL para inserir produtos na venda
    $sql = "
    INSERT INTO RL_VENDAS_PRODUTOS 
    VALUES (:ID_VENDA, :ID_PRODUTO, :PRECO_UNIT, :QTDE)
    ";

    foreach ($produtos as $key => $item) {
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':ID_VENDA', $id_venda, PDO::PARAM_INT);
        $stmt->bindValue(':ID_PRODUTO', $item['id_produto'], PDO::PARAM_INT);
        $stmt->bindValue(':PRECO_UNIT', $item['preco_unit'], PDO::PARAM_STR);
        $stmt->bindValue(':QTDE', $item['qtde'], PDO::PARAM_INT);
        $stmt->execute();
    }

    // Finaliza as sequências de Querys
    $conn->commit();

    $response = [
        'status' => 'success',
        'message' => 'Venda salva com sucesso!',
        'id' => $id_venda
    ];
} catch (PDOException $e) {
    // Desfazer sequência de Querys em caso de erro
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }

    $response = [
        'status' => 'error',
        'message' => $e->getMessage()
    ];
} finally {
    // Retornar resposta API
    echo json_encode($response ?? []);
}