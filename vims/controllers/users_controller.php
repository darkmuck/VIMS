<?php
/**********
Class Name: UsersController
Description: This class (controller) will perform all functions related to 'users' and render the views accordingly
Variables: $name, $uses, $helpers, $components, $paginate
Functions: beforeFilter, login, logout, __validateLoginStatus, index, delete, view, add, edit, disable, enable
Last Modified: 2009/03/24 01:32 PM
Last Modified By: William DiStefano
**********/
class UsersController extends AppController {
	var $name = 'Users'; //Name of this controller
	var $uses = array('User');  //The items in this array are the model classes that will be accessible from this controller
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
	  Description: This function will call the validateLogin function with the username/password and store the data in a session file if it is validated.
	  Last Modified: 2009/01/26 02:04 PM
	  Last Modified By: William DiStefano
	**********/
	function login() {
	  $this->pageTitle = 'Voyager Incident Management System :: Employee Login';
	  $this->layout = 'default_noToolbar';

	  //Before you can login as an user the admin session must be destroyed
	  if($this->Session->check('sessionAdmin') == true) {
		//Admin session data exists, destroy it before user can login
		$this->redirect('/admins/logout/');
	  } //end if

	  if(empty($this->data) == false) {
		if(($sessionUser = $this->User->validateLogin($this->data['User'])) == true) {
		  if($sessionUser[0]['users']['enabled'] == 1) {
			  $this->Session->write('sessionUser', $sessionUser); //write the user's data to a session variable $sessionUser
			  $this->flash('Welcome '. $sessionUser[0]['users']['username'] .'!  ... redirecting ...','/articles/');
		  } else {
			  $this->flash('There was a problem validating your login information.', 'login');
		  } //end if
		} else {
		  $this->flash('There was a problem validating your login information.', 'login');
		} //end if
	  } //end if
	} //end function login


	/**********
	  Function Name: logout
	  Description: This function will delete the session variable and redirect back to the login
	  Last Modified: 2009/03/23 06:31 PM
	  Last Modified By: William DiStefano
	**********/
	function logout() {
	  if(($this->Session->check('sessionUser'))) {
		  $this->flash('You are now logged out!  ...redirecting...', '/users/login');
		  $this->Session->del('sessionUser'); //delete the session variable $sessionUser
	  } else if(($this->Session->check('sessionAdmin'))) {
		  $this->flash('You are now logged out!  ...redirecting...', '/admins/login');
		  $this->Session->del('sessionAdmin'); //delete the session variable $sessionAdmin
	  } //end if
	} //end function logout



