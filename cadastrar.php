<?php
session_start();
ob_start();
include_once './conexao.php';
include_once './funcoes.php';
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Cadastrar</title>
    <!-- <link rel="stylesheet" type="text/css" href="./index.css" /> -->
</head>

<body>
    <div class="container">
        <div class="brand-title">Cadastro</div>
        <div class="menuteste">
            <nav class="navbar">
                <div class="navbar-container contmenu">
                    <ul class="menu-items">
                        <li><a href="index.php">Listar</a></li>
                        <li><a href="cadastrar.php">Cadastrar</a></li>
                    </ul>
                </div>
            </nav>
        </div>

        <?php
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (!empty($dados['CadUsuario'])) {

            $dados = array_map('trim', $dados);
            $empty_input = trataErro($dados, $conexao);

            if (!$empty_input) {
                $query_usuario = "INSERT INTO usuarios (nome, email) VALUES (:nome, :email) ";
                $cad_usuario = $conexao->prepare($query_usuario);
                $cad_usuario->bindParam(':nome', $dados['nome'], PDO::PARAM_STR);
                $cad_usuario->bindParam(':email', $dados['email'], PDO::PARAM_STR);
                $cad_usuario->execute();
                if ($cad_usuario->rowCount()) {
                    unset($dados);
                    $_SESSION['msg'] =  "<p style='color: green;'>Usuário cadastrado com sucesso!</p>";
                    header("Location: index.php");
                } else {
                    echo "<p style='color: #f00;'>Erro: Usuário não cadastrado com sucesso!</p>";
                }
            }
        }
        ?>
        <form name="cad-usuario" method="POST" action="">
            <label>Nome: </label>
            <input type="text" name="nome" id="nome" placeholder="Nome completo" value="<?php
                                                                                        if (isset($dados['nome'])) {
                                                                                            echo $dados['nome'];
                                                                                        }
                                                                                        ?>"><br><br>

            <label>E-mail: </label>
            <input type="email" name="email" id="email" placeholder="Digite aqui seu E-mail" value="<?php
                                                                                                    if (isset($dados['email'])) {
                                                                                                        echo $dados['email'];
                                                                                                    }
                                                                                                    ?>"><br><br>

            <input type="submit" value="Cadastrar" name="CadUsuario">
        </form>
</body>

</html>