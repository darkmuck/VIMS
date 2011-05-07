<?php

/**********
Class Name: KbentriesController
Description: This class (controller) will perform all functions related to 'kbentries' and render the views accordingly
Variables: $name, $uses, $helpers, $components, $paginate
Functions: beforeFilter, __validateLoginStatus, index, delete, add, edit, view
Last Modified: 2009/03/24 01:36 PM
Last Modified By: William DiStefano
**********/
class KbentriesController extends AppController {
	var $name = 'Kbentries';//Name of this controller
	var $uses = array('Kbentry'); //The items in this array are the model classes that will be accessible from this controller
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
				   If you are not logged in it will redirect you to '/users/login' or if you are it will continue to load 'index'.
	  Last Modified: 2009/03/24 01:40 PM
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
	  Last Modified: 2009/03/03 01:40 PM
	  Last Modified By: William DiStefano
	**********/
	function index() {
		$this->pageTitle = 'Voyager Incident Management System :: Knowledge Base';

		/*	Read the session data and create the variables for the view (sessionAdmin and sessionUser) and create 
			the local variable $sessionAdmin																		*/
		$this->set('sessionAdmin', $this->Session->read('sessionAdmin'));
		$sessionAdmin = $this->Session->read('sessionAdmin');
		$this->set('sessionUser', $this->Session->read('sessionUser'));

		//Check if a user or technician is logged in and use the appropriate layout
		if( ($this->Session->check('sessionUser')) == true ) {
		  if($this->RequestHandler->isAjax() == false) {
			$this->layout = 'default_user';
		  } //end if
		} else if($sessionAdmin[0]['admins']['type'] == 2) {
		  if($this->RequestHandler->isAjax() == false) {
			$this->layout = 'default_technician';
		  } //end if
		} //end if

		//Find all admins from the database and store in the 'admins' variable for the view
		$this->set('admins', $this->Kbentry->findAllAdministrators());

		//Find all articles from the database and paginate the results
		$this->set('kbentries', $this->paginate('Kbentry'));
	} //end function index


	/**********
	  Function Name: add
	  Description: This is the add function, it allows an administrator to add a new article to the database
	  Last Modified: 2009/03/03 03:22 PM
	  Last Modified By: William DiStefano
	**********/
	function add() {
		$this->pageTitle = 'Voyager Incident Management System :: Knowledge Base :: Add';

		/*	Read the session data and create the variables for the view (sessionAdmin and sessionUser) and create 
			the local variable $sessionAdmin																		*/
		$this->set('sessionAdmin', $this->Session->read('sessionAdmin'));
		$sessionAdmin = $this->Session->read('sessionAdmin');
		$this->set('sessionUser', $this->Session->read('sessionUser'));

		//Check which session type exists, User or Admin (check if they are a technician) and render the correct layout
		if( ($this->Session->check('sessionUser')) == true) {
		  $this->layout = 'default_user';
		} else if ($sessionAdmin[0]['admins']['type'] == 2) {
		  $this->layout = 'default_technician';
		} //end if

		if (!empty($this->data)) {
			//pr($this->data);	//This will display all of the submitted array data on screen (useful for debugging)

			//You need to set the following in the model to validate the fields because we are avoiding CakePHP's ORM
			$this->Kbentry->set($this->data['Kbentry']);
			if($this->Kbentry->validates()){
				$this->Kbentry->addKbentry($this->data['Kbentry']);
				$this->flash('The new Knowledge Base article has been added.', '/kbentries/'); //FLASH MESSAGE AND REDIRECT
			} else {
				$this->set('errorMessage', 'There was a problem, please check all fields for correct entry.');
				$this->render(); //render the page to display the error messages
			} //end if
		} //end if
	} //end function add
	/////////


	/**********
	  Function Name: edit
	  Description: This is the edit function, it will display all information for a particular KB article and allow modifications
	  Last Modified: 2009/03/03 03:42 PM
	  Last Modified By: William DiStefano
	**********/
	function edit($id = null) {
		$this->Kbentry->id = $id;
		$this->pageTitle = 'Voyager Incident Management System :: Knowledge Base :: Modify - ID: ' . $id;
		
		/*	Read the session data and create the variables for the view (sessionAdmin and sessionUser) and create 
			the local variable $sessionAdmin																		*/
		$this->set('sessionAdmin', $this->Session->read('sessionAdmin'));
		$sessionAdmin = $this->Session->read('sessionAdmin');
		$this->set('sessionUser', $this->Session->read('sessionUser'));

		//Check which session type exists, User or Admin (check if they are a technician) and render the correct layout
		if( ($this->Session->check('sessionUser')) == true) {
		  $this->layout = 'default_user';
		} else if ($sessionAdmin[0]['admins']['type'] == 2) {
		  $this->layout = 'default_technician';
		} //end if

		//Find the user by ID $id and set to the variable selectedAdmin
		$this->set('selectedKbentry', $this->Kbentry->retrieveKbentry($id));

		//Find all administrators from the database and store in the variable admins
		$this->set('admins', $this->Kbentry->findAllAdministrators());

		if (empty($this->data)) {
			$this->data = $this->Kbentry->read();
		} else {
			//pr($this->data);	//This will display all of the submitted array data on screen (useful for debugging)

			//You need to set the following in the model to validate the fields because we are avoiding CakePHP's ORM
			$this->Kbentry->set($this->data['Kbentry']);
			if($this->Kbentry->validates()){
				$this->Kbentry->updateKbentry($this->data['Kbentry']);
				$this->flash('The KB article has been updated.', '/kbentries/'); //FLASH MESSAGE AND REDIRECT
			} else {
				$this->set('errorMessage', 'There was a problem, please check all fields for correct entry.');
				$this->render(); //render the page to display the error messages
			} //end if
		} //end if
	} //end edit function
	/////////


	/**********
	  Function Name: view
	  Description: This is the view function, it will display all information for a particular article
	  Last Modified: 2009/03/03 03:09 PM
	  Last Modified By: William DiStefano
	**********/
	function view($id) {
		$this->pageTitle = 'Voyager Incident Management System :: Knowledge Base :: View - ID: ' . $id;

		//Read the session data and create the variables for the view (sessionAdmin and sessionUser) and create the
		//local variable $sessionAdmin
		$this->set('sessionAdmin', $this->Session->read('sessionAdmin'));
		$sessionAdmin = $this->Session->read('sessionAdmin');
		$this->set('sessionUser', $this->Session->read('sessionUser'));

		//Check if a technician or user is logged in and use the appropriate layout
		if( ($this->Session->check('sessionUser')) == true ) {
			$this->layout = 'default_user';
		} else if ($sessionAdmin[0]['admins']['type'] == 2) {
			$this->layout = 'default_technician';
		} //end if

		//Find the article by ID $id and set to the variable selectedArticle
		$this->set('selectedKbentry', $this->Kbentry->retrieveKbentry($id));

		//Find all administrators from the database and store in the variable admins
		$this->set('admins', $this->Kbentry->findAllAdministrators());

	} // end view function
	/////////


	/**********
	  Function Name: delete
	  Description: This is the delete function, it will delete a row from the database given an ID.
	  Last Modified: 2009/03/03 02:56 PM
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

			//Deletes the KB entry
				$this->Kbentry->deleteKbentry($id); //DELETE THE ARTICLE WITH ID $id
				$this->flash('The Knowledge Base article with id: '.$id.' has been deleted.', '/kbentries/'); //FLASH MESSAGE AND REDIRECT


	} //end delete function


} //end class

?>