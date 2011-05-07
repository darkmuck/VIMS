<?php 

/**********
Class Name: Admin
Description: This class (model) communicates with and validates data to/from the admins DB table
Variables: $name, $validate
Functions: validateLogin, findAllAdministrators, deleteAdmin, retrieveAdmin, retrieveComputers, 
		   retrieveAvailableComputers, addAdministrator, updateAdministrator, checkAdminUsernameExists, 
		   disableAdministrator, enableAdministrator
Last Modified: 2009/03/24 01:27 PM
Last Modified By: William DiStefano
**********/
class Admin extends AppModel
{
	var $name = 'Admin';

	//Validation array - This specifies all of the fields and associated rules for data validation
	var $validate = array(

	  'first_name' => array(		//this line specifies the field name to validate
		'ruleNotEmpty' => array(	//This is the first reference rule name, this is user specified and it doesn't matter what it is
			'rule' => 'notEmpty',	//'rule' specifies that it is a rule and points to the name of the rule from the cake API, in this case it is 'notEmpty'
			'message' => 'Enter the first name')	//This is the message to be displayed should this validation test fail
											//This rule will stop the validate process so the error message will display for this rule instead of
															//moving to the next rule and eventually displaying only the last error message only.
		),

	  'last_name'  => array(
		'ruleNotEmpty' => array(
			'rule' => 'notEmpty',  
			'message' => 'Enter the last name')
		),

	  'username'  => array(
		'ruleNotEmpty' => array(
			'rule' => 'notEmpty',  
			'message' => 'Enter the username')
		),

	  'password'  => array(
		'ruleNotEmpty' => array(
			'rule' => 'notEmpty',  
			'message' => 'Enter the password')
		),

	  'email' => array(
		'ruleNotEmpty' => array(
			'rule' => 'notEmpty',
			'message' => 'Enter the email address',
			'last' => true),
		'ruleValidateEmail' => array(
			'rule' => array('email'),
			'message' => 'Enter the email in the format: username@domain.com .')
		)

	);

