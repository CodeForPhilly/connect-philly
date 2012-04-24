<?php

class Application_Form_AddCenterForm extends Zend_Form 
{
    // what is the action base controller name for this form
    protected $_controllerName = 'add-center';
    protected $_descriptionDivName = 'connect-description';
	
    public function init()
    {   
        $this->setElementFilters( array( 'StringTrim' ) );
        
        $this->addSubForms( (array(
            'general'       => $this->getGeneralForm(),
            'miscillaneous' => $this->getMiscForm(),
            'hours'         => $this->getHoursForm()
        )));
        
        // add custom decorator path for all elements:
        $this->addElementPrefixPath('Application_Form_Decorator_',
                            APPLICATION_PATH . '/forms/decorators',
                            'decorator');
    } // end init()
    
    protected function getGeneralForm() {
        //$subForm = new Zend_Form_SubForm('general');
        $subForm = new Application_Form_ConnectSubform('general');
        $subForm->setPageDescription('Page 1 of 3');
        /*
         *      location title
         */
        $name = new Zend_Form_Element_Text( 'locationTitle' );
        $name->setDescription( 'The Title of the location. Ex.: Free Library - Fishtown Branch' )
                ->setRequired( true )
                ->setAttrib( 'size', 40 )
                ->setLabel( 'Name of center' )
                ;
        
        $subForm->addElement( $name );
        
        $subForm->addElement( 'text', 'type', array( 
                'label'         => 'Center Type',
                'description'   => 'ex. retail store, library, senior center etc.',
                'size'          => '20',
                'maxlength'     => '20'
        ));
        
        /*
         *      address 1
         */
        $address1 = new Zend_Form_Element_Text( 'address1' );
        $address1->setDescription( 'The street address of the location' )
                ->setLabel( 'Address 1' )
                ->setRequired( true )
                ->setAttrib( 'size', 40 )
                ;
        $subForm->addElement( $address1 );
        
        /*
         *      address 2
         */
        $address2 = new Zend_Form_Element_Text( 'address2' );
        $address2->setLabel( 'Address 2' );
        $address2->setAttrib( 'size', 40 );
        $address2->setDescription('Is there a suite number or other notifier?');
        $subForm->addElement( $address2 );
       
        $city = new Zend_Form_Element_Text( 'city' );
        $city->setLabel( 'City' )
            ->setAttrib( 'size', '40' )
            ->setAttrib( 'value', 'Philadelphia' )
            ->setValue( 'Philadelphia' );
        $subForm->addElement( $city );			
		
        $subForm->addElement( 'text', 'city', array( 
                    'label'         => 'City',
                    'size'          => '40'
        ));
        
        $states = $this->getStatesArray();
        $state = new Zend_Form_Element_Select('state');
	$state->setLabel('State')
            ->setMultiOptions($states)
            //->setAttrib( 'size', '5' )
            ->setValue( 'PA' )
            ->setRegisterInArrayValidator(false);
	$subForm->addElement($state);
        
        $subForm->addElement( 'text', 'zip', array( 
                'label'         => 'Zip Code',
                'size'          => '5'
        ));
        
        /*
        *		center website
        */
        $centerWebsite = new Zend_Form_Element_Text( 'centerWebsite' );
        $centerWebsite->setLabel( 'Center Website' );
        $centerWebsite->setDescription( 'What is the URL for the center? [http://www.technicallyphilly.com]' );
        $subForm->addElement( $centerWebsite );

        /*
        *		center email contact
        */
        $emailContact = new Zend_Form_Element_Text( 'centerEmailContact' );
        $emailContact->setLabel( 'Contact Email' ); 
        $emailContact->setDescription( 'What is the URL for the center? [http://www.technicallyphilly.com]' );
        $subForm->addElement( $emailContact );

        /*
        *		phone number
        */        
        $phoneNumber = new Zend_Form_Element_Text( 'centerPhoneNumber' );
        $phoneNumber->setLabel( 'Contact Phone Number' );
        $phoneNumber->setDescription( 'The phone number for the location in seven digit format, no hyphens or parenthesis. Ex. 2152534259' );
        $subForm->addElement( $phoneNumber );
        $subForm->setElementDecorators( array( 'Composite' ) );
        
        return $subForm;
    }
    
