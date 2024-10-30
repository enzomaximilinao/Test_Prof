<?php
session_start();
include("data.php");
// Definimos o nome de usuário e senha de acesso
$userAdm = "userAdm";
$passAdm = "senha123";
// Criamos uma função que exibirá uma mensagem de erro caso os dados estejam errados
function erro()
{
    // Definindo Cabeçalhos
    header('WWW-Authenticate: Basic realm="Administracao"');
    header('HTTP/1.0 401 Unauthorized');
    // Mensagem que será exibida
    echo '<h1>Voce não tem permissão para acessar essa área</h1>';
    // Pára o carregamento da página
    exit;
}
// Se as informações não foram setadas
if (!isset($_SERVER['PHP_AUTH_USER']) or !isset($_SERVER['PHP_AUTH_PW'])) {
    erro();
}
// Se as informações foram setadas
else {
    // Se os dados informados forem diferentes dos definidos
    if ($_SERVER['PHP_AUTH_USER'] != $userAdm or $_SERVER['PHP_AUTH_PW'] != $passAdm) {
        erro();
    }
}

mysqli_close($conn);

?>
<header>
    <h2>Bem vindo a pagina de Administrador</h2>
    <h4>Confirme as credenciais e tenha acesso</h>
        <hr>
</header>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="excluir_registro.php" method="post">
        <label for="userAdm">Usuário:</label>
        <input type="text" id="userAdm" name="userAdm"><br><br>
        <label for="senha123">Senha:</label>
        <input type="password" id="senha123" name="senha123"><br><br>
        <input type="submit" value="Confirmar">
    </form>
</body>

</html>


<?php
// Incluir o arquivo de conexão com o banco de dados
include 'data.php';

// Verificar se os campos de usuário e senha de administrador foram enviados
if (isset($_POST['userAdm']) && isset($_POST['senhaAdm'])) {
}
?>