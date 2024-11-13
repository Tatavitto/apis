<?php
function cadastrarPaciente($nome, $leito) {
    $servidor = 'localhost';
    $banco = 'hospital';
    $usuarioDB = 'root';
    $senhaDB = '';

    try {
        $conexao = new PDO("mysql:host=$servidor;dbname=$banco", $usuarioDB, $senhaDB);
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $codigoSQL = "INSERT INTO `pacientes` (`nome`, `leito`) VALUES (:nome, :leito)";
        $comando = $conexao->prepare($codigoSQL);

        $comando->execute([
            'nome' => $nome,
            'leito' => $leito
        ]);

        return true;
    } catch (PDOException $e) {
        return false;
    }
}

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $leito = isset($_POST['leito']) ? $_POST['leito'] : '';

    if ($nome && $leito) {
        $resultado = cadastrarPaciente($nome, $leito);
        if ($resultado) {
            $response = array('message' => 'Paciente cadastrado com sucesso!');
        } else {
            $response = array('error' => 'Erro ao cadastrar paciente.');
        }
    } else {
        $response = array('error' => 'Dados incompletos.');
    }

    echo json_encode($response);
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Pacientes</title>
    <script>
        function envia() {
            var nome = document.getElementById('nome').value;
            var leito = document.getElementById('leito').value;

            var formData = new FormData();
            formData.append('nome', nome);
            formData.append('leito', leito);

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
                document.getElementById('saida').textContent = "Erro ao cadastrar o paciente.";
            });
        }
    </script>
</head>
<body>
    <h1>Cadastro de Pacientes</h1>

    <form id="formCadastro" onsubmit="event.preventDefault(); envia();">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required><br><br>

        <label for="leito">Leito:</label>
        <input type="text" id="leito" name="leito" required><br><br>

        <input type="button" value="Cadastrar Paciente" onclick="envia()">
    </form>

    <p id="saida"></p>
</body>
</html>
