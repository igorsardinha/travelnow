<?php
include "./content/config/connection.php";
session_start();
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
      header("Location: travelmatch.php");
    }
  } else {
    header("Location: travelmatch.php");
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
    <title>TravelNow - Seu próximo Destino</title>
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
          <li><a href="travelmatch.php" class="menu-item" id="menu-selected"><b>Travel</b>Match®</a></li>
          <li><a href="destinos.php" class="menu-item">Destinos</a></li>
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
        echo "<a href='./content/logout_travelmatch.php'>Sair</a><br>";
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
      
    <div class="container-travelmatch">
      <form id="form-travelmatch" action="#" method="get">
      <div class="tab">
        <div class="teste-tabs"> 
      <h1 class="titulo-banner">
        <b>Travel</b>Match®
        </h1>
        <p class="subtitulo-banner">
          Encontre seu próximo destino com base em suas preferências
        </p>
      </h1>
      </div>
      </div>
      <div class="tab">
        <p id="pergunta"><span id="num-pergunta">1.</span> Qual é o seu tipo de clima favorito?</p>
        <center>
      <input type="radio" name="pergunta1" id="P1_A" class="input-pergunta" value="1A"> <label for="P1_A" class="label-pergunta" id="label-pergunta">Quente e ensolarado</label>
      <input type="radio" name="pergunta1" id="P1_B" class="input-pergunta" value="1B"> <label for="P1_B" class="label-pergunta" id="label-pergunta">Frio e nevado</label>
      <input type="radio" name="pergunta1" id="P1_C" class="input-pergunta" value="1C"> <label for="P1_C" class="label-pergunta" id="label-pergunta">Ameno e fresco</label>
      <input type="radio" name="pergunta1" id="P1_D" class="input-pergunta" value="1D"> <label for="P1_D" class="label-pergunta" id="label-pergunta">Úmido e chuvoso</label>
      <input type="radio" name="pergunta1" id="P1_E" class="input-pergunta" value="1E"> <label for="P1_E" class="label-pergunta" id="label-pergunta">Seco e árido</label>
      </center>
    </div>
    <div class="tab">
    <p id="pergunta"><span id="num-pergunta">2.</span> Qual tipo de comida você mais gosta?</p>
    <center>
      <input type="radio" name="pergunta2" id="P2_A" class="input-pergunta" value="2A"><label for="P2_A" class="label-pergunta" id="label-pergunta">Comida italiana</label>
      <input type="radio" name="pergunta2" id="P2_B" class="input-pergunta" value="2B"><label for="P2_B" class="label-pergunta" id="label-pergunta">Comida asiática</label>
      <input type="radio" name="pergunta2" id="P2_C" class="input-pergunta" value="2C"><label for="P2_C" class="label-pergunta" id="label-pergunta">Comida mexicana</label>
      <input type="radio" name="pergunta2" id="P2_D" class="input-pergunta" value="2D"><label for="P2_D" class="label-pergunta" id="label-pergunta">Comida brasileira</label>
      <input type="radio" name="pergunta2" id="P2_E" class="input-pergunta" value="2E"><label for="P2_E" class="label-pergunta" id="label-pergunta">Comida mediterrânea</label>
      </center>
    </div>
    <div class="tab">
    <p id="pergunta"><span id="num-pergunta">3.</span> Qual tipo de atividade você prefere fazer durante as férias?</p>
    <center>
      <input type="radio" name="pergunta3" id="P3_A" class="input-pergunta" value="3A"><label for="P3_A" class="label-pergunta" id="label-pergunta">Relaxar na praia</label>
      <input type="radio" name="pergunta3" id="P3_B" class="input-pergunta" value="3B"><label for="P3_B" class="label-pergunta" id="label-pergunta">Fazer trilhas em montanhas</label>
      <input type="radio" name="pergunta3" id="P3_C" class="input-pergunta" value="3C"><label for="P3_C" class="label-pergunta" id="label-pergunta">Visitar museus e monumentos históricos</label>
      <input type="radio" name="pergunta3" id="P3_D" class="input-pergunta" value="3D"><label for="P3_D" class="label-pergunta" id="label-pergunta">Participar de atividades esportivas</label>
      <input type="radio" name="pergunta3" id="P3_E" class="input-pergunta" value="3E"><label for="P3_E" class="label-pergunta" id="label-pergunta">Fazer compras</label>
      </center>
    </div>
    <div class="tab">

    <p id="pergunta"><span id="num-pergunta">4.</span> Qual é o seu tipo de hospedagem preferido?</p>
    <center>
      <input type="radio" name="pergunta4" id="P4_A" class="input-pergunta" value="4A"><label for="P4_A" class="label-pergunta" id="label-pergunta">Hotel de luxo</label>
      <input type="radio" name="pergunta4" id="P4_B" class="input-pergunta" value="4B"><label for="P4_B" class="label-pergunta" id="label-pergunta">Casa de férias</label>
      <input type="radio" name="pergunta4" id="P4_C" class="input-pergunta" value="4C"><label for="P4_C" class="label-pergunta" id="label-pergunta">Hostel</label>
      <input type="radio" name="pergunta4" id="P4_D" class="input-pergunta" value="4D"><label for="P4_D" class="label-pergunta" id="label-pergunta">Camping</label>
      <input type="radio" name="pergunta4" id="P4_E" class="input-pergunta" value="4E"><label for="P4_E" class="label-pergunta" id="label-pergunta">Airbnb</label>
    </center>  
    </div>
    <div class="tab">
    <p id="pergunta"><span id="num-pergunta">5.</span> Qual é o seu tipo de transporte preferido durante uma viagem?</p>
    <center>  
      <input type="radio" name="pergunta5" id="P5_A" class="input-pergunta" value="5A"><label for="P5_A" class="label-pergunta" id="label-pergunta">Carro alugado</label>
      <input type="radio" name="pergunta5" id="P5_B" class="input-pergunta" value="5B"><label for="P5_B" class="label-pergunta" id="label-pergunta">Trem</label>
      <input type="radio" name="pergunta5" id="P5_C" class="input-pergunta" value="5C"><label for="P5_C" class="label-pergunta" id="label-pergunta">Avião</label>
      <input type="radio" name="pergunta5" id="P5_D" class="input-pergunta" value="5D"><label for="P5_D" class="label-pergunta" id="label-pergunta">Ônibus</label>
      <input type="radio" name="pergunta5" id="P5_E" class="input-pergunta" value="5E"><label for="P5_E" class="label-pergunta" id="label-pergunta">Moto</label>
      </center>
    </div>
    <div class="tab">
    <p id="pergunta"><span id="num-pergunta">6.</span> Qual é o tipo de paisagem que mais lhe atrai?</p>
    <center>  
      <input type="radio" name="pergunta6" id="P6_A" class="input-pergunta" value="6A"><label for="P6_A" class="label-pergunta" id="label-pergunta">Praias paradisíacas</label>
      <input type="radio" name="pergunta6" id="P6_B" class="input-pergunta" value="6B"><label for="P6_B" class="label-pergunta" id="label-pergunta">Cânions e montanhas rochosas</label>
      <input type="radio" name="pergunta6" id="P6_C" class="input-pergunta" value="6C"><label for="P6_C" class="label-pergunta" id="label-pergunta">Campos verdes e paisagens rurais</label>
      <input type="radio" name="pergunta6" id="P6_D" class="input-pergunta" value="6D"><label for="P6_D" class="label-pergunta" id="label-pergunta">Florestas tropicais</label>
      <input type="radio" name="pergunta6" id="P6_E" class="input-pergunta" value="6E"><label for="P6_E" class="label-pergunta" id="label-pergunta">Desertos e paisagens áridas</label>
      </center>
    </div>
    <div class="tab">
    <p id="pergunta"><span id="num-pergunta">7.</span> Qual é o tipo de música que você mais gosta?</p>
    <center>  
      <input type="radio" name="pergunta7" id="P7_A" class="input-pergunta" value="7A"><label for="P7_A" class="label-pergunta" id="label-pergunta">Pop</label>
      <input type="radio" name="pergunta7" id="P7_B" class="input-pergunta" value="7B"><label for="P7_B" class="label-pergunta" id="label-pergunta">Rock</label>
      <input type="radio" name="pergunta7" id="P7_C" class="input-pergunta" value="7C"><label for="P7_C" class="label-pergunta" id="label-pergunta">Eletrônica</label>
      <input type="radio" name="pergunta7" id="P7_D" class="input-pergunta" value="7D"><label for="P7_D" class="label-pergunta" id="label-pergunta">Samba e pagode</label>
      <input type="radio" name="pergunta7" id="P7_E" class="input-pergunta" value="7E"><label for="P7_E" class="label-pergunta" id="label-pergunta">Sertanejo</label>
      </center>
    </div>
    <div class="tab">
        <div class="teste-tabs"> 
        <p class="subtitulo-enviar">
          Criamos o destino ideal para você
        </p>
        <p class="texto-enviar" style="margin-top: -80px;">
          Nossa tecnologia utiliza perguntas de âmbito pessoal e preferências, sendo assim caso o destino não seja compativel você pode fazer o teste quantas vezes quiser. Clique no botão enviar e se surpreenda!
        </p>
        <img src="./assets/imgs/aviao-last-page.jpg" alt="aviao" width="200px" style="margin-top: -40px;">
      </div>
      </div>

  <div style="overflow:auto;">
    <div style="text-align:center;">
      <button type="button" id="prevBtn" onclick="nextPrev(-1)">Voltar</button>
      <button type="button" id="nextBtn" onclick="nextPrev(1)">Proxima</button>
    </div>
  </div>

  <div style="text-align:center;margin-top:40px;">
  <span class="etapa"></span>
  <span class="etapa"></span>
  <span class="etapa"></span>
  <span class="etapa"></span>
  <span class="etapa"></span>
  <span class="etapa"></span>
  <span class="etapa"></span>
  <span class="etapa"></span>
  <span class="etapa"></span>
</div>
    </form>
    </div>
    

      <footer class="site-footer" style="margin-top:120px;">
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