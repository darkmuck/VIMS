<?php 

/**********
Class Name: Computer
Description: This class (model) communicates with and validates data to/from the computers DB table
Variables: $name, $validate
Functions: findAllAdministrators, findAllUsers, deleteComputer, retrieveComputer, addComputer, updateComputer
Last Modified: 2009/03/24 01:22 PM
Last Modified By: William DiStefano
**********/
class Computer extends AppModel
{
	var $name = 'Computer';

	//Validation array - This specifies all of the fields and associated rules for data validation
	var $validate = array(

	  'name' => array(		//this line specifies the field name to validate
		'ruleNotEmpty' => array(	//This is the first reference rule name, this is user specified and it doesn't matter what it is
			'rule' => 'notEmpty',	//'rule' specifies that it is a rule and points to the name of the rule from the cake API, in this case it is 'notEmpty'
			'message' => 'Enter the name of the computer.')	//This is the message to be displayed should this validation test fail
											//This rule will stop the validate process so the error message will display for this rule instead of
															//moving to the next rule and eventually displaying only the last error message only.
		),

	  'type'  => array(
		'ruleNotEmpty' => array(
			'rule' => 'notEmpty',  
			'message' => 'Enter the type, for example: laptop, desktop, or netbook.')
		),

	  'serial_number'  => array(
		'ruleNotEmpty' => array(
			'rule' => 'notEmpty',  
			'message' => 'Enter the serial number of the computer.')
		),

	  'operating_system'  => array(
		'ruleNotEmpty' => array(
			'rule' => 'notEmpty',  
			'message' => 'Enter the operating system, for example: Windows XP Home or Windows XP Pro.')
		),

	  'memory'  => array(
		'ruleNotEmpty' => array(
			'rule' => 'notEmpty',  
			'message' => 'Enter the total amount of memory.',
			'last' => true),
	  'ruleNumeric' => array(
			'rule' => 'numeric',  
			'message' => 'Enter the total amount of memory as a number only.',
			'last' => true),
		),

	  'hdd_space'  => array(
		'ruleNotEmpty' => array(
			'rule' => 'notEmpty',  
			'message' => 'Enter the total amount of hard drive space.',
			'last' => true),
	  'ruleNumeric' => array(
			'rule' => 'numeric',  
			'message' => 'Enter the total amount of hard drive space as a number only.',
			'last' => true),
		),

	  'processor'  => array(
		'ruleNotEmpty' => array(
			'rule' => 'notEmpty',  
			'message' => 'Enter the brand/type of processor, for example: Intel Core i7 920.')
		)

	);


	/**********
	  Function Name: findAllAdministrators
	  Description: This function will find all administrators in the database.
	  Last Modified: 2009/02/28 12:54 PM
	  Last Modified By: William DiStefano
	**********/
	function findAllAdministrators() {

		return ($this->query('SELECT * FROM admins;'));

	} //end findAllAdministrators function

	/**********
	  Function Name: findAllUsers
	  Description: This function will find all users in the database.
	  Last Modified: 2009/03/04 12:09 PM
	  Last Modified By: William DiStefano
	**********/
	function findAllUsers() {

		return ($this->query('SELECT * FROM users;'));

	} //end findAllUsers function

	/**********
	  Function Name: deleteComputer
	  Description: This function will delete a computer from the database
	  Last Modified: 2009/03/04 01:02 PM
	  Last Modified By: William DiStefano
	**********/
	function deleteComputer($id) {

		//Find all admins from the database and store in the variable admins
		$admins = $this->findAllAdministrators();

		//Find all users from the database and store in the variable users
		$users = $this->findAllUsers();

		foreach($admins as $admin) :
			//If an admin is using the computer we must set the computer_id to 0
			if($admin['admins']['computer_id'] == $id) {
				$this->query('UPDATE admins SET computer_id=' . 0 .' WHERE id='. $admin['admins']['id'] .';');
			} //end if
		endforeach;

		foreach($users as $user) :
			//If a user is using the computer we must set the computer_id to 0
			if($user['users']['computer_id'] == $id) {
				$this->query('UPDATE users SET computer_id=' . 0 .' WHERE id='. $user['users']['id'] .';');
			} //end if
		endforeach;

		return ($this->query('DELETE FROM computers WHERE computers.id = '. $id .';'));

	} //end function deleteComputer

	/**********
	  Function Name: retrieveComputer
	  Description: This function will retrieve all computer data given the ID
	  Last Modified: 2009/03/04 01:39 PM
	  Last Modified By: William DiStefano
	**********/
	function retrieveComputer($id) {

		return ($this->query('SELECT * FROM computers WHERE computers.id = '. $id .';'));

	} //end function retrieveComputer

	/**********
	  Function Name: addComputer
	  Description: This function will add a new computer to the database
	  Last Modified: 2009/03/04 02:22 PM
	  Last Modified By: William DiStefano
	**********/
	function addComputer($data) {


		$this->query('INSERT INTO computers (`id`, `created`, `modified`, `name`, `type`, `serial_number`, `operating_system`, `memory`, `hdd_space`, `processor`) 
							 VALUES (NULL, CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), "'. $data['name'] .'", "'. $data['type'] .'", 
							 "'. $data['serial_number'] .'", "'. $data['operating_system'] .'", '. $data['memory'] .', '. $data['hdd_space'] .', 
							 "'. $data['processor'] .'");');

	} //end function addComputer

	/**********
	  Function Name: updateComputer
	  Description: This function will update a computer in the database
	  Last Modified: 2009/03/04 02:37 PM
	  Last Modified By: William DiStefano
	**********/
	function updateComputer($data) {

	$this->query('UPDATE computers SET `modified` = CURRENT_TIMESTAMP(), `name` = "'. $data['name'] .'", `type` = "'. $data['type'] .'", 
				 `serial_number` = "'. $data['serial_number'] .'", `operating_system` = "'. $data['operating_system'] .'", `memory` = '. $data['memory'] .', 
				 `hdd_space` = "'. $data['hdd_space'] .'", `processor` = "'. $data['processor'] .'" WHERE id = '. $data['id'] .';');


	} //end function updateComputer

} //end class

?>