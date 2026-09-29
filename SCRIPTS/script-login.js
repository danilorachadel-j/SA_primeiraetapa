document.getElementById("form-login"). addEventListener("submit",(e){
    e.preventDefault();

    let email = document.getElementById("email"). value;
    let senha = document.getElementById("senha"). value;

    if(senha.length < 8 ) return ("Senha Inválida!");

    document.getElementById("resultado").innerHTML = "login feito com sucesso <br>" + "Email." + email;
});