<?php

$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "dbremideezy";

$conexao = new mysqli($host, $usuario, $senha, $banco);
if ($conexao->connect_error) {
    die("Erro de conexão: " . $conexao->connect_error);
}

if (isset($_POST["salvar"])) {
    $descricao = $_POST['descricao'];
    $dataInicio = $_POST['dataInicio'];
    $dataFim = $_POST['dataFim'];
    $categoria = $_POST['categoria'];

    $sql = "INSERT INTO tb_tarefa (descricao, dataInicio, dataFim,categoria) VALUES ('$descricao', '$dataInicio', '$dataFim' , '$categoria')";
    $_SERVER['HTTP_REFERER'] = 'http://localhost/Gestao_de_Tarefas/Tarefa.html';

        if ($conexao->query($sql) === TRUE) {
            echo "Dados cadastrados com sucesso!";
            header("Location: " . $_SERVER['HTTP_REFERER']);

            $sqlAtualizarNumeroTarefas = "UPDATE tb_categoria SET numero_tarefas = numero_tarefas + 1 WHERE categoria_nome = '$categoria'";
            if ($conexao->query($sqlAtualizarNumeroTarefas) === TRUE) {
                // Número de tarefas atualizado com sucesso
                echo "Dados cadastrados com sucesso!";
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit();
            } else {
                echo "Erro ao atualizar o número de tarefas para a categoria: " . $conexao->error;
            }

            // exit();
        } else {
            echo "Erro ao cadastrar os dados: " . $conexao->error;
        }
}else{
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $data = json_decode(file_get_contents('php://input'), true);

        // Verifica se o campo 'codigo_categoria' está presente
        if (isset($data['codigo_tarefa'])) {
            $codigoTarefa = $data['codigo_tarefa'];
            $sqlExcluir = "DELETE FROM tb_tarefa WHERE codigo = $codigoTarefa";
            $codigoTarefa = $data['codigo_tarefa'];
            
            if ($conexao->query($sqlExcluir) === TRUE) {
                // Resposta bem-sucedida
                http_response_code(200);
                echo "Categoria excluída com sucesso!";
                $sqlUpdate = "ALTER TABLE tb_tarefa AUTO_INCREMENT = 1";
                
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




    } elseif ($_SERVER['REQUEST_METHOD'] === 'UPDATE') {
    $data = json_decode(file_get_contents('php://input'), true);    

        // Verifica se os campos necessários estão presentes
    if (isset($data['codigo_tarefa']) && isset($data['nova_descricao'])) {
        $codigoTarefa = $data['codigo_tarefa'];
        $novaDescricao = $data['nova_descricao'];
        $novaDataInicio = $data['nova_data_inicio'];
        $novaDataFim = $data['nova_data_fim'];
        $novaCategoria = $data['nova_categoria'];

        // Lógica de atualização aqui...
        $sqlAtualizar = "UPDATE tb_tarefa SET descricao = '$novaDescricao', dataInicio = '$novaDataInicio', dataFim = '$novaDataFim', categoria = '$novaCategoria' WHERE codigo = $codigoTarefa";

        if ($conexao->query($sqlAtualizar) === TRUE) {
            // Resposta bem-sucedida
            http_response_code(200);
            echo "Tarefa atualizada com sucesso!";
        } else {
            // Erro ao atualizar tarefa
            http_response_code(500);
            echo "Erro ao atualizar tarefa: " . $conexao->error;
        }
        } else {
            // Se os campos necessários não estiverem presentes na requisição
            http_response_code(400); // Bad Request
            echo "Parâmetros necessários não fornecidos na requisição UPDATE.";
        }
    }


        }

        if (isset($_GET['codigo_tarefa'])) {
            $codigoTarefa = $_GET['codigo_tarefa'];
        
            // Consulta para obter os detalhes da tarefa com o código especificado fornecido
            $sql = "SELECT * FROM tb_tarefa WHERE codigo = $codigoTarefa";
            $resultado = $conexao->query($sql);
        
            // Verifica se a consulta foi bem-sucedida
            if ($resultado) {
                // Obtém os detalhes da tarefa
                $tarefa = $resultado->fetch_assoc();
        
                // Retorna os detalhes como JSON
                header('Content-Type: application/json');
                echo json_encode($tarefa);
            } else {
                // Se houver um erro na consulta, retorne um JSON com uma mensagem de erro
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Erro ao obter detalhes da tarefa']);
            }
        } else {
            // Se o código da tarefa não foi enviado, retorne um JSON com uma mensagem de erro
            header('Content-Type: application/json');
            //echo json_encode(['error' => 'Código da tarefa não fornecido']);
  		header("Location: " . $_SERVER['HTTP_REFERER']);
                exit();
        }

 $conexao->close();
?>