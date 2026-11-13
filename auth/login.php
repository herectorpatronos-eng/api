<?php
// Importar arquivo de conexão com banco de dados
require __DIR__ . '/../conn.php';

try {
    // Verifica se usuário digitou EMAIL
    if (isset($_GET['email']) && !empty($_GET['email']) && is_string($_GET['email'])) {
        $email = trim($_GET['email']);
    } else {
        http_response_code(422);
        throw new Exception('Campo E-MAIL é obrigatório');
    }

    // Verifica se usuário digitou SENHA
    if (isset($_GET['senha']) && !empty($_GET['senha']) && is_string($_GET['senha'])) {
        $senha = trim($_GET['senha']);
    } else {
        http_response_code(422);
        throw new Exception('Campo SENHA é obrigatório');
    }

    // Verifica se EMAIL está cadastrado no banco de dados
    $sql = "
    SELECT
        ID_ADMINISTRADOR, NOME, SENHA, PERMISSAO, ATIVO
    FROM
        ADMINISTRADORES
    WHERE
        EMAIL = :EMAIL
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':EMAIL', $email, PDO::PARAM_STR);
    $stmt->execute();

    // Verifica se encontrou registro
    if ($stmt->rowCount() > 0) {
        // Recuperar informações do banco de dados
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        // Verificar se SENHA está correta
        if (password_verify($senha, $data['SENHA'])) {
            // Verifica se usuário está ATIVO
            if ($data['ATIVO'] === true || $data['ATIVO'] === 1) {
                // Iniciar uma sessão GLOBAL
                session_start();
                // Criar autentição do usuário
                $_SESSION['ID_ADMINISTRADOR'] = $data['ID_ADMINISTRADOR'];
                $_SESSION['NOME_ADMINISTRADOR'] = $data['NOME'];
                $_SESSION['PERMISSAO_ADMINISTRADOR'] = $data['PERMISSAO'];
                // Gerar resposta
                $response = [
                    'status' => 'success',
                    'data' => [
                        'id_administrador' => $data['ID_ADMINISTRADOR'],
                        'nome_administrador' => $data['NOME'],
                        'permissao_administrador' => $data['PERMISSAO']
                    ],
                    'token' => session_id()
                ];
                
            } else {
                http_response_code(401);
                throw new Exception('Usuário está INATIVO');
            }
        } else {
            http_response_code(401);
            throw new Exception('SENHA incorreta');
        }
    } else {
        http_response_code(401);
        throw new Exception('E-MAIL incorreto');
    }
} catch (PDOException $e) {
    $response = [
        'status' => 'error',
        'message' => $e->getMessage()
    ];
} catch (Exception $e) {
    $response = [
        'status' => 'error',
        'message' => $e->getMessage()
    ];
} finally {
    // Retornar resposta API
    echo json_encode($response ?? []);
}
