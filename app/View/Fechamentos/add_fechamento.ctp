<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false));
?>
<br>
<br>
<?php echo $this->Form->create('Fechamento'); ?>
<fieldset>
    <?php
    echo $this->Form->input('nomeCliente', array('type' => 'text', 'label' => 'Cliente', 'value' => $animal['Cliente']['nome'], 'readonly'));
    echo $this->Form->input('nomeAnimal', array('type' => 'text', 'label' => 'Animal', 'value' => $animal['Animai']['nome'], 'readonly'));
    echo $this->Form->input('dataInicial', array('type' => 'text', 'label' => 'Data inicial', 'value' => $dadosFormulario['Fechamento']['dtinicial'], 'readonly'));
    echo $this->Form->input('dataFinal', array('type' => 'text', 'label' => 'Data final', 'value' => $dadosFormulario['Fechamento']['dtfinal'], 'readonly'));
    
    $listaServicos = array();
    foreach($animal['Tiposervico'] as $key => $subcat){
        $listaServicos[] = $subcat['descricao'];
    }
    
    echo $this->Form->input('sanitarios', array('label'=>'Lista dos serviços contratados: ', 'id' => 'servicos', 'type'=>'select', 'multiple'=>true, 'options' => $listaServicos, 'style' => 'height: 180px;'));
    
    echo $this->Form->input('valorparcial', array('id' => 'valor', 'type' => 'text', 'label' => 'Valor dos serviços contratados', 'value' => $animal['Animai']['valor'], 'readonly'));
    
    $valorfinal = str_replace(",", ".", $animal['Animai']['valor']);
    
    if (count($eventosExibicao) > 0 || count($alimentacaoExibicao) > 0) {
        $opcoesEventos = array();
        if (count($eventosExibicao) > 0) {
            $opcoesEventos[] = "Eventos sanitários realizados no período: ";
        }
        foreach ($eventosExibicao as $itemEvento):
            $opcoesEventos[] = $itemEvento['Eventosanitario']['dtevento'] . ' - ' . $itemEvento['Medicamento']['descricao'] . ' - ' . $itemEvento['Eventosanitario']['valor'];
            $valorfinal = $valorfinal + str_replace(",", ".", $itemEvento['Eventosanitario']['valor']);
        endforeach;
        $opcoesEventos[] = "\n";
        if (count($alimentacaoExibicao) > 0) {
            $opcoesEventos[] = "Eventos de alimentação realizados no período: ";
        }
        foreach ($alimentacaoExibicao as $itemAlimentacao):
            $opcoesEventos[] = $itemAlimentacao['Eventoalimentacao']['dtalimentacao'] . ' - ' . $itemAlimentacao['Alimentacao']['descricao'] . ' - ' . $itemAlimentacao['Eventoalimentacao']['valor'];
            $valorfinal = $valorfinal + str_replace(",", ".", $itemAlimentacao['Eventoalimentacao']['valor']);
        endforeach;

        echo $this->Form->input('sanitarios', array('label'=>'Eventos extras realizados no período: ', 'id' => 'eventosanitario', 'type'=>'select', 'multiple'=>true, 'options' => $opcoesEventos, 'style' => 'height: 180px;'));
    }
        
    echo $this->Form->input('valordesconto', array('id' => 'valorDesconto', 'type' => 'text', 'label' => 'Valor do desconto', 'placeholder' => 'Informe o valor de desconto'));
    echo $this->Form->input('valorfinal', array('id' => 'valorFinal', 'type' => 'text', 'label' => 'Valor final', 'value' => str_replace(".", ",", $valorfinal), 'readonly'));
    echo $this->Form->input('valorfinalcalculo', array('type' => 'hidden', 'id' => 'valorFinalCalculo', 'value' => str_replace(".", ",", $valorfinal)));
    echo $this->Form->input('Eventosanitario', array('type' => 'hidden'));
    echo $this->Form->input('Eventoalimentacao', array('type' => 'hidden'));
    echo $this->Form->input('cliente_id', array('type' => 'hidden'));
    echo $this->Form->input('animai_id', array('type' => 'hidden'));
    echo $this->Form->input('dtinicial', array('type' => 'hidden'));
    echo $this->Form->input('dtfinal', array('type' => 'hidden'));
    echo $this->Form->input('empresa_id', array('type' => 'hidden'));
    echo $this->Form->input('confirma', array('type' => 'hidden', 'value' => 'S'));
    ?>
</fieldset>
<?php echo $this->Form->end(__('Confirma fechamento?')); ?>

<script type="text/javascript">
    jQuery(document).ready(function(){
        document.getElementById('valorDesconto').focus();
        $('#servicos').prop('disabled', true);
        $('#eventosanitario').prop('disabled', true);
        $("#valorFinal").maskMoney({showSymbol:false, decimal:",", thousands:"", precision:2});
        $("#valorDesconto").maskMoney({showSymbol:false, decimal:",", thousands:"", precision:2});
        $("#valorDesconto").change( function(){
            var valorFinal = parseFloat($('#valorFinalCalculo').val().replace(",", ".")) - parseFloat($('#valorDesconto').val().replace(",", "."));
            $("#valorFinal").val(valorFinal.toFixed(2).replace(".", ","));
        })
    });
</script>