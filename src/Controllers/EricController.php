<?php

declare(strict_types=1);

namespace Invo\Controllers;

use Invo\Models\Eric;
use Invo\Forms\EricForm;



class EricController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();

        $this->tag->setTitle('Eric');
    }

    public function indexAction(): void
    {
        $erics = Eric::find();
        $this->view->erics = $erics;
    }



    public function updateAction(): void
    {
        $eric = new Eric();
        $eric->id = $this->request->getPost('id', 'int');
        $eric->description = $this->request->getPost('description');
        $eric->price = $this->request->getPost('price');
        if ($eric->id) {
            if ($eric->save()) {
                $this->response('success', 'Eric actualizado', 'eric', 'index');
            } else {
                $this->response('error', 'Error al actulizar esta entidad', 'eric', 'index');
            }
        } else {
            $this->response('error', 'Error al actulizar esta entidad', 'eric', 'index');
        }
    }


    public function editAction($id): void
    {
        $eric = Eric::findFirstById($id);
        if (!$eric) {
            $this->response('error', 'Error este registro no esta registrado en la base de datos', 'eric', 'index');
        } else {
            $this->view->form = new EricForm($eric, ['edit' => true]);
        }
    }


    public function deleteAction($id): void
    {
        $eric = Eric::findFirstById($id);
        if ($eric->delete()) {
            $this->response('success', 'Registro eliminado', 'eric', 'index');
        }else {
            $this->response('error', 'error al eliminar el registro ', 'eric', 'index');
        }
    }


    public function newAction(): void
    {
        $this->view->form = new EricForm(null, ['edit' => true]);
    }


    public function createAction(): void
    {
        if (!$this->request->isPost()) {
            $this->response('error', 'Error al guardar el registro', 'eric', 'index');
        }

        $form = new EricForm();
        $eric = new Eric();

        if (!$form->isValid($this->request->getPost(), $eric)) {
            foreach ($form->getMessages() as $message) {
                $this->flash->error($message);
            }
            $this->dispatcher->forward([
                'controller' => 'eric',
                'action'     => 'new',
            ]);
        }
        if (!$eric->save()) {
            foreach ($eric->getMessages() as $message) {
                $this->flash->error((string) $message);
            }
            $this->dispatcher->forward([
                'controller' => 'eric',
                'action'     => 'new',
            ]);
        }

        $form->clear();
        $this->response('success', 'El registro se ha creado correctamente ', 'eric', 'index');
        
    }

    private function response($status, $message, $controller, $action): void
    {
        $this->flash->$status($message);
        $this->dispatcher->forward([
            'controller' => $controller,
            'action'     => $action,
        ]);
    }
}
