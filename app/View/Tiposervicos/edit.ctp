<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
?>
<br>
<br>
<?php echo $this->Form->create('Tiposervico'); ?>
<fieldset>
    <?php
    echo $this->Form->input('descricao', array('id' => 'descricao'));
    echo $this->Form->input('valor', array('id' => 'valor', 'type' => 'text', 'label' => 'Valor do serviço'));
    ?>
</fieldset>
<?php echo $this->Form->end(__('Editar')); ?>

<script type="text/javascript">
    jQuery(document).ready(function(){
        $('#descricao').focus();
        $("#valor").maskMoney({showSymbol:false, decimal:",", thousands:"", precision:2});
    });
</script>