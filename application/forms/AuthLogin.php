<?
class Form_AuthLogin extends Zend_Dojo_Form
{
	public function init()
	{
		$this->setName('myForm');
	    $this->setMethod('post');

	   
	$this->addElement(
                'ValidationTextBox',
                'username',
                array(
            'value' => '',
		    'required' => true,
                    'label' => 'Username',
                    'trim' => true,
		   // 'regExp'   => '[\w]+[A-Za-z]',
		    'invalidMessage' => 'No spaces or non-word characters allowed',
		    'promptMessage'  => 'Single word consisting of alphanumeric ' .
		          'characters and underscores only',
		    'style' => 'width:140px',
                    )
            );
	
	$this->addElement(
		'PasswordTextBox',
		'password',
		array(
		'required' => true,
		'trim' => true,
                'lowercase' => true,
		//'regExp'         => '^[a-z0-9]{15,}$',
		'invalidMessage' => 'Invalid password; ' .
                            'must be at least 6 alphanumeric characters',
		'style' => 'width:140px',
		)
	     );
	     
	$this->addElement(
		'PasswordTextBox',
		'confirmpassword',
		array(
		'trim' => true,
        'lowercase' => true,
		//'regExp'         => '^[a-z0-9]{6,}$',
		'invalidMessage' => 'Invalid password; ' .
                            'must be at least 6 alphanumeric characters',
		'style' => 'width:140px',
		)
	     );
	     
	$this->addElement(
		'PasswordTextBox',
		'newpassword',
		array(
		'trim' => true,
                'lowercase' => true,
		//'regExp'         => '^[a-z0-9]{6,}$',
		'invalidMessage' => 'Invalid password; ' .
		           'must be at least 6 alphanumeric characters',
		'style' => 'width:140px',
		)
	     );
	     
	$this->addElement(
		'SubmitButton',
		'submit',
		array(
		'required' => false,
		'ignore' => true,
		'label' => 'Login',
		'style' => 'width:200px',
		)
	);
	
	$this->addElement(
		'SubmitButton',
		'submit2',
		array(
		'required' => false,
		'ignore' => true,
		'label' => 'Change Password',
		'style' => 'width:200px',
		)
	);


	$newpassword2 = new Zend_Form_Element_Password('newpassword2');
	$newpassword2->removeDecorator('label')
				 ->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 //->setAttrib('onKeyPress','return numOnlyHyphen(event)')
				 ->setAttrib('size','20');
	$this->addElement($newpassword2);

	$confirmpassword2 = new Zend_Form_Element_Password('confirmpassword2');
	$confirmpassword2->removeDecorator('label')
				 ->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 //->setAttrib('onKeyPress','return numOnlyHyphen(event)')
				 ->setAttrib('size','20');
	$this->addElement($confirmpassword2);
	
	
	
	
	
	
	
	
	
	
	
	
	}
}