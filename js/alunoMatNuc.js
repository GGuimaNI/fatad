let selectCategoria = document.getElementById('idNucleo');

let montaSelect = function () {
    let selectSubcategoria = document.getElementById('idAluno');
    let valor = selectCategoria.value;
    let selected = selectSubcategoria.dataset.subcategoria_id;

    fetch("selectAlunosSubcategoriaNuc.php?idNucleo=" + valor + "&selected=" + selected)
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

 // Atualizar o número de registros exibido
    document.getElementById('recordCount').textContent = 'Número de registros: ' + montaSelect.options.length;

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