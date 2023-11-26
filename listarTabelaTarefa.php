<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "dbremideezy";

$conexao = new mysqli($host, $usuario, $senha, $banco);

if ($conexao->connect_error) {
    die("Erro de conexão: " . $conexao->connect_error);
}

$sql = "SELECT codigo, descricao, dataInicio, dataFim,categoria FROM tb_tarefa";
$result = $conexao->query($sql);


$diaAtual = date("d");
$mesAtual = date("m");
$anoAtual = date("Y");
// Verifique se há resultados
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Adicione as linhas da tabela com os dados do banco de dados
        $diaFim = date("d", strtotime($row["dataFim"])); // Obtém o dia da data de fim
        $mesFim = date("m", strtotime($row["dataFim"]));
        $anoFim = date("Y", strtotime($row["dataFim"]));

        // Compara ambos, dia e mês
        $status = (
            $anoFim > $anoAtual ||
            ($anoFim == $anoAtual && $mesFim > $mesAtual) ||
            ($anoFim == $anoAtual && $mesFim == $mesAtual && $diaFim >= $diaAtual)
        ) ? "Pendente" : "Concluído";
       
        $statusClass = ($status == "Pendente") ? "pendente" : "concluido";


        echo "<tr>";
        echo "<td>" . $row["codigo"] . "</td>";
        echo "<td style='max-width: 150px; word-wrap: break-word;'>" . $row["descricao"] . "</td>";
        echo "<td>" . $row["dataInicio"] . "</td>";
        echo "<td>" . $row["dataFim"] . "</td>";
        echo "<td style='max-width: 150px; word-wrap: break-word;'>" . $row["categoria"] . "</td>";
        echo "<td class='" . $statusClass . "'>".$status."</td>";
        echo '<td>
        <button class="editar-button" onclick="prepararEdicao(this)" name="editar" id="openModalUP" data-codigo="' . $row["codigo"] . '">Editar</button>
        <button class="excluir-button" onclick="excluirLinha(this)" data-codigo="' . $row["codigo"] . '" name="excluir"> Excluir </button></td>';      
        // Adicione esta coluna conforme necessário
        echo "</tr>";
    }
} else {
    // echo "0 resultados";
}
// Feche a conexão
$conexao->close();
?>