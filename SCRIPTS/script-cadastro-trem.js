document.addEventListener("DOMContentLoaded", function () {

const formulario = document.querySelector("form");

formulario.addEventListener("submit", function (event) {
    event.preventDefault();

    const nome = document.getElementById("nome").value;
    const dataFabricacao = document.getElementById("nascimento").value;
    const telefone = document.getElementById("telefone").value;
    const tipo = document.getElementById("tipo").value;
    const cidade = document.getElementById("cidade").value;
    const estado = document.getElementById("estado").value;

    alert("Trem cadastrado com sucesso\n\n + "Nome: + nome+"\n" + "Data de Fabricação: "+ dataFabricacao + "\n" + "Tipo:" + tipo+"\n" + "Cidade/UF:" + cidade+"/" + estado);

    formulario.reset();
});
});

