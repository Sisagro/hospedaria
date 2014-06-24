<?php
App::uses('AppController', 'Controller');

App::import('Controller', 'Users');

App::import('Vendor', 'PHPJasperXML/ReportToPDF');

/**
 * Animais Controller
 */
class AnimaisController extends AppController {
    
    function beforeFilter() {
        $this->set('title_for_layout', 'Animais');
    }
    
    public function isAuthorized($user) {
        $Users = new UsersController;
        return $Users->validaAcesso($this->Session->read(), $this->request->controller);
        return parent::isAuthorized($user);
    }
    
    public function imprimir() {
        
        $dadosUser = $this->Session->read();
                
        $sexos = array('M' => 'MACHO', 'F' => 'FÊMEA');
        $this->set('sexos', $sexos);
        
        $especies = $this->Animai->Especy->find('list', array(
            'fields' => array('id', 'descricao'), 
            'conditions' => array('holding_id' => $dadosUser['Auth']['User']['holding_id']),
            'order' => array('descricao' => 'asc')
        ));
        $this->set(compact('especies'));
        
        $animais = 0;
        $especie = 0;
        $categoria = 0;
        
        if ($this->request->is('post')) {
            
            if (empty($this->request->data['Relatorio']['especie_id'])) {
                
                $especie = 0;
                
                $animais = $this->Animai->find('count', array(
                    'conditions' => array(
                        'Animai.empresa_id' => $dadosUser['empresa_id'],
                    )
                ));
                
            } else {
                
                $especie = $this->request->data['Relatorio']['especie_id'];
                
                if (empty($this->request->data['Relatorio']['categoria_id'])) {
                    
                    $categoria = 0;
                    
                    $animais = $this->Animai->find('count', array(
                        'conditions' => array(
                            'Animai.empresa_id' => $dadosUser['empresa_id'],
                            'Animai.especie_id' => $especie,
                        )
                    ));
                    
                } else {
                    
                    $categoria = $this->request->data['Relatorio']['categoria_id'];
                    
                    $animais = $this->Animai->find('count', array(
                        'conditions' => array(
                            'Animai.empresa_id' => $dadosUser['empresa_id'],
                            'Animai.especie_id' => $especie,
                            'Animai.categoria_id' => $categoria,
                        )
                    ));
                    
                }
                
            }
            
            if ($animais == 0) {
                
                $this->Session->setFlash('Não existem dados para exibir o relatório.', 'default', array('class' => 'mensagem_erro'));
                
            } else {
                
                $params = array(
                    'empresa_id' => $dadosUser['empresa_id'],
                    'especie_id' => $especie,
                    'categoria_id' => $categoria,
                    'nomedaempresa' => $dadosUser['nomeEmpresa'],
                    'nomedousuario' => $dadosUser['Auth']['User']['nome'],
                    'data' => date("d/m/Y"),
                );

                ReportToPDF::generateReport($params, 'animais.jrxml', '2', 'Animais');
                
                
            }
            
        }
        
    }
    
    /**
     * index method
     */
    public function index() {
        
        $dadosUser = $this->Session->read();
        
        // busca espécies cadastradas
        $this->Animai->Especy->recursive = -1;
        $especies = $this->Animai->Especy->find('list', array('order' => 'descricao ASC', 'fields' => array('id', 'descricao'), 'conditions' => array('holding_id' => $dadosUser['Auth']['User']['holding_id'])));
        
        // busca clientes cadastrados
        $this->Animai->Cliente->recursive = -1;
        $clientes = $this->Animai->Cliente->find('list', array('order' => 'nome ASC', 'fields' => array('id', 'nome'), 'conditions' => array('holding_id' => $dadosUser['Auth']['User']['holding_id'], 'ativo' => 'A')));
        
        // Sexos
        $sexos = array('M' => 'MACHO', 'F' => 'FÊMEA');
        
        // Categorias
        $filtroCategorias = array('' => '-- Categorias --');
        
        $this->Filter->addFilters(
            array(
                'filter7' => array(
                    'Animai.cliente_id' => array(
                        'select' => $clientes
                    ),
                ),
                'filter1' => array(
                    'Animai.especie_id' => array(
                        'select' => $especies
                    ),
                ),
                'filter2' => array(
                    'Categoria.sexo' => array(
                        'select' => $sexos
                    ),
                ),
                'filter3' => array(
                    'Animai.categoria_id' => array(
                        'select' => $filtroCategorias
                    ),
                ),
                'filter4' => array(
                    'Animai.brinco' => array(
                        'operator' => 'LIKE',
                        'value' => array(
                            'before' => '%',
                            'after'  => '%'
                        )
                    )
                ),
                'filter5' => array(
                    'Animai.tatuagem' => array(
                        'operator' => 'LIKE',
                        'value' => array(
                            'before' => '%',
                            'after'  => '%'
                        )
                    )
                ),
                'filter6' => array(
                    'Animai.nome' => array(
                        'operator' => 'LIKE',
                        'value' => array(
                            'before' => '%',
                            'after'  => '%'
                        )
                    )
                )
            )
        );
        
        $this->Filter->setPaginate('order', array('Cliente.nome' => 'asc'));
        
        $this->Filter->setPaginate('conditions', array($this->Filter->getConditions(), 'Animai.empresa_id' => $dadosUser['empresa_id']));
        //$this->Filter->setPaginate('conditions', array($this->Filter->getConditions()));
        
        $this->set('animais', $this->paginate());
        
        $this->set('validaPlano', $this->validaPlano($dadosUser['Auth']['User']['holding_id'], $dadosUser['Auth']['User']['Holding']['plano_id']));

    }
    
