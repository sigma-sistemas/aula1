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

// Verificar se o formulário foi submetido para atualizar responsável
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['atualizar_responsavel'])) {
    $novo_responsavel = $conn->real_escape_string($_POST['responsavel_atual']);
    $data_atualizacao = date('Y-m-d H:i:s'); // Incluindo a hora

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

// Verificar se o formulário foi submetido para adicionar nova demanda
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['adicionar_demanda'])) {
    $titulo = $conn->real_escape_string($_POST['titulo']);
    $criador = $conn->real_escape_string($_POST['criador']);
    $responsavel = $conn->real_escape_string($_POST['responsavel_atual']);
    $data_criacao = date('Y-m-d H:i:s'); // Incluindo a hora
    $status = 'Em Progresso';

    // Inserir a nova demanda na tabela
    $sql = "INSERT INTO demandas (titulo, criador, data_criacao, status, responsavel_atual) VALUES ('$titulo', '$criador', '$data_criacao', '$status', '$responsavel')";
    
    if ($conn->query($sql) === TRUE) {
        $demanda_id = $conn->insert_id;

        // Adicionar o histórico inicial da demanda
        $historico_sql = "INSERT INTO historico_demandas (demanda_id, data_atualizacao, responsavel) VALUES ('$demanda_id', '$data_criacao', '$responsavel')";
        $conn->query($historico_sql);

        // Redirecionar para a página inicial
        header("Location: index.php");
        exit;
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
    <title>Controle de Demandas</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .demanda { border: 1px solid #ddd; padding: 10px; margin-bottom: 10px; }
        .historico { font-size: 0.9em; color: #555; }
        .linha-do-tempo { position: relative; padding-left: 30px; }
        .evento { margin-bottom: 20px; }
        .bolha { 
            position: relative; 
            background: #f1f1f1; 
            border-radius: 5px; 
            padding: 10px; 
            display: inline-block; 
            max-width: 200px; 
        }
        .bolha::after { 
            content: ''; 
            position: absolute; 
            left: -10px; 
            top: 10px; 
            border: 10px solid transparent; 
            border-right-color: #f1f1f1; 
            margin-top: -10px; 
        }
    </style>
</head>
<body>
    <h1>Lista de Demandas</h1>

    <?php if (isset($demanda)): ?>
        <div class="demanda">
            <h3><?php echo $demanda['titulo']; ?> (Responsável: <?php echo $demanda['responsavel_atual']; ?>)</h3>
            <p>Criador: <?php echo $demanda['criador']; ?> | Data de Criação: <?php echo $demanda['data_criacao']; ?></p>
            <p>Status: <?php echo $demanda['status']; ?></p>

            <div class="historico">
                <h4>Histórico</h4>
                <div class="linha-do-tempo">
                    <?php
                    $historico_sql = "SELECT * FROM historico_demandas WHERE demanda_id = " . $demanda['id'] . " ORDER BY data_atualizacao ASC";
                    $historico_result = $conn->query($historico_sql);
                    while ($historico = $historico_result->fetch_assoc()) {
                    ?>
                        <div class="evento">
                            <div class="bolha">
                                <p><strong>Responsável:</strong> <?php echo $historico['responsavel']; ?></p>
                                <p><strong>Data:</strong> <?php echo date('d/m/Y H:i', strtotime($historico['data_atualizacao'])); ?></p>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <a href="atualizar_demanda.php?id=<?php echo $demanda['id']; ?>">Atualizar Responsável</a>
        </div>
    <?php endif; ?>

    <h2>Adicionar Nova Demanda</h2>
    <form action="adicionar_demanda.php" method="POST">
        <label for="titulo">Título:</label><br>
        <input type="text" id="titulo" name="titulo" required><br><br>

        <label for="criador">Criador:</label><br>
        <input type="text" id="criador" name="criador" required><br><br>

        <label for="responsavel_atual">Responsável Atual:</label><br>
        <input type="text" id="responsavel_atual" name="responsavel_atual" required><br><br>

        <input type="submit" name="adicionar_demanda" value="Adicionar Demanda">
    </form>

    <a href="index.php">Voltar para a lista de demandas</a>
</body>
</html>

<?php
// Fechar a conexão com o banco de dados
$conn->close();
?>
