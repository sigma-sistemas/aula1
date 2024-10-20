<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "controle_demandas");

// Verifica se a conexão foi bem sucedida
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Adicionar uma nova demanda
if (isset($_POST['adicionar_demanda'])) {
    $titulo = $_POST['titulo'];
    $criador = $_POST['criador'];
    $data_criacao = date('Y-m-d');
    $responsavel = $_POST['responsavel_atual'];
    $status = 'Em Progresso';

    // Inserir nova demanda
    $sql = "INSERT INTO demandas (titulo, criador, data_criacao, status, responsavel_atual) 
            VALUES ('$titulo', '$criador', '$data_criacao', '$status', '$responsavel')";
    
    if ($conn->query($sql) === TRUE) {
        $demanda_id = $conn->insert_id;

        // Adicionar ao histórico
        $historico_sql = "INSERT INTO historico_demandas (demanda_id, data_atualizacao, responsavel) 
                          VALUES ($demanda_id, '$data_criacao', '$responsavel')";
        $conn->query($historico_sql);
        echo "Demanda adicionada com sucesso!";
    } else {
        echo "Erro: " . $conn->error;
    }
}

// Listar todas as demandas
$demandas_sql = "SELECT * FROM demandas";
$demandas_result = $conn->query($demandas_sql);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de Demandas</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .formulario { margin: 20px; }
        .linha-do-tempo { position: relative; margin: 20px 0; padding: 10px 0; border-left: 2px solid #ddd; }
        .evento { margin: 10px 0; position: relative; }
        .bolha { background: #f1f1f1; border-radius: 10px; padding: 10px; max-width: 250px; position: relative; margin-left: 20px; }
        .bolha:before { content: ''; position: absolute; top: 10px; left: -15px; border-width: 10px; border-style: solid; border-color: transparent #f1f1f1 transparent transparent; }
        .evento:nth-child(even) .bolha { background: #cfe2ff; margin-left: 0; margin-right: 20px; }
        .evento:nth-child(even) .bolha:before { left: auto; right: -15px; border-color: transparent transparent transparent #cfe2ff; }
        .linha-do-tempo .evento .bolha p { margin: 5px 0; }
        .linha-do-tempo .evento:after { content: ""; position: absolute; width: 10px; height: 10px; border-radius: 50%; background-color: #ddd; left: -7px; top: 10px; }
    </style>
</head>
<body>
    <div class="formulario">
        <h1>Adicionar Nova Demanda</h1>
        <form action="index.php" method="POST">
            <label for="titulo">Título da Demanda:</label><br>
            <input type="text" id="titulo" name="titulo" required><br><br>
            
            <label for="criador">Criador:</label><br>
            <input type="text" id="criador" name="criador" required><br><br>

            <label for="responsavel_atual">Responsável Atual:</label><br>
            <input type="text" id="responsavel_atual" name="responsavel_atual" required><br><br>

            <input type="submit" name="adicionar_demanda" value="Adicionar Demanda">
        </form>
    </div>

    <div class="historico">
        <h4>Demandas</h4>
        <?php while ($demanda = $demandas_result->fetch_assoc()) { ?>
            <h3><?php echo $demanda['titulo']; ?> (Criador: <?php echo $demanda['criador']; ?>)</h3>
            <p>Status: <?php echo $demanda['status']; ?></p>
            <p>Data de Criação: <?php echo $demanda['data_criacao']; ?></p>

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
                            <p><strong>Data:</strong> <?php echo $historico['data_atualizacao']; ?></p>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>

</body>
</html>

<?php
// Fechar conexão
$conn->close();
?>
