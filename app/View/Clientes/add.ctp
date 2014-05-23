<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
?>
<br>
<br>
<?php echo $this->Form->create('Cliente'); ?>
<fieldset>
    <?php
    echo $this->Form->input('nome');
    echo $this->Form->input('sobrenome');
    echo $this->Form->input('cpf', array('id' => 'cpf', 'label' => 'CPF'));
    echo $this->Form->input('rg', array('id' => 'rg', 'label' => 'RG'));
    echo $this->Form->input('dtnascimento', array('id' => 'dtnascimento', 'class' => 'data', 'type' => 'text', 'label' => 'Data de nascimento'));
    echo $this->Form->input('sexo', array ('id' => 'sexoID', 'type' => 'select', 'options' => $sexos, 'label' => 'Sexo', 'empty' => '-- Selecione o sexo --'));
    echo $this->Form->input('ativo', array ('id' => 'ativoID', 'type' => 'select', 'options' => $status, 'label' => 'Status'));
    echo $this->Form->input('holding_id', array('type' => 'hidden', 'value' => $holding_id));
    ?>
</fieldset>
<?php echo $this->Form->end(__('Adicionar')); ?>

<script type="text/javascript">
    jQuery(document).ready(function(){
        $("#dtnascimento").mask("99/99/9999");
        $("#cpf").mask("999.999.999-99");
        $("#rg").mask("9999999999");
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