<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));

//debug(empty($cliente['Endereco']));

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
    
    if (!empty($cliente['Endereco'])) {
        echo "<br>Endereço:<br><br>";
        echo $this->Form->input('Endereco.0.cidade_id', array('id' => 'cidadeID', 'options' => $cidades, 'label' => 'Cidade:'));
        echo $this->Form->input('Endereco.0.rua', array('id' => 'rua', 'label' => 'Rua:'));
        echo $this->Form->input('Endereco.0.numero', array('id' => 'numero', 'label' => 'Número:'));
        echo $this->Form->input('Endereco.0.complemento', array('id' => 'complemento', 'label' => 'Complemento:'));
        echo $this->Form->input('Endereco.0.bairro', array('id' => 'bairro', 'label' => 'Bairro:'));
        echo $this->Form->input('Endereco.0.cep', array('id' => 'cep', 'label' => 'Cep:'));
        echo $this->Form->input('Endereco.0.telefone', array('id' => 'telefone', 'label' => 'Telefone:'));
        echo $this->Form->input('Endereco.0.observacao', array('id' => 'cep', 'label' => 'Observação:'));
        echo $this->Form->input('Endereco.0.empresa_id', array('type' => 'hidden'));
        echo $this->Form->input('Endereco.0.id', array('type' => 'hidden'));
    }
    
    echo $this->Form->input('holding_id', array('type' => 'hidden'));
    
    ?>
</fieldset>
<?php echo $this->Form->end(__('Editar')); ?>

<script type="text/javascript">
    jQuery(document).ready(function(){
        $("#dtnascimento").mask("99/99/9999");
        $("#cpf").mask("999.999.999-99");
        $("#rg").mask("9999999999");
        $("#telefone").mask("(99) 99999999");
        $("#cep").mask("99.999-999");
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