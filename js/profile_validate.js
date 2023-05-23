const ProfileForm = document.getElementById("profile-editor-form");
const msgAlertErroProfile = document.getElementById("msgAlertErroProfile");
const msgAlertErroSenha = document.getElementById("msgAlertErroSenha");

ProfileForm.addEventListener("submit", async (e) => {
	e.preventDefault();

	if (document.getElementById("nome").value === "") {
		msgAlertErroProfile.innerHTML =
			"<span id='msgAlertErroProfile' style='text-size: 12pt;color: #D50000;'>Erro: Necessário preencher o campo nome!</span>";
	} else if (document.getElementById("sobrenome").value === "") {
		msgAlertErroProfile.innerHTML =
			"<span id='msgAlertErroProfile' style='text-size: 12pt;color: #D50000;'>Erro: Necessário preencher o campo sobrenome!</span>";
	} else if (document.getElementById("telefone").value === "") {
		msgAlertErroProfile.innerHTML =
			"<span id='msgAlertErroProfile' style='text-size: 12pt;color: #D50000;'>Erro: Necessário preencher o campo telefone!</span>";
	} else if (document.getElementById("senha").value !== "") {
		if (document.getElementById("confirmar-senha").value === "") {
			msgAlertErroSenha.innerHTML =
				"<span id='msgAlertErroSenha' style='text-size: 12pt;color: #D50000;'><b>Erro:</b> Você precisa confirmar para trocar a senha!</span>";
		} else if (
			document.getElementById("senha").value !=
			document.getElementById("confirmar-senha").value
		) {
			msgAlertErroSenha.innerHTML =
				"<span id='msgAlertErroSenha' style='text-size: 12pt;color: #D50000;'><b>Erro:</b> As senhas digitadas não são iguais!</span>";
		} else if (document.getElementById("senha-antiga").value === "") {
			msgAlertErroSenha.innerHTML =
				"<span id='msgAlertErroSenha' style='text-size: 12pt;color: #D50000;'><b>Erro:</b> Informe sua senha antiga!</span>";
			document.getElementById("senha-antiga").focus();
		} else {
			const dadosForm = new FormData(ProfileForm);

			const dados = await fetch("./content/profile_validate.php", {
				method: "POST",
				body: dadosForm,
			});

			const resposta = await dados.json();

			console.log(resposta);

			if (resposta["erro"]) {
				msgAlertErroProfile.innerHTML = resposta["msg"];
			} else {
				msgAlertErroProfile.innerHTML = resposta["msg"];
				ProfileForm.reset();
				location.reload();
			}
		}
	} else {
		const dadosForm = new FormData(ProfileForm);

		const dados = await fetch("./content/profile_validate.php", {
			method: "POST",
			body: dadosForm,
		});

		const resposta = await dados.json();

		console.log(resposta);

		if (resposta["erro"]) {
			msgAlertErroProfile.innerHTML = resposta["msg"];
		} else {
			msgAlertErroProfile.innerHTML = resposta["msg"];
			setTimeout(function () {
				ProfileForm.reset();
				location.reload();
			}, 500);
		}
	}
});
