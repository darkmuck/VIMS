<?php
/**********
View Name: view
Description: This view will display detailed information about a particular user.
Access Control: Administrators (yes) | Technicians (no) | Users (no)
Last Modified: 2009/03/24 03:08 PM
Last Modified By: William DiStefano
**********/

foreach ($selectedUser as $user):
//BREADCRUMB//
$breadCrumbText = 'Users';
$breadCrumbUrl = '/users/';
$html->addCrumb($breadCrumbText, $breadCrumbUrl);

$breadCrumbText = 'View - ID: ' . $user['users']['id'];
$breadCrumbUrl = '/users/view/' . $user['users']['id'];
$html->addCrumb($breadCrumbText, $breadCrumbUrl);
//////////////
endforeach;

//HEADING TEXT//
echo '<h2> View Details </h2>';
////////////////


	//Check administrator is logged in
	if(!empty($sessionAdmin)) {


	foreach ($selectedUser as $user):

	//Check admin type
	if($sessionAdmin[0]['admins']['type'] == 1) {
	//Display the modify button because they are an administrator and NOT a technician
		echo '<div class="navTools">';
					echo 
						'&nbsp;' .
						$html->link($html->image("buttons/button_user-modify.png", array('title' => 'Modify', 'border' => 0)), "/users/edit/{$user['users']['id']}", array('escape'=>false, 'class'=>'navTools'))
					;
		echo '</div>';
	} //end if


	//Start HTML Form
	echo $form->create('User', array('action' => 'edit'));

	//We need to have the ID present so cakephp fills in most form fields automatically
	echo $form->input('id', array('type'=>'hidden'));


	echo '
	<font size="-1">
	<table border="0" bordercolor="#333333" cellspacing="5" cellpadding="0">
	<tr><td height="1" width="30"><img src="/mis450/img/dot_clear.gif" width="30" height="1"></td>
	<td height="1" width="800"><img src="/mis450/img/dot_clear.gif" width="800" height="1"></td></tr>
	  <tr><td bgcolor="#333333" height="1" width="100%" colspan="2"></td></tr>
	  <tr width="100%">
		<td align="center" valign="top" width="30"><strong>
		&nbsp;
		</strong>
		</td>
		<td><table border="0" width="100%" cellspacing="0" cellpadding="0">
		</td></tr><tr>
		<td align="right" valign="top" width="100">
		Created: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $user['users']['created'];
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Last Modified: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $user['users']['modified'];
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		First Name: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $user['users']['first_name'];
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Middle Name: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $user['users']['middle_name'];
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Last Name: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $user['users']['last_name'];
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Username: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $user['users']['username'];
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Location: &nbsp;&nbsp;
		</td>
		<td valign="top">';
					//Match up the current user's location (if one is assigned) and display it
					foreach ($locations as $location) :
							if ($user['users']['location_id'] == $location['locations']['id']) {
								echo $location['locations']['name'];
							} //end if
					endforeach;
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Computer (ID): &nbsp;&nbsp;
		</td>
		<td valign="top">';
					//Match up the current user's computer (if one is assigned) and display it
					foreach ($computers as $computer) :
							if ($user['users']['computer_id'] == $computer['computers']['id']) {
								echo $computer['computers']['name'] . ' (' . $computer['computers']['id'] .') &nbsp;';
								//Check admin type
								if($sessionAdmin[0]['admins']['type'] == 1) {
								//Display the modify button because they are an administrator and NOT a technician
									echo $html->link($html->image("icons/computer_edit.png", array('title' => 'Modify Computer', 'border' => 0)), 
												 "/computers/edit/{$computer['computers']['id']}", array('escape'=>false, 'class'=>'navTools'));
								} //end if
							} //end if
					endforeach;

					//Check if the user has a computer assigned to them
					if($user['users']['computer_id'] == 0) {
						echo 'NONE';
					} //end if
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Network Port: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $user['users']['network_port'];
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Email: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $user['users']['email'];
		echo'</td>
		</tr><tr>
		<td align="right" valign="top" width="100">
		Enabled: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			if( $user['users']['enabled'] == 0) {
				echo 'No';
			} else if($user['users']['enabled'] == 1) {
				echo 'Yes';
			} else {
				echo 'Error';
			} //end if
	echo'</td>
		</tr></table>
	</td></tr>
	 <tr>
		<td>&nbsp;</td>
		<td align="center" valign="top">';
		echo '</font>' . $form->end(); //end the form
	echo'</td></tr>
	</table>';

	endforeach;


	} else {
	
		echo'<div class="divContentCenter">
				You do not have access to this area.
			 </div>'
		;
	
	}

?>