    protected function getMiscForm() {
		
        $subForm = new Application_Form_ConnectSubform();
        $subForm->setPageDescription( 'Pages 2 of 3');
        
        /*
        * 		number of workstations
        */
        $numWorkstations = new Zend_Form_Element_Text( 'numberOfWorkstations' );
        $numWorkstations->setLabel( 'Number of Workstations' )
                                        ->addValidator( 'int' )
                                        ->setAttrib( 'size', '4' )
                                        ->setAttrib( 'maxlength', '3' );
        $numWorkstations->setDescription( 'How many workstations does this center have?' );
        $subForm->addElement( $numWorkstations );

        /*
        * 		time limit in minutes
        */
        $timeLimit = new Zend_Form_Element_Text( 'timeLimitInMinutes' );
        $timeLimit->setLabel( 'Time Limit(in minutes)' )
                                ->addValidator( 'int' )
                                ->setAttrib( 'size', '4' )
                                ->setAttrib( 'maxlength', '4' );
        $timeLimit->setDescription( 'Does the center place limits on usage?' );
        $subForm->addElement( $timeLimit );
        
        /*
        *		has internet access
        */
        $hasInternetSelect = new Zend_Form_Element_Select( 'hasInternetAccess' );
        $hasInternetSelect->setLabel( 'Internet Access' );
        $hasInternetSelect->addMultiOption('unknown', '');
        $hasInternetSelect->addMultiOption('yes','yes');
        $hasInternetSelect->addMultiOption('no','no');
        $hasInternetSelect->setDescription( 'Does this location provide access to the Internet?' );
        $subForm->addElement( $hasInternetSelect );
        
        
        $hasWifiSelect = new Zend_Form_Element_Select( 'hasWifiAccess' );
        $hasWifiSelect->setLabel( 'Wifi Access' );
        $hasWifiSelect->addMultiOption('unknown', '');
        $hasWifiSelect->addMultiOption('yes','yes');
        $hasWifiSelect->addMultiOption('no','no');
        $hasWifiSelect->setDescription( 'Does this center have WiFi?' );
        $subForm->addElement( $hasWifiSelect );
		
        /*
        *		wifi access description
        */
        $wifiDescription = new Zend_Form_Element_Text( 'wifiDescription' );
        $wifiDescription->setLabel( 'Wifi Access Description' );
        $wifiDescription->setDescription( 'What kind of WiFi network is it? Ex.: Open, Private, With Purchase' );
        $subForm->addElement( $wifiDescription );
		
        /*
		*		number of staff
		*/
        $staffNum = new Zend_Form_Element_Text( 'numberOfStaff' );
        $staffNum->setLabel( 'Number of Staff' )
                        ->addValidator( 'int' )
                        ->setAttrib( 'size', '3' )
                        ->setAttrib( 'maxlength', '3' );
        $staffNum->setDescription( 'Does this center have staff dedicated to Internet services? How many?' );
        $subForm->addElement( $staffNum );
		
        /*
        *		has disabled access
        */
        $hasDisabledSelect = new Zend_Form_Element_Select( 'hasDisabledAccess' );
        $hasDisabledSelect->setLabel( 'Disabled Access' )
                        ->addMultiOption('unknown', '')
                        ->addMultiOption('yes','yes')
                        ->addMultiOption('no','no');
        $hasDisabledSelect->setDescription( 'Does this center offer computer services to disabled individuals?' );
        $subForm->addElement( $hasDisabledSelect );
        
		/*
		*		language options
		*/
        $languages = 
            new Zend_Form_Element_Text('centerLanguages');
        $languages->setLabel("Center Languages")
                    ->setDescription( 'What languages does this center serve other than English?' )
                    ->setAttrib( 'size', '40')
                    ->setAttrib( 'maxlength', '40' );
        $subForm->addElement( $languages );
			
        /*
        *		service age description
        */
        $serviceAge = new Zend_Form_Element_Text( 'serviceAgeDescription' );
        $serviceAge->setLabel( 'Service Age' );
        $serviceAge->setDescription( 'Who does this center serve? Ex. All, Seniors, Youth.' );
        $subForm->addElement( $serviceAge );

        /*
        *		ancilarry programming description
        */
        $ancillaryProgramming = new Zend_Form_Element_Text( 'ancillaryProgrammingDescription' );
        $ancillaryProgramming->setLabel( 'Ancillary Programming' )
                ->setAttrib( 'rows', '2' )
                ->setAttrib( 'cols', '30' )
                ->setDescription( 'Does this center have training or other services?' );
        $subForm->addElement( $ancillaryProgramming );
		
        $subForm->setElementDecorators( array( 'Composite' ) );

        return $subForm;
    }
    
    
    /**
     * generates the hours subform
     * returns mixed an hours subform
     */
    protected function getHoursForm() {
        
        $subForm = new Application_Form_ConnectSubform();
        
        $subForm->setPageDescription( 'Pages 3 of 3');
        
        $mondayHoursOpen = new Application_Form_Elements_TimeElement('mondayHoursOpen');
        $mondayHoursOpen->setLabel( 'Monday Open Time');
        $subForm->addElement( $mondayHoursOpen );

        $mondayHoursClose = new Application_Form_Elements_TimeElement('mondayHoursClose');
        $mondayHoursClose->setLabel( 'Monday Close Time' );
        $subForm->addElement( $mondayHoursClose );

        $tuesdayHoursOpen = new Application_Form_Elements_TimeElement('tuesdayHoursOpen');
        $tuesdayHoursOpen->setLabel( 'Tuesday Open Time' );
        $subForm->addElement( $tuesdayHoursOpen );

        $tuesdayHoursClose = new Application_Form_Elements_TimeElement('tuesdayHoursClose');
        $tuesdayHoursClose->setLabel( 'Tuesday Close Time' );
        $subForm->addElement( $tuesdayHoursClose );
        
        $wednesdayHoursOpen = new Application_Form_Elements_TimeElement('wednesdayHoursOpen');
        $wednesdayHoursOpen->setLabel( 'Wednesday Open Time' );
        $subForm->addElement( $wednesdayHoursOpen );

        $wednesdayHoursClose = new Application_Form_Elements_TimeElement('wednesdayHoursClose');
        $wednesdayHoursClose->setLabel( 'Wednesday Close Time' );
        $subForm->addElement( $wednesdayHoursClose );
        
        $thursdayHoursOpen = new Application_Form_Elements_TimeElement('thursdayHoursOpen');
        $thursdayHoursOpen->setLabel( 'Thursday Open Time' );
        $subForm->addElement( $thursdayHoursOpen );

        $thursdayHoursClose = new Application_Form_Elements_TimeElement('thursdayHoursClose');
        $thursdayHoursClose->setLabel( 'Thursday Close Time' );
        $subForm->addElement( $thursdayHoursClose );
        
        $fridayHoursOpen = new Application_Form_Elements_TimeElement('fridayHoursOpen');
        $fridayHoursOpen->setLabel( 'Friday Open Time' );
        $subForm->addElement( $fridayHoursOpen );

        $fridayHoursClose = new Application_Form_Elements_TimeElement('fridayHoursClose');
        $fridayHoursClose->setLabel( 'Friday Close Time' );
        $subForm->addElement( $fridayHoursClose );
        
        $saturdayHoursOpen = new Application_Form_Elements_TimeElement('saturdayHoursOpen');
        $saturdayHoursOpen->setLabel( 'Saturday Open Time' );
        $subForm->addElement( $saturdayHoursOpen );

        $saturdayHoursClose = new Application_Form_Elements_TimeElement('saturdayHoursClose');
        $saturdayHoursClose->setLabel( 'Saturday Close Time' );
        $subForm->addElement( $saturdayHoursClose );
        
        $sundayHoursOpen = new Application_Form_Elements_TimeElement('sundayHoursOpen');
        $sundayHoursOpen->setLabel( 'Sunday Open Time' );
        $subForm->addElement( $sundayHoursOpen );

        $sundayHoursClose = new Application_Form_Elements_TimeElement('sundayHoursClose');
        $sundayHoursClose->setLabel( 'Sunday Close Time' );
        $subForm->addElement( $sundayHoursClose );
        
        $subForm->setElementDecorators( array( 
                'ViewHelper',
                'Description',
                'Errors',
                'TimeDecorator',
                'TimeLabelDecorator') );
        
        $dataDescription = 'If you know the hours that the center is open, enter'
                . ' it now.  Otherwise, click the \'Next\' button.  For any days where the open hours are not known, leave'
				. ' the default times.';
				
        $subForm->setDataDescription( $dataDescription );
        return $subForm;
    }
    
    
    /**
     * Prepare a sub form for display
     *
     * @param  string|Zend_Form_SubForm $spec
     * @return Zend_Form_SubForm
     */
    public function prepareSubForm($spec)
    {
        if (is_string($spec)) {
            $subForm = $this->{$spec};
        } elseif ($spec instanceof Zend_Form_SubForm) {
            $subForm = $spec;
        } else {
            throw new Exception('Invalid argument passed to ' .
                                __FUNCTION__ . '()');
        }
        $this->addSubFormDecorators($subForm)
             ->addSubmitButton($subForm)
             ->addSubFormActions($subForm);
        return $subForm;
    }
    
