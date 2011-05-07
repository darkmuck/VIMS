<?php

/**********
Class Name: IncidentsController
Description: This class (controller) will perform all functions related to 'incidents' and render the views accordingly
Variables: $name, $uses, $helpers, $components, $paginate
Functions: beforeFilter, __validateLoginStatus, index, index2, index3, reports, add, edit, view, deleteWorklog, addWorklog
Last Modified: 2009/03/24 01:37 PM
Last Modified By: William DiStefano
**********/
class IncidentsController extends AppController {
	var $name = 'Incidents';//Name of this controller
	var $uses = array('Incident'); //The items in this array are the model classes that will be accessible from this controller
	var $helpers = array('Ajax', 'Paginator', 'Javascript', 'Html', 'Form');
	var $components = array('RequestHandler'); //request handler is needed to have CakePHP Ajax support
	//paginate options are set here for the entire controller where pagination is used
	var $paginate = array(		//Paginate the results and order
		'limit' => 10,			//Limit each page to this many results
		'order' => array('created'=>'asc','priority'=>'asc')
		);


	/**********
	  Function Name: beforeFilter
	  Description: This function will execute before every controller action automatically.
				   It's purpose is to  call the '__validateLoginStatus' function before executing each controller action
	  Last Modified: 2009/01/26 02:04 PM
	  Last Modified By: William DiStefano
	**********/
	function beforeFilter() {
	  $this->__validateLoginStatus(); 
	} //end function beforeFilter


	/**********
	  Function Name: __validateLoginStatus
	  Description: This function will check to see if the $sessionUser or $sessionAdmin variable in the session exists to see if you are 
				   logged in or not.
				   If you are not logged in it will redirect you to '/users/login' or if you are it will continue to load 'index'.
	  Last Modified: 2009/03/24 01:56 PM
	  Last Modified By: William DiStefano
	**********/
	function __validateLoginStatus() {
	  if($this->action != 'login' && $this->action != 'logout') { //make sure this function wasn't called from the login or logout functions

		//check if the admin or user is logged in, if they aren't then redirect to the employee login page
		if( ($this->Session->check('sessionUser') == false) && ($this->Session->check('sessionAdmin') == false) ) { 
		  $this->flash('You must login to view this page.  ...redirecting...', '/users/login');
		} //end if

	  } //end if
	} //end function __validateLoginStatus


	/**********
	  Function Name: index
	  Description: This is the index function; which is the initial function of the controller.
				   For users: This index shows only incidents pending and accepted submitted by the logged in user.
				   For admins: This index shows incidents pending (all) and accepted by the logged in admin.
	  Last Modified: 2009/03/21 02:07 PM
	  Last Modified By: William DiStefano
	**********/
	function index() {

		/*	Read the session data and create the variables for the view (sessionAdmin and sessionUser) and create 
			the local variables $sessionAdmin and $sessionUser														*/
		$this->set('sessionAdmin', $this->Session->read('sessionAdmin'));
		$sessionAdmin = $this->Session->read('sessionAdmin');
		$this->set('sessionUser', $this->Session->read('sessionUser'));
		$sessionUser = $this->Session->read('sessionUser');

		//Check if a user, tech, or admin is logged in
		if( ($this->Session->check('sessionUser')) == true ) {
		  //USER IS LOGGED IN
		  $this->pageTitle = 'Voyager Incident Management System :: Incidents - All Open Incidents';

		  if($this->RequestHandler->isAjax() == false) {
			$this->layout = 'default_user';
		  } //end if

		  $conditions = '(status = 0 AND user_id = '. $sessionUser[0]['users']['id'] .') OR (status = 1 AND user_id = '. $sessionUser[0]['users']['id'] .')';
		  //Find the incidents from the database and paginate the results
		  $this->set('incidents', $this->paginate('Incident', $conditions));

		} else if ($sessionAdmin[0]['admins']['type'] == 2) {
		  //TECHNICIAN IS LOGGED IN
		  $this->pageTitle = 'Voyager Incident Management System :: Incidents - Pending & Your Incidents';

		  if($this->RequestHandler->isAjax() == false) {
			$this->layout = 'default_technician';
		  } //end if

		  $conditions = '(status = 0) OR (status = 1 AND admin_id = '. $sessionAdmin[0]['admins']['id'] .')';
		  //Find the incidents from the database and paginate the results
		  $this->set('incidents', $this->paginate('Incident', $conditions));

		} else if ($sessionAdmin[0]['admins']['type'] == 1){
		  //ADMINISTRATOR IS LOGGED IN
		  $this->pageTitle = 'Voyager Incident Management System :: Incidents - Pending & Your Incidents';
		  $conditions = '(status = 0) OR (status = 1 AND admin_id = '. $sessionAdmin[0]['admins']['id'] .')';
		  //Find the incidents from the database and paginate the results
		  $this->set('incidents', $this->paginate('Incident', $conditions));

		}//end if


		//Find all administrators, users, and categories; then store the data in variables for the view
		$this->set('admins', $this->Incident->findAllAdministrators());
		$this->set('users', $this->Incident->findAllUsers());
		$this->set('categories', $this->Incident->findAllCategories());

	} //end function index


