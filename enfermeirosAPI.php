<?php
function cadastrarEnfermeiro($nome, $coren, $usuario, $senha) {
    $servidor = 'localhost';
    $banco = 'hospital';
    $usuarioDB = 'root';
    $senhaDB = '';

    try {
        $conexao = new PDO("mysql:host=$servidor;dbname=$banco", $usuarioDB, $senhaDB);
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $codigoSQL = "INSERT INTO `enfermeiros` (`nome`, `coren`, `usuario`, `senha`) VALUES (:nome, :coren, :usuario, :senha)";
        $comando = $conexao->prepare($codigoSQL);

        $comando->execute([
            'nome' => $nome,
            'coren' => $coren,
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
    $coren = isset($_POST['coren']) ? $_POST['coren'] : '';
    $usuario = isset($_POST['usuario']) ? $_POST['usuario'] : '';
    $senha = isset($_POST['senha']) ? $_POST['senha'] : '';

    if ($nome && $coren && $usuario && $senha) {
        $resultado = cadastrarEnfermeiro($nome, $coren, $usuario, $senha);
        if ($resultado) {
            $response = array('message' => 'Enfermeiro cadastrado com sucesso!');
        } else {
            $response = array('error' => 'Erro ao cadastrar enfermeiro.');
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
    <title>Cadastro de Enfermeiros</title>
    <script>
        function envia() {
            var nome = document.getElementById('nome').value;
            var coren = document.getElementById('coren').value;
            var usuario = document.getElementById('usuario').value;
            var senha = document.getElementById('senha').value;

            var formData = new FormData();
            formData.append('nome', nome);
            formData.append('coren', coren);
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
                document.getElementById('saida').textContent = "Erro ao cadastrar o enfermeiro.";
            });
        }
    </script>
</head>
<body>
    <h1>Cadastro de Enfermeiros</h1>

    <form id="formCadastro" onsubmit="event.preventDefault(); envia();">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required><br><br>

        <label for="coren">COREN:</label>
        <input type="text" id="coren" name="coren" required><br><br>
        
        <label for="usuario">Nome de Usuário:</label>
        <input type="text" id="usuario" name="usuario" required><br><br>
        
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required><br><br>

        <input type="button" value="Cadastrar Paciente" onclick="envia()">
    </form>

    <p id="saida"></p>
</body>
</html>
