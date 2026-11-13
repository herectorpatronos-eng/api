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
    $PRODUTO = !empty($json['PRODUTO']) ? trim($json['PRODUTO']) : null;
    $DESCRICAO = !empty($json['DESCRICAO']) ? trim($json['DESCRICAO']) : null;
    $PRECO_UNIT = !empty($json['PRECO_UNIT']) ? trim($json['PRECO_UNIT']) : null;
    $QTDE = !empty($json['QTDE']) ? trim($json['QTDE']) : null;
    $MARCA = !empty($json['MARCA']) ? $json['MARCA'] : null;
    $IMAGEM = !empty($json['IMAGEM']) ? $json['IMAGEM'] : null;

    // Verificar campos obrigatórios
    if (is_null($PRODUTO) || empty($PRODUTO)) {
        http_response_code(422);
        $response = [
            'status' => 'error',
            'message' => 'Campo PRODUTO é obrigatório'
        ];
        echo json_encode($response);
        exit;
    } elseif (is_null($PRECO_UNIT) || empty($PRECO_UNIT)) {
        http_response_code(422);
        $response = [
            'status' => 'error',
            'message' => 'Campo PREÇO UNITÁRIO é obrigatório'
        ];
        echo json_encode($response);
        exit;
    } elseif (is_null($QTDE) || empty($QTDE)) {
        http_response_code(422);
        $response = [
            'status' => 'error',
            'message' => 'Campo QUANTIDADE é obrigatório'
        ];
        echo json_encode($response);
        exit;
    } elseif (is_null($MARCA) || empty($MARCA)) {
        http_response_code(422);
        $response = [
            'status' => 'error',
            'message' => 'Campo MARCA é obrigatório'
        ];
        echo json_encode($response);
        exit;
    }

    // Formatar campo PRECO_UNIT
    if (!strpos($PRECO_UNIT, ',') === false) {
        $PRECO_UNIT = str_replace('.', '', $PRECO_UNIT); // 9.999,99 => 9999,99
        $PRECO_UNIT = str_replace(',', '.', $PRECO_UNIT); // 9999,99 => 9999.99
    }

    // Verifica se imagem foi enviada
    $NOME_IMAGEM = null;
    if (!empty($IMAGEM)) {
        // Verifica se precisa manter a imagem atual
        if (!strpos($IMAGEM, 'produtos') === false) {
            $explode = explode('/', $IMAGEM);
            $NOME_IMAGEM = end($explode);
        } else {
            // Separar blocos da string IMAGEM
            $explode = explode(",", $IMAGEM);
            // Verificar se é imagem
            $extensoes_permitidas = array('jpg', 'jpeg', 'png', 'webp');
            $permitir = false;
            $extensao_imagem = "";
            // Verifica extensões permitidas
            foreach ($extensoes_permitidas as $extensao) {
                if (!strpos($explode[0], $extensao) === false) {
                    $permitir = true;
                    $extensao_imagem = $extensao;
                }
            }
            // Verifica se o arquivo é uma extensão NÃO permitida
            if ($permitir === false) {
                throw new Exception("Formato de imagem não permitida!");
            }
            // Converter Base64 em imagem
            $imagemBinaria = base64_decode($explode[1], true);
            // Verifica se a imagem foi criada
            if ($imagemBinaria === false) {
                throw new Exception("Imagem inválida!");
            }
            // Gerar nome aleatório para imagem
            $NOME_IMAGEM = uniqid() . "." . $extensao_imagem;
            // Gravar imagem na pasta
            file_put_contents(
                __DIR__ . "/imagens/$NOME_IMAGEM",
                $imagemBinaria
            );
            // Consultar imagem antiga para deletar
            $sql = "
            SELECT 
                IMAGEM
            FROM 
                PRODUTOS
            WHERE 
                ID_PRODUTO = :ID
            ";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':ID', $id, PDO::PARAM_INT);
            $stmt->execute();

            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            // Deletar imagem antiga na pasta
            @unlink(__DIR__ . "/imagens/$data[IMAGEM]");
        }
    }

    // Montar sintaxe SQL para gravar no banco de dados
    $sql = "
    UPDATE PRODUTOS SET
        PRODUTO = :PRODUTO,
        DESCRICAO = :DESCRICAO,
        PRECO_UNIT = :PRECO_UNIT,
        QTDE = :QTDE,
        ID_MARCA = :ID_MARCA,
        IMAGEM = :IMAGEM
    WHERE
        ID_PRODUTO = :ID
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':ID', $id, PDO::PARAM_INT);
    $stmt->bindValue(':PRODUTO', $PRODUTO, PDO::PARAM_STR);
    $stmt->bindValue(':DESCRICAO', $DESCRICAO, is_null($DESCRICAO) ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':PRECO_UNIT', $PRECO_UNIT, PDO::PARAM_STR);
    $stmt->bindValue(':QTDE', $QTDE, PDO::PARAM_INT);
    $stmt->bindValue(':ID_MARCA', $MARCA['ID_MARCA'], PDO::PARAM_INT);
    $stmt->bindValue(':IMAGEM', $NOME_IMAGEM, is_null($NOME_IMAGEM) ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->execute();

    $response = [
        'status' => 'success',
        'message' => 'Produto salvo com sucesso!'
    ];
} catch (PDOException $e) {
    http_response_code(400);
    $response = [
        'status' => 'error',
        'message' => $e->getMessage()
    ];
} catch (Exception $e) {
    http_response_code(400);
    $response = [
        'status' => 'error',
        'message' => $e->getMessage()
    ];
} finally {
    // Retornar resposta API
    echo json_encode($response ?? []);
}
