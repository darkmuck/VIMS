<?php
/**********
View Name: index
Description: This view is the first page that the administrator will see for the locations component, it shows a listing of the locations from the database
Access Control: Administrators (yes) | Technicians (no) | Users (no)
Last Modified: 2009/03/24 03:06 PM
Last Modified By: William DiStefano
**********/


//BREADCRUMB//
$breadCrumbText = 'Users';
$breadCrumbUrl = '/users/';
$html->addCrumb($breadCrumbText, $breadCrumbUrl);

$breadCrumbText = 'Locations';
$breadCrumbUrl = '/locations/';
$html->addCrumb($breadCrumbText, $breadCrumbUrl);
//////////////

//HEADING TEXT//
echo '<h2> Locations </h2>';
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
	<tr><td height="1" width="300"><img src="/mis450/img/dot_clear.gif" width="30" height="1"></td>
	<td height="1" width="800"><img src="/mis450/img/dot_clear.gif" width="800" height="1"></td></tr>
	  <tr><td bgcolor="#333333" height="1" width="100%" colspan="2"></td></tr>
	  <tr width="100%">
		<td align="center" valign="top" width="30"><strong>
		  &nbsp;
		</td>
		<td><table border="0" cellspacing="0" cellpadding="0">
		</td></tr>
		<tr width="100%" align="center">
		  <td colspan="2" align="center">
			<h2> Add a New Location </h2>
		  </td>
		</tr>
		<tr width="100%" align="center">';

			//Start HTML Form
			echo $form->create('Location', array('action' => 'add'));

			echo'<td align="right" valign="top">'.
				$form->input('Location.name', array('maxlength' => '50', 'size' => 50, 'label' => '')) .
				'</td>
				 <td valign="top" aligh="left">'.
					$form->end('Create') .'
				</td></tr><tr>';

	echo'</table>
	</td></tr>

	  <tr width="100%">
		<td align="center" valign="top" width="30"><strong>
		  &nbsp;
		</td>
		<td><table border="0" cellspacing="0" cellpadding="0">
		</td></tr>
		<tr width="100%" align="center">
		  <td colspan="2" align="center">
			<h2> Current Locations </h2>
		  </td>
		  <td>
			&nbsp;
		  </td>
		</tr>
		<tr width="100%" align="center">';
		foreach($locations as $location) :

			//Check if location name is 'NONE' because we do not want administrators to delete or modify this
			if($location['locations']['name'] != 'NONE') {

			//Start HTML Form
			echo $form->create('Location', array('action' => 'index'));

			//We need to have the ID present so cakephp fills in most form fields automatically
			echo $form->input('id', array('type'=>'hidden', 'value' => $location['locations']['id']));

			echo'<td align="right" valign="top">'.
				$form->input('Location.name', array('maxlength' => '50', 'size' => 50, 'label' => '', 'value' => $location['locations']['name'])) .
				'</td>
				 <td valign="top" aligh="left">'.
					$form->end('Update').'
				</td>
				<td align="right" valign="top">'.
					'&nbsp;'.
					$html->link($html->image("icons/map_delete.png", array('title' => 'Delete This Location', 'border' => 0)), 
								 "/locations/delete/{$location['locations']['id']}", array('escape'=>false), 
								 "WARNING: If you delete this location (". $location['locations']['name'] .") the associated employees will then have their location set to NONE.").'
				</td></tr><tr>';

			}//end if

		endforeach;

	echo'</table>
	</td></tr></table>';

	echo	'</div>'
		;

	} else {
	
		echo'<div class="divContentCenter">
				You do not have access to this area.
			 </div>'
		;
	
	}

?>