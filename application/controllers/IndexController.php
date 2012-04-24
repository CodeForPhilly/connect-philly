<?php

class IndexController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $form = new Application_Form_MapSearchForm();
        //$form->setAction(null);
        $form->setMethod('GET');
        $this->view->searchForm = $form;
    }
}

