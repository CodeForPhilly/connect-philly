<?php

class AddCenterController extends Zend_Controller_Action {

    protected $_namespace = 'AddCenterController';
    protected $_session;
    protected $_form;

    public function indexAction() {
        // Either re-display the current page, or grab the "next"
        // (first) sub form
        if (!$form = $this->getCurrentSubForm()) {
            Connect_FileLogger::debug("pulling out the first subform");
            $this->getSessionNamespace()->unsetAll();
            $form = $this->getNextSubForm();
        }

        $subform = $this->getForm()->prepareSubForm($form);
        //$subform->isValid(array());
        //$this->clearErrorDecorators($subform);
        $this->view->form = $subform;
    }

    public function processAction() {
        
        if (!$form = $this->getCurrentSubForm()) {
            Connect_FileLogger::debug("forwarding to the index");
            return $this->_forward('index');
        }

        if (!$this->subFormIsValid($form, $this->getRequest()->getPost())) {
            Connect_FileLogger::debug("form '" . $form->getName() . "' wasn't valid, we need to redisplay it");
            $this->view->form = $this->getForm()->prepareSubForm($form);
            return $this->render('index');
        }

        if (!$this->formIsValid()) {
            $form = $this->getNextSubForm();
            Connect_FileLogger::debug("entire form wasn't valid; prepping to display next subform '" . $form->getName() . "'");
            $this->view->form = $this->getForm()->prepareSubForm($form);
            return $this->render('index');
        }

        // Valid form!
        Connect_FileLogger::debug( "form is valid, proceeding to verificaton");
 		
        $computerCenter = 
            new Connect_ComputerCenter( $this->getData() );
        $computerCenter->setPendingConfirmation('true');
        
        $position = Connect_PhillyGeocoder::geocode( $computerCenter->getAddress1() );
        
        if( $position ) {
            $computerCenter->setLatitude( $position['lat'] );
            $computerCenter->setLongitude( $position['lng'] );
        }
        else {
            Connect_FileLogger::warn( 'unable to geolocate new center address ' 
                    . $computerCenter->getAddress1() );
        }
        
        $mapper  = new Application_Model_CenterDirectoryMapper();
        $mapper->save($computerCenter);
        
        $mailOptions = Connect_Mail_MessageBuilder::addCenter( $computerCenter );
        Connect_Mail::send( $mailOptions );
        
        // Render information in a verification page
        $this->view->info = $this->getSessionNamespace();
        $this->view->fusionTableURL = $this->fusionTableURL();
        $this->render('verification');
    }

    /**
     * Get the session namespace we're using
     *
     * @return Zend_Session_Namespace
     */
    public function getSessionNamespace() {
        if (null === $this->_session) {
            $this->_session =
                    new Zend_Session_Namespace($this->_namespace);
        }

        return $this->_session;
    }

    public function getForm() {
        if (null === $this->_form) {
            $this->_form = new Application_Form_AddCenterForm();
        }
        return $this->_form;
    }

    /**
     * Get a list of forms already stored in the session
     *
     * @return array
     */
    public function getStoredForms() {
        $stored = array();
        foreach ($this->getSessionNamespace() as $key => $value) {
            $stored[] = $key;
        }

        return $stored;
    }

    /**
     * Get list of all subforms available
     *
     * @return array
     */
    public function getPotentialForms() {
        return array_keys($this->getForm()->getSubForms());
    }

    /**
     * What sub form was submitted?
     *
     * @return false|Zend_Form_SubForm
     */
    public function getCurrentSubForm() {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return false;
        }

        foreach ($this->getPotentialForms() as $name) {
            if ($data = $request->getPost($name, false)) {
                if (is_array($data)) {
                    return $this->getForm()->getSubForm($name);
                    break;
                }
            }
        }

        return false;
    }

    /**
     * Get the next sub form to display
     *
     * @return Zend_Form_SubForm|false
     */
    public function getNextSubForm() {
        $storedForms = $this->getStoredForms();
        $potentialForms = $this->getPotentialForms();

        foreach ($potentialForms as $name) {
            if (!in_array($name, $storedForms)) {
                return $this->getForm()->getSubForm($name);
            }
        }

        return false;
    }

    /**
     * Is the sub form valid? and if it is, store it's data in the namespace
     *
     * @param  Zend_Form_SubForm $subForm
     * @param  array $data
     * @return bool
     */
    public function subFormIsValid(Zend_Form_SubForm $subForm, array $data) {
        $name = $subForm->getName();
        Connect_FileLogger::debug("validating subform form '$name'");
        
        if ($subForm->isValid($data)) {
            $data = $subForm->getValues();
            $this->getSessionNamespace()->$name = $subForm->getValues();
            //JS_FileLogger::debug("subform '$name' valid");
            return true;
        }

        //JS_FileLogger::debug("subform '$name' not valid");
        return false;
    }

    /**
     * Is the full form valid?
     *
     * @return bool
     */
    public function formIsValid()
    { 
       Connect_FileLogger::debug( "we're trying to validate the whole form" );
        
       $data = array();
        foreach ($this->getSessionNamespace() as $key => $info) {
            $data[$key] = $info[$key];
        }
        return (count($this->getStoredForms()) < count($this->getPotentialForms()))? false : $this->getForm()->isValid($data);
    }

    /*
     * pull all of the data from the namespace, returning one associative array
     */
    public function getData() {
        
        $data = array();
        foreach( $this->getSessionNamespace() as $info ) {
            
            foreach( $info as $subform => $subformData ) {
                
                foreach( $subformData as $key => $value ) {
                    $data[$key] = $value;
                }
            }
        }
        
        return $data;
    }

    /**
     * remove the error decorators on the form so they don't display
     * @param Zend_Form_SubForm $subform 
     */
    public function clearErrorDecorators(Zend_Form $form) {
        
        foreach( $form->getElements() as $element ) {
            $element->removeDecorator( 'Errors' );
        }
        
        foreach( $form->getSubForms() as $subform ) {
            $this->clearErrorDecorators( $subform );
        }
    }
    
    protected function fusionTableURL() {
        $config = Zend_Registry::get('configuration');
        return $config->gmap->FusionTableURL;
    }
}

