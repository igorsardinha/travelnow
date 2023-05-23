<?php
session_start();
include_once "./config/connection.php";

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);


if (empty($dados['nome'])) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Necessário preencher o campo nome!</div>"];
} elseif (empty($dados['e-mail'])) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Necessário preencher o campo e-mail!</div>"];
} elseif (empty($dados['mensagem'])) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Necessário preencher o campo mensagem!</div>"];
} else {
    $query_contato =
        "INSERT INTO contatos (nome, email, telefone, mensagem) VALUES (:nome, :email, :telefone, :mensagem)";
    $contato_insert = $conn->prepare($query_contato);
    $contato_insert->bindParam(":nome", $dados["nome"], PDO::PARAM_STR);
    $contato_insert->bindParam(":email", $dados["e-mail"], PDO::PARAM_STR);
    $contato_insert->bindParam(":telefone", $dados["telefone"], PDO::PARAM_STR);
    $contato_insert->bindParam(":mensagem", $dados["mensagem"], PDO::PARAM_STR);

    $contato_insert->execute();

    $emailenviar = $dados['e-mail'];
    $destino = $emailenviar;
    $assunto = "Contato pelo Site";

    // É necessário indicar que o formato do e-mail é html
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= 'From: $nome <$email>';

    $enviaremail = mail($destino, $assunto, $dados['mensagem'], $headers);


    if ($contato_insert->rowCount()) {
        $retorna = [
            "erro" => false,
            "msg" =>
            "<div class='alert alert-success' role='alert'><b>Enviada</b><br>Mensagem enviada com sucesso!</b></div>",
        ];
    } else {
        $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro ao enviar mensagem!</div>"];
    }
}

echo json_encode($retorna);