	/**********
	  Function Name: validateLogin
	  Description: This function will check if the administrator exists in the database and returns the information
	  Last Modified: 2009/02/28 12:54 PM
	  Last Modified By: William DiStefano
	**********/
	function validateLogin($data) {
		$sessionAdmin = $this->query('SELECT * FROM 
							  admins WHERE username="'. $data['username'] . '" AND password=md5("'. $data['password'] .'");' );
		if(empty($sessionAdmin) == false) //If the $sessionAdmin array isn't empty return the results
		  return $sessionAdmin;
		return false;
	} //end function validateLogin

	/**********
	  Function Name: findAllAdministrators
	  Description: This function will find all administrators in the database.
	  Last Modified: 2009/02/28 12:54 PM
	  Last Modified By: William DiStefano
	**********/
	function findAllAdministrators() {

		return ($this->query('SELECT * FROM admins;'));

	} //end function findAllAdministrators

	/**********
	  Function Name: deleteAdmin
	  Description: This function will delete an administrator from the database
	  Last Modified: 2009/02/28 07:19 PM
	  Last Modified By: William DiStefano
	**********/
	function deleteAdmin($id) {

		return ($this->query('DELETE FROM admins WHERE admins.id = '. $id .';'));

	} //end function deleteAdmin

	/**********
	  Function Name: retrieveAdmin
	  Description: This function will retrieve all administrator data given the ID
	  Last Modified: 2009/03/01 07:19 PM
	  Last Modified By: William DiStefano
	**********/
	function retrieveAdmin($id) {

		return ($this->query('SELECT `id`, `created`, `enabled`, `modified`, `username`, `password`, `first_name`, `middle_name`, 
							 `last_name`, `email`, `type`, `computer_id` FROM admins WHERE admins.id = '. $id .';'));

	} //end function retrieveAdmin

	/**********
	  Function Name: retrieveComputers
	  Description: This function will retrieve all computers from the database
	  Last Modified: 2009/03/01 07:19 PM
	  Last Modified By: William DiStefano
	**********/
	function retrieveComputers() {

		return ($this->query('SELECT * FROM computers;'));

	} //end function retrieveComputers

	/**********
	  Function Name: retrieveAvailableComputers
	  Description: This function will retrieve all available computers from the database that are not associated with a user or admin
	  Last Modified: 2009/03/01 04:44 PM
	  Last Modified By: William DiStefano
	**********/
	function retrieveAvailableComputers() {
		//Retrieve list of computers
		$computers = $this->query('SELECT * FROM computers;');

		$availableComputers = array();

		foreach($computers as $computer) :

			$adminWithComputer = $this->query('SELECT * FROM admins WHERE computer_id='. $computer['computers']['id'] .';');
			$userWithComputer = $this->query('SELECT * FROM users WHERE computer_id='. $computer['computers']['id'] .';');

			//Make sure the computer isn't already assigned to an admin or user
			if( (empty($adminWithComputer)) && (empty($userWithComputer)) ) {
					//Add the available computers to an array
					array_push($availableComputers, $computer['computers']['id']);
			} //end if

			//unset the variables before they are used again
			unset($adminWithComputer);
			unset($userWithComputer);

		endforeach;


		return $availableComputers;

	} //end function retrieveAvailableComputers

	/**********
	  Function Name: addAdministrator
	  Description: This function will add a new administrator to the database
	  Last Modified: 2009/03/01 02:29 PM
	  Last Modified By: William DiStefano
	**********/
	function addAdministrator($data) {
	
		//Check to see if the type is zero or not
		if($data['type'] == 0) {
			$type = 2; //Set the type variable to 2 (technician)
		} else if($data['type'] == 1) {
			$type = 1;
		} else if($data['type'] == 2) {
			$type = 2;
		} //end if

		$this->query('INSERT INTO admins (`id`, `created`, `modified`, `username`, `password`, `first_name`, `middle_name`, 
							 `last_name`, `email`, `type`, `computer_id`) 
							 VALUES (NULL, CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), "'. $data['username'] .'", md5("'. $data['password'] .'"), "'. $data['first_name'] .'",
									 "'. $data['middle_name'] .'", "'. $data['last_name'] .'", "'. $data['email'] .'", '. $type .', '. $data['computer_id'] .');');

	} //end function addAdministrator

	/**********
	  Function Name: updateAdministrator
	  Description: This function will update an administrator in the database
	  Last Modified: 2009/03/01 05:46 PM
	  Last Modified By: William DiStefano
	**********/
	function updateAdministrator($data) {
	
		//Check to see if the type is zero or not
		if($data['type'] == 0) {
			$type = 2; //Set the type variable to 2 (technician)
		} else if($data['type'] == 1) {
			$type = 1;
		} else if($data['type'] == 2) {
			$type = 2;
		} //end if

		//We must check if the password has changed because it won't store properly with encryption if it was changed
		$adminPassword = $this->query('SELECT password FROM admins WHERE id='. $data['id'] .';');

		//check if the password has changed
		if( ($data['password']) != ($adminPassword['0']['admins']['password']) ) {

		//The password has been changed, update all fields
		$this->query('UPDATE admins SET `modified` = CURRENT_TIMESTAMP(), `username` = "'. $data['username'] .'", `password` = md5("'. $data['password'] .'"), 
					 `first_name` = "'. $data['first_name'] .'", `middle_name`= "'. $data['middle_name'] .'", `last_name` = "'. $data['last_name'] .'", 
					 `email` = "'. $data['email'] .'",  `type` = '. $type .',  `computer_id` = '. $data['computer_id'] .' WHERE id= '. $data['id'] .';');

		} else {

		//The password has not changed so update everything except for the password
		$this->query('UPDATE admins SET `modified` = CURRENT_TIMESTAMP(), `username` = "'. $data['username'] .'", `enabled` = '. $data['enabled'] .', 
					 `first_name` = "'. $data['first_name'] .'", `middle_name`= "'. $data['middle_name'] .'", `last_name` = "'. $data['last_name'] .'", 
					 `email` = "'. $data['email'] .'",  `type` = '. $type .',  `computer_id` = '. $data['computer_id'] .' WHERE id= '. $data['id'] .';');

		} //end if

	} //end function updateAdministrator

	/**********
	  Function Name: checkAdminUsernameExists
	  Description: This function will check if the administrator username exists in the database
	  Last Modified: 2009/02/28 12:54 PM
	  Last Modified By: William DiStefano
	**********/
	function checkAdminUsernameExists($data) {
		$admin = $this->query(' SELECT * FROM 
								admins WHERE username="'. $data['username'] . '";' );
		if(empty($admin) == false) //If the $admin array isn't empty return false because it does not exist in the database
		  return true;
		return false;
	} //end function checkAdminUsernameExists

	/**********
	  Function Name: disableAdministrator
	  Description: This function will disable an administrator in the database
	  Last Modified: 2009/03/01 05:46 PM
	  Last Modified By: William DiStefano
	**********/
	function disableAdministrator($id) {

		$this->query('UPDATE admins SET `enabled` = 0 WHERE id= '. $id .';');

	} //end function disableAdministrator

	/**********
	  Function Name: enableAdministrator
	  Description: This function will disable an administrator in the database
	  Last Modified: 2009/03/01 05:46 PM
	  Last Modified By: William DiStefano
	**********/
	function enableAdministrator($id) {

		$this->query('UPDATE admins SET `enabled` = 1 WHERE id= '. $id .';');

	} //end function enableAdministrator

} //end class

?>