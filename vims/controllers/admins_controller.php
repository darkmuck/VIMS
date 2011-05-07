<?php

/**********
Class Name: AdminsController
Description: This class (controller) will perform all functions related to 'admins' and render the views accordingly
Variables: $name, $uses, $helpers, $components, $paginate
Functions: beforeFilter, __validateLoginStatus, login, index, delete, view, add, edit, disable, enable
Last Modified: 2009/03/24 01:50 PM
Last Modified By: William DiStefano
**********/
class AdminsController extends AppController {
	var $name = 'Admins';//Name of this controller
	var $uses = array('Admin');//The items in this array are the model classes that will be accessible from this controller
	var $helpers = array('Html', 'Form', 'Ajax', 'Paginator', 'Javascript');
	var $components = array('RequestHandler'); //request handler is needed to have CakePHP Ajax support
	//paginate options are set here for the entire controller where pagination is used
	var $paginate = array(		//Paginate the results and order
		'limit' => 10			//Limit each page to this many results
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
	  Function Name: login
	  Description: This function will call the Admin model's validateLogin function with the username/password and then store the 
					data in a session file if it is validated.
	  Last Modified: 2009/03/24 2:01 PM
	  Last Modified By: William DiStefano
	**********/
	function login() {
	  $this->pageTitle = 'Voyager Incident Management System :: Administrator Login';
	  $this->layout = 'default_noToolbar';

	  //Before you can login as an admin the user session must be destroyed
	  if($this->Session->check('sessionUser') == true) {
		//User session data exists, destroy it before admin can login
		$this->redirect('/users/logout/');
	  } //end if

	  if(empty($this->data) == false) {

		if(($sessionAdmin = $this->Admin->validateLogin($this->data['Admin'])) == true) {
		  if($sessionAdmin[0]['admins']['enabled'] == 1) {
			  //The admin is enabled, continue w/ storing info in the session and redirecting
			  $this->Session->write('sessionAdmin', $sessionAdmin); //write the admin's data to a session variable $sessionAdmin

			  //Check if the admin is an administrator or technician and redirect appropriately
			  if($sessionAdmin['0']['admins']['type'] == 1) {
				$this->flash('Welcome '. $sessionAdmin[0]['admins']['username'] .'!  ... redirecting ...','/incidents/');
			  } else {
				$this->flash('Welcome '. $sessionAdmin[0]['admins']['username'] .'!  ... redirecting ...','/incidents/');
			  } //end if
		  } else {
			  $this->flash('Your account is disabled, please contact an administrator.', 'login');
		  } //end if

		} else {
		  $this->flash('There was a problem validating your login information.', 'login');

		} //end if

	  } //end if

	} //end function login


	
	/**********
	  Function Name: __validateLoginStatus
	  Description: This function will check to see if the $sessionAdmin variable in the session exists to see if you are logged in or not.
				   If you are logged in it will redirect to '/articles/' if you are a user or continue to load 'index' if you are an admin.
	  Last Modified: 2009/03/24 01:54 PM
	  Last Modified By: William DiStefano
	**********/
	function __validateLoginStatus() {
	  if($this->action != 'login' && $this->action != 'logout') { //make sure this function wasn't called from the login or logout functions
		if($this->Session->check('sessionAdmin') == false) { //check if the $sessionAdmin session variable exists, if it doesn't exist then redirect to the login page
		  $this->flash('You must login to view this page.  ...redirecting...', 'login');
		} //end if

		//Check if a user is logged in, they must be redirected to the incidents component after logging in
		if( ($this->Session->check('sessionUser') == true) ){
		  $this->redirect('/articles/');
		} //end if

	  } //end if
	} //end function __validateLoginStatus


	/**********
	  Function Name: index
	  Description: This is the index function; which is the initial function of the controller.
				   This function will display a list of the current administartors from the database.
	  Last Modified: 2009/03/24 02:04 PM
	  Last Modified By: William DiStefano
	**********/
	function index() {
	  $this->pageTitle = 'Voyager Incident Management System :: Administrators';

	  //Read the session data and create the variable for the view (sessionAdmin)
	  $this->set('sessionAdmin', $this->Session->read('sessionAdmin'));

	  //Find all users from the database and paginate the results
	  $this->set('admins', $this->paginate('Admin'));

	} //end function index


	/**********
	  Function Name: delete
	  Description: This is the delete function, it will delete a row from the database given an ID.
	  Last Modified: 2009/01/26 07:13 PM
	  Last Modified By: William DiStefano
	**********/
	function delete($id) {

			//Check the type from the database to make sure the admin is an administrator and not a technician
			if($sessionAdmin = $this -> Session -> read('sessionAdmin')) {
				//Since the sessionAdmin variable exists in the session, store the admin's type in $type
				$type = $sessionAdmin[0]['admins']['type'];
			} else {
				//The session data for the admin doesn't exist, this means that the admin is not logged in
				$this->flash('You do not have permission to do this.', '/admins/'); //FLASH MESSAGE AND REDIRECT
			}

			//Make sure the admin has permission to modify other administrator data, a type of 1 is an administrator, a type of 2 is a technician
			if($type == 1) {
				$this->Admin->deleteAdmin($id); //DELETE THE ADMIN WITH ID $id
				$this->flash('The Admin with id: '.$id.' has been deleted.', '/admins/'); //FLASH MESSAGE AND REDIRECT
			} else {
				$this->flash('You do not have permission to do this.', '/articles/'); //FLASH MESSAGE AND REDIRECT
			} //end if

	} //end function delete
	/////////

	/**********
	  Function Name: view
	  Description: This is the view function, it will display all information for an administrator (specified by $id)
	  Last Modified: 2009/03/24 02:03 PM
	  Last Modified By: William DiStefano
	**********/
	function view($id) {
		$this->pageTitle = 'Voyager Incident Management System :: Administrators :: View - ID: ' . $id;

		//Read the session data and create the variable for the view (sessionAdmin)
		$this->set('sessionAdmin', $this->Session->read('sessionAdmin'));
		$sessionAdmin = $this->Session->read('sessionAdmin');

		//Find the user by ID $id and set to the variable selectedAdmin
		$this->set('selectedAdmin', $this->Admin->retrieveAdmin($id));

		//Find all computers from the database and store in the variable computers
		$this->set('computers', $this->Admin->retrieveComputers());

		//Check which session type exists, User or Admin (check if they are a technician) and render the correct layout
		if( ($this->Session->check('sessionUser')) == true) {
		  $this->layout = 'default_user';
		} else if ($sessionAdmin[0]['admins']['type'] == 2) {
		  $this->layout = 'default_technician';
		} //end if

	} // end function view
	/////////


	/**********
	  Function Name: add
	  Description: This is the add function, it allows an administrator to add a new administrator or technician
	  Last Modified: 2009/03/24 02:04 PM
	  Last Modified By: William DiStefano
	**********/
	function add() {
		$this->pageTitle = 'Voyager Incident Management System :: Administrators :: Add';

		//Read the session data and create the variable for the view (sessionAdmin)
		$this->set('sessionAdmin', $this->Session->read('sessionAdmin'));

		//Find all computers from the database and store in the variable computers
		$this->set('computers', $this->Admin->retrieveComputers());

		//Find all available computers from the database and store in the variable availableComputers
		$this->set('availableComputers', $this->Admin->retrieveAvailableComputers());

		if (!empty($this->data)) {
			//pr($this->data);	//This will display all of the submitted array data on screen (useful for debugging)

			//You need to set the following in the model to validate the fields because we are avoiding CakePHP's ORM
			$this->Admin->set($this->data['Admin']);
			if($this->Admin->validates()){
				//Make sure the username doesn't already exist in the database
				if($this->Admin->checkAdminUsernameExists($this->data['Admin']) == true) {
					$this->set('errorMessage', 'This username already exists, please choose a different username.');
					$this->render(); //render the page to display the error messages
				} else {
					//The username does not exist, add the new administrator to the databse
					$this->Admin->addAdministrator($this->data['Admin']);
					$this->flash('The new administrator/technician has been added.', '/admins/'); //FLASH MESSAGE AND REDIRECT
				} //end if
			} else {
				$this->set('errorMessage', 'There was a problem, please check all fields for correct entry.');
				$this->render(); //render the page to display the error messages
			} //end if
		} //end if
	} //end function add
	/////////

	/**********
	  Function Name: edit
	  Description: This is the edit function, it will display all information for a particular administrator (specified by $id) and 
				   allow modifications.
	  Last Modified: 2009/03/01 05:36 PM
	  Last Modified By: William DiStefano
	**********/
	function edit($id = null) {
		$this->Admin->id = $id;
		$this->pageTitle = 'Voyager Incident Management System :: Administrators :: Modify - ID: ' . $id;

		//Read the session data and create the variable for the view (sessionAdmin)
		$this->set('sessionAdmin', $this->Session->read('sessionAdmin'));

		//Find the user by ID $id and set to the variable selectedAdmin
		$this->set('selectedAdmin', $this->Admin->retrieveAdmin($id));

		//Find all computers from the database and store in the variable computers
		$this->set('computers', $this->Admin->retrieveComputers());

		//Find all available computers from the database and store in the variable availableComputers
		$this->set('availableComputers', $this->Admin->retrieveAvailableComputers());

		if (empty($this->data)) {
			$this->data = $this->Admin->read();
		} else {
			//pr($this->data);	//This will display all of the submitted array data on screen (useful for debugging)

			//You need to set the following in the model to validate the fields because we are avoiding CakePHP's ORM
			$this->Admin->set($this->data['Admin']);
			if($this->Admin->validates()){
				//The username does not exist, update the administrator to the databse
				$this->Admin->updateAdministrator($this->data['Admin']);
				$this->flash('The administrator/technician has been updated.', '/admins/'); //FLASH MESSAGE AND REDIRECT
			} else {
				$this->set('errorMessage', 'There was a problem, please check all fields for correct entry.');
				$this->render();  //render the page to display the error messages
			} //end if
		} //end if
	} //end function edit
	/////////

	/**********
	  Function Name: disable
	  Description: This is the disable function, it will allow disabling of an administrator (specified by $id).
	  Last Modified: 2009/03/16 02:23 PM
	  Last Modified By: William DiStefano
	**********/
	function disable($id = null) {
		$this->Admin->id = $id;

			//Check the type from the database to make sure the admin is an administrator and not a technician
			if($sessionAdmin = $this -> Session -> read('sessionAdmin')) {
				//Since the sessionAdmin variable exists in the session, find the admin's type
				$type = $sessionAdmin[0]['admins']['type'];
			} else {
				//The session data for the admin doesn't exist, this means that the admin is not logged in
				$this->flash('You do not have permission to do this.', '/admins/'); //FLASH MESSAGE AND REDIRECT
			}

			//Make sure the admin has permission to modify other administrator data, a type of 1 is an administrator, a type of 2 is a technician
			if($type == 1) {
				$this->Admin->disableAdministrator($id);
				$this->flash('The administrator/technician has been disabled.', '/admins/'); //FLASH MESSAGE AND REDIRECT
			} else {
				$this->flash('You do not have permission to do this.', '/incidents/'); //FLASH MESSAGE AND REDIRECT
			} //end if
	} //end function disable
	/////////

	/**********
	  Function Name: enable
	  Description: This is the enable function, it will allow enabling of an administrator (specified by $id).
	  Last Modified: 2009/03/16 02:23 PM
	  Last Modified By: William DiStefano
	**********/
	function enable($id = null) {
		$this->Admin->id = $id;

			//Check the type from the database to make sure the admin is an administrator and not a technician
			if($sessionAdmin = $this -> Session -> read('sessionAdmin')) {
				//Since the sessionAdmin variable exists in the session, find the admin's type
				$type = $sessionAdmin[0]['admins']['type'];
			} else {
				//The session data for the admin doesn't exist, this means that the admin is not logged in
				$this->flash('You do not have permission to do this.', '/admins/'); //FLASH MESSAGE AND REDIRECT
			}

			//Make sure the admin has permission to modify other administrator data, a type of 1 is an administrator, a type of 2 is a technician
			if($type == 1) {
				$this->Admin->enableAdministrator($id);
				$this->flash('The administrator/technician has been enabled.', '/admins/'); //FLASH MESSAGE AND REDIRECT
			} else {
				$this->flash('You do not have permission to do this.', '/incidents/'); //FLASH MESSAGE AND REDIRECT
			} //end if
	} //end function enable
	/////////

} //end class

?>