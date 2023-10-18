<?php

session_start();
include_once "./config/connection.php";

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

$senha = $dados["senha"];
$senha_antiga = $dados["senha-antiga"];

if (empty($dados["nome"])) {
  $retorna = [
    "erro" => true,
    "msg" =>
      "<span id='msgAlertErroProfile' style='text-size: 12pt;color: #D50000;'>Erro: Necessário preencher o campo nome!</span>",
  ];
} elseif (empty($dados["sobrenome"])) {
  $retorna = [
    "erro" => true,
    "msg" =>
      "<span id='msgAlertErroProfile' style='text-size: 12pt;color: #D50000;'>Erro: Necessário preencher o campo sobrenome!</span>",
  ];
} elseif (empty($dados["telefone"])) {
  $retorna = [
    "erro" => true,
    "msg" =>
      "<span id='msgAlertErroProfile' style='text-size: 12pt;color: #D50000;'>Erro: Necessário preencher o campo telefone!</span>",
  ];
} else {
  if (empty($senha)) {
    $query_usuario_pes =
      "SELECT id,nome,sobrenome FROM usuarios WHERE email=:email LIMIT 1";
    $result_usuario = $conn->prepare($query_usuario_pes);
    $result_usuario->bindParam(":email", $dados["email"], PDO::PARAM_STR);
    $result_usuario->execute();
    if ($result_usuario and $result_usuario->rowCount() != 0) {
      $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
      $query_update =
        "UPDATE usuarios SET nome=:nome,sobrenome=:sobrenome,telefone=:telefone WHERE id=:id AND email=:email";
      $update_usuario = $conn->prepare($query_update);
      $update_usuario->bindParam(":nome", $dados["nome"], PDO::PARAM_STR);
      $update_usuario->bindParam(
        ":sobrenome",
        $dados["sobrenome"],
        PDO::PARAM_STR
      );
      $update_usuario->bindParam(
        ":telefone",
        $dados["telefone"],
        PDO::PARAM_STR
      );
      $update_usuario->bindParam(":id", $row_usuario["id"], PDO::PARAM_STR);
      $update_usuario->bindParam(":email", $dados["email"], PDO::PARAM_STR);
      $cad_usuario = $update_usuario->execute();

      $query_update_pes =
        "SELECT id,nome,sobrenome FROM usuarios WHERE email=:email LIMIT 1";
      $result_update_usuario = $conn->prepare($query_update_pes);
      $result_update_usuario->bindParam(
        ":email",
        $dados["email"],
        PDO::PARAM_STR
      );
      $result_update_usuario->execute();
      $row_usuario_update = $result_update_usuario->fetch(PDO::FETCH_ASSOC);

      $_SESSION["id"] = $row_usuario_update["id"];
      $_SESSION["nome"] = $row_usuario_update["nome"];
      $_SESSION["sobrenome"] = $row_usuario_update["sobrenome"];

      if ($cad_usuario) {
        $retorna = [
          "erro" => false,
          "msg" =>
            "<span id='msgAlertErroProfile' style='text-size: 12pt;color: #2E7D32;'>Cadastro atualizado com sucesso!</span>",
        ];
      } else {
        $retorna = [
          "erro" => true,
          "msg" =>
            "<span id='msgAlertErroProfile' style='text-size: 12pt;color: #D50000;'>Ocorreu um erro ao atualizar o cadastro, tente novamente!</span>",
        ];
      }
    } else {
      $retorna = [
        "erro" => true,
        "msg" =>
          "<span id='msgAlertErroProfile' style='text-size: 12pt;color: #D50000;'>Erro: Usuario não encontrado!</span>",
      ];
    }
  } else {
    $query_usuario = "SELECT id,senha,email
            FROM usuarios
            WHERE email=:email
            LIMIT 1";
    $result_usuario = $conn->prepare($query_usuario);
    $result_usuario->bindParam(":email", $dados["email"], PDO::PARAM_STR);
    $result_usuario->execute();
    if ($result_usuario and $result_usuario->rowCount() != 0) {
      $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
      if (password_verify($senha_antiga, $row_usuario["senha"])) {
        $query_update =
          "UPDATE usuarios SET senha=:senha WHERE id=:id AND email=:email";
        $update_usuario = $conn->prepare($query_update);
        $senha_cript = password_hash($senha, PASSWORD_DEFAULT);
        $update_usuario->bindParam(":senha", $senha_cript, PDO::PARAM_STR);
        $update_usuario->bindParam(":id", $row_usuario["id"], PDO::PARAM_STR);
        $update_usuario->bindParam(":email", $dados["email"], PDO::PARAM_STR);
        $cad_usuario = $update_usuario->execute();

        if ($cad_usuario) {
          $retorna = [
            "erro" => false,
            "msg" =>
              "<span id='msgAlertErroProfile' style='text-size: 12pt;color: #2E7D32;'>Cadastro atualizado com sucesso!</span>",
          ];
        } else {
          $retorna = [
            "erro" => true,
            "msg" =>
              "<span id='msgAlertErroProfile' style='text-size: 12pt;color: #D50000;'>Ocorreu um erro ao atualizar o cadastro, tente novamente!</span>",
          ];
        }
      } else {
        $retorna = [
          "erro" => true,
          "msg" =>
            "<span id='msgAlertErroProfile' style='text-size: 12pt;color: #D50000;'>Senha antiga está incorreta!</span>",
        ];
      }
    } else {
      $retorna = [
        "erro" => true,
        "msg" =>
          "<span id='msgAlertErroProfile' style='text-size: 12pt;color: #D50000;'>Erro: Usuario não encontrado!</span>",
      ];
    }
  }
}

echo json_encode($retorna);
