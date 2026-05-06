document.addEventListener('DOMContentLoaded',() => {
    const btn = document.getElementById('betn-cadastro');

    btn.addEventListener('click', () =>{
        btn.innerText = "Carregando...";
    btn.style.opacity = "0.7";

    setTimeout(() => {
        alert("Iniciando fluxo de cadastro!");
    },1000);
});
});

