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
    UPDATE ADMINISTRADORES SET
        NOME = :NOME,
        EMAIL = :EMAIL,
        SENHA = :SENHA,
        PERMISSAO = :PERMISSAO,
        ATIVO = :ATIVO
    WHERE
        ID_ADMINISTRADOR = :ID
    ";

    // Gerando criptografia da senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':ID', $id, PDO::PARAM_INT);
    $stmt->bindValue(':NOME', $nome, PDO::PARAM_STR);
    $stmt->bindValue(':EMAIL', $email, PDO::PARAM_STR);
    $stmt->bindValue(':SENHA', $senha_hash, PDO::PARAM_STR);
    $stmt->bindValue(':PERMISSAO', $permissao, PDO::PARAM_STR);
    $stmt->bindValue(':ATIVO', $ativo, PDO::PARAM_BOOL);
    $stmt->execute();

    $response = [
        'status' => 'success',
        'message' => 'Administrador salvo com sucesso!'
    ];
} catch (PDOException $e) {
    switch ($e->getCode()) {
        case 23000:
            $response = [
                'status' => 'error',
                'message' => 'E-MAIL já estão cadastrados em outro administrador.'
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
