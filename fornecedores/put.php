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
    $RAZAO_SOCIAL = !empty($json['RAZAO_SOCIAL']) ? trim($json['RAZAO_SOCIAL']) : null;
    $NOME_FANTASIA = !empty($json['NOME_FANTASIA']) ? trim($json['NOME_FANTASIA']) : null;
    $CNPJ = !empty($json['CNPJ']) ? trim($json['CNPJ']) : null;
    $TELEFONE = !empty($json['TELEFONE']) ? trim($json['TELEFONE']) : null;
    $EMAIL = !empty($json['EMAIL']) ? trim($json['EMAIL']) : null;

    // Verificar campos obrigatórios
    if (is_null($RAZAO_SOCIAL) || empty($RAZAO_SOCIAL)) {
        http_response_code(422);
        $response = [
            'status' => 'error',
            'message' => 'Campo RAZÃO SOCIAL é obrigatório'
        ];
        echo json_encode($response);
        exit;
    } elseif (is_null($NOME_FANTASIA) || empty($NOME_FANTASIA)) {
        http_response_code(422);
        $response = [
            'status' => 'error',
            'message' => 'Campo NOME FANTASIA é obrigatório'
        ];
        echo json_encode($response);
        exit;
    } elseif (is_null($CNPJ) || empty($CNPJ)) {
        http_response_code(422);
        $response = [
            'status' => 'error',
            'message' => 'Campo CNPJ é obrigatório'
        ];
        echo json_encode($response);
        exit;
    }

    // Montar sintaxe SQL para gravar no banco de dados
    $sql = "
    UPDATE FORNECEDORES SET
        RAZAO_SOCIAL = :RAZAO_SOCIAL,
        NOME_FANTASIA = :NOME_FANTASIA,
        CNPJ = :CNPJ,
        TELEFONE = :TELEFONE,
        EMAIL = :EMAIL
    WHERE
        ID_FORNECEDOR = :ID
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':ID', $id, PDO::PARAM_INT);
    $stmt->bindValue(':RAZAO_SOCIAL', $RAZAO_SOCIAL, PDO::PARAM_STR);
    $stmt->bindValue(':NOME_FANTASIA', $NOME_FANTASIA, PDO::PARAM_STR);
    $stmt->bindValue(':CNPJ', $CNPJ, PDO::PARAM_STR);
    $stmt->bindValue(':TELEFONE', $TELEFONE, is_null($TELEFONE) ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':EMAIL', $EMAIL, is_null($EMAIL) ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->execute();

    $response = [
        'status' => 'success',
        'message' => 'Fornecedor salvo com sucesso!'
    ];
} catch (PDOException $e) {
    switch ($e->getCode()) {
        case 23000:
            $response = [
                'status' => 'error',
                'message' => 'RAZÃO SOCIAL e CNPJ já estão cadastrados em outro fornecedor.'
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
