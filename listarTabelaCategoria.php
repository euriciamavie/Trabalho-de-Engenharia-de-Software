<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "dbremideezy";

$conexao = new mysqli($host, $usuario, $senha, $banco);

if ($conexao->connect_error) {
    die("Erro de conexão: " . $conexao->connect_error);
}

$sql = "SELECT codigo, categoria_nome, criado_em, atualizado_em,numero_tarefas FROM tb_categoria";
$result = $conexao->query($sql);

// Verifique se há resultados
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Adicione as linhas da tabela com os dados do banco de dados
        echo "<tr>";
        echo "<td>" . $row["codigo"] . "</td>";
        echo "<td>" . $row["categoria_nome"] . "</td>";
        echo "<td>" . $row["criado_em"] . "</td>";
        echo "<td>" . $row["atualizado_em"] . "</td>";
        echo "<td>" . $row["numero_tarefas"] . "</td>"; // Adicione esta coluna conforme necessário
        echo '<td><button class="excluir-button" onclick="excluirLinha(this)" data-codigo="' . $row["codigo"] . '" name="excluir"> Excluir </button></td>';       
        // Adicione esta coluna conforme necessário
        echo "</tr>";
    }
} else {
    // echo "0 resultados";
}
// Feche a conexão
$conexao->close();
?>