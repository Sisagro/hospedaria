<?php

App::uses('AppModel', 'Model');

/**
 * Estado Model
 * 
 */
class Endereco extends AppModel {

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'rua' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'numero' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                'allowEmpty' => true,
            ),
        ),
        'cidade_id' => array(
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
        'Cliente' => array(
            'className' => 'Cliente',
            'foreignKey' => 'cliente_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Cidade' => array(
            'className' => 'Cidade',
            'foreignKey' => 'cidade_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );

    /**
     * hasMany associations
     */
    public $hasMany = array(
        
    );
    
    
    public function beforeSave($options = array()) {
       if (isset($this->data[$this->alias]['cep'])) {
           $this->data[$this->alias]['cep'] = str_replace(".", "", $this->data[$this->alias]['cep']);
           $this->data[$this->alias]['cep'] = str_replace("-", "", $this->data[$this->alias]['cep']);
       }
       if (isset($this->data[$this->alias]['telefone'])) {
           $this->data[$this->alias]['telefone'] = str_replace("(", "", $this->data[$this->alias]['telefone']);
           $this->data[$this->alias]['telefone'] = str_replace(")", "", $this->data[$this->alias]['telefone']);
           $this->data[$this->alias]['telefone'] = str_replace(" ", "", $this->data[$this->alias]['telefone']);
       }
       return true;
   }

   public function afterFind($dados, $primary = false) {
       foreach ($dados as $key => $value) {
           if (!empty($value["Endereco"]["cep"])) {
               $dados[$key]["Endereco"]["cep"] = substr($value['Endereco']['cep'],0,2) . "." . substr($value['Endereco']['cep'],2,3) . "-" . substr($value['Endereco']['cep'],5,3);
           }
           if (!empty($value["Endereco"]["telefone"])) {
               $dados[$key]["Endereco"]["telefone"] = "(" . substr($value['Endereco']['telefone'],0,2) . ") " . substr($value['Endereco']['telefone'],2,8);
           }
       }
       return $dados;
   }

}

?>
