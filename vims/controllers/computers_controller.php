<?php

/**********
Class Name: ComputersController
Description: This class (controller) will perform all functions related to 'computers' and render the views accordingly
Variables: $name, $uses, $helpers, $components, $paginate
Functions: beforeFilter, __validateLoginStatus, index, delete, view, add, edit
Last Modified: 2009/03/24 01:39 PM
Last Modified By: William DiStefano
**********/
class ComputersController extends AppController {
	var $name = 'Computers';//Name of this controller
	var $uses = array('Computer'); //The items in this array are the model classes that will be accessible from this controller
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
	  Function Name: __validateLoginStatus
	  Description: This function will check to see if the $sessionUser or $sessionAdmin variable in the session exists to see if you are 
				   logged in or not.
				   If the user is logged in it will redirect to '/articles/' or if the admin is logged in it will continue to load 'index'.
	  Last Modified: 2009/03/24 01:55 PM
	  Last Modified By: William DiStefano
	**********/
	function __validateLoginStatus() {
	  if($this->action != 'login' && $this->action != 'logout') { //make sure this function wasn't called from the login or logout functions

		//check if the admin or user is logged in, if they aren't then redirect to the employee login page
		if( ($this->Session->check('sessionUser') == false) && ($this->Session->check('sessionAdmin') == false) ) { 
		  $this->flash('You must login to view this page.  ...redirecting...', '/users/login');
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
				   This function will display a list of computers from the database.
	  Last Modified: 2009/03/24 02:24 PM
	  Last Modified By: William DiStefano
	**********/
	function index() {
		$this->pageTitle = 'Voyager Incident Management System :: Computers';

		//Read the session data and create the variable for the view (sessionAdmin)
		$this->set('sessionAdmin', $this->Session->read('sessionAdmin'));

		//Find all admins from the database and store in the variable admins
		$this->set('admins', $this->Computer->findAllAdministrators());

		//Find all users from the database and store in the variable users
		$this->set('users', $this->Computer->findAllUsers());

		//Find all computers from the database and paginate the results
		$this->set('computers', $this->paginate('Computer'));
	} //end function index


	/**********
	  Function Name: delete
	  Description: This is the delete function, it will delete a row from the database given an ID.
	  Last Modified: 2009/03/04 01:00 PM
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

			//Make sure the admin has permission to modify article data, a type of 1 is an administrator, a type of 2 is a technician
			if($type == 1) {
				$this->Computer->deleteComputer($id); //DELETE THE COMPUTER WITH ID $id
				$this->flash('The article with id: '.$id.' has been deleted.', '/computers/'); //FLASH MESSAGE AND REDIRECT
			} else {
				$this->flash('You do not have permission to do this.', '/incidents/'); //FLASH MESSAGE AND REDIRECT
			} //end if

	} //end delete function
	/////////


	/**********
	  Function Name: view
	  Description: This is the view function, it will display all information for a particular computer
	  Last Modified: 2009/03/04 01:39 PM
	  Last Modified By: William DiStefano
	**********/
	function view($id) {
		$this->pageTitle = 'Voyager Incident Management System :: Computers :: View - ID: ' . $id;

		//Read the session data and create the variable for the view (sessionAdmin)
		$this->set('sessionAdmin', $this->Session->read('sessionAdmin'));
		$sessionAdmin = $this->Session->read('sessionAdmin');

		//Find the article by ID $id and set to the variable selectedArticle
		$this->set('selectedComputer', $this->Computer->retrieveComputer($id));

		//Find all admins from the database and store in the variable admins
		$this->set('admins', $this->Computer->findAllAdministrators());

		//Find all users from the database and store in the variable users
		$this->set('users', $this->Computer->findAllUsers());

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
	  Description: This is the add function, it allows an administrator to add a new computer to the database
	  Last Modified: 2009/03/04 02:21 PM
	  Last Modified By: William DiStefano
	**********/
	function add() {
		$this->pageTitle = 'Voyager Incident Management System :: Computers :: Add';

		//Read the session data and create the variable for the view (sessionAdmin)
		$this->set('sessionAdmin', $this->Session->read('sessionAdmin'));

		if (!empty($this->data)) {
			//pr($this->data);	//This will display all of the submitted array data on screen (useful for debugging)

			//You need to set the following in the model to validate the fields because we are avoiding CakePHP's ORM
			$this->Computer->set($this->data['Computer']);
			if($this->Computer->validates()){
				$this->Computer->addComputer($this->data['Computer']);
				$this->flash('The new computer has been added.', '/computers/'); //FLASH MESSAGE AND REDIRECT
			} else {
				$this->set('errorMessage', 'There was a problem, please check all fields for correct entry.');
				$this->render(); //render the page to display the error messages
			} //end if
		} //end if
	} //end function add
	/////////


	/**********
	  Function Name: edit
	  Description: This is the edit function, it will display all information for a particular computer and allow modifications
	  Last Modified: 2009/03/04 02:37 PM
	  Last Modified By: William DiStefano
	**********/
	function edit($id = null) {
		$this->Computer->id = $id;
		$this->pageTitle = 'Voyager Incident Management System :: Computers :: Modify - ID: ' . $id;

		//Read the session data and create the variable for the view (sessionAdmin)
		$this->set('sessionAdmin', $this->Session->read('sessionAdmin'));

		//Find the user by ID $id and set to the variable selectedAdmin
		$this->set('selectedComputer', $this->Computer->retrieveComputer($id));

		//Find all admins from the database and store in the variable admins
		$this->set('admins', $this->Computer->findAllAdministrators());

		//Find all users from the database and store in the variable users
		$this->set('users', $this->Computer->findAllUsers());

		if (empty($this->data)) {
			$this->data = $this->Computer->read();
		} else {
			//pr($this->data);	//This will display all of the submitted array data on screen (useful for debugging)

			//You need to set the following in the model to validate the fields because we are avoiding CakePHP's ORM
			$this->Computer->set($this->data['Computer']);
			if($this->Computer->validates()){
				$this->Computer->updateComputer($this->data['Computer']);
				$this->flash('The computer has been updated.', '/computers/'); //FLASH AND REDIRECT
			} else {
				$this->set('errorMessage', 'There was a problem, please check all fields for correct entry.');
				$this->render(); //render the page to display error messages
			} //end if
		} //end if
	} //end edit function
	/////////


} //end class

?>