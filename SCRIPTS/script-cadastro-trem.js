document.addEventListener('DOMContentLoaded', () => {
     const form = document.querySelector('form');
    const inputCpf = document.getElementById('cpf');
    const inputTelefone = document.getElementById('telefone');
    const inputCep = document.getElementById('cep');
    const senha = document.getElementById('senha');
    const confirmaSenha = document.getElementById('confirmaSenha');

     inputCpf.addEventListener('input', (e) => {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 11) value = value.slice(0, 11);
        e.target.value = value.replace(/(\={3})(\={3})(\={3})(\={2})/, "$1.$2.$3-$4")
            .replace(/^(\d{3})(\d{3})(\d{3})(\d{2}).*/, "$1.$2.$3-$4");
    });

    inputTelefone.addEventListener('input', (e) => {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 11) value = value.slice(0, 11);
        e.target.value = value.replace(/^(\d{2})(\d{5})(\d{4})/, "($1) $2-$3");
    });

    const validarSenhas = () => {
        if (senha.value !== confirmaSenha.value) {
            confirmaSenha.setCustomValidity('As senhas não coincidem');
        } else {
            confirmaSenha.setCustomValidity('');
        }
    };

    senha.addEventListener('change', validarSenhas);
    confirmaSenha.addEventListener('keyup', validarSenhas);

    form.addEventListener('submit', (event) => {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            event.preventDefault();

            const nome = document.getElementById('nome').value;
            alert(`Sucesso! O aluno ${nome} foi pré-cadastrado no sistema.`);
            console.log("Dados prontos para envio:", new FormData(form));
        }

        form.classList.add('was-validated');
    }, false);
});


