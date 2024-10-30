<?php
include("head.html");
include("data.php"); // Inclui o arquivo de configuração do banco de dados
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Obtém os dados atuais do usuário
$user_id = $_SESSION['id'];
$sql = "SELECT id, email FROM dataphp WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $email = $row['email'];
} else {
    echo "Erro ao buscar informações do usuário.";
    exit();
}

// Processar o formulário de edição se o método for POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se os campos foram preenchidos
    if (!empty($_POST["email"])) {
        $new_email = $_POST["email"];

        // Query SQL para atualizar o email do usuário
        $sql_update = "UPDATE dataphp SET email = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("si", $new_email, $user_id);

        if ($stmt_update->execute()) {
            echo "Dados atualizados com sucesso!";
            // Atualiza o email na sessão, se necessário
            $_SESSION['email'] = $new_email;
        } else {
            echo "Erro ao atualizar os dados: " . $conn->error;
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
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
</head>
<body>
    <h3>Editar Perfil</h3>
    <p>Usuário: <?php echo $email; ?></p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="email">Novo Email:</label><br>
        <input type="email" id="email" name="email" value="<?php echo $email; ?>" required><br><br>
        <input type="submit" value="Salvar Alterações">
    </form>
</body>
</html>

<?php
include"footer.html";
mysqli_close($conn); // Fecha a conexão com o banco de dados
?>
