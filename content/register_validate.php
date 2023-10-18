<?php

include_once "./config/connection.php";

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (empty($dados["nome"])) {
  $retorna = [
    "erro" => true,
    "msg" =>
      "<span id='msgAlertErroRegister' style='text-size: 12pt;color: #D50000;'>Erro: Necessário preencher o campo nome!</span>",
  ];
} elseif (empty($dados["sobrenome"])) {
  $retorna = [
    "erro" => true,
    "msg" =>
      "<span id='msgAlertErroRegister' style='text-size: 12pt;color: #D50000;'>Erro: Necessário preencher o campo sobrenome!</span>",
  ];
} elseif (empty($dados["email"])) {
  $retorna = [
    "erro" => true,
    "msg" =>
      "<span id='msgAlertErroRegister' style='text-size: 12pt;color: #D50000;'>Erro: Necessário preencher o campo email!</span>",
  ];
} elseif (empty($dados["telefone"])) {
  $retorna = [
    "erro" => true,
    "msg" =>
      "<span id='msgAlertErroRegister' style='text-size: 12pt;color: #D50000;'>Erro: Necessário preencher o campo telefone!</span>",
  ];
} elseif (empty($dados["senha"])) {
  $retorna = [
    "erro" => true,
    "msg" =>
      "<span id='msgAlertErroRegister' style='text-size: 12pt;color: #D50000;'>Erro: Necessário preencher o campo senha!</span>",
  ];
} else {
  $query_usuario_pes = "SELECT id FROM usuarios WHERE email=:email LIMIT 1";
  $result_usuario = $conn->prepare($query_usuario_pes);
  $result_usuario->bindParam(":email", $dados["email"], PDO::PARAM_STR);
  $result_usuario->execute();

  if ($result_usuario and $result_usuario->rowCount() != 0) {
    $retorna = [
      "erro" => true,
      "msg" =>
        "<span id='msgAlertErroRegister' style='text-size: 12pt;color: #D50000;'>Erro: Este e-mail já está cadastrado!</span>",
    ];
  } else {
    $query_usuario =
      "INSERT INTO usuarios (nome, sobrenome, email, telefone, senha) VALUES (:nome, :sobrenome, :email, :telefone, :senha)";
    $cad_usuario = $conn->prepare($query_usuario);
    $cad_usuario->bindParam(":nome", $dados["nome"], PDO::PARAM_STR);
    $cad_usuario->bindParam(":sobrenome", $dados["sobrenome"], PDO::PARAM_STR);
    $cad_usuario->bindParam(":email", $dados["email"], PDO::PARAM_STR);
    $cad_usuario->bindParam(":telefone", $dados["telefone"], PDO::PARAM_STR);
    $senha_cript = password_hash($dados["senha"], PASSWORD_DEFAULT);
    $cad_usuario->bindParam(":senha", $senha_cript, PDO::PARAM_STR);

    $cad_usuario->execute();

    if ($cad_usuario->rowCount()) {
      $retorna = [
        "erro" => false,
        "msg" =>
          "<span id='msgAlertErroRegister' style='text-size: 12pt;color: #2E7D32;'>Cadastro realizado com sucesso!</span>",
      ];
    } else {
      $retorna = [
        "erro" => true,
        "msg" =>
          "<span id='msgAlertErroRegister' style='text-size: 12pt;color: #D50000;'>Ocorreu um erro ao realizar o cadastro, tente novamente!</span>",
      ];
    }
  }
}

echo json_encode($retorna);