	/**********
	  Function Name: index2
	  Description: This is the index2 function
				   For users: This index shows nothing for users, they can't view this page
				   For admins: This index shows incidents all accepted incidents regardless of who they are assigned to.
	  Last Modified: 2009/03/21 02:15 PM
	  Last Modified By: William DiStefano
	**********/
	function index2() {

		/*	Read the session data and create the variables for the view (sessionAdmin and sessionUser) and create 
			the local variables $sessionAdmin and $sessionUser														*/
		$this->set('sessionAdmin', $this->Session->read('sessionAdmin'));
		$sessionAdmin = $this->Session->read('sessionAdmin');
		$this->set('sessionUser', $this->Session->read('sessionUser'));
		$sessionUser = $this->Session->read('sessionUser');

		//Check if a user, technician, or admin is logged in
		if( ($this->Session->check('sessionUser')) == true) {
		  //USER IS LOGGED IN

		  if($this->RequestHandler->isAjax() == false) {
			$this->layout = 'default_user';
		  } //end if

		} else if($sessionAdmin[0]['admins']['type'] == 2) {
		  //TECHNICIAN IS LOGGED IN
		  $this->pageTitle = 'Voyager Incident Management System :: Incidents - All Assigned Incidents';

		  if($this->RequestHandler->isAjax() == false) {
			$this->layout = 'default_technician';
		  } //end if

		  $conditions = '(status = 1)';

		  //Find the incidents from the database and paginate the results
		  $this->set('incidents', $this->paginate('Incident', $conditions));

		} else if($sessionAdmin[0]['admins']['type'] == 1) {
		  //ADMINISTRATOR IS LOGGED IN
		  $this->pageTitle = 'Voyager Incident Management System :: Incidents - All Assigned Incidents';

		  $conditions = '(status = 1)';

		  //Find the incidents from the database and paginate the results
		  $this->set('incidents', $this->paginate('Incident', $conditions));

		}//end if

		//Find all admins, users, and categories; then store the data in variables for the view
		$this->set('admins', $this->Incident->findAllAdministrators());
		$this->set('users', $this->Incident->findAllUsers());
		$this->set('categories', $this->Incident->findAllCategories());

	} //end function index2


