<?php
App::uses('AppController', 'Controller');

App::import('Controller', 'Users');

/**
 * Fechamentos Controller
 */
class FechamentosController extends AppController {
    
    function beforeFilter() {
        $this->set('title_for_layout', 'Fechamento');
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
        $clientes = $this->Fechamento->Cliente->find('list', array(
            'fields' => array('id', 'nome'), 
            'conditions' => array('holding_id' => $dadosUser['Auth']['User']['holding_id']),
            'order' => array('nome' => 'asc')
        ));
        $this->set(compact('clientes'));
        
        if ($this->request->is('post') || $this->request->is('put')) {
            
            $this->Fechamento->set($this->request->data);
            
//            $teste = $this->Fechamento->invalidFields();
//            debug($teste);
            
           if ($this->Fechamento->validates()) {
                echo "sda";
            } else {
//                $errors = $this->Fechamento->validationErrors();
//                debug($errors);
            }
            
        }
        
        
        
    }
    
    /**
     * view method
     */
    public function view($id = null) {
        
//        $this->Categoria->id = $id;
//        if (!$this->Categoria->exists()) {
//            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
//            $this->redirect(array('action' => 'index'));
//        }
//        
//        $dadosUser = $this->Session->read();
//        $holding_id = $dadosUser['Auth']['User']['Holding']['id'];
//        $categoria = $this->Categoria->read(null, $id);
//        if ($categoria['Especy']['holding_id'] != $holding_id) {
//            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
//            $this->redirect(array('action' => 'index'));
//        }
//        
//        $this->set('categoria', $categoria);
        
    }

    /**
     * add method
     */
    public function add() {
        
//        $dadosUser = $this->Session->read();
//        $especies = $this->Categoria->Especy->find('list', array(
//            'fields' => array('id', 'descricao'), 
//            'conditions' => array('holding_id' => $dadosUser['Auth']['User']['holding_id']),
//            'order' => array('descricao' => 'asc')
//        ));
//        $this->set(compact('especies'));
//        
//        $opcoes = array('M' => 'Macho', 'F' => 'Fêmea');
//        $this->set('opcoes', $opcoes);
//        
//        if ($this->request->is('post')) {
//            $this->Categoria->create();
//            if ($this->Categoria->save($this->request->data)) {
//                $this->Session->setFlash('Categoria adicionada com sucesso!', 'default', array('class' => 'mensagem_sucesso'));
//                $this->redirect(array('action' => 'index'));
//            } else {
//                $this->Session->setFlash('Registro não foi salvo. Por favor tente novamente.', 'default', array('class' => 'mensagem_erro'));
//            }
//        }
        
    }
    
    
    /**
     * edit method
     */
    public function edit($id = null) {
        
//        $this->Categoria->id = $id;
//        if (!$this->Categoria->exists()) {
//            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
//            $this->redirect(array('action' => 'index'));
//        }
//        
//        $dadosUser = $this->Session->read();
//        $holding_id = $dadosUser['Auth']['User']['Holding']['id'];
//        $categoria = $this->Categoria->read(null, $id);
//        if ($categoria['Especy']['holding_id'] != $holding_id) {
//            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
//            $this->redirect(array('action' => 'index'));
//        }
//        
//        $especies = $this->Categoria->Especy->find('list', array(
//            'fields' => array('id', 'descricao'), 
//            'conditions' => array('holding_id' => $holding_id),
//            'order' => array('descricao' => 'asc')
//        ));
//        $this->set(compact('especies'));
//        
//        $opcoes = array('M' => 'Macho', 'F' => 'Fêmea');
//        $this->set('opcoes', $opcoes);
//        
//        if ($this->request->is('post') || $this->request->is('put')) {
//            if ($this->Categoria->save($this->request->data)) {
//                $this->Session->setFlash('Categoria alterada com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
//                $this->redirect(array('action' => 'index'));
//            } else {
//                $this->Session->setFlash('Registro não foi alterado. Por favor tente novamente.', 'default', array('class' => 'mensagem_erro'));
//            }
//        } else {
//            $this->request->data = $categoria;
//        }
        
    }

    /**
     * delete method
     */
    public function delete($id = null) {
        
//        $this->Categoria->id = $id;
//        if (!$this->Categoria->exists()) {
//            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
//            $this->redirect(array('action' => 'index'));
//        }
//        $this->request->onlyAllow('post', 'delete');
//        if ($this->Categoria->delete()) {
//            $this->Session->setFlash('Categoria deletada com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
//            $this->redirect(array('action' => 'index'));
//        }
//        if(!$this->Session->check('Message.flash')) {
//            $this->Session->setFlash('Registro não foi deletado.', 'default', array('class' => 'mensagem_erro'));
//        } 
//        $this->redirect(array('action' => 'index'));
        
    }
    
}

?>
