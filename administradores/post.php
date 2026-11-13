<?php

try {
    // Recuperar informações via JSON e transformar em ARRAY
    $json = json_decode(file_get_contents('php://input'), true);

    // Listar campos
    $nome = !empty($json['nome']) ? trim($json['nome']) : null;
    $email = !empty($json['email']) ? trim($json['email']) : null;
    $senha = !empty($json['senha']) ? trim($json['senha']) : null;
    $permissao = !empty($json['permissao']) ? trim($json['permissao']) : null;
    $ativo = !empty($json['ativo']) ? trim($json['ativo']) : false;

    // Verificar campos obrigatórios
    if (is_null($nome) || empty($nome)) {
        http_response_code(422);
        $response = [
            'status' => 'error',
            'message' => 'Campo NOME é obrigatório'
        ];
        echo json_encode($response);
        exit;
    } elseif (is_null($email) || empty($email)) {
        http_response_code(422);
        $response = [
            'status' => 'error',
            'message' => 'Campo EMAIL é obrigatório'
        ];
        echo json_encode($response);
        exit;
    } elseif (is_null($senha) || empty($senha)) {
        http_response_code(422);
        $response = [
            'status' => 'error',
            'message' => 'Campo SENHA é obrigatório'
        ];
        echo json_encode($response);
        exit;
    }

    // Montar sintaxe SQL para gravar no banco de dados
    $sql = "
    INSERT INTO ADMINISTRADORES (NOME, EMAIL, SENHA, PERMISSAO, ATIVO)
    VALUES (:NOME, :EMAIL, :SENHA, :PERMISSAO, :ATIVO)
    ";

    // Gerando criptografia da senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':NOME', $nome, PDO::PARAM_STR);
    $stmt->bindValue(':EMAIL', $email, PDO::PARAM_STR);
    $stmt->bindValue(':SENHA', $senha_hash, PDO::PARAM_STR);
    $stmt->bindValue(':PERMISSAO', $permissao, PDO::PARAM_STR);
    $stmt->bindValue(':ATIVO', $ativo, PDO::PARAM_BOOL);
    $stmt->execute();

    $response = [
        'status' => 'success',
        'message' => 'Administrador salvo com sucesso!',
        'id' => $conn->lastInsertId()
    ];
} catch (PDOException $e) {
    switch ($e->getCode()) {
        case 23000:
            $response = [
                'status' => 'error',
                'message' => 'RAZÃO SOCIAL e PERMISSAO já estão cadastrados em outro fornecedor.'
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
