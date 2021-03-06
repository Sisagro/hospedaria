<?php

App::uses('AppModel', 'Model');

/**
 * Causabaixa Model
 * 
 */
class Alimentacao extends AppModel {

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'descricao' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'holding_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        )
    );

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Holding' => array(
            'className' => 'Holding',
            'foreignKey' => 'holding_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    
    /**
     * hasMany associations
     */
    
    public $hasMany = array(
        'Eventoalimentacao' => array(
            'className' => 'Eventoalimentacao',
            'foreignKey' => 'alimentacao_id',
            'dependent' => false,
        ),
    );
}
