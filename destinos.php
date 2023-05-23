<?php
include "./content/config/connection.php";

session_start();

$query_destino = "SELECT id, destino,foto
FROM destinos
ORDER BY id
LIMIT 10";
$result_destino = $conn->prepare($query_destino);
$result_destino->execute();

if (isset($_SESSION["id"])) {
  $id = $_SESSION["id"];
  $query_profile = "SELECT id, senha
  FROM usuarios
  WHERE id=:id
  LIMIT 1";
  $result_profile = $conn->prepare($query_profile);
  $result_profile->bindParam(":id", $id, PDO::PARAM_STR);
  $result_profile->execute();

  if ($result_profile and $result_profile->rowCount() != 0) {
    $row_profile = $result_profile->fetch(PDO::FETCH_ASSOC);
    $senha = $row_profile["senha"];
    if ($_SESSION["senha"] != $senha) {
      session_destroy();
      header("Location: destinos.php");
    }
  } else {
    header("Location: destinos.php");
  }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/styles.css" />
    <link rel="stylesheet" href="css/travelmatch.css" />
    <link rel="icon" type="image/x-icon" href="assets/imgs/favicon.png" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;400;600;700&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
      integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2"
      crossorigin="anonymous"
    />
    <script
      src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
      integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx"
      crossorigin="anonymous"
    ></script>
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
    <title>TravelNow - Destinos</title>
  </head>
  <body onload="loading()" style="margin:0;padding:0;">
  <div id="carregando"></div>
    <div style="display:none;" id="index-body">
    <div class="index-body">
    <header class="menu-container">
      <div class="logo">
        <a href="index.php"
          ><img src="./assets/imgs/logo_normal.png" width="250px" alt="Logo"
        /></a>
      </div>
      <nav class="menu">
        <ul class="menu-list">
          <li>
            <a href="index.php" class="menu-item">Home</a>
          </li>
          <li><a href="travelmatch.php" class="menu-item"><b>Travel</b>Match®</a></li>
          <li><a href="destinos.php" class="menu-item"  id="menu-selected">Destinos</a></li>
          <li><a href="contato.php" class="menu-item">Contato</a></li>
        </ul>
      </nav>
      <?php if (
        isset($_SESSION["id"]) and
        isset($_SESSION["nome"]) and
        isset($_SESSION["sobrenome"])
      ) {
        echo "<div id='dados-usuario'>";
        echo "<div id='profile-picture'><a href='profile.php'><img src='" .
          $_SESSION["picture"] .
          "' width='52px' height ='52px' style='border: 3px solid #d0d0d0;border-radius: 50%'></a> </div>";
        echo "<div id='profile-info'>";
        echo $_SESSION["nome"] . " " . $_SESSION["sobrenome"] . "<br>";
        echo "<a href='./content/logout_destinos.php'>Sair</a><br>";
        echo "</div><br><br>";
        //echo "<span id='editar-perfil' style='display:none'>Editar perfil</span>";
        echo "</div>";
      } else {
        echo "<div class='login-area'>";
        echo "<button type='button' name='btnLogin' class='btnLogin' data-toggle='modal' data-target='#modalLogin'> Login </button>";
        echo "</div>";
      } ?>
      </div>
    </header>
    <div
      class="modal fade"
      id="modalLogin"
      data-backdrop="static"
      data-keyboard="true"
    >
      <div class="modal-dialog modal-xl modal-dialog-centered" id="loginModal">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="modal-title-font">Login / Registre-se</h4>
            <button type="button" class="close" data-dismiss="modal">
              &times;
            </button>
          </div>
          <div class="modal-body">
            <div class="areas-modal">
              <div class="area-login">
                <form id="login-usuario-form">
                <span id="msgAlertErroLogin"></span>
                <div class="signin-input">
                  <label for="email"
                    >E-mail: <br /><input
                      type="email"
                      name="email"
                      id="email"
                      required /></label
                  ><br />
                  </div>
                  <div class="signin-input">
                  <label for="senha"
                    >Senha: <br />
                    <input
                      type="password"
                      name="senha"
                      id="senha"
                  /></label>
                  </div>
                  <a href="recuperar-senha.html">Esqueceu sua senha?</a><br />
                  <br />
                  <button type="submit" name="login" class="logarBtn" id="login-usuario-btn">
                    Login
                  </button>
                </form>
              </div>
              <div class="area-register">
                <h4 class="title-registre">Não possui conta?</h4>
                <br />
                <p class="text-registre">
                  Registre-se agora mesmo e tenha acesso a todos os nossos
                  destinos!
                </p>
                <br />
                <a
                  href="signup.html"
                  rel="noopener noreferrer"
                  >Registre-se</a
                >
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
      
    <div class="body-destinos">
      <div class="destinos-title">
        <h1 id="destinos-title">Destinos</h1>
        <p id="destinos-subtitle">
          Conheça os destinos mais visitados do mundo e escolha o seu!
        </p>
        <div class="lista-destinos">
        <div class="destinos" id="container-dest">
      <?php if ($result_destino and $result_destino->rowCount() != 0) {
        while ($row_destino = $result_destino->fetch(PDO::FETCH_ASSOC)) {
          echo "<a href='destino.html' target='__blank' id='destino-link'> ";
          echo "<div id='destino'>";
          echo "<img src='" .
            $row_destino["foto"] .
            "' id='destino-img'>
          <h4 id='destino-texto'>" .
            $row_destino["destino"] .
            "</h4>";
          echo "</div>";
          echo "</a>";
        }
      } else {
        for ($x = 0; $x <= 101; $x++) {
          echo "<a href='destino.html' target='__blank' id='destino-link'> ";
          echo "<div id='destino'>";
          echo "<img src='./assets/imgs/destinos.png' id='destino-img'>
          <h4 id='destino-texto'>Destino Padrão</h4>";
          echo "</div>";
          echo "</a>";
        }
      } ?>
        </div>
        </div>
        </div>
    </div>
      
      <footer class="site-footer">
      <nav class="menu-footer">
        <h4 class="title-navegacao">Navegação</h4>
        <ul class="menu-list-footer">
          <li> <a href="index.php" class="menu-item-footer">Home</a></li>
          <li><a href="travelmatch.php" class="menu-item-footer"><b>Travel</b>Match®</a></li>
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

    var button = document.getElementById('slide');
    button.onclick = function () {
        var container = document.getElementById('container-destinos');
        sideScroll(container,'right',25,100,10);
    };

    var back = document.getElementById('slideBack');
    back.onclick = function () {
        var container = document.getElementById('container-destinos');
        sideScroll(container,'left',25,100,10);
    };

function sideScroll(element,direction,speed,distance,step){
    scrollAmount = 0;
    var slideTimer = setInterval(function(){
        if(direction == 'left'){
            element.scrollLeft -= step;
        } else {
            element.scrollLeft += step;
        }
        scrollAmount += step;
        if(scrollAmount >= distance){
            window.clearInterval(slideTimer);
        }
    }, speed);
}

document.getElementById("dados-usuario").onmouseover = function() {mostraEditar()};
document.getElementById("dados-usuario").onmouseout = function() {ocultaEditar()};

function mostraEditar(){
  document.getElementById("editar-perfil").style.display = "block";
}
function ocultaEditar(){
  document.getElementById("editar-perfil").style.display = "none";
}


</script>
    <script src="js/login_validate.js"></script>
    <script src="js/travelmatch-test-form.js"></script>
  </body>
</html>