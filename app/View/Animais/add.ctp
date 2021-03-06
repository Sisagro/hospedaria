<?php
echo $this->Html->link($this->Html->image("botoes/retornar.png", array("alt" => "Retornar", "title" => "Retornar")), array('action' => 'index'), array('escape' => false, 'onclick' => 'history.go(-1); return false;'));
?>
<br>
<br>
<?php echo $this->Form->create('Animai'); ?>
<fieldset>
    <?php
    echo $this->Form->input('cliente_id', array ('id' => 'clienteID', 'type' => 'select','options' => $clientes, 'label' => 'Cliente', 'empty' => '-- Selecione o cliente --'));
    echo $this->Form->input('especie_id', array ('id' => 'especieID', 'type' => 'select','options' => $especies, 'label' => 'Espécie', 'empty' => '-- Selecione a espécie --'));
    echo $this->Form->input('sexo', array ('id' => 'sexoID', 'type' => 'select', 'options' => $sexos, 'label' => 'Sexo', 'empty' => '-- Selecione o sexo --'));
    echo $this->Form->input('categoria_id', array('id' => 'categoriaID', 'type' => 'select', 'label' => 'Categoria'));
    echo $this->Form->input('raca_id', array('id' => 'racaID', 'type' => 'select', 'label' => 'Raça'));
    echo $this->Form->input('pelagen_id', array('id' => 'pelagenID', 'type' => 'select', 'label' => 'Pelagem'));
    echo $this->Form->input('grausangue_id', array ('id' => 'grausangueID', 'type' => 'select','options' => $grausangues, 'label' => 'Grau de sangue', 'empty' => '-- Selecione o grau de sangue --'));
    ?>
    <div class="fomr_checkbox_animais">
        <label>Identificação</label><br>
        <input type="checkbox" name="nome" id="nome" value="check" onclick="myfunction(this);" > Nome
        <input type="checkbox" name="brinco" id="brinco" value="check" onclick="myfunction(this);" > Brinco
        <input type="checkbox" name="tatuagem" id="tatuagem" value="check" onclick="myfunction(this);"> Tatuagem
        <input type="checkbox" name="hbbsbb" id="hbbsbb" value="check" onclick="myfunction(this);"> HBB/SBB/FBB
        <input type="checkbox" name="chip" id="chip" value="check" onclick="myfunction(this);"> Chip eletrônico
        <input type="checkbox" name="colar" id="colar" value="check" onclick="myfunction(this);"> Colar Eletrônico
        <input type="checkbox" name="cor" id="cor" value="check" onclick="myfunction(this);"> Cor
    </div>
    <div id="formMostraNome">
        <?php echo $this->Form->input('nome', array('id' => 'nomeInput', 'type' => 'text', 'label' => 'Nome do animal')); ?>
    </div>
    <div id="formMostraBrinco">
        <?php echo $this->Form->input('brinco', array ('id' => 'brincoInput', 'label' => 'Brinco')); ?>
    </div>
    <div id="formMostraTatuagem">
        <?php echo $this->Form->input('tatuagem', array ('id' => 'tatuagemInput', 'label' => 'Tatuagem')); ?>
    </div>
    <div id="formMostraHBBSBB">
        <?php echo $this->Form->input('hbbsbb', array ('id' => 'hbbsbbInput', 'label' => 'HBB/SBB/FBB')); ?>
    </div>
    <div id="formMostraChip">
        <?php echo $this->Form->input('chipeletronico', array ('id' => 'chipeletronicoInput', 'label' => 'Chip eletrônico')); ?>
    </div>
    <div id="formMostraColar">
        <?php echo $this->Form->input('colareletronico', array ('id' => 'colareletronicoInput', 'label' => 'Colar eletrônico')); ?>
    </div>
    <div id="formMostraCor">
        &nbsp;Cor:<br>
        <div id="colorSelector">
            <div style="background-color: #0000EE">
            </div>
        </div>
    </div>
    <div id="coranimal">
        <?php echo $this->Form->input('cor', array ('id' => 'corAnimal', 'label' => 'Cor')); ?>
    </div>
    
    <?php
    echo $this->Form->input('dtentrada', array('id' => 'dtentrada', 'class' => 'data', 'type' => 'text', 'label' => 'Data de entrada'));
    echo $this->Form->input('caracteristica', array ('id' => 'caracteristica', 'type' => 'textarea', 'label' => 'Características', 'escape' => false));
    echo $this->Form->input('Tiposervico.Tiposervico',array('title' => 'CTRL + Click (para selecionar mais de um)', 'label'=>'Escolha os tipos de serviços', 'type'=>'select', 'multiple'=>true, 'style' => 'height: 210px;'));
    echo $this->Form->input('valor', array('id' => 'valor', 'type' => 'text', 'label' => 'Valor mensal do animal'));
    echo $this->Form->input('ativo', array ('id' => 'ativo', 'type' => 'hidden', 'value' => 'A'));
    echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresa_id));
    ?>
