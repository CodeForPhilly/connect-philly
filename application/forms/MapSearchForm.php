<?php

class Application_Form_MapSearchForm extends Zend_Form
{

    public function init()
    {
        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'dl', 'class' => 'connect-search-form')),
            'Form',
        ));
        
        $this->addElement( 'text', 'address', array( 
                    'label'         => 'Enter your address',
                    'required'      => true,
                    'size'          => 25
        ));
		
        $narrowSearchRadios = new Zend_Form_Element_Radio('narrowsearch');
        $narrowSearchRadios->setLabel( 'Choose Services' );
        $narrowSearchRadios->addMultiOptions( array(
                'none'		=> 'All Centers',
                'retail'        => 'Retail Locations',
                'public'        => 'Public Locations',
                'disabled' 	=> 'Disabled Access',
                'wifi' 		=> 'Wifi Access',
                'training'      => 'Computer Training',
                'nonenglish'    => 'Non-English Language Locations'
                 ) );
        
        $narrowSearchRadios->setValue(array('none', 'All Centers'));
        $narrowSearchRadios->setAttrib('onclick', "narrowSearch();");
        $this->addElement( $narrowSearchRadios );

        $this->addElement('image', 'find address', array(
            'ignore'   => true,
	    'src' => '/images/map_locations_button.jpg',
	    'onclick' => 'codeAddress("address"); return false;'
        ));
    }

}

