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
    $NOME = !empty($json['NOME']) ? trim($json['NOME']) : null;
    $CPF = !empty($json['CPF']) ? trim($json['CPF']) : null;
    $DATA_NASCIMENTO = !empty($json['DATA_NASCIMENTO']) ? trim($json['DATA_NASCIMENTO']) : null;
    $WHATSAPP = !empty($json['WHATSAPP']) ? trim($json['WHATSAPP']) : null;
    $EMAIL = !empty($json['EMAIL']) ? trim($json['EMAIL']) : null;
    $ENDERECO = !empty($json['ENDERECO']) ? $json['ENDERECO'] : [];

    // Verificar campos obrigatórios
    if (is_null($NOME) || empty($NOME)) {
        http_response_code(422);
        $response = [
            'status' => 'error',
            'message' => 'Campo NOME é obrigatório'
        ];
        echo json_encode($response);
        exit;
    } elseif (is_null($CPF) || empty($CPF)) {
        http_response_code(422);
        $response = [
            'status' => 'error',
            'message' => 'Campo CPF é obrigatório'
        ];
        echo json_encode($response);
        exit;
    }

    // Montar sintaxe SQL para gravar no banco de dados
    $sql = "
    UPDATE CLIENTES SET
        NOME = :NOME, 
        CPF = :CPF, 
        DATA_NASCIMENTO = :DATA_NASCIMENTO, 
        WHATSAPP = :WHATSAPP, 
        EMAIL = :EMAIL, 
        LOGRADOURO = :LOGRADOURO, 
        NUMERO = :NUMERO, 
        COMPLEMENTO = :COMPLEMENTO, 
        BAIRRO = :BAIRRO, 
        CIDADE = :CIDADE, 
        ESTADO = :ESTADO, 
        CEP = :CEP
    WHERE
        ID_CLIENTE = :ID
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':ID', $id, PDO::PARAM_INT);
    $stmt->bindValue(':NOME', $NOME, PDO::PARAM_STR);
    $stmt->bindValue(':CPF', $CPF, PDO::PARAM_STR);
    $stmt->bindValue(':DATA_NASCIMENTO', $DATA_NASCIMENTO, PDO::PARAM_STR);
    $stmt->bindValue(':WHATSAPP', $WHATSAPP, PDO::PARAM_STR);
    $stmt->bindValue(':EMAIL', $EMAIL, PDO::PARAM_STR);
    $stmt->bindValue(':LOGRADOURO', $ENDERECO['LOGRADOURO'], is_null($ENDERECO['LOGRADOURO']) ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':NUMERO', $ENDERECO['NUMERO'], is_null($ENDERECO['NUMERO']) ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':COMPLEMENTO', $ENDERECO['COMPLEMENTO'], is_null($ENDERECO['COMPLEMENTO']) ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':BAIRRO', $ENDERECO['BAIRRO'], is_null($ENDERECO['BAIRRO']) ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':CIDADE', $ENDERECO['CIDADE'], is_null($ENDERECO['CIDADE']) ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':ESTADO', $ENDERECO['ESTADO'], is_null($ENDERECO['ESTADO']) ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':CEP', $ENDERECO['CEP'], is_null($ENDERECO['CEP']) ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->execute();

    $response = [
        'status' => 'success',
        'message' => 'Cliente salvo com sucesso!'
    ];
} catch (PDOExCEPtion $e) {
    switch ($e->getCode()) {
        case 23000:
            http_response_code(400);
            $response = [
                'status' => 'error',
                'message' => 'CPF já está cadastrado em outro cliente.'
            ];
            break;
        default:
        http_response_code(500);
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