	/**********
	  Function Name: index3
	  Description: This is the index3 function
				   For users: This index shows all closed incidents that the logged in user submitted
				   For admins: This index shows all closed incidents
	  Last Modified: 2009/03/21 02:21 PM
	  Last Modified By: William DiStefano
	**********/
	function index3() {

		/*	Read the session data and create the variables for the view (sessionAdmin and sessionUser) and create 
			the local variables $sessionAdmin and $sessionUser														*/
		$this->set('sessionAdmin', $this->Session->read('sessionAdmin'));
		$sessionAdmin = $this->Session->read('sessionAdmin');
		$this->set('sessionUser', $this->Session->read('sessionUser'));
		$sessionUser = $this->Session->read('sessionUser');

		//Check if a user, admin, or tech is logged in
		if( ($this->Session->check('sessionUser')) == true) {
		  //USER IS LOGGED IN
		  $this->pageTitle = 'Voyager Incident Management System :: Incidents - All Closed Incidents';

		  if($this->RequestHandler->isAjax() == false) {
			$this->layout = 'default_user';
		  } //end if

		  $conditions = '(status != 0 AND status != 1) AND (user_id = '. $sessionUser[0]['users']['id'] .')';

		  //Find the incidents from the database and paginate the results
		  $this->set('incidents', $this->paginate('Incident', $conditions));

		} else if($sessionAdmin[0]['admins']['type'] == 2) {
		  //TECHNICIAN IS LOGGED IN
		  $this->pageTitle = 'Voyager Incident Management System :: Incidents - All Closed Incidents';

		  if($this->RequestHandler->isAjax() == false) {
			$this->layout = 'default_technician';
		  } //end if

		  $conditions = '(status != 0 AND status != 1)';

		  //Find the incidents from the database and paginate the results
		  $this->set('incidents', $this->paginate('Incident', $conditions));

		} else if($sessionAdmin[0]['admins']['type'] == 1) {
		  //ADMINISTRATOR IS LOGGED IN
		  $this->pageTitle = 'Voyager Incident Management System :: Incidents - All Closed Incidents';

		  $conditions = '(status != 0 AND status != 1)';

		  //Find the incidents from the database and paginate the results
		  $this->set('incidents', $this->paginate('Incident', $conditions));

		}//end if

		//Find all admins, users, and categories; then store in variables for the view
		$this->set('admins', $this->Incident->findAllAdministrators());
		$this->set('users', $this->Incident->findAllUsers());
		$this->set('categories', $this->Incident->findAllCategories());

	} //end function index3


	/**********
	  Function Name: reports
	  Description: This is the reports function; which will display all available reports
	  Last Modified: 2009/03/05 03:08 PM
	  Last Modified By: William DiStefano
	**********/
	function reports() {
		$this->pageTitle = 'Voyager Incident Management System :: Reports';

		//Read the session data and create the variables for the view (sessionAdmin and sessionUser)
		$this->set('sessionAdmin', $this->Session->read('sessionAdmin'));
		$this->set('sessionUser', $this->Session->read('sessionUser'));

	} //end function reports


	/**********
	  Function Name: view
	  Description: This is the view function, it will display all information for a particular incident and associated worklogs
	  Last Modified: 2009/03/09 03:20 PM
	  Last Modified By: William DiStefano
	**********/
	function view($id) {
		$this->pageTitle = 'Voyager Incident Management System :: Incidents :: View - ID: ' . $id;

		/*	Read the session data and create the variables for the view (sessionAdmin and sessionUser) and create 
			the local variable $sessionAdmin																		*/
		$this->set('sessionAdmin', $this->Session->read('sessionAdmin'));
		$sessionAdmin = $this->Session->read('sessionAdmin');
		$this->set('sessionUser', $this->Session->read('sessionUser'));

		//Check if a user or technician is logged in and use the appropriate layout
		if( ($this->Session->check('sessionUser')) == true ) {
			$this->layout = 'default_user';
		} else if($sessionAdmin[0]['admins']['type'] == 2) {
			$this->layout = 'default_technician';
		} //end if

		//Find the article by ID $id and set to the variable selectedArticle
		$this->set('selectedIncident', $this->Incident->retrieveIncident($id));

		//Find all administrators from the database and store in the variable admins
		$this->set('admins', $this->Incident->findAllAdministrators());
		//Find all users from the database and store in the variable users
		$this->set('users', $this->Incident->findAllUsers());
		//Find all categories from the database and store in the variable categories
		$this->set('categories', $this->Incident->findAllCategories());
		//Find all computers from the database and store in the variable computers
		$this->set('computers', $this->Incident->findAllComputers());
		//Find all locations from the database and store in the variable locations
		$this->set('locations', $this->Incident->findAllLocations());
		//Find all associated worklogs from the database and store in the variable worklogs
		$this->set('worklogs', $this->Incident->retrieveWorklogs($id));

	} // end view function
	/////////