    /**
     * Add form decorators to an individual sub form
     *
     * @param  Zend_Form_SubForm $subForm
     * @return My_Form_Registration
     */
    public function addSubFormDecorators(Zend_Form_SubForm $subForm)
    {
        $subForm->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'dl',
                                   'class' => 'connect-add-center-form')),
            'Form',
        ));
        return $this;
    }
 
    /**
     * Add a submit button to an individual sub form
     *
     * @param  Zend_Form_SubForm $subForm
     * @return My_Form_Registration
     */
    public function addSubmitButton(Zend_Form_SubForm $subForm)
    {
        $submit = new Zend_Form_Element_Submit(
            'save',
            array(
                'label'    => 'Next',
                'required' => false,
                'ignore'   => true,
            )
        );
        
        $submit->setDecorators(array(
            'ViewHelper',
            'Description',
            'Errors',
            array('HtmlTag', array('tag' => 'div', 'class' => 'connect-form-button')),
            array(),
        ));
        $subForm->addElement( $submit );
        
        return $this;
    }
 
    /**
     * Add action and method to sub form
     *
     * @param  Zend_Form_SubForm $subForm
     * @return My_Form_Registration
     */
    public function addSubFormActions(Zend_Form_SubForm $subForm)
    {
        $action = '/'.$this->_controllerName.'/process';
        $subForm->setAction($action)
                ->setMethod('post');
        return $this;
    }

    protected function getStatesArray() {
        return array('AL'=>'Alabama','AK'=>'Alaska','AZ'=>'Arizona','AR'=>'Arkansas',
            'CA'=>'California','CO'=>'Colorado','CT'=>'Connecticut',
            'DE'=>'Delaware','DC'=>'District Of Columbia','FL'=>'Florida',
            'GA'=>'Georgia','HI'=>'Hawaii','ID'=>'Idaho','IL'=>'Illinois', 
            'IN'=>'Indiana', 'IA'=>'Iowa',  'KS'=>'Kansas','KY'=>'Kentucky',
            'LA'=>'Louisiana','ME'=>'Maine','MD'=>'Maryland', 
            'MA'=>'Massachusetts','MI'=>'Michigan','MN'=>'Minnesota',
            'MS'=>'Mississippi','MO'=>'Missouri','MT'=>'Montana',
            'NE'=>'Nebraska','NV'=>'Nevada','NH'=>'New Hampshire',
            'NJ'=>'New Jersey','NM'=>'New Mexico','NY'=>'New York',
            'NC'=>'North Carolina','ND'=>'North Dakota','OH'=>'Ohio',
            'OK'=>'Oklahoma', 'OR'=>'Oregon','PA'=>'Pennsylvania',
            'RI'=>'Rhode Island','SC'=>'South Carolina','SD'=>'South Dakota',
            'TN'=>'Tennessee','TX'=>'Texas','UT'=>'Utah','VT'=>'Vermont',
            'VA'=>'Virginia','WA'=>'Washington','WV'=>'West Virginia',
            'WI'=>'Wisconsin','WY'=>'Wyoming');
    }
}

