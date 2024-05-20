//Gráfico
var myChart

function createChart(totalDespesas, totalVendas) {
    var ctx = document.getElementById('myChart').getContext('2d')

    //console.log('criou grafico')

    if(myChart) {
        myChart.destroy()
        //console.log('Destrui grafico antigo')
    }

    myChart = new Chart(ctx, {
        type: 'pie', // tipo do gráfico: bar, line, pie, etc.
        data: {
            labels: ['Despesas: ' + totalDespesas , 'Vendas: ' + totalVendas],
            datasets: [{
                label: 'R$',
                data: [totalDespesas, totalVendas],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 5
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    })
    
    //console.log('Adicionou valores')


}



$(document).ready(() => {

    $('#documentacao').on('click', () => {
        //$('#pagina').load('documentacao.html')

        /*
        $.get('documentacao.html', data => { 
            $('#pagina').html(data)
        })*/

        $.post('documentacao.html', data => {
            $('#pagina').html(data)
        })
    })

    $('#suporte').on('click', () => {
        //$('#pagina').load('suporte.html')

        /*
        $.get('suporte.html', data => { 
            $('#pagina').html(data)
        })*/

        $.post('suporte.html', data => {
            $('#pagina').html(data)
        })
    })




    //ajax
    $('#competencia').on('change', e => {

        let competencia = $(e.target).val()

        $.ajax({
            type: 'GET',
            url: 'app.php',
            data: `competencia=${competencia}`, //x-www-form-urlencoded
            success: dados => {
                // Converta a string JSON para um objeto JavaScript
                let dadosJson = JSON.parse(dados);

                // Acesse os atributos específicos
                let totalVendas = dadosJson.totalVendas;
                let numeroVendas = dadosJson.numeroVendas;
                let clientesAtivos = dadosJson.clientes_ativos;
                let clientesInativos = dadosJson.clientes_inativos;
                let totalReclamacoes = dadosJson.totalReclamacoes;
                let totalElogios = dadosJson.totalElogios;
                let totalSugestoes = dadosJson.totalSugestoes;
                let totalDespesas = dadosJson.totalDespesas;

                $('#numeroVendas').html(numeroVendas)
                $('#totalVendas').html(totalVendas)
                $('#clientesAtivos').html(clientesAtivos)
                $('#clientesInativos').html(clientesInativos)
                $('#totalReclamacoes').html(totalReclamacoes)
                $('#totalElogios').html(totalElogios)
                $('#totalSugestoes').html(totalSugestoes)
                $('#totalDespesas').html(totalDespesas)

                createChart(totalDespesas, totalVendas)
            },
            error: erro => { console.log(erro) }
        })

        //método, url, dados, sucesso, erro
    })

})