	/**********
	  Function Name: edit
	  Description: This is the edit function, it will display and allow modifications to an incident/worklogs
	  Last Modified: 2009/03/09 03:20 PM
	  Last Modified By: William DiStefano
	**********/
	function edit($id = null) {
		$this->Incident->id = $id;
		$this->pageTitle = 'Voyager Incident Management System :: Incidents :: Modify - ID: ' . $id;

		/*	Read the session data and create the variables for the view (sessionAdmin and sessionUser) and create 
			the local variable $sessionAdmin																		*/
		$this->set('sessionAdmin', $this->Session->read('sessionAdmin'));
		$sessionAdmin = $this->Session->read('sessionAdmin');
		$this->set('sessionUser', $this->Session->read('sessionUser'));

		//Check if a user or technician is logged in and use the appropriate layout
		if( ($this->Session->check('sessionUser')) == true ) {
			$this->layout = 'default_user';
		} else if ($sessionAdmin[0]['admins']['type'] == 2) {
			$this->layout = 'default_technician';
		} //end if

		if(!empty($id)) {
		//Find the article by ID $id and set to the variable selectedArticle
		$this->set('selectedIncident', $this->Incident->retrieveIncident($id));

		//Find all administrators from the database and store in the variable admins
		$this->set('admins', $this->Incident->findAllAdministrators());
		//Find all users from the database and store in the variable users
		$this->set('users', $this->Incident->findAllUsers());
		//Find all categories from the database and store in the variable categories
		$this->set('categories', $this->Incident->findAllCategories());
		//Find all computers from the database and store in the variable computers
		$this->set('computers', $this->Incident->findAllComputers());
		//Find all locations from the database and store in the variable locations
		$this->set('locations', $this->Incident->findAllLocations());
		//Find all associated worklogs from the database and store in the variable worklogs
		$this->set('worklogs', $this->Incident->retrieveWorklogs($id));
		} //end if

		if (!empty($this->data)) {
			//pr($this->data);	//This will display all of the submitted array data on screen (useful for debugging)

			//You need to set the following in the model to validate the fields because we are avoiding CakePHP's ORM
			$this->Incident->set($this->data['Incident']);
			if($this->Incident->validates()){
				$this->Incident->updateIncident($this->data['Incident'], $sessionAdmin);
				$this->flash('The incident has been updated.', '/incidents/'); //FLASH MESSAGE AND REDIRECT
			} else {
				$this->set('selectedIncident', $this->Incident->retrieveIncident($this->data['Incident']['id']));
				$this->set('worklogs', $this->Incident->retrieveWorklogs($this->data['Incident']['id']));
				//Find all administrators from the database and store in the variable admins
				$this->set('admins', $this->Incident->findAllAdministrators());
				//Find all users from the database and store in the variable users
				$this->set('users', $this->Incident->findAllUsers());
				//Find all categories from the database and store in the variable categories
				$this->set('categories', $this->Incident->findAllCategories());
				//Find all computers from the database and store in the variable computers
				$this->set('computers', $this->Incident->findAllComputers());
				//Find all locations from the database and store in the variable locations
				$this->set('locations', $this->Incident->findAllLocations());
				$this->set('errorMessage', 'There was a problem, please check all fields for correct entry.');
				$this->render(); //render the page to display the error messages
			} //end if
		} //end if

	} // end edit function
	/////////


	/**********
	  Function Name: deleteWorklog
	  Description: This is the deleteWorklog function, it will delete a row from the database given an ID.
	  Last Modified: 2009/03/18 03:49 PM
	  Last Modified By: William DiStefano
	**********/
	function deleteWorklog($id) {

			//Check the type from the database to make sure the admin is an administrator and not a technician
			if($sessionAdmin = $this -> Session -> read('sessionAdmin')) {
				//Since the sessionAdmin variable exists in the session, find the admin's type
				$type = $sessionAdmin[0]['admins']['type'];
			} else {
				//The session data for the admin doesn't exist, this means that the admin is not logged in
				$this->flash('You do not have permission to do this.', '/admins/'); //FLASH MESSAGE AND REDIRECT
			}

			//Make sure the admin has permission to modify article data, a type of 1 is an administrator, a type of 2 is a technician
			if($type == 1) {
				$this->Incident->deleteWorklog($id); //DELETE THE WORKLOG WITH ID $id
				$this->flash('The worklog with id: '.$id.' has been deleted.', '/incidents/'); //FLASH MESSAGE AND REDIRECT
			} else {
				$this->flash('You do not have permission to do this.', '/incidents/'); //FLASH MESSAGE AND REDIRECT
			} //end if

	} //end deleteWorklog function
	/////////


