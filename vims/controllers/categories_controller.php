<?php

/**********
Class Name: CategoriesController
Description: This class (controller) will perform all functions related to 'categories' and render the views accordingly
Variables: $name, $uses, $helpers, $components
Functions: beforeFilter, __validateLoginStatus, index, disable, add
Last Modified: 2009/03/24 01:39 PM
Last Modified By: William DiStefano
**********/
class CategoriesController extends AppController {
	var $name = 'Categories';//Name of this controller
	var $uses = array('Category'); //The items in this array are the model classes that will be accessible from this controller
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
				   This function will display a list of categories and allow new categories to be added
	  Last Modified: 2009/03/24 02:22 PM
	  Last Modified By: William DiStefano
	**********/
	function index($id = null) {
		$this->Category->id = $id;
		$this->pageTitle = 'Voyager Incident Management System :: Incidents :: Categories';

		//Read the session data and create the variable for the view (sessionAdmin)
		$this->set('sessionAdmin', $this->Session->read('sessionAdmin'));

		//Find all categories from the database and store in the variable categories
		$this->set('categories', $this->Category->findAllCategories());

		if (empty($this->data)) {
			$this->data = $this->Category->read();
		} else {
			//pr($this->data);	//This will display all of the submitted array data on screen (useful for debugging)
			//You need to set the following in the model to validate the fields because we are avoiding CakePHP's ORM
			$this->Category->set($this->data['Category']);
			if($this->Category->validates()){
				$this->Category->updateCategory($this->data['Category']);
				$this->flash('The category has been updated.', '/categories/'); //FLASH MESSAGE AND REDIRECT
			} else {
				$this->set('errorMessage', 'There was a problem, you must enter the name the category you are adding.');
				$this->flash('There was a problem, you must enter the name the category you are adding.', '/categories/'); //FLASH MESSAGE AND REDIRECT
			} //end if
		} //end if
	} //end function index


	/**********
	  Function Name: disable
	  Description: This is the disable function, it will disable a row from the database given an ID.
	  Last Modified: 2009/03/06 01:55 PM
	  Last Modified By: William DiStefano
	**********/
	function disable($id = null) {
			$this->Category->id = $id;
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
				if($this->Category->disableCategory($id) == 1) { //DISABLE THE CATEGORY WITH ID $id
					//SUCCESS
					$this->flash('The category with id: '.$id.' has been disabled.', '/categories/'); //FLASH MESSAGE AND REDIRECT
				} else if ($this->Category->disableCategory($id) == 2) {
					//FAILURE
					$this->flash('You cannot disable this category until you first disable all of its child categories.', 'index');//FLASH MESSAGE AND REDIRECT
				} //end if
			} else {
				$this->flash('You do not have permission to do this.', '/incidents/'); //FLASH MESSAGE AND REDIRECT
			} //end if

	} //end disable function
	/////////


	/**********
	  Function Name: enable
	  Description: This is the enable function, it will enable a row from the database given an ID.
	  Last Modified: 2009/03/26 03:41 PM
	  Last Modified By: William DiStefano
	**********/
	function enable() {
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
				$enableCategoryResult = $this->Category->enableCategory($this->data['Category']['id'], $this->data['Category']['parent_id']);
				if($enableCategoryResult == 1) { //ENABLE THE CATEGORY WITH ID $id
					//SUCCESS
					$this->flash('The category with id: '.$this->data['Category']['id'].' has been enabled.', '/categories/'); //FLASH MESSAGE AND REDIRECT
				} else if ($enableCategoryResult == 2) {
					//FAILURE
					$this->flash('You cannot enable this category until you first enable the original parent category.', 'index');//FLASH MESSAGE AND REDIRECT
				} //end if
			} else {
				$this->flash('You do not have permission to do this.', '/incidents/'); //FLASH MESSAGE AND REDIRECT
			} //end if

	} //end enable function
	/////////


	/**********
	  Function Name: add
	  Description: This is the add function, it allows an administrator to add a new category to the database
	  Last Modified: 2009/03/06 01:57 PM
	  Last Modified By: William DiStefano
	**********/
	function add() {

		if (!empty($this->data)) {
			//pr($this->data);	//This will display all of the submitted array data on screen (useful for debugging)

			//You need to set the following in the model to validate the fields because we are avoiding CakePHP's ORM
			$this->Category->set($this->data['Category']);
			if($this->Category->validates()){
				$this->Category->addCategory($this->data['Category']);
				$this->flash('The new category has been added.', '/categories/');
			} else {
				$this->set('errorMessage', 'There was a problem, you must enter the name of the category you are adding.');
				$this->flash('There was a problem, you must enter the name the category you are adding.', '/categories/'); //FLASH MESSAGE AND REDIRECT
			} //end if
		} //end if

		$this->redirect('/categories/'); //REDIRECT

	} //end function add
	/////////


} //end class

?>