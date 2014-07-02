<?php

App::uses('AppModel', 'Model');

class Animalalimentacao extends AppModel {
    
    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Eventoalimentacao' => array(
            'className' => 'Eventoalimentacao',
            'foreignKey' => 'alimentacao_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    
}
?>