	/**********
	  Function Name: addWorklog
	  Description: This is the addWorklog function, it allows an admin, user, or technician to add a new worklog to the DB
	  Last Modified: 2009/03/18 03:51 PM
	  Last Modified By: William DiStefano
	**********/
	function addWorklog() {

		if (!empty($this->data)) {
			//pr($this->data);	//This will display all of the submitted array data on screen (useful for debugging)

			if(!empty($this->data['Worklog']['content'])){
				$this->Incident->addWorklog($this->data['Worklog']);
				$this->flash('You have successfully added to the worklog', '/incidents/'); //FLASH MESSAGE AND REDIRECT
			} else {
				$this->flash('You must enter something to successfully submit a worklog entry.', '/incidents/edit/' . $this->data['Worklog']['incident_id']); //FLASH MESSAGE AND REDIRECT
			} //end if
		} //end if
	} //end function addWorklog
	/////////


	/**********
	  Function Name: add
	  Description: This is the add function, it allows an administrator/tech/user to add a new incident to the database
	  Last Modified: 2009/03/20 02:53 PM
	  Last Modified By: William DiStefano
	**********/
	function add() {
		$this->pageTitle = 'Voyager Incident Management System :: Incidents :: Add';

		//Read the session data and create the variables for the view (sessionAdmin and sessionUser)
		$this->set('sessionAdmin', $this->Session->read('sessionAdmin'));
		$this->set('sessionUser', $this->Session->read('sessionUser'));

		//Find all administrators from the database and store in the variable admins
		$this->set('admins', $this->Incident->findAllAdministrators());
		//Find all users from the database and store in the variable users
		$this->set('users', $this->Incident->findAllUsers());
		//Find all categories from the database and store in the variable categories
		$this->set('categories', $this->Incident->findAllCategories());

		if (!empty($this->data)) {
			//pr($this->data);	//This will display all of the submitted array data on screen (useful for debugging)

			//You need to set the following in the model to validate the fields because we are avoiding CakePHP's ORM
			$this->Incident->set($this->data['Incident']);
			if($this->Incident->validates()){
				$this->Incident->addIncident($this->data['Incident']);
				$this->flash('The new incident has been submitted.', '/incidents/'); //FLASH MESSAGE AND REDIRECT
			} else {
				$this->set('errorMessage', 'There was a problem, please check all fields for correct entry.');
				$this->render(); //render the page to display the error messages
			} //end if
		} //end if
	} //end function add
	/////////


	/**********
	  Function Name: search
	  Description: This is the search function, it allows an administrator/tech/user to search the incidents' description field
					For admins/techs: This will search all incidents
					For users: This will only search incidents that they submitted.
	  Last Modified: 2009/03/25 03:05 PM
	  Last Modified By: William DiStefano
	**********/
	function search() {

		/*	Read the session data and create the variables for the view (sessionAdmin and sessionUser) and create 
			the local variables $sessionAdmin and $sessionUser														*/
		$this->set('sessionAdmin', $this->Session->read('sessionAdmin'));
		$sessionAdmin = $this->Session->read('sessionAdmin');
		$this->set('sessionUser', $this->Session->read('sessionUser'));
		$sessionUser = $this->Session->read('sessionUser');

		//Check if a user, admin, or tech is logged in
		if( ($this->Session->check('sessionUser')) == true) {
		  //USER IS LOGGED IN
		  $this->pageTitle = 'Voyager Incident Management System :: Incidents - Search';

		  if($this->RequestHandler->isAjax() == false) {
			$this->layout = 'default_user';
		  } //end if

		  //Search for the incidents from the database
		  if(!empty($this->data['Incident']['searchterm'])) {
			$this->set('incidents', $this->Incident->search($this->data['Incident']['searchterm'], $this->data['Incident']['searchtype'], 2, 
				$sessionUser[0]['users']['id']));
		  } //end if

		} else if($sessionAdmin[0]['admins']['type'] == 2) {
		  //TECHNICIAN IS LOGGED IN
		  $this->pageTitle = 'Voyager Incident Management System :: Incidents - Search';

		  if($this->RequestHandler->isAjax() == false) {
			$this->layout = 'default_technician';
		  } //end if

		  //Search for the incidents from the database
		  if(!empty($this->data['Incident']['searchterm'])) {
			$this->set('incidents', $this->Incident->search($this->data['Incident']['searchterm'], $this->data['Incident']['searchtype'], 1, 
				$sessionAdmin[0]['admins']['id']));
		  } //end if

		} else if($sessionAdmin[0]['admins']['type'] == 1) {
		  //ADMINISTRATOR IS LOGGED IN
		  $this->pageTitle = 'Voyager Incident Management System :: Incidents - Search';

		  //Search for the incidents from the database
		  if(!empty($this->data['Incident']['searchterm'])) {
		  $this->set('incidents', $this->Incident->search($this->data['Incident']['searchterm'], $this->data['Incident']['searchtype'], 1, 
				$sessionAdmin[0]['admins']['id']));
		  } //end if

		}//end if

		//Find all admins, users, and categories; then store in variables for the view
		$this->set('admins', $this->Incident->findAllAdministrators());
		$this->set('users', $this->Incident->findAllUsers());
		$this->set('categories', $this->Incident->findAllCategories());

	} //end function search


