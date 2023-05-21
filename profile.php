<?php
include('./content/config/connection.php');
session_start();
if (isset($_SESSION['id'])) {
  $id=$_SESSION['id'];
  $query_profile = "SELECT id, email, nome, sobrenome, email, telefone, picture, senha, create_at, updated_at
  FROM usuarios
  WHERE id=:id
  LIMIT 1";
$result_profile = $conn->prepare($query_profile);
$result_profile->bindParam(':id', $id, PDO::PARAM_STR);
$result_profile->execute();

if(($result_profile) and ($result_profile->rowCount() != 0)){
  $row_profile = $result_profile->fetch(PDO::FETCH_ASSOC);
  $nome = $row_profile['nome'];
  $sobrenome = $row_profile['sobrenome'];
  $email = $row_profile['email'];
  $telefone = $row_profile['telefone'];
  $picture = $row_profile['picture'];
  $create = $row_profile['create_at'];
  $update = $row_profile['updated_at'];
}
} else{
header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/styles.css" />
    <link rel="icon" type="image/x-icon" href="assets/imgs/favicon.png" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;400;600;700&display=swap"
      rel="stylesheet"
    />
    <script src="https://kit.fontawesome.com/592119c07d.js" crossorigin="anonymous"></script>
    <style>
      #carregando {
      position: absolute;
      left: 50%;
      top: 50%;
      z-index: 1;
      width: 60px;
      height: 60px;
      margin: -76px 0 0 -76px;
      border: 5px solid #f3f3f3;
      border-radius: 50%;
      border-top: 5px solid #220845;
      -webkit-animation: spin 0.6s linear infinite;
      animation: spin 0.6s linear infinite;
      }

      @-webkit-keyframes spin {
        0% { -webkit-transform: rotate(0deg); }
        100% { -webkit-transform: rotate(360deg); }
      }

      @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
      }

      /* Add animation to "page content" */
      .index-body {
        position: relative;
        -webkit-animation-name: animatebottom;
        -webkit-animation-duration: 1s;
        animation-name: animatebottom;
        animation-duration: 1s
      }

      @-webkit-keyframes animatebottom {
        from { bottom:-100px; opacity:0 } 
        to { bottom:0px; opacity:1 }
      }

      @keyframes animatebottom { 
        from{ bottom:-100px; opacity:0 } 
        to{ bottom:0; opacity:1 }
}
    </style>
    <title>TravelNow - <?php echo $nome; ?> <?php echo $sobrenome; ?></title>
  </head>
  <body onload="loading()" style="margin:0;padding:0;">
  <div id="carregando"></div>
    <div style="display:none;" id="index-body">
    <div class="index-body">
    <header class="menu-container">
    <div class="profile-back">
            <a href="../projeto_tcc/index.php" id="url-back"
              >⬅️ Voltar para página inicial...</a
            >
          </div>
      <div class="logo-profile">
        <a href="index.php"
          ><img src="./assets/imgs/logo_normal.png" width="250px" alt="Logo"
        /></a>
      </div>
      </div>
    </header>
    <h2 class="profile-title">Editor de Perfil</h2>
    <div class="profile-container">
      <div class="profile-picture-editor">
      <!--<label class="-label" for="file">
    <span class="glyphicon glyphicon-camera"></span>
    <span>Alterar foto</span>
  </label>
  <input id="file" type="file" onchange="loadFile(event)"/>-->
          <img src="<?php echo $picture; ?>" alt="Foto de Perfil" class="profile-picture"/>
    <h2 class="profile-name"><?php echo $nome; ?> <?php echo $sobrenome; ?></h2>
    <br>
    <br>
    <div class="profile-info">
    <p class="profile-created"><b>Criado:</b> <?php echo $create; ?></p>
    <p class="profile-updated"><b>Atualizado:</b> <?php echo $update; ?></p>
    </div>
    <br>
    <center>
        <span id="msgAlertErroProfile"></span>
        </center> 
    </div>
        <form method="post" id="profile-editor-form">
        <h3>Alteração de dados:</h3>
        <br>
        <center>
        <span id="msgAlertErroProfile"></span>
        </center>
                <br>
          <div class="profile-input">
            <label for="nome">Nome</label>
            <br>
            <input
              type="text"
              name="nome"
              id="nome"
              placeholder="Nome"
              value="<?php echo $nome; ?>"
            />
            </div>
            <div class="profile-input">
            <label for="sobrenome">Sobrenome</label>
            <br>
            <input
              type="text"
              name="sobrenome"
              id="sobrenome"
              placeholder="Sobrenome"
              value="<?php echo $sobrenome; ?>"
            />
            </div>
            <span style="font-size: 8pt;color:#D50000; float:right;">* Edição não permitida</span>
            <div class="profile-input">
            <label for="email-show">Email<span style="color:#D50000;">*</span></label>
            <br>
            <input
              type="email"
              name="email-show"
              id="email-show"
              placeholder="Email"
              disabled
              value="<?php echo $email; ?>"
            />
            <input type="hidden" name="email" id="email" value="<?php echo $email; ?>"/>
            </div>
            <div class="profile-input">
            <label for="telefone">Telefone</label>
            <br>
            <input
              type="text"
              name="telefone"
              id="telefone"
              placeholder="Telefone"
              value="<?php echo $telefone; ?>"
            />
            </div>
            <br>
            <h3>Alteração de senha:</h3>
            <center>
                  <span id="msgAlertErroSenha"></span>
            </center>
            <br>
            <div class="profile-input">
            <label for="senha">Nova Senha</label>
            <br>
            <input
              type="password"
              name="senha"
              id="senha"
              placeholder="Nova Senha"
            />
            <center>
                      <span
                        id="display-forca"
                        class="badge displayBadge"
                        style="font-size: 10pt"></span>
                    </center>
            </div>  
            <div class="profile-input">
            <label for="confirmar-senha">Confirmar Nova Senha</label>
            <br>
            <input
              type="password"
              name="confirmar-senha"
              id="confirmar-senha"
              placeholder="Confirmar Nova Senha"
            />
            <span
                      id="display-identicas"
                      class="badge displayBadge"
                      style="font-size: 9pt"
                    ></span>
            </div>
            <br>
            <div class="profile-input">
            <label for="senha-antiga">Senha Antiga</label>
            <br>
            <input
              type="password"
              name="senha-antiga"
              id="senha-antiga"
              placeholder="Senha Antiga"
            />
            </div> 
            <br>
            <br>
            <div class="profile-input">
            <center><input type="submit" value="Atualizar Perfil" name="update" class="AtualizarPerfilBtn"/><center>
          </div>
        </form>
        </div>
        <footer class="site-footer" style="margin-top: 250px">
      <nav class="menu-footer">
        <h4 class="title-navegacao">Navegação</h4>
        <ul class="menu-list-footer">
          <li>
            <a href="index.html" class="menu-item-footer">Home</a>
          </li>
          <li><a href="match.html" class="menu-item-footer"><b>Travel</b>Match®</a></li>
          <li><a href="destinos.html" class="menu-item-footer">Destinos</a></li>
          <li><a href="contato.html" class="menu-item-footer">Contato</a></li>
        </ul>

      </nav>
      <center>
      <p class="copy-text">
          &copy; Todos direitos reservados a <b>TravelNow</b>
        </p>
        </center>

        <nav class="social-menu-footer">
        <ul class="social-menu-list-footer">
          <li class="social-icons"><a href="https://instagram.com/igor.sardinha" target="__blank"><i class="fa-brands fa-instagram fa-2x" id="icone-social"></i></a></li>
          <li class="social-icons"><a href="https://github.com/igorsardinha" target="__blank"><i class="fa-brands fa-github fa-2x" id="icone-social"></i></a></li>
          <li class="social-icons"><a href="https://linkedin.com/in/igorsardinha" target="__blank"><i class="fa-brands fa-linkedin fa-2x" id="icone-social"></i></a></li>
        </ul>
      </nav>
    </footer>
    </div>
    </div>
    <script>
  var variavel;

      function loading() {
        variavel = setTimeout(showPage, 1200);
      }

    function showPage() {
      document.getElementById("carregando").style.display = "none";
      document.getElementById("index-body").style.display = "block";
    }
</script>
<script src="js/password_validate.js"></script>
<script src="js/profile_validate.js"></script>

  </body>
</html>