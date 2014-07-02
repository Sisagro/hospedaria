<?php

App::uses('AppModel', 'Model');

class Animalevento extends AppModel {
    
    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Eventosanitario' => array(
            'className' => 'Eventosanitario',
            'foreignKey' => 'eventosanitario_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    
}
?>