	/**********
	  Function Name: rptcountincidents
	  Description: This is function will retrieve and render data for the report "Number of Incidents Per Category"
	  Last Modified: 2009/04/03 03:18 PM
	  Last Modified By: William DiStefano
	**********/
	function rptcountincidents() {

		/*	Read the session data and create the variables for the view (sessionAdmin and sessionUser) and create 
			the local variables $sessionAdmin and $sessionUser														*/
		$this->set('sessionAdmin', $this->Session->read('sessionAdmin'));
		$sessionAdmin = $this->Session->read('sessionAdmin');
		$this->set('sessionUser', $this->Session->read('sessionUser'));
		$sessionUser = $this->Session->read('sessionUser');

		$this->pageTitle = 'Voyager Incident Management System :: Reports :: Number of Incidents Per Category';
		$this->layout = 'default_noToolbar';

		if($sessionAdmin[0]['admins']['type'] == 1) {
		  //ADMINISTRATOR IS LOGGED IN

		  //Retrieve the report data
		  $this->set('reportData', $this->Incident->rptCountIncidents());

		}//end if

	} //end function rptcountincidents


	/**********
	  Function Name: rptemployeescomputers
	  Description: This is function will retrieve and render data for the report "Enabled Employees and Computers"
	  Last Modified: 2009/04/03 03:23 PM
	  Last Modified By: William DiStefano
	**********/
	function rptemployeescomputers() {

		/*	Read the session data and create the variables for the view (sessionAdmin and sessionUser) and create 
			the local variables $sessionAdmin and $sessionUser														*/
		$this->set('sessionAdmin', $this->Session->read('sessionAdmin'));
		$sessionAdmin = $this->Session->read('sessionAdmin');
		$this->set('sessionUser', $this->Session->read('sessionUser'));
		$sessionUser = $this->Session->read('sessionUser');

		$this->pageTitle = 'Voyager Incident Management System :: Reports :: Enabled Employees and Computers';
		$this->layout = 'default_noToolbar';

		if($sessionAdmin[0]['admins']['type'] == 1) {
		  //ADMINISTRATOR IS LOGGED IN

		  //Retrieve the report data
		  $this->set('reportData', $this->Incident->rptEmployeesComputers());

		}//end if

	} //end function rptemployeescomputers


	/**********
	  Function Name: rptadminscomputers
	  Description: This is function will retrieve and render data for the report "Enabled Admins/Techs and Computers"
	  Last Modified: 2009/04/05 02:15 PM
	  Last Modified By: William DiStefano
	**********/
	function rptadminscomputers() {

		/*	Read the session data and create the variables for the view (sessionAdmin and sessionUser) and create 
			the local variables $sessionAdmin and $sessionUser														*/
		$this->set('sessionAdmin', $this->Session->read('sessionAdmin'));
		$sessionAdmin = $this->Session->read('sessionAdmin');
		$this->set('sessionUser', $this->Session->read('sessionUser'));
		$sessionUser = $this->Session->read('sessionUser');

		$this->pageTitle = 'Voyager Incident Management System :: Reports :: Enabled Admins/Techs and Computers';
		$this->layout = 'default_noToolbar';

		if($sessionAdmin[0]['admins']['type'] == 1) {
		  //ADMINISTRATOR IS LOGGED IN

		  //Retrieve the report data
		  $this->set('reportData', $this->Incident->rptAdminsComputers());

		}//end if

	} //end function rptadminscomputers


