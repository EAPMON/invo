<?php
declare(strict_types=1);

namespace Invo\Controllers;

use Invo\Forms\CompaniesForm;
use Invo\Models\Companies;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class CompaniesController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();

        $this->tag->setTitle('Manage your companies');
    }

    /**
     * Shows the index action
     */
    public function indexAction()
    {
        $this->session->conditions = null;
        $this->view->form = new CompaniesForm;
    }

    /**
     * Search companies based on current criteria
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput(
                $this->di,
                'Companies',
                $this->request->getPost()
            );

            $this->persistent->searchParams = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery('page', 'int');
        }

        $parameters = [];
        if ($this->persistent->searchParams) {
            $parameters = $this->persistent->searchParams;
        }

        $companies = Companies::find($parameters);
        if (count($companies) == 0) {
            $this->flash->notice('The search did not find any companies');

            return $this->dispatcher->forward([
                'controller' => 'companies',
                'action'     => 'index',
            ]);
        }

        $paginator = new Paginator([
            'data'  => $companies,
            'limit' => 10,
            'page'  => $numberPage,
        ]);

        $this->view->page = $paginator->paginate();
        $this->view->companies = $companies;
    }

    /**
     * Shows the form to create a new company
     */
    public function newAction()
    {
        $this->view->form = new CompaniesForm(null, ['edit' => true]);
    }

    /**
     * Edits a company based on its id
     *
     * @param int $id
     */
    public function editAction($id)
    {
        $company = Companies::findFirstById($id);
        if (!$company) {
            $this->flash->error('Company was not found');

            return $this->dispatcher->forward([
                'controller' => 'companies',
                'action'     => 'index',
            ]);
        }

        $this->view->form = new CompaniesForm($company, ['edit' => true]);
    }

    /**
     * Creates a new company
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward([
                'controller' => 'companies',
                'action'     => 'index',
            ]);
        }

        $form = new CompaniesForm;
        $company = new Companies();

        $data = $this->request->getPost();
        if (!$form->isValid($data, $company)) {
            foreach ($form->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward([
                'controller' => 'companies',
                'action'     => 'new',
            ]);
        }

        if (!$company->save()) {
            foreach ($company->getMessages() as $message) {
                $this->flash->error((string)$message);
            }

            return $this->dispatcher->forward([
                'controller' => 'companies',
                'action'     => 'new',
            ]);
        }

        $form->clear();

        $this->flash->success('Company was created successfully');

        return $this->dispatcher->forward([
            'controller' => 'companies',
            'action'     => 'index',
        ]);
    }

    /**
     * Saves current company in screen
     */
    public function saveAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward([
                'controller' => 'companies',
                'action'     => 'index',
            ]);
        }

        $id = $this->request->getPost('id', 'int');
        $company = Companies::findFirstById($id);
        if (!$company) {
            $this->flash->error('Company does not exist');

            return $this->dispatcher->forward([
                'controller' => 'companies',
                'action'     => 'index',
            ]);
        }

        $data = $this->request->getPost();
        $form = new CompaniesForm;
        if (!$form->isValid($data, $company)) {
            foreach ($form->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward([
                'controller' => 'companies',
                'action'     => 'new',
            ]);
        }

        if (!$company->save()) {
            foreach ($company->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward([
                'controller' => 'companies',
                'action'     => 'new',
            ]);
        }

        $form->clear();

        $this->flash->success('Company was updated successfully');

        return $this->dispatcher->forward([
            'controller' => 'companies',
            'action'     => 'index',
        ]);
    }

    /**
     * Deletes a company
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $companies = Companies::findFirstById($id);
        if (!$companies) {
            $this->flash->error('Company was not found');

            return $this->dispatcher->forward([
                'controller' => 'companies',
                'action'     => 'index',
            ]);
        }

        if (!$companies->delete()) {
            foreach ($companies->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward([
                'controller' => 'companies',
                'action'     => 'search',
            ]);
        }

        $this->flash->success('Company was deleted');

        return $this->dispatcher->forward([
            'controller' => 'companies',
            'action'     => 'index',
        ]);
    }
}