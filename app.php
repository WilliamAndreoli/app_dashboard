<?php

// Classe dashboard
class Dashboard implements JsonSerializable
{
    private $data_inicio;
    private $data_fim;
    private $numeroVendas;
    private $totalVendas;
    private $clientes_ativos;
    private $clientes_inativos;
    private $totalReclamacoes;
    private $totalElogios;
    private $totalSugestoes;
    private $totalDespesas;

    public function __get($atr) {
        return $this->$atr;
    }

    public function __set($atr, $valor) {
        $this->$atr = $valor;
        return $this;
    }

    public function jsonSerialize() {
        return get_object_vars($this);
    }
}


// Classe de conexao bd
class Conexao {
    private $host = 'localhost';
    private $dbname = 'dashboard';
    private $user = 'root';
    private $pass = '';

    public function conectar() {
        try {
            $conexao = new PDO(
                "mysql:host=$this->host;dbname=$this->dbname",
                $this->user,
                $this->pass
            );

            $conexao->exec('set names utf8');

            return $conexao;

        } catch (PDOException $e) {
            echo '<p>' . $e->getMessage() . '</p>';
        }
    }
}

// Classe (model)
class Bd {
    private $conexao;
    private $dashboard;

    public function __construct(Conexao $conexao, Dashboard $dashboard) {
        $this->conexao = $conexao->conectar();
        $this->dashboard = $dashboard;
    }

    public function getNumeroVendas() {
        $query = 'select 
                    count(*) as numero_vendas 
                from 
                    tb_vendas 
                where data_venda between :data_inicio and :data_fim';

        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
        $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->numero_vendas;
    }

    public function getTotalVendas() {
        $query = 'select 
                    SUM(total) as total_vendas 
                from 
                    tb_vendas 
                where data_venda between :data_inicio and :data_fim';

        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
        $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->total_vendas;
    }

    public function getClientesAtivos() {
        $query = 'select 
                    count(cliente_ativo) as total_clientes 
                from 
                    tb_clientes 
                where cliente_ativo = 1';

        $stmt = $this->conexao->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->total_clientes;
    }

    public function getClientesInativos() {
        $query = 'select 
                    count(cliente_ativo) as total_clientes 
                from 
                    tb_clientes 
                where cliente_ativo = 0';

        $stmt = $this->conexao->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->total_clientes;
    }

    public function getTotalReclamacoes() {
        $query = 'select 
                    count(tipo_contato) as total_reclamacoes 
                from 
                    tb_contatos 
                where tipo_contato = 1';

        $stmt = $this->conexao->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->total_reclamacoes;
    }

    public function getTotalElogios() {
        $query = 'select 
                    count(tipo_contato) as total_elogios
                from 
                    tb_contatos 
                where tipo_contato = 3';

        $stmt = $this->conexao->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->total_elogios;
    }

    public function getTotalSugestoes() {
        $query = 'select 
                    count(tipo_contato) as total_sugestoes
                from 
                    tb_contatos 
                where tipo_contato = 2';

        $stmt = $this->conexao->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->total_sugestoes;
    }

    public function getTotalDespesas() {
        $query = 'select 
                    SUM(total) as total_despesas
                from 
                    tb_despesas';

        $stmt = $this->conexao->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->total_despesas;
    }
}

// Lógica do script
$dashboard = new Dashboard();

$conexao = new Conexao();

$competencia = explode('-', $_GET['competencia']);
$ano = $competencia[0];
$mes = $competencia[1];

$dias_do_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);

$dashboard->__set('data_inicio', "$ano-$mes-01");
$dashboard->__set('data_fim', "$ano-$mes-$dias_do_mes");

$bd = new Bd($conexao, $dashboard);

$dashboard->__set('totalVendas', $bd->getTotalVendas());
$dashboard->__set('numeroVendas', $bd->getNumeroVendas());
$dashboard->__set('clientes_ativos', $bd->getClientesAtivos());
$dashboard->__set('clientes_inativos', $bd->getClientesInativos());
$dashboard->__set('totalReclamacoes', $bd->getTotalReclamacoes());
$dashboard->__set('totalElogios', $bd->getTotalElogios());
$dashboard->__set('totalSugestoes', $bd->getTotalSugestoes());
$dashboard->__set('totalDespesas', $bd->getTotalDespesas());

echo json_encode($dashboard);

?>
