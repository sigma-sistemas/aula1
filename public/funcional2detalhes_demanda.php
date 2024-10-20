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
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da Demanda</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f9f9f9; }
        .demanda { border: 1px solid #ddd; background-color: #fff; padding: 15px; margin-bottom: 20px; border-radius: 8px; }
        .historico { font-size: 0.9em; color: #555; }
        .linha-do-tempo { margin-top: 20px; position: relative; padding-left: 20px; border-left: 2px solid #ddd; }
        .evento { margin-bottom: 20px; position: relative; }

        /* Estilo dos balões */
        .bolha {
            background-color: #e1f5fe; /* Cor padrão do balão */
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 10px;
            position: relative;
            max-width: 300px;
            margin: 10px 0;
        }

        /* Alternar cor dos balões */
        .evento:nth-child(even) .bolha {
            background-color: #ffcdd2; /* Cor alternada */
        }

        /* Bolha e seta para a esquerda (default) */
        .bolha::before {
            content: '';
            position: absolute;
            top: 10px;
            left: -9px;
            width: 16px;
            height: 16px;
            background-color: #fff;
            border: 2px solid #ddd;
            border-radius: 50%;
        }

        .bolha::after {
            content: '';
            position: absolute;
            top: 10px;
            left: -7px;
            border-width: 8px;
            border-style: solid;
            border-color: transparent transparent transparent #fff;
        }

        /* Bolha para eventos à direita */
        .evento:nth-child(even) .bolha {
            margin-left: 50px;
        }

        .evento:nth-child(even) .bolha::before {
            left: auto;
            right: -9px;
        }

        .evento:nth-child(even) .bolha::after {
            left: auto;
            right: -7px;
            border-color: transparent #fff transparent transparent;
        }
    </style>
</head>
<body>
    <h1>Detalhes da Demanda</h1>

    <div class="demanda">
        <h3><?php echo $demanda['titulo']; ?> (Responsável: <?php echo $demanda['responsavel_atual']; ?>)</h3>
        <p>Criador: <?php echo $demanda['criador']; ?> | Data de Criação: <?php echo $demanda['data_criacao']; ?></p>
        <p>Status: <?php echo $demanda['status']; ?></p>
        
        <!-- Exibir os detalhes da demanda -->
        <p><strong>Detalhes:</strong> <?php echo $demanda['detalhes']; ?></p>

        <div class="historico">
            <h4>Histórico</h4>
            <div class="linha-do-tempo">
                <?php
                $historico_sql = "SELECT * FROM historico_demandas WHERE demanda_id = $demanda_id ORDER BY data_atualizacao ASC";
                $historico_result = $conn->query($historico_sql);
                while ($historico = $historico_result->fetch_assoc()) {
                ?>
                    <div class="evento">
                        <div class="bolha">
                            <p><strong>Responsável:</strong> <?php echo $historico['responsavel']; ?></p>
                            <p><strong>Data:</strong> <?php echo date('d/m/Y H:i:s', strtotime($historico['data_atualizacao'])); ?></p>
                            <p><strong>Comentário:</strong> <?php echo !empty($historico['comentario']) ? $historico['comentario'] : 'Nenhum comentário'; ?></p>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <!-- Link para atualizar a demanda -->
        <a href="atualizar_demanda.php?id=<?php echo $demanda['id']; ?>">Atualizar Responsável</a>
    </div>

    <!-- Link para voltar à lista de demandas -->
    <a href="index.php">Voltar à lista de demandas</a>
    
    <!-- Link para adicionar nova demanda -->
    <a href="adicionar_demanda.php">Adicionar Nova Demanda</a>
</body>
</html>

<?php
    } else {
        echo "Demanda não encontrada.";
    }
} else {
    echo "ID da demanda não fornecido.";
}

// Fechar a conexão com o banco de dados
$conn->close();
?>
