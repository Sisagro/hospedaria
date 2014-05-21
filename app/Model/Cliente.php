<?php

App::uses('AppModel', 'Model');

/**
 * Description of Cliente
 *
 * @author quinho
 */
class Cliente extends AppModel {
    
    /**
    * Validation rules
    *
    * @var array
    */
   public $validate = array(
       'nome' => array(
           'notempty' => array(
               'rule' => array('notempty'),
           ),
           'maximo' => array(
               'rule'    => array('maxLength', '200'),
               'message' => 'Máximo 200 caracteres',
           )
       ),
       'sobrenome' => array(
           'maximo' => array(
               'rule'    => array('maxLength', '200'),
               'message' => 'Máximo 200 caracteres',
               'allowEmpty' => true
           )
       ),
       'dtnascimento' => array(
           'tamanho' => array(
               'rule' => array('maxLength', 10),
               'message' => 'Este campo não pode ter mais que 10 caracteres.',
               'allowEmpty' => true
           ),
       ),
       'holding_id' => array(
           'numeric' => array(
               'rule' => array('numeric'),
           ),
       ),
       'sexo' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
       'ativo' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo é obrigatório.',
            ),
        ),
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
       
   );


   public function beforeSave($options = array()) {
       if (isset($this->data[$this->alias]['dtnascimento'])) {
           $this->data[$this->alias]['dtnascimento'] = $this->formataData($this->data[$this->alias]['dtnascimento'], 'EN', 'N');
       }
       if (isset($this->data[$this->alias]['cpf'])) {
           $this->data[$this->alias]['cpf'] = str_replace(".", "", $this->data[$this->alias]['cpf']);
           $this->data[$this->alias]['cpf'] = str_replace("-", "", $this->data[$this->alias]['cpf']);
       }
       return true;
   }

   public function afterFind($dados, $primary = false) {
       foreach ($dados as $key => $value) {
           if (!empty($value["Cliente"]["dtnascimento"])) {
               $dados[$key]["Cliente"]["dtnascimento"] = $this->formataData($value["Cliente"]["dtnascimento"], 'PT', 'N');
           }
           if (!empty($value["Cliente"]["cpf"])) {
               $dados[$key]["Cliente"]["cpf"] = substr($value['Cliente']['cpf'],0,3) . "." . substr($value['Cliente']['cpf'],3,3) . "." . substr($value['Cliente']['cpf'],6,3) . "-" . substr($value['Cliente']['cpf'],9,2);
           }
       }
       return $dados;
   }
   
   
    
    
}

?>
