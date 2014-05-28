<?php

App::uses('AppModel', 'Model');

/**
 * Grausangue Model
 * 
 */
class Tiposervico extends AppModel {

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
        'empresa_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
    );

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Empresa' => array(
            'className' => 'Empresa',
            'foreignKey' => 'empresa_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    
    /**
     * hasMany associations
     */
    public $hasMany = array(
        
    );
    
    
    public function beforeSave($options = array()) {
        if (isset($this->data[$this->alias]['valor'])) {
            $this->data[$this->alias]['valor'] = str_replace(".", "", $this->data[$this->alias]['valor']);
            $this->data[$this->alias]['valor'] = str_replace(",", ".", $this->data[$this->alias]['valor']);
        }
        return true;
    }

    public function afterFind($dados, $primary = false) {
        foreach ($dados as $key => $value) {
            if (!empty($value["Tiposervico"]["valor"])) {
                $dados[$key]["Tiposervico"]["valor"] = str_replace(".", ",", $value["Tiposervico"]["valor"]);
            }
        }
        return $dados;
    }


}
