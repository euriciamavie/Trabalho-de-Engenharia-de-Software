<?php

$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "dbremideezy";

$conexao = new mysqli($host, $usuario, $senha, $banco);
if ($conexao->connect_error) {
    die("Erro de conexão: " . $conexao->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sqlListarCategorias = "SELECT codigo, categoria_nome FROM tb_categoria";
    $result = $conexao->query($sqlListarCategorias);

    if ($result) {
        $categorias = array();

        while ($row = $result->fetch_assoc()) {
            $categorias[] = $row;
        }
        header('Content-Type: application/json');
        echo json_encode($categorias);
        exit();
    } else {
        http_response_code(500);
        echo "Erro ao obter categorias: " . $conexao->error;
        exit();
    }
}



if (isset($_POST["salvar"])) {
    $categoria = $_POST['categoria'];

    $sql = "INSERT INTO tb_categoria (categoria_nome,criado_em, atualizado_em, numero_tarefas) VALUES ('$categoria', NOW(), NOW(), 0)";
    $_SERVER['HTTP_REFERER'] = 'http://localhost/Gestao_de_Tarefas/categoria.html';

        if ($conexao->query($sql) === TRUE) {
            echo "Dados cadastrados com sucesso!";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            echo "Erro ao cadastrar os dados: " . $conexao->error;
        }
}else{
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $data = json_decode(file_get_contents('php://input'), true);

        // Verifica se o campo 'codigo_categoria' está presente
        if (isset($data['codigo_categoria'])) {
            $codigoCategoria = $data['codigo_categoria'];
            $sqlExcluir = "DELETE FROM tb_categoria WHERE codigo = $codigoCategoria";
    
            if ($conexao->query($sqlExcluir) === TRUE) {
                // Resposta bem-sucedida
                http_response_code(200);
                echo "Categoria excluída com sucesso!";
                $sqlUpdate = "ALTER TABLE tb_categoria AUTO_INCREMENT = 1";
                
                if ($conexao->query($sqlUpdate) === TRUE) {
                    echo "Autoincrement resetado com sucesso!";
                } else {
                    echo "Erro ao resetar o autoincrement: " . $conn->error;
                }

            } else {
                // Erro ao excluir categoria
                http_response_code(500);
                echo "Erro ao excluir categoria: " . $conexao->error;
            }
        } else {
            // Se o campo 'codigo_categoria' não estiver presente na requisição
            http_response_code(400); // Bad Request
            echo "Parâmetro 'codigo_categoria' não fornecido na requisição DELETE.";
        }
    }
}
 $conexao->close();
?>