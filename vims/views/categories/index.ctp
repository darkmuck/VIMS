<?php
/**********
View Name: index
Description: This view is the first page that the administrator will see for the categories component, it shows a listing of the categories from the database
Access Control: Administrators (yes) | Technicians (no) | Users (no)
Last Modified: 2009/03/24 02:54 PM
Last Modified By: William DiStefano
**********/


//BREADCRUMB//
$breadCrumbText = 'Incidents';
$breadCrumbUrl = '/incidents/';
$html->addCrumb($breadCrumbText, $breadCrumbUrl);

$breadCrumbText = 'Categories';
$breadCrumbUrl = '/categories/';
$html->addCrumb($breadCrumbText, $breadCrumbUrl);
//////////////

//HEADING TEXT//
echo '<h2> Categories </h2>';
////////////////

	//Check administrator type
	if($sessionAdmin[0]['admins']['type'] == 1) {

	echo'<div class="divContentCenter">';

			//Display any error messages if they exist
			if (!empty($errorMessage)) {
				echo '<br /> <center>'. $html->image('icons/exclamation2.png', array('style'=>'vertical-align: middle')) .' <strong>'. $errorMessage . '</strong> </center> ';
			} //end if


	echo '
	<font size="-1">
	<table border="0" bordercolor="#333333" cellspacing="5" cellpadding="0">
	<tr><td height="1" width="200"><img src="/mis450/img/dot_clear.gif" width="30" height="1"></td>
	<td height="1" width="800"><img src="/mis450/img/dot_clear.gif" width="800" height="1"></td></tr>
	  <tr><td bgcolor="#333333" height="1" width="100%" colspan="2"></td></tr>
	  <tr width="100%">
		<td align="center" valign="top" width="30"><strong>
		  &nbsp;
		</td>
		<td><table border="0" cellspacing="0" cellpadding="2">
		</td></tr>
		<tr width="100%" align="center">
		  <td colspan="5" align="center">
			<h2> Add a New Category </h2>
		  </td>
		</tr>

		<tr width="100%" align="center">';
			echo'<td align="center" valign="top">
					Category Name
				 </td>
				 <td align="center" valign="top">
					Description
				 </td>
				 <td align="center" valign="top" colspan="3">
					Parent
				 </td>
		</tr>

		<tr width="100%" align="center">';

			//Start HTML Form
			echo $form->create('Category', array('action' => 'add'));

			echo'<td align="center" valign="top">'.
				$form->input('Category.name', array('maxlength' => '50', 'size' => 15, 'label' => '')) .
				'</td>
				 <td align="center" valign="top">'.
				 $form->input('Category.description', array('maxlength' => '150', 'size' => 30, 'label' => '')) .
				'</td>
				<td align="center" valign="top" colspan="3">';

/////////////////////////////
/////PARENT ID SELECT BOX/////
				echo '<select name="data[Category][parent_id]" id="CategoryParentId">';

					foreach($categories as $categoryLevel1) :

						if($categoryLevel1['categories']['parent_id'] == 0 && $categoryLevel1['categories']['enabled'] == 1) {

							echo '<option value="'. $categoryLevel1['categories']['id'] .'" >';
							echo $categoryLevel1['categories']['name'];
							echo '</option>';

							foreach($categories as $categoryLevel2) :

								if(($categoryLevel2['categories']['parent_id'] == $categoryLevel1['categories']['id']) && 
									$categoryLevel2['categories']['enabled'] == 1 ) {

									echo '<option value="'. $categoryLevel2['categories']['id'] .'" >';
									echo '--'. $categoryLevel2['categories']['name'];
									echo '</option>';

									/* WE DON'T WANT ADMINISTRATORS TO BE ABLE TO ADD CHILD CATEGORIES HIGHER THAN THE 2ND LEVEL
									foreach($categories as $categoryLevel3) :

										if($categoryLevel3['categories']['parent_id'] == $categoryLevel2['categories']['id']) {

											echo '<option value="'. $categoryLevel3['categories']['id'] .'" >';
											echo '----'. $categoryLevel3['categories']['name'];
											echo '</option>';

										} //end if

									endforeach;
									*/

								} //end if

							endforeach;

						} //end if

					endforeach;

				echo '</select> &nbsp;';
/////////////////////////////
/////////////////////////////

			echo'</td>
			</tr>
			<tr>
			  <td valign="top" colspan="5" aligh="center">'.
				$form->end('Create') .'
			</tr><tr>';


	echo'</table>
	</td></tr>

	  <tr width="100%">
		<td align="center" valign="top" width="30"><strong>
		  &nbsp;
		</td>
		<td><table border="0" cellspacing="0" cellpadding="2">
		</td></tr>
		<tr width="100%" align="center">
		  <td colspan="5" align="center">
			<h2> Current Categories </h2>
		  </td>
		</tr>
		<tr width="100%" align="center">
		  <td align="center" valign="top">
			Category Name
		  </td>
		  <td align="center" valign="top">
			Description
		  </td>
		  <td align="center" valign="top">
			Parent
		  </td>
		  <td valign="top">
			&nbsp;
		  </td>
		  <td valign="top">
			&nbsp;
		  </td>
		<tr width="100%" align="center">';
		foreach($categories as $category) :

			//Check if location ID is 1 (General), 2 (Hardware), or 3 (Software) because we do not want administrators to delete or modify this
			if($category['categories']['id'] != 1 && $category['categories']['id'] != 2 && $category['categories']['id'] != 3) {
				if($category['categories']['enabled'] == 1) {
					//Start HTML Form
					echo $form->create('Category', array('action' => 'index'));

					//We need to have the ID present so cakephp fills in most form fields automatically
					echo $form->input('id', array('type'=>'hidden', 'value' => $category['categories']['id']));

					//We need to have the PARENT ID present so cakephp fills in the field automatically
					echo $form->input('parent_id', array('type'=>'hidden', 'value' => $category['categories']['parent_id']));

					echo'<td align="right" valign="top">'.
						$form->input('Category.name', array('maxlength' => '50', 'size' => 15, 'label' => '', 'value' => $category['categories']['name'])) .
						'</td>
						 <td align="right" valign="top">'.
						 $form->input('Category.description', array('maxlength' => '150', 'size' => 30, 'label' => '', 
										'value' => $category['categories']['description'])) .
						'</td>
						<td align="right" valign="top">';
							foreach($categories as $categoryParents) :

								if( $category['categories']['parent_id'] == $categoryParents['categories']['id'] ) {
									echo $categoryParents['categories']['name'];
								} //end if

							endforeach;
					echo'</td>
						 <td valign="top" aligh="left">'.
							$form->end('Update').'
						</td>
						<td align="right" valign="top">'.
							'&nbsp;'.
							$html->link($html->image("icons/tag_blue_delete.png", array('title' => 'Disable This Category', 'border' => 0)), 
										 "/categories/disable/{$category['categories']['id']}", array('escape'=>false), 
										 "You are about to disable the category (". $category['categories']['name'] ."), are you sure you want to continue?").'
						</tr><tr>';
				} else {
					$existsDisabledCategories = true;
				} //end if
			} //end if

		endforeach;

	echo'</table>
	</td></tr>';

if(!empty($existsDisabledCategories)) {
	if($existsDisabledCategories == true) {
		echo'
		  <tr width="100%">
		<td align="center" valign="top" width="30"><strong>
		  &nbsp;
		</td>
		<td><table border="0" cellspacing="0" cellpadding="2">
		</td></tr>
		<tr width="100%" align="center">
		  <td colspan="6" align="center">
			<h2> Disabled Categories </h2>
		  </td>
		</tr>
		<tr width="100%" align="center">
		  <td align="center" valign="top">
			Category Name
		  </td>
		  <td align="center" valign="top">
			Description
		  </td>
		  <td align="center" valign="top">
			Parent
		  </td>
		  <td valign="top" colspan="3">
			&nbsp;
		  </td>
		<tr width="100%" align="center">';
		foreach($categories as $category) :

			//Check if location ID is 1 (General), 2 (Hardware), or 3 (Software) because we do not want administrators to delete or modify this
			if($category['categories']['id'] != 1 && $category['categories']['id'] != 2 && $category['categories']['id'] != 3) {
				if($category['categories']['enabled'] == 0) {
					//Start HTML Form
					echo $form->create('Category', array('action' => 'index'));

					//We need to have the ID present so cakephp fills in most form fields automatically
					echo $form->input('id', array('type'=>'hidden', 'value' => $category['categories']['id']));

					//We need to have the PARENT ID present so cakephp fills in the field automatically
					echo $form->input('parent_id', array('type'=>'hidden', 'value' => $category['categories']['parent_id']));

					echo'<td align="right" valign="top">'.
						$form->input('Category.name', array('maxlength' => '50', 'size' => 15, 'label' => '', 'value' => $category['categories']['name'])) .
						'</td>
						 <td align="right" valign="top">'.
						 $form->input('Category.description', array('maxlength' => '150', 'size' => 30, 'label' => '', 
										'value' => $category['categories']['description'])) .
						'</td>
						<td align="right" valign="top">';
							foreach($categories as $categoryParents) :

								if( $category['categories']['parent_id'] == $categoryParents['categories']['id'] ) {
									echo $categoryParents['categories']['name'];
								} //end if

							endforeach;
					echo'</td>
						 <td valign="top" aligh="left">'.
							$form->end('Update').'
						</td>
						<td align="right" valign="top">'.
							$form->create('Category', array('action' => 'enable')) .
							//We need to have the ID present so cakephp fills in most form fields automatically
							$form->input('id', array('type'=>'hidden', 'value' => $category['categories']['id'])) .
							//We need to have the PARENT ID present so cakephp fills in the field automatically
							$form->input('parent_id', array('type'=>'hidden', 'value' => $category['categories']['parent_id'])) .'
							<td align="left" valign="top">'.
							$form->end('icons/tag_blue_add.png') .
						'</tr><tr>';
				} //end if
			}//end if

		endforeach;

		echo'</table>
		</td></tr>';
	} //end if
} //end if

	echo'
	</table>';

	echo	'</div>'
		;

	} else {
	
		echo'<div class="divContentCenter">
				You do not have access to this area.
			 </div>'
		;
	
	}

?>