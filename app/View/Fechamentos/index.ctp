<?php
echo $this->Html->link($this->Html->image("botoes/add.png", array("alt" => "Adicionar", "title" => "Adicionar")), array('action' => 'add'), array('escape' => false));
?>
<br><br>
<div id="filtroGrade">
    <?php
    echo $this->Search->create();
    echo $this->Search->input('filter1', array('class' => 'select-box', 'empty' => '-- Cliente --'));
    echo $this->Html->image("separador.png");
    echo $this->Search->input('filter2', array('class' => 'input-box', 'id' => 'nome', 'placeholder' => 'Nome'));
    echo $this->Html->image("separador.png");
    ?>
    <input  type="submit" value="Filtrar" class="botaoFiltro"/>

</div>

<form action="/lelelele/" name="post_69348294s3efsfd989" id="post_69348294s3efsfd989" style="display:none;" method="post">
    <input type="hidden" name="_method" value="POST"/>
</form>
<a href="#" onclick="if (confirm('Você realmete deseja apagar esse item?')) { document.post_69348294s3efsfd989.submit(); } event.returnValue = false; return false;">
</a>

<table cellpadding="0" cellspacing="0">
    <tr>
        <th><?php echo $this->Paginator->sort('id'); ?></th>
        <th><?php echo $this->Paginator->sort('Cliente.nome', 'Cliente'); ?></th>
        <th><?php echo $this->Paginator->sort('Animai.nome', 'Nome do animal'); ?></th>
        <th><?php echo $this->Paginator->sort('dtinicial', 'Data inicial'); ?></th>
        <th><?php echo $this->Paginator->sort('dtfinal', 'Data final'); ?></th>
        <th><?php echo $this->Paginator->sort('valorfinal', 'Valor final'); ?></th>
        <th class="actions"><?php echo __('Ações'); ?></th>
    </tr>
    <?php foreach ($fechamentos as $item): ?>
        <tr>
            <td><?php echo h($item['Fechamento']['id']); ?>&nbsp;</td>
            <td><?php echo h($item['Cliente']['nome']); ?>&nbsp;</td>
            <td><?php echo h($item['Animai']['nome']); ?>&nbsp;</td>
            <td><?php echo h($item['Fechamento']['dtinicial']); ?>&nbsp;</td>
            <td><?php echo h($item['Fechamento']['dtfinal']); ?>&nbsp;</td>
            <td><?php echo h($item['Fechamento']['valorfinal']); ?>&nbsp;</td>
            <td>
                <div id="botoes">
                    <?php
                    echo $this->Html->link($this->Html->image("botoes/view.png", array("alt" => "Visualizar", "title" => "Visualizar")), array('action' => 'view', $item['Fechamento']['id']), array('escape' => false));
                    echo $this->Form->postLink($this->Html->image('botoes/excluir.gif', array('alt' => 'Exluir', 'title' => 'Exluir')),
                                               array('action' => 'delete', $item['Fechamento']['id']), array('escape' => false),
                                               __('Você realmete deseja apagar esse item?')
                                              );
                    
                    ?>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<br>
<p>
    <?php
    if ($this->Paginator->counter('{:pages}') > 1) {
        echo "<p> &nbsp; | " . $this->Paginator->numbers() . "| </p>";
    } else {
        echo $this->Paginator->counter('{:count}') . " registros encontrados.";
    }
    ?>
</p>

<script type="text/javascript">
    jQuery(document).ready(function(){
        document.getElementById('filterFilter1').focus();
    });
</script>