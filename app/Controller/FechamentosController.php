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

        // busca clientes cadastrados
        $this->Fechamento->Cliente->recursive = -1;
        $clientes = $this->Fechamento->Cliente->find('list', array('order' => 'nome ASC', 'fields' => array('id', 'nome'), 'conditions' => array('holding_id' => $dadosUser['Auth']['User']['holding_id'], 'ativo' => 'A')));

        $this->Filter->addFilters(
                array(
                    'filter1' => array(
                        'Animai.cliente_id' => array(
                            'select' => $clientes
                        ),
                    ),
                    'filter2' => array(
                        'Animai.nome' => array(
                            'operator' => 'LIKE',
                            'value' => array(
                                'before' => '%',
                                'after' => '%'
                            )
                        )
                    ),
                )
        );

        $this->Filter->setPaginate('order', array('Cliente.nome' => 'asc'));

        $this->Filter->setPaginate('conditions', array($this->Filter->getConditions(), 'Fechamento.empresa_id' => $dadosUser['empresa_id']));

        $this->set('fechamentos', $this->paginate());
    }

    /**
     * view method
     */
    public function view($id = null) {

        $this->Fechamento->id = $id;
        if (!$this->Fechamento->exists()) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }
        
        $dadosUser = $this->Session->read();
        $empresa_id = $dadosUser['empresa_id'];
        $this->Fechamento->recursive = 2;
        $fechamento = $this->Fechamento->read(null, $id);
        if ($fechamento['Fechamento']['empresa_id'] != $empresa_id) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }
        
        $this->set('fechamento', $fechamento);
        
    }

    /**
     * add method
     */
    public function add() {

        $dadosUser = $this->Session->read();
        $this->set('empresa_id', $dadosUser['empresa_id']);
        $clientes = $this->Fechamento->Cliente->find('list', array(
            'fields' => array('id', 'nome'),
            'conditions' => array('holding_id' => $dadosUser['Auth']['User']['holding_id']),
            'order' => array('nome' => 'asc')
        ));
        $this->set(compact('clientes'));

        if ($this->request->is('post') || $this->request->is('put')) {

            $this->Fechamento->set($this->request->data);

            if ($this->Fechamento->validates()) {
                
                $this->Fechamento->Animai->recursivo = 0;
                $animal = $this->Fechamento->Animai->findById($this->request->data['Fechamento']['animai_id']);
                $this->set('animal', $animal);
                
                $tipoServicos = array();
                foreach($animal['Tiposervico'] as $key => $subcat){
                    $tipoServicos[] = $subcat['id'];
                }
                
                $this->loadModel('Animallote');
                $this->Animallote->recursivo = -1;
                $categoriaLote = $this->Animallote->find('all', array(
                    'fields' => array('categorialote_id', 'animai_id'),
                    'conditions' => array('animai_id' => $this->request->data['Fechamento']['animai_id'])
                ));
                
                $this->Fechamento->Eventosanitario->Animalevento->recursive = -1;
                $eventosDoAnimal = $this->Fechamento->Eventosanitario->Animalevento->find('all', array(
                    'conditions' => array(
                        'animai_id' => $this->request->data['Fechamento']['animai_id'],
                    ),
                 ));
                $arrayEventos = array();
                foreach($eventosDoAnimal as $key => $subcat){
                    $arrayEventos[$key] = $subcat['Animalevento']['eventosanitario_id'];
                }
                
                $this->Fechamento->Eventosanitario->recursive = 1;
                if (count($arrayEventos) > 0) {
                    $eventosExibicao = $this->Fechamento->Eventosanitario->find('all', array(
                        'conditions' => array(
                            'categorialote_id' => $categoriaLote[0]['Animallote']['categorialote_id'],
                            'dtevento >=' => $this->Fechamento->formataData($this->request->data['Fechamento']['dtinicial'], 'EN'),
                            'dtevento <=' => $this->Fechamento->formataData($this->request->data['Fechamento']['dtfinal'], 'EN'),
                            'Eventosanitario.id IN' => $arrayEventos,
                        ),
                        'order' => array('dtevento' => 'desc'),
                    ));
                } else {
                    $eventosExibicao = $this->Fechamento->Eventosanitario->find('all', array(
                        'conditions' => array(
                            'categorialote_id' => $categoriaLote[0]['Animallote']['categorialote_id'],
                            'dtevento >=' => $this->Fechamento->formataData($this->request->data['Fechamento']['dtinicial'], 'EN'),
                            'dtevento <=' => $this->Fechamento->formataData($this->request->data['Fechamento']['dtfinal'], 'EN'),
                        ),
                        'order' => array('dtevento' => 'desc'),
                    ));
                } 
                $this->set('eventosExibicao', $eventosExibicao);
                
                $eventosSanitarios = array();
                if (count($eventosExibicao) == 1) {
                    $eventosSanitarios['Eventosanitario'] = array (0 => $eventosExibicao[0]['Eventosanitario']['id']);
                } elseif (count($eventosExibicao) > 1) {
                    foreach($eventosExibicao as $key => $subcat){
                        $eventosSanitarios['Eventosanitario'][$key] = $subcat['Eventosanitario']['id'];
                    }
                }
                
                $this->Fechamento->Eventoalimentacao->Animalalimentacao->recursive = -1;
                $alimentacoesDoAnimal = $this->Fechamento->Eventoalimentacao->Animalalimentacao->find('all', array(
                    'conditions' => array(
                        'animai_id' => $this->request->data['Fechamento']['animai_id'],
                    ),
                 ));
                $arrayAlimentacoes = array();
                foreach($alimentacoesDoAnimal as $key => $subcat){
                    $arrayAlimentacoes[$key] = $subcat['Animalalimentacao']['alimentacao_id'];
                }
                $this->Fechamento->Eventoalimentacao->recursive = 0;
                if (count($arrayAlimentacoes) > 0) {
                    $alimentacaoExibicao = $this->Fechamento->Eventoalimentacao->find('all', array(
                        'conditions' => array(
                            'categorialote_id' => $categoriaLote[0]['Animallote']['categorialote_id'],
                            'dtalimentacao >=' => $this->Fechamento->formataData($this->request->data['Fechamento']['dtinicial'], 'EN'),
                            'dtalimentacao <=' => $this->Fechamento->formataData($this->request->data['Fechamento']['dtfinal'], 'EN'),
                            'Eventoalimentacao.id IN' => $arrayAlimentacoes,
                        ),
                        'order' => array('dtalimentacao' => 'desc')
                    ));
                } else {
                    $alimentacaoExibicao = $this->Fechamento->Eventoalimentacao->find('all', array(
                        'conditions' => array(
                            'categorialote_id' => $categoriaLote[0]['Animallote']['categorialote_id'],
                            'dtalimentacao >=' => $this->Fechamento->formataData($this->request->data['Fechamento']['dtinicial'], 'EN'),
                            'dtalimentacao <=' => $this->Fechamento->formataData($this->request->data['Fechamento']['dtfinal'], 'EN'),
                        ),
                        'order' => array('dtalimentacao' => 'desc')
                    ));
                }
                $this->set('alimentacaoExibicao', $alimentacaoExibicao);
              
                $eventosAlimentacoes = array();
                if (count($alimentacaoExibicao) == 1) {
                    $eventosAlimentacoes['Eventoalimentacao'] = array (0 => $alimentacaoExibicao[0]['Eventoalimentacao']['id']);
                } elseif (count($alimentacaoExibicao) > 1) {
                    foreach($alimentacaoExibicao as $key => $subcat){
                        $eventosAlimentacoes['Eventoalimentacao'][$key] = $subcat['Eventoalimentacao']['id'];
                    }
                }
                                
                $dadosFormulario = $this->request->data;
                $this->set('dadosFormulario', $dadosFormulario);
                $this->render('addFechamento');
                
                $this->request->data['Eventosanitario'] = $eventosSanitarios;
                $this->request->data['Eventoalimentacao'] = $eventosAlimentacoes;
                $this->request->data['Tiposervico']['Tiposervico'] = $tipoServicos;
                
                if ($this->request->data['Fechamento']['confirma'] == 'S') {
                    
                    if ($this->Fechamento->save($this->request->data)) {
                        $this->Session->setFlash('Fechamento adicionado com sucesso!', 'default', array('class' => 'mensagem_sucesso'));
                        $this->redirect(array('action' => 'index'));
                    } else {
                        $this->Session->setFlash('Registro não foi salvo. Por favor tente novamente.', 'default', array('class' => 'mensagem_erro'));
                    }
                }
                
            }
        }
    }

    /**
     * delete method
     */
    public function delete($id = null) {

        $this->Fechamento->id = $id;
        if (!$this->Fechamento->exists()) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->Fechamento->delete()) {
            $this->Session->setFlash('Fechamento deletado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
            $this->redirect(array('action' => 'index'));
        }
        if(!$this->Session->check('Message.flash')) {
            $this->Session->setFlash('Registro não foi deletado.', 'default', array('class' => 'mensagem_erro'));
        } 
        $this->redirect(array('action' => 'index'));
    }

}

?>
