<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
?>
<br>
<br>
<?php echo $this->Form->create('Fechamento'); ?>
<fieldset>
    <?php
    echo $this->Form->input('cliente_id', array ('id' => 'clienteID', 'type' => 'select','options' => $clientes, 'label' => 'Cliente', 'empty' => ''));
    echo $this->Form->input('animai_id', array ('id' => 'animalID', 'type' => 'select', 'label' => 'Animal', 'empty' => ''));
    echo $this->Form->input('dtinicial', array('id' => 'dtinicial', 'class' => 'data', 'type' => 'text', 'label' => 'Data inicial do fechamento'));
    echo $this->Form->input('dtfinal', array('id' => 'dtfinal', 'class' => 'data', 'type' => 'text', 'label' => 'Data final do fechamento'));
    echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresa_id));
    echo $this->Form->input('confirma', array('type' => 'hidden', 'value' => 'N'));
    ?>
</fieldset>
<?php echo $this->Form->end(__('Visualizar fechamento')); ?>

<?php
$this->Js->get('#clienteID')->event(
    'change',
    $this->Js->request(
        array('controller' => 'Animais', 'action' => 'buscaAnimaisPorCliente', 'Fechamento'),
        array(  'update' => '#animalID',
                'async' => true,
		'method' => 'post',
		'dataExpression'=>true,
		'data'=> $this->Js->serializeForm(array(
			'isForm' => true,
			'inline' => true
			)),
            )
    )
);
?>

<script type="text/javascript">
    jQuery(document).ready(function(){
        document.getElementById('clienteID').focus();
        $("#dtinicial").mask("99/99/9999");
        $("#dtfinal").mask("99/99/9999");
        $(".data").datepicker({
            dateFormat: 'dd/mm/yy',
            dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
            dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
            dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
            monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
            monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
            nextText: 'Próximo',
            prevText: 'Anterior'
        });
    });
</script>