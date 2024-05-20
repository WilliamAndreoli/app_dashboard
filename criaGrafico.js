$(document).ready(() => {


    $.ajax({
        type: 'GET',
        url: 'app.php',
        data: 'competencia=2018-10', //x-www-form-urlencoded
        success: dados => {
            // Converta a string JSON para um objeto JavaScript
            let dadosJson = JSON.parse(dados);

            // Acesse os atributos específicos
            var totalVendas = dadosJson.totalVendas;
            var totalDespesas = dadosJson.totalDespesas;

            


        },
        error: erro => { console.log(erro) }
    })




})