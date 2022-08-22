<?php
session_start();
include_once './conexao.php';
include_once './funcoes.php';
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Listar</title>
    <!-- <link rel="stylesheet" type="text/css" href="./index.css" /> -->
</head>

<body>
    <div class="brand-title">Listar</div>
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
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }

    $pagina_atual = numberPage();
    $pagina = (!empty($pagina_atual)) ? $pagina_atual : 1;

    $limite_resultado = 40;
    $inicio = isInicio($limite_resultado, $pagina, $limite_resultado);

    $query_usuarios = "SELECT id, nome, email FROM usuarios ORDER BY id DESC LIMIT $inicio, $limite_resultado";
    $result_usuarios = $conexao->prepare($query_usuarios);
    $result_usuarios->execute();

    if (($result_usuarios) and ($result_usuarios->rowCount() != 0)) {
        while ($row_usuario = $result_usuarios->fetch(PDO::FETCH_ASSOC)) {
            extract($row_usuario);           
            echo "ID: $id <br>";
            echo "Nome: $nome <br>";
            echo "E-mail: $email <br><br>";

            echo "<a href='visualizar.php?id=$id'>Visualizar</a><br>";
            echo "<a href='editar.php?id=$id'>Editar</a><br>";
            echo "<a href='apagar.php?id=$id'>Apagar</a><br>";
            echo "<hr>";
        }

        $row_qnt_registros = totalDeRegistros($conexao);
        $qnt_pagina        = quantidadeDePagina($row_qnt_registros['num_result'], $limite_resultado);
        $maximo_link       = 2;

        echo "<a href='index.php?page=1'>Primeira</a> ";

        for ($pagina_anterior = $pagina - $maximo_link; $pagina_anterior <= $pagina - 1; $pagina_anterior++) {
            if ($pagina_anterior >= 1) {
                echo "<a href='index.php?page=$pagina_anterior'>$pagina_anterior</a> ";
            }
        }

        echo "$pagina ";

        for ($proxima_pagina = $pagina + 1; $proxima_pagina <= $pagina + $maximo_link; $proxima_pagina++) {
            if ($proxima_pagina <= $qnt_pagina) {
                echo "<a href='index.php?page=$proxima_pagina'>$proxima_pagina</a> ";
            }
        }

        echo "<a href='index.php?page=$qnt_pagina'>Última</a> ";
    } else {
        echo "<p style='color: #f00;'>Erro: Nenhum usuário encontrado!</p>";
    }
    ?>
</body>

</html>