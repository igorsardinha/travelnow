const contatoForm = document.getElementById("contato-form");
const msgAlertErroContato = document.getElementById("msgAlertErroContato");

contatoForm.addEventListener("submit", async (e) => {
	e.preventDefault();

	if (document.getElementById("nome").value === "") {
		msgAlertErroContato.innerHTML =
			"<div class='alert alert-danger' role='alert'><b>Erro:</b> Necessário preencher o campo nome</div>";
	} else if (document.getElementById("e-mail").value === "") {
		msgAlertErroContato.innerHTML =
			"<div class='alert alert-danger' role='alert'><b>Erro:</b> Necessário preencher o campo email!</div>";
	} else if (document.getElementById("mensagem").value === "") {
		msgAlertErroContato.innerHTML =
			"<div class='alert alert-danger' role='alert'><b>Erro:</b> Necessário preencher o campo mensagem!</div>";
	} else {
		const dadosForm = new FormData(contatoForm);

		const dados = await fetch("./content/contato_validate.php", {
			method: "POST",
			body: dadosForm,
		});

		const resposta = await dados.json();

		if (resposta["erro"]) {
			msgAlertErroContato.innerHTML = resposta["msg"];
		} else {
			msgAlertErroContato.innerHTML = resposta["msg"];
			contatoForm.reset();
		}
	}
});
