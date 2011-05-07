<?php

/**********
Class Name: LocationsController
Description: This class (controller) will perform all functions related to 'locations' and render the views accordingly
Variables: $name, $uses, $helpers, $components
Functions: beforeFilter, __validateLoginStatus, index, delete, add
Last Modified: 2009/03/24 01:36 PM
Last Modified By: William DiStefano
**********/
class LocationsController extends AppController {
	var $name = 'Locations';//Name of this controller
	var $uses = array('Location'); //The items in this array are the model classes that will be accessible from this controller
	var $helpers = array('Html', 'Form', 'Ajax', 'Paginator', 'Javascript');
	var $components = array('RequestHandler'); //request handler is needed to have CakePHP Ajax support


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
				   If a user is logged in it will redirect them to '/articles' or if an admin is logged in it will continue to load 'index'
	  Last Modified: 2009/03/24 01:57 PM
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
	  Last Modified: 2009/03/05 10:52 AM
	  Last Modified By: William DiStefano
	**********/
	function index($id = null) {
		$this->Location->id = $id;
		$this->pageTitle = 'Voyager Incident Management System :: Locations';

		//Read the session data and store the admin information in the variable 'sessionAdmin for the view
		$this->set('sessionAdmin', $this->Session->read('sessionAdmin'));

		//Find all locations from the database and store in the variable locations
		$this->set('locations', $this->Location->findAllLocations());

		//Find all users from the database and store in the variable users
		$this->set('users', $this->Location->findAllUsers());

		if (empty($this->data)) {
			$this->data = $this->Location->read();
		} else {
			//pr($this->data);	//This will display all of the submitted array data on screen (useful for debugging)

			//You need to set the following in the model to validate the fields because we are avoiding CakePHP's ORM
			$this->Location->set($this->data['Location']);
			if($this->Location->validates()){
				$this->Location->updateLocation($this->data['Location']);
				$this->flash('The location has been updated.', '/locations/'); //FLASH MESSAGE AND REDIRECT
			} else {
				$this->set('errorMessage', 'There was a problem, please check all fields for correct entry.');
				$this->render(); //render the page to display the error messages
			} //end if
		} //end if
	} //end function index


	/**********
	  Function Name: delete
	  Description: This is the delete function, it will delete a row from the database given an ID.
	  Last Modified: 2009/03/05 10:49 AM
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

			//Make sure the admin has permission to modify location data, a type of 1 is an administrator, a type of 2 is a technician
			if($type == 1) {
				$this->Location->deleteLocation($id); //DELETE THE LOCATION WITH ID $id
				$this->flash('The location with id: '.$id.' has been deleted.', '/locations/'); //FLASH MESSAGE AND REDIRECT
			} else {
				$this->flash('You do not have permission to do this.', '/incidents/'); //FLASH MESSAGE AND REDIRECT
			} //end if

	} //end delete function
	/////////


	/**********
	  Function Name: add
	  Description: This is the add function, it allows an administrator to add a new location to the database
	  Last Modified: 2009/03/05 10:50 AM
	  Last Modified By: William DiStefano
	**********/
	function add() {

		if (!empty($this->data)) {
			//pr($this->data);	//This will display all of the submitted array data on screen (useful for debugging)

			//You need to set the following in the model to validate the fields because we are avoiding CakePHP's ORM
			$this->Location->set($this->data['Location']);
			if($this->Location->validates()){
				$this->Location->addLocation($this->data['Location']);
				$this->flash('The new location has been added.', '/locations/'); //FLASH MESSAGE AND REDIRECT
			} else {
				$this->set('errorMessage', 'There was a problem, you must enter the name of the location you are adding.');
				$this->flash('There was a problem, you must enter the name the location you are adding.', '/locations/'); //FLASH MESSAGE AND REDIRECT
			} //end if
		} //end if

		$this->redirect('/locations/'); //REDIRECT

	} //end function add
	/////////


} //end class

?>