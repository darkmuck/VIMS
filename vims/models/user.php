<?php 

/**********
Class Name: User
Description: This class (model) communicates with and validates data to/from the users DB table
Variables: $name, $validate
Functions: validateLogin, findAllUsers, deleteUser, retrieveUser, retrieveComputers, retrieveLocations, 
		   retrieveAvailableComputers, addUser, updateUser, checkUserUsernameExists, disableUser,  enableUser
Last Modified: 2009/03/24 01:26 PM
Last Modified By: William DiStefano
**********/
class User extends AppModel
{
	var $name = 'User';

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
	  Description: This function will check if the user exists in the database and returns the information
	  Last Modified: 2009/01/26 02:04 PM
	  Last Modified By: William DiStefano
	**********/
	function validateLogin($data) {
		$user = $this->query('SELECT id, created, modified, `enabled`, username, first_name, middle_name, last_name, email, computer_id, network_port, location_id FROM 
							  users WHERE username="'. $data['username'] . '" AND password= md5("'. $data['password'] .'");' );
		if(empty($user) == false) //If the $user array isn't empty return the results
		  return $user;
		return false;
	} //end function validateLogin

	/**********
	  Function Name: findAllUsers
	  Description: This function will find all users in the database.
	  Last Modified: 2009/03/02 01:39 PM
	  Last Modified By: William DiStefano
	**********/
	function findAllUsers() {

		return ($this->query('SELECT * FROM users;'));

	} //end function findAllUsers

	/**********
	  Function Name: deleteUser
	  Description: This function will delete a user from the database
	  Last Modified: 2009/03/02 01:39 PM
	  Last Modified By: William DiStefano
	**********/
	function deleteUser($id) {

		return ($this->query('DELETE FROM users WHERE users.id = '. $id .';'));

	} //end function deleteUser

	/**********
	  Function Name: retrieveUser
	  Description: This function will retrieve all user data given the ID
	  Last Modified: 2009/03/01 07:19 PM
	  Last Modified By: William DiStefano
	**********/
	function retrieveUser($id) {

		return ($this->query('SELECT `id`, `created`, `enabled`, `modified`, `username`, md5(password), `first_name`, `middle_name`, 
							 `last_name`, `email`, `location_id`, `computer_id`, `network_port` FROM users WHERE users.id = '. $id .';'));

	} //end function retrieveUser

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
	  Function Name: retrieveLocations
	  Description: This function will retrieve all locations from the database
	  Last Modified: 2009/03/01 07:19 PM
	  Last Modified By: William DiStefano
	**********/
	function retrieveLocations() {

		return ($this->query('SELECT * FROM locations;'));

	} //end function retrieveLocations

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
	  Function Name: addUser
	  Description: This function will add a new user to the database
	  Last Modified: 2009/03/02 02:07 PM
	  Last Modified By: William DiStefano
	**********/
	function addUser($data) {

		$this->query('INSERT INTO users (`id`, `created`, `modified`, `username`, `password`, `first_name`, `middle_name`, 
							 `last_name`, `email`, `location_id`, `computer_id`, `network_port`) 
							 VALUES (NULL, CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), "'. $data['username'] .'", md5("'. $data['password'] .'"), "'. $data['first_name'] .'",
									 "'. $data['middle_name'] .'", "'. $data['last_name'] .'", "'. $data['email'] .'", 
									 '. $data['location_id'] .', '. $data['computer_id'] .', "'. $data['network_port'] .'");');

	} //end function addUser

	/**********
	  Function Name: updateUser
	  Description: This function will update an user in the database
	  Last Modified: 2009/03/01 05:46 PM
	  Last Modified By: William DiStefano
	**********/
	function updateUser($data) {

		//We must check if the password has changed because it won't store properly with encryption if it was changed
		$userPassword = $this->query('SELECT password FROM users WHERE id='. $data['id'] .';');

		//check if the password has changed
		if( ($data['password']) != ($userPassword['0']['users']['password']) ) {

			//The password has been changed, update all fields
			$this->query('UPDATE users SET `modified` = CURRENT_TIMESTAMP(), `username` = "'. $data['username'] .'", `password` = md5("'. $data['password'] .'"), 
						 `first_name` = "'. $data['first_name'] .'", `middle_name`= "'. $data['middle_name'] .'", `last_name` = "'. $data['last_name'] .'", 
						 `email` = "'. $data['email'] .'",  `location_id` = '. $data['location_id'] .',  `computer_id` = '. $data['computer_id'] .', `network_port` = "'. $data['network_port'] .'", `enabled` = '. $data['enabled'] .' WHERE id= '. $data['id'] .';');

		} else {

			//The password has not changed so update everything except for the password
			$this->query('UPDATE users SET `modified` = CURRENT_TIMESTAMP(), `username` = "'. $data['username'] .'", `first_name` = "'. $data['first_name'] .'", `middle_name`= "'. $data['middle_name'] .'", `last_name` = "'. $data['last_name'] .'", `enabled` = '. $data['enabled'] .', 
						 `email` = "'. $data['email'] .'",  `location_id` = '. $data['location_id'] .',  `computer_id` = '. $data['computer_id'] .', `network_port` = "'. $data['network_port'] .'" WHERE id= '. $data['id'] .';');

		} //end if

	} //end function updateUser

	/**********
	  Function Name: checkUserUsernameExists
	  Description: This function will check if the user username exists in the database
	  Last Modified: 2009/03/02 02:17 PM
	  Last Modified By: William DiStefano
	**********/
	function checkUserUsernameExists($data) {
		$user = $this->query(' SELECT * FROM 
								users WHERE username="'. $data['username'] . '";' );
		if(empty($user) == false) //If the $user array isn't empty return false because it does not exist in the database
		  return true;
		return false;
	} //end function checkUserUsernameExists

	/**********
	  Function Name: disableUser
	  Description: This function will disable an user in the database
	  Last Modified: 2009/03/01 05:46 PM
	  Last Modified By: William DiStefano
	**********/
	function disableUser($id) {

			$this->query('UPDATE users SET `enabled` = 0 WHERE id= '. $id .';');

	} //end function disableUser

	/**********
	  Function Name: enableUser
	  Description: This function will enable an user in the database
	  Last Modified: 2009/03/01 05:46 PM
	  Last Modified By: William DiStefano
	**********/
	function enableUser($id) {

			$this->query('UPDATE users SET `enabled` = 1 WHERE id= '. $id .';');

	} //end function enableUser

} //end class

?>