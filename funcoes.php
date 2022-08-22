<?php

function trataErro($dados, $conexao){

    $empty_input = false;
    
    if (in_array("", $dados)) {
        $empty_input = true;
        echo "<p style='color: #f00;'>Erro: Necessário preencher todos campos!</p>";
    } elseif (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
        $empty_input = true;
        echo "<p style='color: #f00;'>Erro: Necessário preencher com e-mail válido!</p>";
    } elseif (isEmailCadastrado($dados, $conexao)) {
        $empty_input = true;
        echo "<p style='color: #f00;'>Erro: E-mail já cadastrado!</p>";
    }

    return $empty_input;
}

function isEmailCadastrado($dados, $conexao)
{
    $select_email = "SELECT email FROM usuarios WHERE email = :email"; // :email
    $email_cadastrado = $conexao->prepare($select_email);
    $email_cadastrado->bindParam(':email', $dados['email'], PDO::PARAM_STR);
    $email_cadastrado->execute();

    return !empty($email_cadastrado->rowCount());
}

function isInicio($a, $b, $c ){
    return ($a * $b) - $c;
}

function numberPage(){
    return filter_input(INPUT_GET, "page", FILTER_SANITIZE_NUMBER_INT);
}

function totalDeRegistros($conexao){
    $query_qnt_registros = "SELECT COUNT(id) AS num_result FROM usuarios";
    $result_qnt_registros = $conexao->prepare($query_qnt_registros);
    $result_qnt_registros->execute();
    return $result_qnt_registros->fetch(PDO::FETCH_ASSOC);
}

function quantidadeDePagina($a, $b){
    return ceil($a / $b);
}

function userOnClick($conexao, $dados, $id){

    if (!empty($dados['EditUsuario'])) {
        $empty_input = false;
        $dados = array_map('trim', $dados);
        if (in_array("", $dados)) {
            $empty_input = true;
            echo "<p style='color: #f00;'>Erro: Necessário preencher todos campos!</p>";
        } elseif (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            $empty_input = true;
            echo "<p style='color: #f00;'>Erro: Necessário preencher com e-mail válido!</p>";
        }

        if (!$empty_input) {
            $query_up_usuario = "UPDATE usuarios SET nome=:nome, email=:email WHERE id=:id";
            $edit_usuario = $conexao->prepare($query_up_usuario);
            $edit_usuario->bindParam(':nome', $dados['nome'], PDO::PARAM_STR);
            $edit_usuario->bindParam(':email', $dados['email'], PDO::PARAM_STR);
            $edit_usuario->bindParam(':id', $id, PDO::PARAM_INT);
            if ($edit_usuario->execute()) {
                $_SESSION['msg'] = "<p style='color: green;'>Usuário editado com sucesso!</p>";
                header("Location: index.php");
            } else {
                echo "<p style='color: #f00;'>Erro: Usuário não editado com sucesso!</p>";
            }
        }
    }
}
?>