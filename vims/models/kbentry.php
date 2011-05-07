<?php 

/**********
Class Name: Kbentry
Description: This class (model) communicates with and validates data to/from the kbentries DB table
Variables: $name, $validate
Functions: findAllAdministrators, addArticle, deleteArticle, retrieveArticle, updateArticle
Last Modified: 2009/03/24 01:26 PM
Last Modified By: William DiStefano
**********/
class Kbentry extends AppModel
{
	var $name = 'Kbentry';
	
	//Validation array - This specifies all of the fields and associated rules for data validation
	var $validate = array(

	  'title' => array(		//this line specifies the field name to validate
		'ruleNotEmpty' => array(	//This is the first reference rule name, this is user specified and it doesn't matter what it is
			'rule' => 'notEmpty',	//'rule' specifies that it is a rule and points to the name of the rule from the cake API, in this case it is 'notEmpty'
			'message' => 'Enter the title of the knowledge base article')	//This is the message to be displayed should this validation test fail
											//This rule will stop the validate process so the error message will display for this rule instead of
															//moving to the next rule and eventually displaying only the last error message only.
		),

	  'content'  => array(
		'ruleNotEmpty' => array(
			'rule' => 'notEmpty',  
			'message' => 'Enter the knowledge base article')
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
	  Function Name: addArticle
	  Description: This function will add a new article to the database
	  Last Modified: 2009/03/03 03:25 PM
	  Last Modified By: William DiStefano
	**********/
	function addKbentry($data) {

	//Before we store the content in the databse some work needs to be done so it will display properly when viewing it

	//We use the following if statement to add slashes to the HTML elements so it stores in the database properly
	if (!get_magic_quotes_runtime())
	{
		$content = addslashes($data['content']);
	}


		$this->query('INSERT INTO kbentries (`id`, `created`, `modified`, `title`, `content`, `admin_id`) 
							 VALUES (NULL, CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), "'. $data['title'] . '", "'. $content .'", '. $data['admin_id'] .');');

	} //end function addArticle


	/**********
	  Function Name: deleteArticle
	  Description: This function will delete an article from the database
	  Last Modified: 2009/03/03 02:58 PM
	  Last Modified By: William DiStefano
	**********/
	function deleteKbentry($id) {

		return ($this->query('DELETE FROM kbentries WHERE kbentries.id = '. $id .';'));

	} //end function deleteArticle


	/**********
	  Function Name: retrieveArticle
	  Description: This function will retrieve all article data given the ID
	  Last Modified: 2009/03/03 03:10 PM
	  Last Modified By: William DiStefano
	**********/
	function retrieveKbentry($id) {

		return ($this->query('SELECT * FROM kbentries WHERE kbentries.id = '. $id .';'));

	} //end function retrieveArticle


	/**********
	  Function Name: updateArticle
	  Description: This function will update an article in the database
	  Last Modified: 2009/03/03 03:50 PM
	  Last Modified By: William DiStefano
	**********/
	function updateKbentry($data) {

	//Before we store the content in the databse some work needs to be done so it will display properly when viewing it

	//We use the following if statement to add slashes to the HTML elements so it stores in the database properly
	if (!get_magic_quotes_runtime())
	{
		$content = addslashes($data['content']);
	}

	$this->query('UPDATE kbentries SET `modified` = CURRENT_TIMESTAMP(), `title` = "'. $data['title'] .'", 
				 `content` = "'. $content .'" WHERE id = '. $data['id'] .';');


	} //end function updateArticle


} //end class

?>