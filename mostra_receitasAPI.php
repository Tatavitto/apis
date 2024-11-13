<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <title>Listagem de Receitas</title>
</head>
<body>
    <h1>Receitas Cadastradas</h1>
    
    <?php
$servidor = 'localhost';
$banco = 'hospital';
$usuario = 'root';
$senha = '';

try {
    $conexao = new PDO("mysql:host=$servidor;dbname=$banco", $usuario, $senha);
} catch (PDOException $e) {
    echo json_encode(array('error' => 'Erro na conexão: ' . $e->getMessage()));
    exit;
}
$consultaSQL = "SELECT * FROM `receitas`";
$comando = $conexao->prepare($consultaSQL);
$comando->execute();
$receitas = $comando->fetchAll(PDO::FETCH_ASSOC);
if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
    if (count($receitas) > 0) {
        echo json_encode($receitas);
    } else {
        echo json_encode(array('message' => 'Não há receitas cadastradas.'));
    }
} else {
    if (count($receitas) > 0) {
        echo "<table border='1'>";
        echo "<tr><th>Paciente</th><th>Medicamento</th><th>Data</th><th>Hora</th><th>Dose</th><th>Ações</th></tr>";
        foreach ($receitas as $receita) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($receita['paciente']) . "</td>";
            echo "<td>" . htmlspecialchars($receita['medicamento']) . "</td>";
            echo "<td>" . $receita['data'] . "</td>";
            echo "<td>" . $receita['hora'] . "</td>";
            echo "<td>" . htmlspecialchars($receita['dose']) . "</td>";
            echo "<td><a href='admin.php?id=" . $receita['id'] . "'>Registrar o medicamento</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Não há receitas cadastradas.</p>";
    }
}

$conexao = null;
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>