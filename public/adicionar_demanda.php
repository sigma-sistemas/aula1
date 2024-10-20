<?php
// Incluir a configuração do banco de dados
require_once('../config/db.php');

// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['adicionar_demanda'])) {
    $titulo = $conn->real_escape_string($_POST['titulo']);
    $criador = $conn->real_escape_string($_POST['criador']);
    $responsavel = $conn->real_escape_string($_POST['responsavel_atual']);
    $detalhes = $conn->real_escape_string($_POST['detalhes']);  // Novo campo
    $data_criacao = date('Y-m-d H:i:s');
    $status = 'Em Progresso';

    // Inserir a nova demanda na tabela
    $sql = "INSERT INTO demandas (titulo, criador, data_criacao, status, responsavel_atual, detalhes) VALUES ('$titulo', '$criador', '$data_criacao', '$status', '$responsavel', '$detalhes')";
    
    if ($conn->query($sql) === TRUE) {
        $demanda_id = $conn->insert_id;

        // Adicionar o histórico inicial da demanda
        $historico_sql = "INSERT INTO historico_demandas (demanda_id, data_atualizacao, responsavel) VALUES ('$demanda_id', '$data_criacao', '$responsavel')";
        $conn->query($historico_sql);

        // Redirecionar para a página inicial
        header("Location: index.php");
    } else {
        echo "Erro ao adicionar a demanda: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Nova Demanda</title>
</head>
<body>
    <h1>Adicionar Nova Demanda</h1>
    <form action="adicionar_demanda.php" method="POST">
        <label for="titulo">Título:</label><br>
        <input type="text" id="titulo" name="titulo" required><br><br>

        <label for="criador">Criador:</label><br>
        <input type="text" id="criador" name="criador" required><br><br>

        <label for="responsavel_atual">Responsável Atual:</label><br>
        <input type="text" id="responsavel_atual" name="responsavel_atual" required><br><br>

        <label for="detalhes">Detalhes da Demanda:</label><br>
        <textarea id="detalhes" name="detalhes" rows="5" cols="40" required></textarea><br><br>

        <input type="submit" name="adicionar_demanda" value="Adicionar Demanda">
    </form>
</body>
</html>
