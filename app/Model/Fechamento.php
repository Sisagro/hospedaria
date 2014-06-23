<?php

App::uses('AppModel', 'Model');

/**
 * Fechamento Model
 * 
 */
class Fechamento extends AppModel {

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'empresa_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                'message' => 'Este campo não pode ser vazio.',
            ),
        ),
        'cliente_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                'message' => 'Este campo não pode ser vazio.',
            ),
        ),
        'animai_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                'message' => 'Este campo não pode ser vazio.',
            ),
        ),
        'dtinicial' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Este campo não pode ser vazio.',
                'last' => false
            ),
            'tamanho' => array(
                'rule' => array('maxLength', 10),
                'message' => 'Este campo não pode ter mais que 10 caracteres.',
            ),
        ),
        'dtfinal' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Este campo não pode ser vazio.',
                'last' => false
            ),
            'tamanho' => array(
                'rule' => array('maxLength', 10),
                'message' => 'Este campo não pode ter mais que 10 caracteres.',
            ),
        ),
        'valorfinal' => array(
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
        'Empresa' => array(
            'className' => 'Empresa',
            'foreignKey' => 'empresa_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Cliente' => array(
            'className' => 'Cliente',
            'foreignKey' => 'cliente_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Animai' => array(
            'className' => 'Animai',
            'foreignKey' => 'animai_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );
    
    /**
     * hasAndBelongsToMany associations
     */
    public $hasAndBelongsToMany = array(
        'Tiposervico' => array(
            'className'             => 'Tiposervico',
            'joinTable'             => 'fechamentotiposervicos',
            'foreignKey'            => 'fechamento_id',
            'associationForeignKey' => 'tiposervico_id',
            'order'                 => 'Tiposervico.descricao',
        ),
        'Eventosanitario' => array(
            'className'             => 'Eventosanitario',
            'joinTable'             => 'fechamentoeventos',
            'foreignKey'            => 'fechamento_id',
            'associationForeignKey' => 'eventosanitario_id',
        ),
        'Eventoalimentacao' => array(
            'className'             => 'Eventoalimentacao',
            'joinTable'             => 'fechamentoalimentacaos',
            'foreignKey'            => 'fechamento_id',
            'associationForeignKey' => 'eventoalimentacao_id',
        ),
    );
    
    
    /**
     * Validações
     */
    
    public function beforeSave($options = array()) {
        if (isset($this->data[$this->alias]['dtinicial'])) {
            $this->data[$this->alias]['dtinicial'] = $this->formataData($this->data[$this->alias]['dtinicial'], 'EN', 'N');
        }
        if (isset($this->data[$this->alias]['dtfinal'])) {
            $this->data[$this->alias]['dtfinal'] = $this->formataData($this->data[$this->alias]['dtfinal'], 'EN', 'N');
        }
        if (isset($this->data[$this->alias]['valordesconto'])) {
            $this->data[$this->alias]['valordesconto'] = str_replace(".", "", $this->data[$this->alias]['valordesconto']);
            $this->data[$this->alias]['valordesconto'] = str_replace(",", ".", $this->data[$this->alias]['valordesconto']);
        }
        if (isset($this->data[$this->alias]['valorfinal'])) {
            $this->data[$this->alias]['valorfinal'] = str_replace(".", "", $this->data[$this->alias]['valorfinal']);
            $this->data[$this->alias]['valorfinal'] = str_replace(",", ".", $this->data[$this->alias]['valorfinal']);
        }
        return true;
    }

    public function afterFind($dados, $primary = false) {
        foreach ($dados as $key => $value) {
            if (!empty($value["Fechamento"]["dtinicial"])) {
                $dados[$key]["Fechamento"]["dtinicial"] = $this->formataData($value["Fechamento"]["dtinicial"], 'PT', 'N');
            }
            if (!empty($value["Fechamento"]["dtfinal"])) {
                $dados[$key]["Fechamento"]["dtfinal"] = $this->formataData($value["Fechamento"]["dtfinal"], 'PT', 'N');
            }
            if (!empty($value["Fechamento"]["valordesconto"])) {
                $dados[$key]["Fechamento"]["valordesconto"] = str_replace(".", ",", $value["Fechamento"]["valordesconto"]);
            }
            if (!empty($value["Fechamento"]["valorfinal"])) {
                $dados[$key]["Fechamento"]["valorfinal"] = str_replace(".", ",", $value["Fechamento"]["valorfinal"]);
            }
        }
        return $dados;
    }
    
}

?>
