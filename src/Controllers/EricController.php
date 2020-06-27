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
        $id = $this->request->getPost('id', 'int');
        $eric = Eric::findFirstById($id);
        if (!$eric) {
            $this->flash->error('Product was not found');

            $this->dispatcher->forward([
                'controller' => 'products',
                'action'     => 'index',
            ]);

            return;
        }

        $eric->description = $this->request->getPost('description');
        $eric->price = $this->request->getPost('price');

        $eric->save();

        $this->flash->success('Eric actualizado');
        $this->dispatcher->forward([
            'controller' => 'eric',
            'action'     => 'index',
        ]);
    }

    public function editAction($id): void
    {
        $eric = Eric::findFirstById($id);
        if (!$eric) {
            return;
        }

        $this->view->form = new EricForm($eric, ['edit' => true]);
    }


    public function deleteAction($id): void
    {
        $eric = Eric::findFirstById($id);
        if ($eric->delete()) {
            $this->flash->success('Eric eliminado');
            $this->dispatcher->forward([
                'controller' => 'eric',
                'action'     => 'index',
            ]);
        }
        return;
    }

    public function newAction(): void
    {
        $this->view->form = new EricForm(null, ['edit' => true]);
    }

    public function createAction(): void
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => 'eric',
                'action'     => 'index',
            ]);

            return;
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

            return;
        }

        if (!$eric->save()) {
            foreach ($eric->getMessages() as $message) {
                $this->flash->error((string) $message);
            }

            $this->dispatcher->forward([
                'controller' => 'eric',
                'action'     => 'new',
            ]);

            return;
        }

        $form->clear();
        $this->flash->success('Product was created successfully');

        $this->dispatcher->forward([
            'controller' => 'eric',
            'action'     => 'index',
        ]);
    }
}
