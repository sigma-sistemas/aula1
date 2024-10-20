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
        body { font-family: Arial, sans-serif; padding: 20px; }
        .demanda { border: 1px solid #ddd; background-color: #fff; padding: 15px; margin-bottom: 10px; border-radius: 8px; }
        .demanda h3 { margin: 0; }
        a { text-decoration: none; color: #007bff; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h1>Lista de Demandas</h1>

    <?php while ($demanda = $result->fetch_assoc()) { ?>
        <div class="demanda">
            <h3><?php echo $demanda['titulo']; ?></h3>
            <p>Responsável: <?php echo $demanda['responsavel_atual']; ?> | Status: <?php echo $demanda['status']; ?></p>
            <!-- Link para visualizar os detalhes da demanda -->
            <a href="detalhes_demanda.php?id=<?php echo $demanda['id']; ?>">Ver detalhes</a>
        </div>
    <?php } ?>

    <a href="adicionar_demanda.php">Adicionar Nova Demanda</a>
</body>
</html>

<?php
// Fechar a conexão com o banco de dados
$conn->close();
?>