	/**********
	  Function Name: rptpendingandacceptedincidents
	  Description: This is function will retrieve and render data for the report "All Unresolved Incidents"
	  Last Modified: 2009/04/03 03:23 PM
	  Last Modified By: William DiStefano
	**********/
	function rptpendingandacceptedincidents() {

		/*	Read the session data and create the variables for the view (sessionAdmin and sessionUser) and create 
			the local variables $sessionAdmin and $sessionUser														*/
		$this->set('sessionAdmin', $this->Session->read('sessionAdmin'));
		$sessionAdmin = $this->Session->read('sessionAdmin');
		$this->set('sessionUser', $this->Session->read('sessionUser'));
		$sessionUser = $this->Session->read('sessionUser');

		$this->pageTitle = 'Voyager Incident Management System :: Reports :: All Unresolved Incidents';
		$this->layout = 'default_noToolbar';

		if($sessionAdmin[0]['admins']['type'] == 1) {
		  //ADMINISTRATOR IS LOGGED IN

		  //Retrieve the report data
		  $this->set('reportData', $this->Incident->rptPendingAndAcceptedIncidents());

		}//end if

	} //end function rptpendingandacceptedincidents


	/**********
	  Function Name: rptcountincidentsmonthly
	  Description: This is function will retrieve and render data for the report "Number of Incidents Per Month"
	  Last Modified: 2009/04/03 03:25 PM
	  Last Modified By: William DiStefano
	**********/
	function rptcountincidentsmonthly() {

		/*	Read the session data and create the variables for the view (sessionAdmin and sessionUser) and create 
			the local variables $sessionAdmin and $sessionUser														*/
		$this->set('sessionAdmin', $this->Session->read('sessionAdmin'));
		$sessionAdmin = $this->Session->read('sessionAdmin');
		$this->set('sessionUser', $this->Session->read('sessionUser'));
		$sessionUser = $this->Session->read('sessionUser');

		$this->pageTitle = 'Voyager Incident Management System :: Reports :: Number of Incidents Per Month';
		$this->layout = 'default_noToolbar';

		if($sessionAdmin[0]['admins']['type'] == 1) {
		  //ADMINISTRATOR IS LOGGED IN

		  //Retrieve the report data
		  $this->set('reportData', $this->Incident->rptCountIncidentsMonthly());

		}//end if

	} //end function rptcountincidentsmonthly


	/**********
	  Function Name: rptcountincidentsperadmin
	  Description: This is function will retrieve and render data for the report "Number of Incidents per Admin/Tech (past 30 days)"
	  Last Modified: 2009/04/03 03:27 PM
	  Last Modified By: William DiStefano
	**********/
	function rptcountincidentsperadmin() {

		/*	Read the session data and create the variables for the view (sessionAdmin and sessionUser) and create 
			the local variables $sessionAdmin and $sessionUser														*/
		$this->set('sessionAdmin', $this->Session->read('sessionAdmin'));
		$sessionAdmin = $this->Session->read('sessionAdmin');
		$this->set('sessionUser', $this->Session->read('sessionUser'));
		$sessionUser = $this->Session->read('sessionUser');

		$this->pageTitle = 'Voyager Incident Management System :: Reports :: Completed Incidents per Admin/Tech (past 30 days)';
		$this->layout = 'default_noToolbar';

		if($sessionAdmin[0]['admins']['type'] == 1) {
		  //ADMINISTRATOR IS LOGGED IN

		  //Retrieve the report data
		  $this->set('reportData', $this->Incident->rptCountIncidentsPerAdmin());

		}//end if

	} //end function rptcountincidentsperadmin

} //end class

?>