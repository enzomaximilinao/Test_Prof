<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Registro</title>
</head>

<body>
    <header>
        <h2>Página de exclusão de registro</h2>
        <h4>Siga as orientações para excluir registros dos usuários</h4>
        <hr>
    </header>

    <form action="excluir_registro.php" method="post">
        <label for="id">ID do Registro:</label>
        <input type="text" id="id" name="id" required><br><br>

        <label for="senha">Senha de Adm:</label>
        <input type="password" id="senha" name="senha" required><br><br>

        <input type="submit" value="Confirmar Exclusão"> <br>
    </form>
</body>

</html>


<?php
session_start();
include("data.php"); // Inclui o arquivo de configuração do banco de dados

// Verificar se ID e senha foram enviados
if (isset($_POST['id']) && isset($_POST['senha'])) {
    $id = $_POST['id'];
    $senhaAdm = $_POST['senha'];

    // Definir usuário e senha de administrador corretos
    $userAdmCorreto = "userAdm";
    $passAdmCorreta = "senha123";

    // Verificar se a senha corresponde à senha de administrador
    if ($senhaAdm === $passAdmCorreta) {
        // Se a senha está correta, proceder com a exclusão

        // Comando SQL para excluir o registro com o ID especificado
        $sql = "DELETE FROM dataphp WHERE id = ?";

        // Preparar a declaração SQL
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        // Executar o comando
        if ($stmt->execute()) {
            echo "Registro excluído com sucesso!";
        } else {
            echo "Erro ao excluir o registro: " . $conn->error;
        }
    } else {
        echo "Senha de administrador incorreta.";
    }
} else {
    echo "Por favor, preencha o ID do registro e a senha de administrador.";
}

// Exibir registros restantes
$sql = "SELECT * FROM dataphp";
$result = mysqli_query($conn, $sql);

echo "<br><br>Estes são os usuários restantes:<br>";

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "Id: " . htmlspecialchars($row["id"]) . "<br>";
        echo "Usuário: " . htmlspecialchars($row["user"]) . "<br>";
        echo "Email: " . htmlspecialchars($row["email"]) . "<br><br>";
    }
} else {
    echo "Nenhum usuário encontrado.";
}

// Fechar conexão
$conn->close();
include("footer.html");
?>