    /**
     * view method
     */
    public function view($id = null) {
        
        $this->Animai->id = $id;
        if (!$this->Animai->exists($id)) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }
        
        $dadosUser = $this->Session->read();
        $animal = $this->Animai->read(null, $id);
        
        if ($animal['Animai']['empresa_id'] != $dadosUser['empresa_id']) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }
        
        $this->set('animal', $animal);
        
    }
    
    
    /**
     * add method
     */
    public function add() {
        
        $dadosUser = $this->Session->read();
        $empresa_id = $dadosUser['empresa_id'];
        $this->set('empresa_id', $empresa_id);
        
        if (!$this->validaPlano($dadosUser['Auth']['User']['holding_id'], $dadosUser['Auth']['User']['Holding']['plano_id'])) {
            $this->redirect(array('action' => 'index'));
        }
        
        // busca clientes cadastrados
        $this->Animai->Cliente->recursive = -1;
        $clientes = $this->Animai->Cliente->find('list', array('order' => 'nome ASC', 'fields' => array('id', 'nome'), 'conditions' => array('holding_id' => $dadosUser['Auth']['User']['holding_id'], 'ativo' => 'A')));
        $this->set('clientes', $clientes);
        
        $sexos = array('M' => 'MACHO', 'F' => 'FÊMEA');
        $this->set('sexos', $sexos);
        
        $status = array('A' => 'ATIVO', 'I' => 'INATIVO');
        $this->set('status', $status);
        
        $especies = $this->Animai->Especy->find('list', array(
            'fields' => array('id', 'descricao'), 
            'conditions' => array('holding_id' => $dadosUser['Auth']['User']['holding_id']),
            'order' => array('descricao' => 'asc')
        ));
        $this->set(compact('especies'));
        
        $grausangues = $this->Animai->Grausangue->find('list', array(
            'fields' => array('id', 'descricao'), 
            'conditions' => array('holding_id' => $dadosUser['Auth']['User']['holding_id']),
            'order' => array('descricao' => 'asc')
        ));
        $this->set(compact('grausangues'));
        
        $causabaixas = $this->Animai->Causabaixa->find('list', array(
            'fields' => array('id', 'descricao'), 
            'conditions' => array('holding_id' => $dadosUser['Auth']['User']['holding_id']),
            'order' => array('descricao' => 'asc')
        ));
        $this->set(compact('causabaixas'));
        
        $tiposervicos = $this->Animai->Tiposervico->find('list',array(
            'fields'=>array('id','descricao'),
            'conditions' => array('empresa_id' => $empresa_id),
            'order'=>array('Tiposervico.descricao' => 'asc')
        ));
        $this->set(compact('tiposervicos'));
        
        if ($this->request->is('post')) {
            $this->Animai->create();
            if ($this->Animai->save($this->request->data)) {
                $this->Session->setFlash('Animal adicionado com sucesso!', 'default', array('class' => 'mensagem_sucesso'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Registro não foi salvo. Por favor tente novamente.', 'default', array('class' => 'mensagem_erro'));
            }
        }
        
    }
    
    /**
     * edit method
     */
    public function edit($id = null) {
        
        $this->Animai->id = $id;
        if (!$this->Animai->exists($id)) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }
        
        $dadosUser = $this->Session->read();
        
        $animal = $this->Animai->read(null, $id);
        if ($animal['Animai']['empresa_id'] != $dadosUser['empresa_id']) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }
        
        // busca clientes cadastrados
        $this->Animai->Cliente->recursive = -1;
        $clientes = $this->Animai->Cliente->find('list', array('order' => 'nome ASC', 'fields' => array('id', 'nome'), 'conditions' => array('holding_id' => $dadosUser['Auth']['User']['holding_id'], 'ativo' => 'A')));
        $this->set('clientes', $clientes);
        
        $sexos = array('M' => 'MACHO', 'F' => 'FÊMEA');
        $this->set('sexos', $sexos);
        
        $status = array('A' => 'ATIVO', 'I' => 'INATIVO');
        $this->set('status', $status);
        
        $categorias = $this->Animai->Categoria->find('list', array(
            'fields' => array('id', 'descricao'), 
            'conditions' => array('especie_id' => $animal['Animai']['especie_id']),
            'order' => array('descricao' => 'asc')
        ));
        $this->set(compact('categorias'));
        
        $grausangues = $this->Animai->Grausangue->find('list', array(
            'fields' => array('id', 'descricao'), 
            'conditions' => array('holding_id' => $dadosUser['Auth']['User']['holding_id']),
            'order' => array('descricao' => 'asc')
        ));
        $this->set(compact('grausangues'));
        
        $causabaixas = $this->Animai->Causabaixa->find('list', array(
            'fields' => array('id', 'descricao'), 
            'conditions' => array('holding_id' => $dadosUser['Auth']['User']['holding_id']),
            'order' => array('descricao' => 'asc')
        ));
        $this->set(compact('causabaixas'));
        
        $tiposervicos = $this->Animai->Tiposervico->find('list',array('fields'=>array('id','descricao'),'order'=>array('Tiposervico.descricao' => 'asc')));
        $this->set(compact('tiposervicos'));
        
        $this->set('coranimal', $animal['Animai']['cor']);
        
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->Animai->id = $id;
            if ($this->Animai->save($this->request->data)) {
                $this->Session->setFlash('Animal alterado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Registro não foi alterado. Por favor tente novamente.', 'default', array('class' => 'mensagem_erro'));
            }
        } else {
            $this->request->data = $animal;
        }
        
    }

    /**
     * delete method
     */
    public function delete($id = null) {
        
        $this->Animai->id = $id;
        if (!$this->Animai->exists()) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }
        
        $this->request->onlyAllow('post', 'delete');
        if ($this->Animai->delete()) {
            $this->Session->setFlash('Animal deletado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
            $this->redirect(array('action' => 'index'));
        }
        if(!$this->Session->check('Message.flash')) {
            $this->Session->setFlash('Registro não foi deletado.', 'default', array('class' => 'mensagem_erro'));
        } 
        $this->redirect(array('action' => 'index'));
        
    }
    
    /**
     * Funções ajax
     */
    
    public function buscaAnimais($chave) {
        $this->layout = 'ajax';
        if (array_key_exists("categoria_id", $this->request->data[$chave])) {
            $catID = $this->request->data[$chave]['categoria_id'];
        }
        
        $this->loadModel('Animallote');
        $db = $this->Animallote->getDataSource();
        $subQuery = $db->buildStatement(
            array(
                'fields'     => array('Animallote.animai_id'),
                'table'      => $db->fullTableName($this->Animallote),
                'alias'      => 'Animallote',
                'limit'      => null,
                'offset'     => null,
                'joins'      => array(),
                'conditions' => null,
                'order'      => null,
                'group'      => null
            ),
            $this->Animallote
        );
        $subQuery = ' Animai.id NOT IN (' . $subQuery . ') ';
        $subQuery = 'categoria_id = ' . $catID . ' AND ' . $subQuery;
        $subQueryExpression = $db->expression($subQuery);

        $conditions[] = $subQueryExpression;
                
        $this->Animai->recursive = -1;
        $animais = $this->Animai->find('list', array(
            'fields' => array('id', 'descricao'),
            'conditions' => $conditions,
            ));
        
        $this->set('animais', $animais);

    }
    
    public function buscaAnimaisPorCliente($chave) {
        $this->layout = 'ajax';
        if (array_key_exists("cliente_id", $this->request->data[$chave])) {
            $catID = $this->request->data[$chave]['cliente_id'];
        }
        
        $animais = $this->Animai->find('list', array(
            'fields' => array('id', 'nome'), 
            'conditions' => array('cliente_id' => $catID, 'ativo' => 'A'),
            'order' => array('nome' => 'asc')
        ));
        $this->set('animais', $animais);

    }
    
    
    public function validaPlano ($holding_id, $plano_id) {
        
        $this->Animai->recursive = 1;
        $totalAnimais = $this->Animai->find('count', array(
            'conditions' => array('Cliente.holding_id' => $holding_id)
        ));
        
        $this->Animai->Cliente->Holding->Plano->recursive = 0;
        $plano = $this->Animai->Cliente->Holding->Plano->find('first', array('conditions' => array('id' => $plano_id)));
        
        if(empty($plano['Plano']['animal'])) {
            return true;
        } else {
            if ($totalAnimais >= $plano['Plano']['animal']) {
                return false;
            } else {
                return true;
            }
        }    
        
    }
    
    
}

?>
