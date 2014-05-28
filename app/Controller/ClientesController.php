<?php

App::import('Controller', 'Users');

/**
 * Description of ClientesController
 *
 * @author quinho
 */
class ClientesController extends AppController {
    
    function beforeFilter() {
        $this->set('title_for_layout', 'Clientes');
    }
    
    public function isAuthorized($user) {
        $Users = new UsersController;
        return $Users->validaAcesso($this->Session->read(), $this->request->controller);
        return parent::isAuthorized($user);
    }
    
    /**
     * index method
     */
    public function index() {
        $dadosUser = $this->Session->read();
        $this->Cliente->recursive = 0;
        $this->Paginator->settings = array(
            'conditions' => array('holding_id' => $dadosUser['Auth']['User']['holding_id']),
            'order' => array('nome' => 'asc')
        );
        $this->set('clientes', $this->Paginator->paginate('Cliente'));
    }
    
    /**
     * view method
     */
    public function view($id = null) {
        
        $this->Cliente->id = $id;
        if (!$this->Cliente->exists($id)) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }
        
        $this->Cliente->recursive = 2;
        $dadosUser = $this->Session->read();
        $holding_id = $dadosUser['Auth']['User']['holding_id'];
        
        $cliente = $this->Cliente->read(null, $id);
        if ($cliente['Cliente']['holding_id'] != $holding_id) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }
        
        $this->set('cliente', $cliente);
        
    }
    
    /**
     * add method
     */
    public function add() {
        
        $dadosUser = $this->Session->read();
        
        $empresa_id = $dadosUser['empresa_id'];
        $this->set(compact('empresa_id'));
        
        $holding_id = $dadosUser['Auth']['User']['holding_id'];
        $this->set(compact('holding_id'));
        
        $sexos = array('M' => 'MASCULINO', 'F' => 'FEMININO');
        $this->set('sexos', $sexos);
        
        $status = array('A' => 'ATIVO', 'I' => 'INATIVO');
        $this->set('status', $status);
        
        $this->Cliente->Endereco->Cidade->Estado->recursive = 1;
        $estados = $this->Cliente->Endereco->Cidade->Estado->find('list', array(
            'fields' => array('id', 'nome'),
            'conditions' => array('holding_id' => $dadosUser['Auth']['User']['holding_id'], 'pais_id' => 1),
            'order' => array('nome' => 'asc')
        ));
        $this->set('estados', $estados);
        
        if ($this->request->is('post')) {
            $this->Cliente->create();
            if ($this->Cliente->saveAssociated($this->request->data)) {
                $this->Session->setFlash('Cliente adicionado com sucesso!', 'default', array('class' => 'mensagem_sucesso'));
                $this->redirect(array('action' => 'index'));
            } else {
                $errors = $this->Cliente->validationErrors;
                debug($errors);
                die();
                $this->Session->setFlash('Registro não foi salvo. Por favor tente novamente.', 'default', array('class' => 'mensagem_erro'));
            }
        }
        
    }
    
    /**
     * edit method
     */
    public function edit($id = null) {
        
        $this->Cliente->id = $id;
        if (!$this->Cliente->exists($id)) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }
        
        $dadosUser = $this->Session->read();
        $holding_id = $dadosUser['Auth']['User']['holding_id'];
        $this->set(compact('holding_id'));
        
        $cliente = $this->Cliente->read(null, $id);
        if ($cliente['Cliente']['holding_id'] != $holding_id) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('cliente', $cliente);
        
        $sexos = array('M' => 'MASCULINO', 'F' => 'FEMININO');
        $this->set('sexos', $sexos);
        
        $status = array('A' => 'ATIVO', 'I' => 'INATIVO');
        $this->set('status', $status);
        
        if (!empty($cliente['Endereco'])) {
            $this->Cliente->Endereco->Cidade->recursive = -1;
            $cidade = $this->Cliente->Endereco->Cidade->findById($cliente['Endereco'][0]['cidade_id']);
            $cidades = $this->Cliente->Endereco->Cidade->find('list', array(
                'fields' => array('id', 'nome'),
                'conditions' => array('estado_id' => $cidade['Cidade']['estado_id']),
                'order' => array('nome' => 'asc')
            ));
            $this->set('cidades', $cidades);
        }
        
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->Cliente->id = $id;
            if ($this->Cliente->save($this->request->data['Cliente'])) {
                if (!empty($this->request->data['Endereco'][0])) {
                    $this->Cliente->Endereco->id = $this->request->data['Endereco'][0]['id'];
                    if ($this->Cliente->Endereco->save($this->request->data['Endereco'][0])) {
                        $this->Session->setFlash('Cliente alterado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
                        $this->redirect(array('action' => 'index'));
                    }
                } else {
                    $this->Session->setFlash('Cliente alterado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
                    $this->redirect(array('action' => 'index'));
                }
                
            } else {
                $this->Session->setFlash('Registro não foi alterado. Por favor tente novamente.', 'default', array('class' => 'mensagem_erro'));
            }
        } else {
            $this->request->data = $cliente;
        }
        
    }
    
    /**
     * delete method
     */
    public function delete($id = null) {
        
        $this->Cliente->id = $id;
        if (!$this->Cliente->exists()) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->Cliente->delete()) {
            $this->Session->setFlash('Cliente deletado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash('Registro não foi deletado.', 'default', array('class' => 'mensagem_erro'));
        $this->redirect(array('action' => 'index'));
        
    }
    
}

?>
