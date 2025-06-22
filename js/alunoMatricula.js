let selectCategoria = document.getElementById('idCurso');

let montaSelect = function () {
    console.log("montaSelect foi chamada!");

    let selectSubcategoria = document.getElementById('idTurma');
    let valor = selectCategoria.value;
    let selected = selectSubcategoria.dataset.subcategoria_id;

    fetch("selectTurmasSubcategoria.php?idCurso=" + valor + "&selected=" + selected)
        .then(response => {
            return response.text();
        })
        .then(texto => {
            selectSubcategoria.innerHTML = texto;
        });
}

selectCategoria.onchange = () => {
    montaSelect();
}

montaSelect();

// cadastro de subcategoria NÃO É UTILIZADO AQUI
$('#formCadastroSubCategoria').submit(function () {
    let dados = $(this).serialize();
    let action = this.action;

    $.post(action, dados, function (dt) {
        alert(dt);
        montaSelect();
        document.getElementById('formCadastroSubCategoria').reset();
    });

    return false;
});