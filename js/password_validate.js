//Variaveis que link com o elemeto no HTML pela ID
let timeout;
let password = document.getElementById("senha");
let confirmPassword = document.getElementById("confirmar-senha");
let strengthBadge = document.getElementById("display-forca");
let equalBadge = document.getElementById("display-identicas");

var strongPassword = new RegExp(
  "^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])(?=.{8,})"
);
var mediumPassword = new RegExp(
  "^(((?=.*[a-z])(?=.*[A-Z]))|((?=.*[a-z])(?=.*[0-9]))|((?=.*[A-Z])(?=.*[0-9])))(?=.{6,})"
);
//Funcao que testa a força da senha
function StrengthChecker(PasswordParameter) {
  if (strongPassword.test(PasswordParameter)) {
    strengthBadge.style.backgroundColor = "#69F0AE";
    strengthBadge.style.color = "#2E7D32";
    strengthBadge.textContent = "Senha Forte";
  } else if (mediumPassword.test(PasswordParameter)) {
    strengthBadge.style.backgroundColor = "#FFF59D";
    strengthBadge.style.color = "#F9A825";
    strengthBadge.textContent = "Senha Média";
  } else {
    strengthBadge.style.backgroundColor = "#EF9A9A";
    strengthBadge.style.color = "#D50000";
    strengthBadge.textContent = "Senha Fraca";
  }
}

//Funcao que testa se as senhas sao iguais
function EqualsChecker(passwordParam, confirmPasswordParam) {
  if (passwordParam == confirmPasswordParam) {
    equalBadge.textContent = "";
    password.style.borderColor = "#ccc";
    confirmPassword.style.borderColor = "#ccc";
  } else {
    equalBadge.style.color = "#D50000";
    password.style.borderColor = "#D50000";
    confirmPassword.style.borderColor = "#D50000";
    equalBadge.textContent = "Senhas são diferentes!";
  }
}
//Auditor da forca da senha
password.addEventListener(
  "input",
  () => {
    strengthBadge.style.display = "block";
    clearTimeout(timeout);

    timeout = setTimeout(() => StrengthChecker(password.value), 500);

    if (password.value.length !== 0) {
      strengthBadge.style.display != "block";
    } else {
      strengthBadge.style.display = "none";
      password.style.borderColor = "#ccc";
      confirmPassword.style.borderColor = "#ccc";
    }
  },
  //Auditor se as senhas sao iguais
  confirmPassword.addEventListener("input", () => {
    equalBadge.style.display = "block";
    clearTimeout(timeout);

    timeout = setTimeout(
      () => EqualsChecker(password.value, confirmPassword.value),
      500
    );

    if (confirmPassword.value.length !== 0) {
      equalBadge.style.display != "block";
    } else {
      equalBadge.style.display = "none";
    }
  })
);
