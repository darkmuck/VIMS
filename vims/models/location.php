<?php 

/**********
Class Name: Location
Description: This class (model) communicates with and validates data transferred to/from the locations DB table
Variables: $name, $validate
Functions: findAllLocations, findAllUsers, deleteLocation, addLocation, updateLocation
Last Modified: 2009/03/24 01:18 PM
Last Modified By: William DiStefano
**********/
class Location extends AppModel
{
	var $name = 'Location';

	//Validation array - This specifies all of the fields and associated rules for data validation
	var $validate = array(

	  'name' => array(		//this line specifies the field name to validate
		'ruleNotEmpty' => array(	//This is the first reference rule name, this is user specified and it doesn't matter what it is
			'rule' => 'notEmpty',	//'rule' specifies that it is a rule and points to the name of the rule from the cake API, in this case it is 'notEmpty'
			'message' => 'Enter the category name')	//This is the message to be displayed should this validation test fail
		)

	);


	/**********
	  Function Name: findAllLocations
	  Description: This function will find all locations in the database.
	  Last Modified: 2009/03/05 10:54 AM
	  Last Modified By: William DiStefano
	**********/
	function findAllLocations() {

		return ($this->query('SELECT * FROM locations;'));

	} //end findAllLocations function

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
	  Function Name: deleteLocation
	  Description: This function will delete a location from the database
	  Last Modified: 2009/03/05 10:55 AM
	  Last Modified By: William DiStefano
	**********/
	function deleteLocation($id) {

		//Find all users from the database and store in the variable users
		$users = $this->findAllUsers();

		foreach($users as $user) :
			//If a user is location at the location we must set the location_id to 0
			if($user['users']['location_id'] == $id) {
				$this->query('UPDATE users SET location_id=' . 0 .' WHERE id='. $user['users']['id'] .';');
			} //end if
		endforeach;

		return ($this->query('DELETE FROM locations WHERE locations.id = '. $id .';'));

	} //end function deleteLocation

	/**********
	  Function Name: addLocation
	  Description: This function will add a new location to the database
	  Last Modified: 2009/03/05 10:57 AM
	  Last Modified By: William DiStefano
	**********/
	function addLocation($data) {


		$this->query('INSERT INTO locations (`id`, `created`, `modified`, `name`) 
							 VALUES (NULL, CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), "'. $data['name'] .'");');

	} //end function addLocation

	/**********
	  Function Name: updateLocation
	  Description: This function will update the location in the database
	  Last Modified: 2009/03/05 10:59 AM
	  Last Modified By: William DiStefano
	**********/
	function updateLocation($data) {

		$this->query('UPDATE locations SET `modified` = CURRENT_TIMESTAMP(), `name` = "'. $data['name'] .'" WHERE id = '. $data['id'] .';');

	} //end function updateLocation

} //end class

?>