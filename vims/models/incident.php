<?php 

/**********
Class Name: Incident
Description: This class (model) communicates with and validates data to/from the incidents DB table
Variables: $name, $validate
Functions: findAllAdministrators, findAllUsers, findAllCategories, findAllComputers, findAllLocations, 
		   findAllIncidents, retrieveIncident, retrieveWorklogs, updateIncident, addWorklog, deleteWorklog, addIncident
Last Modified: 2009/03/24 01:23 PM
Last Modified By: William DiStefano
**********/
class Incident extends AppModel
{
	var $name = 'Incident';

	//Validation array - This specifies all of the fields and associated rules for data validation
	var $validate = array(

	  'description' => array(		//this line specifies the field name to validate
		'ruleNotEmpty' => array(	//This is the first reference rule name, this is user specified and it doesn't matter what it is
			'rule' => 'notEmpty',	//'rule' specifies that it is a rule and points to the name of the rule from the cake API, in this case it is 'notEmpty'
			'message' => 'Enter the description of the incident')	//This is the message to be displayed should this validation test fail
											//This rule will stop the validate process so the error message will display for this rule instead of
															//moving to the next rule and eventually displaying only the last error message only.
		),

	  'content'  => array(
		'ruleNotEmpty' => array(
			'rule' => 'notEmpty',  
			'message' => 'Enter the incident content')
		), 

	  'category_id'  => array(
		'ruleNumeric' => array(
			'rule' => 'numeric', 
			'message' => 'Choose a category.')
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

	} //end function findAllAdministrators

	/**********
	  Function Name: findAllUsers
	  Description: This function will find all users in the database.
	  Last Modified: 2009/03/09 01:47 PM
	  Last Modified By: William DiStefano
	**********/
	function findAllUsers() {

		return ($this->query('SELECT * FROM users;'));

	} //end function findAllUsers

	/**********
	  Function Name: findAllCategories
	  Description: This function will find all categories in the database.
	  Last Modified: 2009/03/09 02:23 PM
	  Last Modified By: William DiStefano
	**********/
	function findAllCategories() {

		return ($this->query('SELECT * FROM categories;'));

	} //end function findAllCategories

	/**********
	  Function Name: findAllComputers
	  Description: This function will find all computers in the database.
	  Last Modified: 2009/03/09 01:47 PM
	  Last Modified By: William DiStefano
	**********/
	function findAllComputers() {

		return ($this->query('SELECT * FROM computers;'));

	} //end function findAllComputers

	/**********
	  Function Name: findAllLocations
	  Description: This function will find all locations in the database.
	  Last Modified: 2009/03/09 01:47 PM
	  Last Modified By: William DiStefano
	**********/
	function findAllLocations() {

		return ($this->query('SELECT * FROM locations;'));

	} //end function findAllLocations

	/**********
	  Function Name: findAllIncidents
	  Description: This function will find all incidents in the database.
	  Last Modified: 2009/03/09 04:12 PM
	  Last Modified By: William DiStefano
	**********/
	function findAllIncidents() {

		return ($this->query('SELECT * FROM incidents;'));

	} //end function findAllIncidents

	/**********
	  Function Name: retrieveIncident
	  Description: This function will retrieve the incident, given the ID number
	  Last Modified: 2009/03/09 03:23 PM
	  Last Modified By: William DiStefano
	**********/
	function retrieveIncident($id) {

		return ($this->query('SELECT * FROM incidents WHERE id='. $id .';'));

	} //end function retrieveIncident

	/**********
	  Function Name: retrieveWorklogs
	  Description: This function will retrieve the worklogs, given the incident's ID number
	  Last Modified: 2009/03/09 03:23 PM
	  Last Modified By: William DiStefano
	**********/
	function retrieveWorklogs($id) {

		return ($this->query('SELECT * FROM worklogs WHERE incident_id='. $id .' ORDER BY created ASC;'));

	} //end function retrieveWorklogs

	/**********
	  Function Name: updateIncident
	  Description: This function will update an incident in the database
	  Last Modified: 2009/03/12 03:58 PM
	  Last Modified By: William DiStefano
	**********/
	function updateIncident($data, $sessionAdmin) {

	//We use the following if statement to add slashes to the HTML elements so it stores in the database properly
	if (!get_magic_quotes_runtime())
	{
		$content = addslashes($data['content']);
	}

	//Check what the status was changed to
	if($data['status'] == "0") {
		//The new status is pending, we must remove any assigned administrators/technicians
		//Update the incident
		$this->query('UPDATE incidents SET `modified` = CURRENT_TIMESTAMP(), `description` = "'. $data['description'] .'", 
				 `status` = '. $data['status'] .', `priority` = '. $data['priority'] .', `content` = "'. $content .'", 
				 `category_id` = '. $data['category_id'] .', `admin_id` = 0 WHERE id = '. $data['id'] .';');
		$this->query('INSERT INTO worklogs (`id`, `created`, `modified`, `content`, `incident_id`, `admin_id`, `user_id`) 
					VALUES (NULL, CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), 
					"SYSTEM MESSAGE: Incident Updated by: '. 
					$sessionAdmin[0]['admins']['username'] .' | Status changed to: pending. | 
					Incident is assigned to: UNASSIGNED",
							'. $data['id'] .', 0, '. $data['user_id'] .')');
	} else {
		//Update the incident and make a new worklog

			  SWITCH($data['status']) {
				CASE 0:
					$status = 'pending';
					break;
				CASE 1:
					$status = 'accepted';
					break;
				CASE 2:
					$status = 'resolved'; //This status level will be archived
					break;
				CASE 3:
					$status = 'duplicate'; //This status level will be archived
					break;
					CASE 4:
					$status = 'inaccurate'; //This status level will be archived
					break;
				CASE 5:
					$status = 'unresolvable'; //This status level will be archived
					break;
				DEFAULT:
					echo '---';
			  }//end switch

		$admins = $this->query('SELECT * FROM admins');
		foreach($admins as $admin) :
			if($data['admin_id'] == $admin['admins']['id']) {
				$assignedAdmin = $admin['admins']['username'];
			} //end if
		endforeach;

		$this->query('UPDATE incidents SET `modified` = CURRENT_TIMESTAMP(), `description` = "'. $data['description'] .'", 
				 `status` = '. $data['status'] .', `priority` = '. $data['priority'] .', `content` = "'. $content .'", 
				 `category_id` = '. $data['category_id'] .', `admin_id` = '. $data['admin_id'] .' WHERE id = '. $data['id'] .';');

		$this->query('INSERT INTO worklogs (`id`, `created`, `modified`, `content`, `incident_id`, `admin_id`, `user_id`) 
					VALUES (NULL, CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), 
					"SYSTEM MESSAGE: Incident Updated by: '. 
					$sessionAdmin[0]['admins']['username'] .' | Status changed to: '. $status .' | 
					Incident assigned to: '. $assignedAdmin .'",
							'. $data['id'] .', 0, '. $data['user_id'] .')');
	}

	} //end function updateIncident

	/**********
	  Function Name: addWorklog
	  Description: This function will create a worklog
	  Last Modified: 2009/03/18 04:02 PM
	  Last Modified By: William DiStefano
	**********/
	function addWorklog($data) {

	//We use the following if statement to add slashes to the HTML elements so it stores in the database properly
	if (!get_magic_quotes_runtime())
	{
		$content = addslashes($data['content']);
	}

	//Check to see if an admin or user submitted the worklog and save it to the database accordingly
	if(empty($data['admin_id'])) {
		return ($this->query('INSERT INTO worklogs 
								(`id`, `created`, `modified`, `content`, `incident_id`, `admin_id`, `user_id`) 
								VALUES (NULL, CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), "'. $content .'",
										'. $data['incident_id'] .', 0, '. $data['user_id'] .');'));
	} else if(empty($data['user_id'])) {
		return ($this->query('INSERT INTO worklogs 
								(`id`, `created`, `modified`, `content`, `incident_id`, `admin_id`, `user_id`) 
								VALUES (NULL, CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), "'. $content .'",
										'. $data['incident_id'] .', '. $data['admin_id'] .', 0);'));
	} //end if

	} //end function addWorklog

	/**********
	  Function Name: deleteWorklog
	  Description: This function will delete a worklog entry
	  Last Modified: 2009/03/19 01:45 PM
	  Last Modified By: William DiStefano
	**********/
	function deleteWorklog($id) {

		return ($this->query('DELETE FROM worklogs WHERE id= '. $id .';'));

	} //end function deleteWorklog

	/**********
	  Function Name: addIncident
	  Description: This function will add an incident to the database
	  Last Modified: 2009/03/20 02:56 PM
	  Last Modified By: William DiStefano
	**********/
	function addIncident($data) {

	//We use the following if statement to add slashes to the HTML elements so it stores in the database properly
	if (!get_magic_quotes_runtime())
	{
		$content = addslashes($data['content']);
	}

	//Check if admin_id is empty or equal to 0, if it is then status remains pending (0), otherwise status is accepted (1)
	if( ($data['admin_id'] != 0) && !empty($data['admin_id']) ) {
		$status = 1;
	} else {
		$status = 0;
	}//end if

		return ($this->query('INSERT INTO incidents 
							(`id`, `created`, `modified`, `description`, `status`, `content`, `category_id`, `priority`, 
							`user_id`, `admin_id`) 
							VALUES (NULL, CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), "'. $data['description'] .'",
							'. $status .', "'. $content .'", '. $data['category_id'] .',
							'. $data['priority'] .', '. $data['user_id'] .', '. $data['admin_id'] .');'));

	} //end function addIncident

	/**********
	  Function Name: search
	  Description: This function will search the database for matching incident descriptions
					For admins/techs: This will search all incidents
					For users: This will only search incidents that they submitted.
	  Last Modified: 2009/03/25 03:03 PM
	  Last Modified By: William DiStefano
	**********/
	function search($searchterm, $searchtype, $type, $id) {

		//$id - The user or admin ID
		//$type - designates a user(2) or administrator (1)
		//$searchterm - The term to search for in the database
		//$searchtype - specified the method of searching the database

		SWITCH($searchtype) {
			CASE 1:
				//SEARCH BY DESCRIPTION
				if($type == 2) {
					//They are a user, search through only incidents they have created
					return ($this->query('SELECT * FROM incidents WHERE description LIKE "%'. $searchterm .'%" AND user_id='. $id .';'));
				} else if($type == 1) {
					//They are an admin, search all incidents
					return ($this->query('SELECT * FROM incidents WHERE description LIKE "%'. $searchterm .'%";'));
				} //end if
				break;
			CASE 2:
				//SEARCH BY PROBLEM
				if($type == 2) {
					//They are a user, search through only incidents they have created
					return ($this->query('SELECT * FROM incidents WHERE content LIKE "%'. $searchterm .'%" AND user_id='. $id .';'));
				} else if($type == 1) {
					//They are an admin, search all incidents
					return ($this->query('SELECT * FROM incidents WHERE content LIKE "%'. $searchterm .'%";'));
				} //end if
				break;
			CASE 3:
				//SEARCH BY WORKLOG
				if($type == 2) {
					//They are a user, search through only incidents they have created
					return($this->query('SELECT * FROM incidents WHERE user_id='. $id .' AND 
						id IN (SELECT incident_id FROM worklogs WHERE content LIKE "%'. $searchterm .'%");'));
				} else if($type == 1) {
					//They are an admin, search all incidents
					return ($this->query('SELECT * FROM incidents WHERE id IN (SELECT incident_id FROM worklogs WHERE content LIKE "%'. $searchterm .'%");'));
				} //end if
				break;
			DEFAULT:
				return false;
				break;
		} //end switch

	} //end function search


//////////////////////////
/* REPORTING FUNCTIONS */

	/**********
	  Function Name: rptEmployeesComputers
	  Description: 
	  Last Modified: 2009/03/31 09:55 AM
	  Last Modified By: William DiStefano
	**********/
	function rptEmployeesComputers() {

		return ($this->query('SELECT 
									users.last_name, users.first_name, users.middle_name, users.username, users.email, users.network_port,
									computers.name, computers.serial_number, computers.hdd_space, computers.processor, computers.memory,
									locations.name
							  FROM 
									computers
									INNER JOIN users ON users.computer_id = computers.id
									INNER JOIN locations ON users.location_id = locations.id
							  WHERE 
									users.enabled = 1
							  ORDER BY 
									users.last_name ASC;'));

	} //end function rptEmployeesComputers

	/**********
	  Function Name: rptAdminsComputers
	  Description: 
	  Last Modified: 2009/04/05 02:14 PM
	  Last Modified By: William DiStefano
	**********/
	function rptAdminsComputers() {

		return ($this->query('SELECT 
									admins.last_name, admins.first_name, admins.middle_name, admins.username,
									computers.name, computers.serial_number
							  FROM 
									computers
									INNER JOIN admins ON admins.computer_id = computers.id
							  WHERE 
									admins.enabled = 1
							  ORDER BY 
									admins.last_name ASC;'));

	} //end function rptAdminsComputers

	/**********
	  Function Name: rptCountIncidents
	  Description: 
	  Last Modified: 2009/03/31 10:03 AM
	  Last Modified By: William DiStefano
	**********/
	function rptCountIncidents() {

		return ($this->query('SELECT 
									COUNT(*) AS numberIncidents, categories.name
							  FROM 
									categories
									INNER JOIN incidents ON incidents.category_id = categories.id
							  GROUP BY 
									categories.name
							  ORDER BY 
									categories.name ASC;'));

	} //end function rptCountIncidents

	/**********
	  Function Name: rptpendingandacceptedincidents
	  Description: 
	  Last Modified: 2009/04/03 03:45 PM
	  Last Modified By: William DiStefano
	**********/
	function rptpendingandacceptedincidents() {

		return ($this->query('SELECT 
									incidents.created, incidents.id, incidents.priority, 
									incidents.status, incidents.description, categories.name,
									users.first_name, users.middle_name, users.last_name, 
									users.username, admins.first_name, admins.middle_name, 
									admins.last_name, admins.username
							  FROM 
									incidents
									INNER JOIN categories ON categories.id = incidents.category_id
									LEFT OUTER JOIN users ON users.id = incidents.user_id
									LEFT OUTER JOIN admins ON admins.id = incidents.admin_id
							  WHERE
									incidents.status = 0 OR incidents.status = 1
							  ORDER BY 
									incidents.created ASC;'));

	} //end function rptpendingandacceptedincidents

	/**********
	  Function Name: rptcountincidentsmonthly
	  Description: 
	  Last Modified: 2009/04/06 02:56 PM
	  Last Modified By: William DiStefano
	**********/
	function rptcountincidentsmonthly() {

		return ($this->query('SELECT
									COUNT(*) AS NumberIncidents,
									CASE
										WHEN MONTH(created) = 1 THEN "January"
										WHEN MONTH(created) = 2 THEN "February"
										WHEN MONTH(created) = 3 THEN "March"
										WHEN MONTH(created) = 4 THEN "April"
										WHEN MONTH(created) = 5 THEN "May"
										WHEN MONTH(created) = 6 THEN "June"
										WHEN MONTH(created) = 7 THEN "July"
										WHEN MONTH(created) = 8 THEN "August"
										WHEN MONTH(created) = 9 THEN "September"
										WHEN MONTH(created) = 10 THEN "October"
										WHEN MONTH(created) = 11 THEN "November"
										WHEN MONTH(created) = 12 THEN "December"
										ELSE "Unknown"
									END AS MONTH,
									YEAR(created) AS YEAR
							  FROM incidents
							  GROUP BY YEAR(created), MONTH(created);'));

	} //end function rptcountincidentsmonthly

	/**********
	  Function Name: rptcountincidentsperadmin
	  Description: 
	  Last Modified: 2009/04/03 04:08 PM
	  Last Modified By: William DiStefano
	**********/
	function rptcountincidentsperadmin() {

		return ($this->query('SELECT 
									count(incidents.id) AS numberIncidents, admins.first_name, admins.middle_name, 
									admins.last_name, admins.username
							  FROM 
									incidents
									RIGHT OUTER JOIN admins ON admins.id = incidents.admin_id
							  WHERE
									incidents.created BETWEEN date(now() - interval 1 month) AND now()
									AND incidents.status > 1
							  GROUP BY
									admins.first_name, admins.middle_name, admins.last_name, admins.username;'));

	} //end function rptcountincidentsperadmin

} //end class

?>