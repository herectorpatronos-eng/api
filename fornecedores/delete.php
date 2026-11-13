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

    // Montar sintaxe SQL para gravar no banco de dados
    $sql = "
    DELETE FROM 
        FORNECEDORES
    WHERE
        ID_FORNECEDOR = :ID
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':ID', $id, PDO::PARAM_INT);
    $stmt->execute();

    $response = [
        'status' => 'success',
        'message' => 'Fornecedor removido com sucesso!'
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
