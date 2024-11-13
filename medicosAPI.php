<?php
function cadastrarMedico($nome, $especialidade, $crm, $usuario, $senha) {
    $servidor = 'localhost';
    $banco = 'hospital';
    $usuarioDB = 'root';
    $senhaDB = '';

    try {
        $conexao = new PDO("mysql:host=$servidor;dbname=$banco", $usuarioDB, $senhaDB);
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $codigoSQL = "INSERT INTO `medicos` (`nome`, `especialidade`, `crm`, `usuario`, `senha`) VALUES (:nome, :especialidade, :crm, :usuario, :senha)";
        $comando = $conexao->prepare($codigoSQL);

        $comando->execute([
            'nome' => $nome,
            'especialidade' => $especialidade,
            'crm' => $crm,
            'usuario' => $usuario,
            'senha' => $senha
        ]);

        return true;
    } catch (PDOException $e) {
        return false;
    }
}

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $especialidade = isset($_POST['especialidade']) ? $_POST['especialidade'] : '';
    $crm = isset($_POST['crm']) ? $_POST['crm'] : '';
    $usuario = isset($_POST['usuario']) ? $_POST['usuario'] : '';
    $senha = isset($_POST['senha']) ? $_POST['senha'] : '';

    if ($nome && $especialidade && $crm && $usuario && $senha) {
        $resultado = cadastrarMedico($nome, $especialidade, $crm, $usuario, $senha);
        if ($resultado) {
            $response = array('message' => 'Médico cadastrado com sucesso!');
        } else {
            $response = array('error' => 'Erro ao cadastrar médico.');
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
    <title>Cadastro de Médicos</title>
    <script>
        function envia() {
            var nome = document.getElementById('nome').value;
            var especialidade = document.getElementById('especialidade').value;
            var crm = document.getElementById('crm').value;
            var usuario = document.getElementById('usuario').value;
            var senha = document.getElementById('senha').value;

            var formData = new FormData();
            formData.append('nome', nome);
            formData.append('especialidade', especialidade);
            formData.append('crm', crm);
            formData.append('usuario', usuario);
            formData.append('senha', senha);

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
                document.getElementById('saida').textContent = "Erro ao cadastrar o médico.";
            });
        }
    </script>
</head>
<body>
    <h1>Cadastro de Médicos</h1>

    <form id="formCadastro" onsubmit="event.preventDefault(); envia();">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>

        <label for="especialidade">Especialidade:</label>
        <input type="text" id="especialidade" name="especialidade" required>

        <label for="crm">CRM:</label>
        <input type="text" id="crm" name="crm" required>

        <label for="usuario">Nome de Usuário:</label>
        <input type="text" id="usuario" name="usuario" required>

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>

        <input type="button" value="Cadastrar Médico" onclick="envia()">
    </form>

    <p id="saida"></p>
</body>
</html>
