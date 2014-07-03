<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
echo $this->Html->link($this->Html->image("botoes/imprimir.png", array("alt" => "Imprimir", "title" => "Imprimir")), array('action' => 'print'), array('escape' => false));
?>
<br>
<br>
<p>
<strong> Cliente: </strong>
<?php echo $fechamento['Cliente']['nome'] . " " . $fechamento['Cliente']['sobrenome']; ?>
<br>
<strong> Animal: </strong>
<?php echo $fechamento['Animai']['nome']; ?>
<br>
<strong> Data inicial: </strong>
<?php echo $fechamento['Fechamento']['dtinicial']; ?>
<br>
<strong> Data final: </strong>
<?php echo $fechamento['Fechamento']['dtfinal']; ?>
<br>
<strong> Serviços contratados: </strong>
<br>
<?php foreach ($fechamento['Tiposervico'] as $key => $tiposervico): ?>

    &nbsp;&nbsp;&nbsp;&nbsp; <i><?php echo $tiposervico['descricao']; ?></i> <br>

<?php endforeach; ?>
<br>
<?php
if (count($fechamento['Eventosanitario']) > 0 || count($fechamento['Eventoalimentacao']) > 0 ) {
    if (count($fechamento['Eventosanitario']) > 0) {
        ?>
        <strong> Eventos sanitários realizados: </strong>
        <br>
        <?php foreach ($fechamento['Eventosanitario'] as $key => $evento): ?>

            &nbsp;&nbsp;&nbsp;&nbsp; <i><?php echo $evento['Medicamento']['descricao'] . ' - ' . str_replace(".", ",", $evento['valor']); ?></i> <br>

        <?php endforeach; ?>
        <br>
        <?php
    }
    if (count($fechamento['Eventoalimentacao']) > 0) {
        ?>
        <strong> Eventos de alimentação realizados: </strong>
        <br>
        <?php foreach ($fechamento['Eventoalimentacao'] as $key => $evento): ?>

            &nbsp;&nbsp;&nbsp;&nbsp; <i><?php echo $evento['Alimentacao']['descricao'] . ' - ' . str_replace(".", ",", $evento['valor']); ?></i> <br>

        <?php endforeach; ?>
        <?php
    }
} else {
    echo "<b>Não foram realizados eventos extras no período</b>";
}
?>
<strong> Valor de desconto: </strong>
<?php echo $fechamento['Fechamento']['valordesconto']; ?>
<br><br>
<strong> Valor final: </strong>
<?php echo $fechamento['Fechamento']['valorfinal']; ?>
<br>

<br>
</p>