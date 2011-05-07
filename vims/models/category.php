<?php 

/**********
Class Name: Category
Description: This class (model) communicates with and validates data to/from the categories DB table
Variables: $name, $validate
Functions: findAllCategories, findAllUsers, deleteCategory, addCategory, updateCategory, disableCategory
Last Modified: 2009/03/24 01:21 PM
Last Modified By: William DiStefano
**********/
class Category extends AppModel
{
	var $name = 'Category';

	//Validation array - This specifies all of the fields and associated rules for data validation
	var $validate = array(

	  'name' => array(		//this line specifies the field name to validate
		'ruleNotEmpty' => array(	//This is the first reference rule name, this is user specified and it doesn't matter what it is
			'rule' => 'notEmpty',	//'rule' specifies that it is a rule and points to the name of the rule from the cake API, in this case it is 'notEmpty'
			'message' => 'Enter the category name')	//This is the message to be displayed should this validation test fail
											//This rule will stop the validate process so the error message will display for this rule instead of
															//moving to the next rule and eventually displaying only the last error message only.
		)
	);


	/**********
	  Function Name: findAllCategories
	  Description: This function will find all categories in the database.
	  Last Modified: 2009/03/06 01:51 PM
	  Last Modified By: William DiStefano
	**********/
	function findAllCategories() {

		return ($this->query('SELECT * FROM categories;'));

	} //end findAllCategories function

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
	  Function Name: deleteCategory
	  Description: This function will delete a category from the database
	  Last Modified: 2009/03/06 01:52 PM
	  Last Modified By: William DiStefano
	**********/
	function deleteCategory($id) {


		return ($this->query('DELETE FROM categories WHERE categories.id = '. $id .';'));

	} //end function deleteCategory

	/**********
	  Function Name: addCategory
	  Description: This function will add a new category to the database
	  Last Modified: 2009/03/06 01:52 PM
	  Last Modified By: William DiStefano
	**********/
	function addCategory($data) {


		return $this->query('INSERT INTO categories (`id`, `name`, `description`, `parent_id`) 
							 VALUES (NULL, "'. $data['name'] .'", "'. $data['description'] .'", '. $data['parent_id'] .');');

	} //end function addCategory

	/**********
	  Function Name: updateCategory
	  Description: This function will update the category in the database
	  Last Modified: 2009/03/06 01:53 PM
	  Last Modified By: William DiStefano
	**********/
	function updateCategory($data) {

		return $this->query('UPDATE categories SET `name` = "'. $data['name'] .'", `description` = "'. $data['description'] .'",
					 `parent_id` = '. $data['parent_id'] .' WHERE id = '. $data['id'] .';');

	} //end function updateCategory

	/**********
	  Function Name: disableCategory
	  Description: This function will disable the category in the database
	  Last Modified: 2009/03/15 05:37 PM
	  Last Modified By: William DiStefano
	**********/
	function disableCategory($id) {

		//Check if the category has any child categories that are enabled
		$checkForChildren = $this->query('SELECT * FROM categories WHERE parent_id='. $id .' AND enabled=1;');
		if($checkForChildren == true) {
			//Child categories exist and are enabled, return an error message
			return 2;
		} else {
			$this->query('UPDATE categories SET `enabled` = 0 WHERE id = '. $id .';');
			return 1;
		} //end if

	} //end function disableCategory

	/**********
	  Function Name: enableCategory
	  Description: This function will enable the category in the database
	  Last Modified: 2009/03/26 03:38 PM
	  Last Modified By: William DiStefano
	**********/
	function enableCategory($id, $parentid) {

		//Check if the category's parent is enabled first
		$checkForParent = $this->query('SELECT * FROM categories WHERE id='. $parentid .' AND enabled=1;');
		if($checkForParent == true) {
			//Parent category exists and is enabled, so enable the category
			$this->query('UPDATE categories SET `enabled` = 1 WHERE id = '. $id .';');
			return 1;
		} else {
			return 2;
		} //end if

	} //end function enableCategory

} //end class

?>