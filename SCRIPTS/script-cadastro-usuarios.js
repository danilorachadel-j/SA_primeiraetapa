document.getElementById("formAdocao"). addEventListener("submit", function(e){
    e.preventDefault();

    let nome = document.getElementById("nome"). value;
    let email = document.getElementById("email"). value;
    let moradia = document. getElementById("moradia"). value;
    let CPF = document. getElementById("CPF"). value;
    let idade = parseInt(document.getElementById("idade").value);
    let telefone = document. getElementById("telefone"). value;

    if(nome.length < 3 ) return ("Nome Inválido!");
    if(telefone.lengh <8 ) return("Número Inválido!");
    if(CPF.length < 11) return("CPF inválido!");
    if(nsNaN(idade) || idade < 18) return alert("É necessário ter pelo menos 18 anos!");
    
    document.getElementById("resultado").innerHTML = "cadastro feito com sucesso <br>" + "Nome." + nome;
});

