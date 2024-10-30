<?php
include("head.html");
include("data.php"); // Inclui o arquivo de configuração do banco de dados
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se os campos de email e senha foram preenchidos
    if (!empty($_POST["email"]) && !empty($_POST["password"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];

        // Query SQL para selecionar o usuário pelo email
        $sql = "SELECT id, email, password FROM dataphp WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // Usuário encontrado
            $row = $result->fetch_assoc();
            $stored_password = $row['password'];

            // Verifica se a senha corresponde ao hash armazenado
            if (password_verify($password, $stored_password)) {
                // Autenticação bem-sucedida
                $_SESSION['email'] = $email; // Guarda o email na sessão
                $_SESSION['id'] = $row['id']; // Guarda o ID do usuário na sessão

                // Redireciona para a página protegida após o login
                header("Location: house.php");
                exit();
            } else {
                echo "Senha incorreta.";
            }
        } else {
            echo "Usuário não encontrado.";
        }
    } else {
        echo "Por favor, preencha todos os campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: left;
            padding: 5px;
        }
        h1 {
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .container a {
            display: block;
            margin-top: 20px;
            font-size: 18px;
            color: #007bff;
            text-decoration: none;
        }
    </style>
    <meta name="viewport" content="width=, initial-scale=1.0">
</head>
<body>
    <h3>Login de Usuário</h3>
    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br>
        <label for="password">Senha:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Entrar">
    </form>
    <p>Não possui uma conta? <a href="login.php">Cadastre-se aqui</a></p>
    <a href="redacted.php">Sou Administrador</a><br>

    <body>
</body>
</html>

<?php
mysqli_close($conn); // Fecha a conexão com o banco de dados
?>