</fieldset>
<?php echo $this->Form->end(__('Adicionar')); ?>

<?php
$this->Js->get('#especieID')->event(
    'change',
    $this->Js->request(
        array('controller' => 'Racas', 'action' => 'buscaRacas', 'Animai'),
        array(  'update' => '#racaID',
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
$this->Js->get('#racaID')->event(
    'change',
    $this->Js->request(
        array('controller' => 'Pelagens', 'action' => 'buscaPelagens', 'Animai'),
        array(  'update' => '#pelagenID',
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
    
    function myfunction(obj){
        if (obj.checked) {
            if (obj.name == "nome") {
                $("#formMostraNome").show();
                document.getElementById('nomeInput').focus();
            } else if (obj.name == "brinco") {
                $("#formMostraBrinco").show();
                document.getElementById('brincoInput').focus();
            } else if (obj.name == "chip") {
                $("#formMostraChip").show();
                document.getElementById('chipeletronicoInput').focus();
            } else if (obj.name == "colar") {
                $("#formMostraColar").show();
                document.getElementById('colareletronicoInput').focus();
            } else if (obj.name == "tatuagem") {
                $("#formMostraTatuagem").show();
                document.getElementById('tatuagemInput').focus();
            } else if (obj.name == "cor") {
                $("#formMostraCor").show();
                document.getElementById('corPlugin').focus();
            } else if (obj.name == "hbbsbb") {
                $("#formMostraHBBSBB").show();
                document.getElementById('hbbsbbInput').focus();
            }
        } else {
            if (obj.name == "nome") {
                $("#formMostraNome").hide();
            } else if (obj.name == "brinco") {
                $("#formMostraBrinco").hide();
            } else if (obj.name == "chip") {
                $("#formMostraChip").hide();
            } else if (obj.name == "colar") {
                $("#formMostraColar").hide();
            } else if (obj.name == "tatuagem") {
                $("#formMostraTatuagem").hide();
            } else if (obj.name == "cor") {
                $("#formMostraCor").hide();
            } else if (obj.name == "hbbsbb") {
                $("#formMostraHBBSBB").hide();
            }
        }
    }
    
    jQuery(document).ready(function(){
        $("#dtentrada").mask("99/99/9999");
        $("#valor").maskMoney({showSymbol:false, decimal:",", thousands:"", precision:2});
        $("#coranimal").hide();
        document.getElementById('clienteID').focus();
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
        
        $('#colorSelector').ColorPicker({
                color: '#0000ff',
                onShow: function (colpkr) {
                        $(colpkr).fadeIn(500);
                        return false;
                },
                onHide: function (colpkr) {
                        $(colpkr).fadeOut(500);
                        return false;
                },
                onChange: function (hsb, hex, rgb) {
                        $('#colorSelector div').css('backgroundColor', '#' + hex);
                        $('#corAnimal').val(hex);
                        
                }
        });
        
        $("#formMostraNome").hide();
        $("#formMostraBrinco").hide();
        $("#formMostraChip").hide();
        $("#formMostraColar").hide();
        $("#formMostraTatuagem").hide();
        $("#formMostraCor").hide();
        $("#formMostraHBBSBB").hide();
        
        
        
        $("#especieID").change( function(){
            $('#categoriaID option').remove();
            var select = jQuery('#sexoID');
            select.val(jQuery('option:first', select).val());
            
        });
        
        $("#sexoID").change( function(){
            $.ajax({async:true, 
                    data:$("#sexoID").serialize(), 
                    dataType:"html",
                    success:function (data, textStatus) {
                        $("#categoriaID").html(data);
                    },
                    type:"post",
                    url:"\/hospedaria/Categorias\/buscaCategoriasAnimais\/Animai\/sexo\/" + $("#especieID option:selected").val()
            });
        });
        
    });
</script>



