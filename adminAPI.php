<?php
function cadastrarReceita($paciente, $medicamento, $data_administracao, $hora_administracao, $dose, $data_registro, $hora_registro) {
    $servidor = 'localhost';
    $banco = 'hospital';
    $usuarioDB = 'root';
    $senhaDB = '';

    try {
        $conexao = new PDO("mysql:host=$servidor;dbname=$banco", $usuarioDB, $senhaDB);
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $codigoSQL = "INSERT INTO `administracoes` (`paciente`, `medicamento`, `data_administracao`, `hora_administracao`, `dose`, `data_registro`, `hora_registro`) 
                      VALUES (:paciente, :medicamento, :data_administracao, :hora_administracao, :dose, :data_registro, :hora_registro)";
        $comando = $conexao->prepare($codigoSQL);

        $comando->execute([
            'paciente' => $paciente,
            'medicamento' => $medicamento,
            'data_administracao' => $data_administracao,
            'hora_administracao' => $hora_administracao,
            'dose' => $dose,
            'data_registro' => $data_registro,
            'hora_registro' => $hora_registro
        ]);

        return true;
    } catch (PDOException $e) {
        return false;
    }
}

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $paciente = isset($_POST['paciente']) ? $_POST['paciente'] : '';
    $medicamento = isset($_POST['medicamento']) ? $_POST['medicamento'] : '';
    $data_administracao = isset($_POST['data_administracao']) ? $_POST['data_administracao'] : '';
    $hora_administracao = isset($_POST['hora_administracao']) ? $_POST['hora_administracao'] : '';
    $dose = isset($_POST['dose']) ? $_POST['dose'] : '';
    $data_registro = isset($_POST['data_registro']) ? $_POST['data_registro'] : '';
    $hora_registro = isset($_POST['hora_registro']) ? $_POST['hora_registro'] : '';

    if ($paciente && $medicamento && $data_administracao && $hora_administracao && $dose && $data_registro && $hora_registro) {
        $resultado = cadastrarReceita($paciente, $medicamento, $data_administracao, $hora_administracao, $dose, $data_registro, $hora_registro);
        if ($resultado) {
            $response = array('message' => 'Receita cadastrada com sucesso!');
        } else {
            $response = array('error' => 'Erro ao cadastrar a receita.');
        }
    } else {
        $response = array('error' => 'Dados incompletos.');
    }

    echo json_encode($response);
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Administração de Receitas</title>
    <script>
        function envia() {
            var paciente = document.getElementById('paciente').value;
            var medicamento = document.getElementById('medicamento').value;
            var data_administracao = document.getElementById('data_administracao').value;
            var hora_administracao = document.getElementById('hora_administracao').value;
            var dose = document.getElementById('dose').value;
            var data_registro = document.getElementById('data_registro').value;
            var hora_registro = document.getElementById('hora_registro').value;

            var formData = new FormData();
            formData.append('paciente', paciente);
            formData.append('medicamento', medicamento);
            formData.append('data_administracao', data_administracao);
            formData.append('hora_administracao', hora_administracao);
            formData.append('dose', dose);
            formData.append('data_registro', data_registro);
            formData.append('hora_registro', hora_registro);

            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    document.getElementById('saida').textContent = data.message;
                    document.getElementById('formCadastro').reset();
                } else {
                    document.getElementById('saida').textContent = data.error;
                }
            })
            .catch(error => {
                document.getElementById('saida').textContent = "Erro ao cadastrar a receita.";
            });
        }
    </script>
</head>
<body>
    <h1>Administração de Receitas</h1>

    <form id="formCadastro" onsubmit="event.preventDefault(); envia();">
        <label for="paciente">Paciente:</label>
        <input type="text" id="paciente" name="paciente" required><br><br>

        <label for="medicamento">Medicamento:</label>
        <input type="text" id="medicamento" name="medicamento" required><br><br>
        
        <label for="data_administracao">Data e hora que a medicação foi dada:</label>
        <input type="date" id="data_administracao" name="data_administracao" required>
        <input type="time" id="hora_administracao" name="hora_administracao" required><br><br>
        
        <label for="dose">Dose:</label>
        <input type="text" id="dose" name="dose" required><br><br>
        
        <label for="data_registro">Data e hora atual:</label>
        <input type="date" id="data_registro" name="data_registro" required>
        <input type="time" id="hora_registro" name="hora_registro" required><br><br>

        <input type="button" value="Salvar" onclick="envia()">
    </form>

    <p id="saida"></p>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

