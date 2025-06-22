let selectCategoria = document.getElementById('categoria');

let montaSelect = function () {
    let selectSubcategoria = document.getElementById('subcategoria');
    let valor = selectCategoria.value;
    let selected = selectSubcategoria.dataset.subcategoria_id;

    fetch("selecionaAluno.php?categoria=" + valor + "&selected=" + selected)
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

// cadastro de subcategoria
$('#montaConsultaAluno').submit(function () {
    let dados = $(this).serialize();
    let action = this.action;

    $.post(action, dados, function (dt) {
        alert(dt);
        montaSelect();
        document.getElementById('montaConsultaAluno').reset();
    });

    return false;
});