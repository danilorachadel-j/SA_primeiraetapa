const usuariosCadastrados = ["Samuel Urdaneta", "Nicolas Lima", "Isack Santana"];
let trens = JSON.parse(localStorage.getItem('trens'))||[];
document.addEventListener('DOMContentLoaded', ()=>{
    carregarResponsaveis();
    renderizarTabela();
    alternarPerfil();
    document.getElementById('form-trem').addEventListener('submit', salvarTrem);
});
function carregarResponsaveis(){
    const select = document.getElementById('usuario_responsavel');
    select.innerHTML = usuariosCadastrados.map(usuario => `<option value ="usuario">{usuario}</option>`).join('');
}
function alternarPerfil(){
    const isAdmin = document.getElementById('perfil').value === 'ADMIN';
    document.getElementById('secao-formulario').style.display = isAdmin ?'block':'nome';

    document.querySelectorAll('.col-acoes, .btn-acao').forEach(el => {
        el.style.display = isAdmin ? ":'nome'; 
    });
    limparFormulario();
}
function renderizarTabela() {
    const tbody = document.getElementById('tabela-trens');
    const isAdmin = document.getElementById('perfil').value === 'ADMIN';

    if (trens.length === 0) {
        tbody.innerHTML = `<tr><td coldspan="6" class = "text-center text-muted">Nenhum trem cadastrado.</td></tr>`;
    }
    tbody.innerHTML = trens.map(trem,index) =>`
    <tr>
    <td><strong>trem.nome < /strong >< br >< smallclass = "text - muted">
{trem.telefone}</small></td>
<td>${trem.tipo}</td>
<td>${trem.nascimento}</td>
<td>trem.cidade/{trem.estado}</td>
<td><span class="badge bg-info text-dark">${trem.responsavel}</span></td>
        <td class="col-acoes" style="display: ${isAdmin ? '' : 'none'};">
                <button class="btn btn-sm btn-outline-warning me-1 btn-acao" onclick="editarTrem(${index})">Editar</button>
                <button class="btn btn-sm btn-outline-danger btn-acao" onclick="excluirTrem(${index})">Excluir</button>
            </td>
        </tr>
    `).join('');
}
function salvarTrem(event) {
    event.preventDefault();
    const id = document.getElementById('trem-id').value;
    const tremData = {
        nome: document.getElementById('nome').value,
        nascimento: document.getElementById('nascimento').value,
        telefone: document.getElementById('telefone').value,
        tipo: document.getElementById('tipo').value,
        cidade: document.getElementById('cidade').value,
        estado: document.getElementById('estado').value.toUpperCase(),
        responsavel: document.getElementById('usuario_responsavel').value
    };
    if (id === '') {
        trens.push(tremData); 
    } else {
        trens[id] = tremData;
    }
    localStorage.setItem('trens', JSON.stringify(trens));
    limparFormulario();
    renderizarTabela();
}
function editarTrem(index) {
    const trem = trens[index];  
    document.getElementById('trem-id').value = index;
    document.getElementById('nome').value = trem.nome;
    document.getElementById('nascimento').value = trem.nascimento;
    document.getElementById('telefone').value = trem.telefone;
    document.getElementById('tipo').value = trem.tipo;
    document.getElementById('cidade').value = trem.cidade;
    document.getElementById('estado').value = trem.estado;
    document.getElementById('usuario_responsavel').value = trem.responsavel;

    document.getElementById('form-titulo').textContent = 'Editar Trem';
    document.getElementById('btn-salvar').textContent = 'Atualizar Trem';
}
function excluirTrem(index) {
    if (confirm('Deseja realmente remover este trem?')) {
        trens.splice(index, 1);
        localStorage.setItem('trens', JSON.stringify(trens));
        renderizarTabela();
    }
}
function limparFormulario() {
    document.getElementById('form-trem').reset();
    document.getElementById('trem-id').value = '';
    document.getElementById('form-titulo').textContent = 'Cadastrar Trem';
    document.getElementById('btn-salvar').textContent = 'Salvar Trem';
}