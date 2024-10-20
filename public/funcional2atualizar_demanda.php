<?php
// Incluir a configuração do banco de dados
require_once('../config/db.php');

// Verificar se o ID da demanda foi fornecido
if (isset($_GET['id'])) {
    $demanda_id = $_GET['id'];

    // Consultar a demanda no banco de dados
    $demanda_sql = "SELECT * FROM demandas WHERE id = $demanda_id";
    $demanda_result = $conn->query($demanda_sql);

    if ($demanda_result->num_rows > 0) {
        $demanda = $demanda_result->fetch_assoc();
    } else {
        echo "Demanda não encontrada.";
        exit();
    }
} else {
    echo "ID da demanda não fornecido.";
    exit();
}

// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['atualizar_demanda'])) {
    $novo_responsavel = $conn->real_escape_string($_POST['responsavel_atual']);
    $comentario = $conn->real_escape_string($_POST['comentario']);
    $data_atualizacao = date('Y-m-d H:i:s');

    // Atualizar o responsável atual na tabela demandas
    $update_sql = "UPDATE demandas SET responsavel_atual = '$novo_responsavel', status = 'Em Progresso' WHERE id = $demanda_id";

    if ($conn->query($update_sql) === TRUE) {
        // Adicionar o histórico da atualização
        $historico_sql = "INSERT INTO historico_demandas (demanda_id, data_atualizacao, responsavel, comentario) VALUES ('$demanda_id', '$data_atualizacao', '$novo_responsavel', '$comentario')";
        if ($conn->query($historico_sql) === TRUE) {
            // Redirecionar para a página de detalhes da demanda
            header("Location: detalhes_demanda.php?id=$demanda_id");
            exit();
        } else {
            echo "Erro ao adicionar o histórico: " . $conn->error;
        }
    } else {
        echo "Erro ao atualizar a demanda: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar Responsável da Demanda</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background-color: #f9f9f9; }
        .form-container { background-color: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 8px; max-width: 500px; margin: auto; }
        label { display: block; margin-top: 10px; }
        input[type="text"], textarea { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px; }
        input[type="submit"] { margin-top: 15px; padding: 10px 15px; background-color: #28a745; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
        input[type="submit"]:hover { background-color: #218838; }
        a { display: block; margin-top: 10px; text-decoration: none; color: #007bff; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Atualizar Responsável da Demanda</h2>
        <form action="atualizar_demanda.php?id=<?php echo $demanda_id; ?>" method="POST">
            <label for="responsavel_atual">Novo Responsável Atual:</label>
            <input type="text" id="responsavel_atual" name="responsavel_atual" value="<?php echo htmlspecialchars($demanda['responsavel_atual']); ?>" required>

            <label for="comentario">Comentário:</label>
            <textarea id="comentario" name="comentario" rows="5" required></textarea>

            <input type="submit" name="atualizar_demanda" value="Atualizar Responsável">
        </form>

        <a href="detalhes_demanda.php?id=<?php echo $demanda_id; ?>">Voltar aos Detalhes da Demanda</a>
    </div>
</body>
</html>

<?php
// Fechar a conexão com o banco de dados
$conn->close();
?>
