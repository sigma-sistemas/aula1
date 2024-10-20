<?php
// Incluir a configuração do banco de dados
require_once('../config/db.php');

// Verificar se foi solicitado o ID da demanda a ser editada
if (isset($_GET['id'])) {
    $demanda_id = intval($_GET['id']);

    // Consultar os dados da demanda atual
    $sql = "SELECT * FROM demandas WHERE id = $demanda_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $demanda = $result->fetch_assoc();
    } else {
        die("Demanda não encontrada.");
    }
} else {
    die("ID da demanda não fornecido.");
}

// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['atualizar_responsavel'])) {
    $novo_responsavel = $conn->real_escape_string($_POST['responsavel_atual']);
    //$data_atualizacao = date('Y-m-d');
    $data_atualizacao = date('Y-m-d H:i:s');
    // Atualizar o responsável atual da demanda
    $sql_update = "UPDATE demandas SET responsavel_atual = '$novo_responsavel' WHERE id = $demanda_id";
    
    if ($conn->query($sql_update) === TRUE) {
        // Inserir no histórico o novo responsável
        $historico_sql = "INSERT INTO historico_demandas (demanda_id, data_atualizacao, responsavel) 
                          VALUES ('$demanda_id', '$data_atualizacao', '$novo_responsavel')";
        $conn->query($historico_sql);

        echo "Responsável atualizado com sucesso!";
        // Redirecionar para a página inicial
        header("Location: index.php");
        exit;
    } else {
        echo "Erro ao atualizar o responsável: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar Demanda</title>
</head>
<body>
    <h1>Atualizar Responsável pela Demanda</h1>

    <form action="atualizar_demanda.php?id=<?php echo $demanda_id; ?>" method="POST">
        <label for="titulo">Título:</label><br>
        <input type="text" id="titulo" name="titulo" value="<?php echo $demanda['titulo']; ?>" disabled><br><br>

        <label for="responsavel_atual">Responsável Atual:</label><br>
        <input type="text" id="responsavel_atual" name="responsavel_atual" value="<?php echo $demanda['responsavel_atual']; ?>" required><br><br>

        <input type="submit" name="atualizar_responsavel" value="Atualizar Responsável">
    </form>
</body>
</html>
