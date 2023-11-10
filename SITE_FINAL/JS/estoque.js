
function mostrarAdicionarItem() {
    document.getElementById("adicionar-item").style.display = "block";
    document.getElementById("remover-item").style.display = "none";
    document.getElementById("consultar-item").style.display = "none";
    document.getElementById("resultado_busca_geral").style.display = "none";
}
function mostrarRemoverItem() {
    document.getElementById("adicionar-item").style.display = "none";
    document.getElementById("remover-item").style.display = "block";
    document.getElementById("consultar-item").style.display = "none";
    document.getElementById("resultado_busca_geral").style.display = "none";
}
function mostrarConsultarItem() {
    document.getElementById("adicionar-item").style.display = "none";
    document.getElementById("remover-item").style.display = "none";
    document.getElementById("consultar-item").style.display = "block";
    document.getElementById("resultado_busca_geral").style.display = "none";
}
function mostrarConsultarTodosItens() {
    document.getElementById("adicionar-item").style.display = "none";
    document.getElementById("remover-item").style.display = "none";
    document.getElementById("consultar-item").style.display = "none";
    document.getElementById("resultado_busca_geral").style.display = "block";
}