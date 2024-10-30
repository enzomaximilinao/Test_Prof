<?php
session_start();
include("data.php");

$errors = []; // Array para armazenar mensagens de erro, se houver

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se todos os campos obrigatórios estão preenchidos
    if (!empty($_POST["username"]) && !empty($_POST["password"]) && !empty($_POST["email"]) && !empty($_POST["cep"]) && !empty($_POST["endereco"]) && !empty($_POST["bairro"]) && !empty($_POST["cidade"]) && !empty($_POST["estado"])) {

        // Sanitiza e obtém os valores dos campos do formulário
        $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $cep = filter_input(INPUT_POST, "cep", FILTER_SANITIZE_NUMBER_INT);
        $endereco = filter_input(INPUT_POST, "endereco", FILTER_SANITIZE_SPECIAL_CHARS);
        $bairro = filter_input(INPUT_POST, "bairro", FILTER_SANITIZE_SPECIAL_CHARS);
        $cidade = filter_input(INPUT_POST, "cidade", FILTER_SANITIZE_SPECIAL_CHARS);
        $estado = filter_input(INPUT_POST, "estado", FILTER_SANITIZE_SPECIAL_CHARS);

        // Hash da senha
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Query SQL para inserir dados no banco
        $sql = "INSERT INTO dataphp (email, password, user, cep, endereco, bairro, cidade, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        // Prepara a query
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            // Associa os parâmetros com os valores
            mysqli_stmt_bind_param($stmt, "ssssssss", $email, $hash, $username, $cep, $endereco, $bairro, $cidade, $estado);

            // Executa a query
            mysqli_stmt_execute($stmt);

            // Verifica se a inserção foi bem-sucedida
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $_SESSION["username"] = $username; // Armazena o nome de usuário na sessão
                header("Location: house.php"); // Redireciona para a página home.php após o cadastro
                exit();
            } else {
                $errors[] = "Erro ao cadastrar usuário: " . mysqli_stmt_error($stmt);
            }

            // Fecha a declaração
            mysqli_stmt_close($stmt);
        } else {
            $errors[] = "Erro na preparação da consulta: " . mysqli_error($conn);
        }

        // Fecha a conexão
        mysqli_close($conn);

    } else {
        $errors[] = "Faltou preencher algum campo obrigatório.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro com consulta Via CEP</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#cep').on('blur', function() {
                var cep = $(this).val().replace(/\D/g, '');
                if (cep.length == 8) {
                    $.getJSON('https://viacep.com.br/ws/' + cep + '/json/?callback=?', function(data) {
                        if (!("erro" in data)) {
                            $('#endereco').val(data.logradouro);
                            $('#bairro').val(data.bairro);
                            $('#cidade').val(data.localidade);
                            $('#estado').val(data.uf);
                        } else {
                            alert('CEP não encontrado.');
                        }
                    });
                }
            });
        });
    </script>
</head>
<body>
    <h2>Cadastro de Usuário</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="username">Nome:</label><br>
        <input type="text" id="username" name="username" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Senha:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <label for="cep">CEP:</label><br>
        <input type="text" id="cep" name="cep" maxlength="9" required><br><br>

        <label for="endereco">Endereço:</label><br>
        <input type="text" id="endereco" name="endereco" required><br><br>

        <label for="bairro">Bairro:</label><br>
        <input type="text" id="bairro" name="bairro" required><br><br>

        <label for="cidade">Cidade:</label><br>
        <input type="text" id="cidade" name="cidade" required><br><br>

        <label for="estado">Estado:</label><br>
        <input type="text" id="estado" name="estado" required><br><br>

        <input type="submit" value="Cadastrar"><br><br>

        <?php
        // Exibe erros, se houver
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<p>$error</p>";
            }
        }
        ?>

        <a href="index.php">Já possui conta</a>
    </form>
</body>
</html>
