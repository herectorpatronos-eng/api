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

    // Verificar STATUS da VENDA
    $sql = "
    SELECT
        STATUS
    FROM
        VENDAS
    WHERE
        ID_VENDA = :ID
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':ID', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Verifica se encontrou a VENDA
    if ($stmt->rowCount() > 0) {
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        // Consultar STATUS
        if ($data['STATUS'] === 'Cancelado' || $data['STATUS'] === 'Finalizado'){
            $response = [
                'status' => 'error',
                'message' => 'Não é possível excluir VENDA com o STATUS "Finalizado" ou "Cancelado"'
            ];
            echo json_encode($response);
            exit;
        }
    }

    // Montar sintaxe SQL para gravar no banco de dados
    $sql = "
    DELETE FROM 
        VENDAS
    WHERE
        ID_VENDA = :ID
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':ID', $id, PDO::PARAM_INT);
    $stmt->execute();

    $response = [
        'status' => 'success',
        'message' => 'Venda removida com sucesso!'
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