	/**********
	  Function Name: __validateLoginStatus
	  Description: This function will check to see if the $sessionUser or $sessionAdmin variable in the session exists to see if you are 
				   logged in or not.
				   If a user is logged in it will redirect to '/articles/' or if an admin is logged in it will continue to load 'index'.
	  Last Modified: 2009/03/24 01:58 PM
	  Last Modified By: William DiStefano
	**********/
	function __validateLoginStatus() {
	  if($this->action != 'login' && $this->action != 'logout') { //make sure this function wasn't called from the login or logout functions

		//check if the admin or user is logged in, if they aren't then redirect to the employee login page
		if( ($this->Session->check('sessionUser') == false) && ($this->Session->check('sessionAdmin') == false) ) { 
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
	  Last Modified: 2009/03/02 12:52 PM
	  Last Modified By: William DiStefano
	**********/
	function index() {
		$this->pageTitle = 'Voyager Incident Management System :: Users';

		//Read the session data and store the admin information in the variable 'sessionAdmin for the view
		$this->set('sessionAdmin', $this->Session->read('sessionAdmin'));

		//Find all locations from the database and store in the variable locations
		$this->set('locations', $this->User->retrieveLocations());

		//Find all users from the database and paginate the results
		$this->set('users', $this->paginate('User'));
	} //end function index


	/**********
	  Function Name: delete
	  Description: This is the delete function, it will delete a row from the database given an ID.
	  Last Modified: 2009/03/02 12:52 PM
	  Last Modified By: William DiStefano
	**********/
	function delete($id) {

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
				$this->User->deleteUser($id); //DELETE THE USER WITH ID $id
				$this->flash('The Admin with id: '.$id.' has been deleted.', '/users/'); //FLASH MESSAGE AND REDIRECT
			} else {
				$this->flash('You do not have permission to do this.', '/incidents/'); //FLASH MESSAGE AND REDIRECT
			} //end if

	} //end delete function
	/////////


	/**********
	  Function Name: view
	  Description: This is the view function, it will display all information for a particular user
	  Last Modified: 2009/03/02 12:52 PM
	  Last Modified By: William DiStefano
	**********/
	function view($id) {
		$this->pageTitle = 'Voyager Incident Management System :: Users :: View - ID: ' . $id;

		//Read the session data and store the admin information in the variable 'sessionAdmin for the view
		$this->set('sessionAdmin', $this->Session->read('sessionAdmin'));
		$sessionAdmin = $this->Session->read('sessionAdmin');

		//Find the user by ID $id and set to the variable selectedUser
		$this->set('selectedUser', $this->User->retrieveUser($id));

		//Find all computers from the database and store in the variable computers
		$this->set('computers', $this->User->retrieveComputers());

		//Find all locations from the database and store in the variable locations
		$this->set('locations', $this->User->retrieveLocations());

		//Check which session type exists, User or Admin (check if they are a technician) and render the correct layout
		if( ($this->Session->check('sessionUser')) == true) {
		  $this->layout = 'default_user';
		} else if ($sessionAdmin[0]['admins']['type'] == 2) {
		  $this->layout = 'default_technician';
		} //end if

	} // end view function
	/////////

	/**********
	  Function Name: add
	  Description: This is the add function, it allows an administrator to add a new user to the database
	  Last Modified: 2009/03/02 12:52 PM
	  Last Modified By: William DiStefano
	**********/
	function add() {
		$this->pageTitle = 'Voyager Incident Management System :: Users :: Add';

		//Read the session data and store the admin information in the variable 'sessionAdmin for the view
		$this->set('sessionAdmin', $this->Session->read('sessionAdmin'));

		//Find all computers from the database and store in the variable computers
		$this->set('computers', $this->User->retrieveComputers());

		//Find all locations from the database and store in the variable locations
		$this->set('locations', $this->User->retrieveLocations());

		//Find all available computers from the database and store in the variable availableComputers
		$this->set('availableComputers', $this->User->retrieveAvailableComputers());

		if (!empty($this->data)) {
			//pr($this->data);	//This will display all of the submitted array data on screen (useful for debugging)

			//You need to set the following in the model to validate the fields because we are avoiding CakePHP's ORM
			$this->User->set($this->data['User']);
			if($this->User->validates()){
				//Make sure the username doesn't already exist in the database
				if($this->User->checkUserUsernameExists($this->data['User']) == true) {
					$this->set('errorMessage', 'This username already exists, please choose a different username.');
					$this->render();
				} else {
					//The username does not exist, add the new user to the databse
					$this->User->addUser($this->data['User']);
					$this->flash('The new user has been added.', '/users/'); //FLASH MESSAGE AND REDIRECT
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
	  Description: This is the edit function, it will display all information for a particular user and allow modifications
	  Last Modified: 2009/03/02 02:18 PM
	  Last Modified By: William DiStefano
	**********/
	function edit($id = null) {
		$this->User->id = $id;
		$this->pageTitle = 'Voyager Incident Management System :: Users :: Modify - ID: ' . $id;

		//Read the session data and store the admin information in the variable 'sessionAdmin for the view
		$this->set('sessionAdmin', $this->Session->read('sessionAdmin'));

		//Find the user by ID $id and set to the variable selectedUser
		$this->set('selectedUser', $this->User->retrieveUser($id));

		//Find all locations from the database and store in the variable locations
		$this->set('locations', $this->User->retrieveLocations());

		//Find all computers from the database and store in the variable computers
		$this->set('computers', $this->User->retrieveComputers());

		//Find all available computers from the database and store in the variable availableComputers
		$this->set('availableComputers', $this->User->retrieveAvailableComputers());

		if (empty($this->data)) {
			$this->data = $this->User->read();
		} else {
			//pr($this->data);	//This will display all of the submitted array data on screen (useful for debugging)

			//You need to set the following in the model to validate the fields because we are avoiding CakePHP's ORM
			$this->User->set($this->data['User']);
			if($this->User->validates()){
				$this->User->updateUser($this->data['User']);
				$this->flash('The user has been updated.', '/users/'); //FLASH MESSAGE AND REDIRECT
			} else {
				$this->set('errorMessage', 'There was a problem, please check all fields for correct entry.');
				$this->render(); //render the page to display the error messages
			} //end if
		} //end if
	} //end edit function
	/////////

	/**********
	  Function Name: disable
	  Description: This is the disable function, it will disable a user
	  Last Modified: 2009/03/16 03:00 PM
	  Last Modified By: William DiStefano
	**********/
	function disable($id) {

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
				$this->User->disableUser($id); //DISABLE THE USER WITH ID $id
				$this->flash('The Admin with id: '.$id.' has been disabled.', '/users/'); //FLASH MESSAGE AND REDIRECT
			} else {
				$this->flash('You do not have permission to do this.', '/incidents/'); //FLASH MESSAGE AND REDIRECT
			} //end if

	} //end disable function
	/////////

	/**********
	  Function Name: enable
	  Description: This is the enable function, it will enable a user
	  Last Modified: 2009/03/16 03:00 PM
	  Last Modified By: William DiStefano
	**********/
	function enable($id) {

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
				$this->User->enableUser($id); //ENABLE THE USER WITH ID $id
				$this->flash('The Admin with id: '.$id.' has been enabled.', '/users/'); //FLASH MESSAGE AND REDIRECT
			} else {
				$this->flash('You do not have permission to do this.', '/incidents/'); //FLASH MESSAGE AND REDIRECT
			} //end if

	} //end enable function
	/////////

} //end class

?>