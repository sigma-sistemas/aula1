<?php
// Incluir a configuração do banco de dados
require_once('../config/db.php');

// Consultar todas as demandas
$sql = "SELECT * FROM demandas";
$result = $conn->query($sql);
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
    </style>
</head>
<body>
    <h1>Lista de Demandas</h1>

    <?php while ($demanda = $result->fetch_assoc()) { ?>
        <div class="demanda">
            <h3><?php echo $demanda['titulo']; ?> (Responsável: <?php echo $demanda['responsavel_atual']; ?>)</h3>
            <p>Criador: <?php echo $demanda['criador']; ?> | Data de Criação: <?php echo $demanda['data_criacao']; ?></p>
            <p>Status: <?php echo $demanda['status']; ?></p>

            <div class="historico">
                <h4>Histórico</h4>
                <ul>
                    <?php
                    $historico_sql = "SELECT * FROM historico_demandas WHERE demanda_id = " . $demanda['id'];
                    $historico_result = $conn->query($historico_sql);
                    while ($historico = $historico_result->fetch_assoc()) {
                        echo "<li>" . $historico['data_atualizacao'] . " - Responsável: " . $historico['responsavel'] . "</li>";
                    }
                    ?>
                </ul>
            </div>

            <!-- Adicionar o link para atualizar a demanda -->
            <a href="atualizar_demanda.php?id=<?php echo $demanda['id']; ?>">Atualizar Responsável</a>
        </div>
    <?php } ?>

    <a href="adicionar_demanda.php">Adicionar Nova Demanda</a>
</body>
</html>

<?php
// Fechar a conexão com o banco de dados
$conn->close();
?>
