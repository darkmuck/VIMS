<?php
/**********
View Name: view
Description: This view will display detailed information about a particular computer.
Access Control: Administrators (yes) | Technicians (no) | Users (no)
Last Modified: 2009/03/24 02:57 PM
Last Modified By: William DiStefano
**********/

foreach ($selectedComputer as $computer):
//BREADCRUMB//
$breadCrumbText = 'Computers';
$breadCrumbUrl = '/computers/';
$html->addCrumb($breadCrumbText, $breadCrumbUrl);

$breadCrumbText = 'View - ID: ' . $computer['computers']['id'];
$breadCrumbUrl = '/computers/view/' . $computer['computers']['id'];
$html->addCrumb($breadCrumbText, $breadCrumbUrl);
//////////////
endforeach;

//HEADING TEXT//
echo '<h2> View Details </h2>';
////////////////


	//Check administrator type
	if(!empty($sessionAdmin)) {

	foreach ($selectedComputer as $computer):

	//Check if tech or admin, hide modify button if technician
	if($sessionAdmin[0]['admins']['type'] == 1) {
	echo '<div class="navTools">';
				echo 
					'&nbsp;' .
					$html->link($html->image("buttons/button_computer-modify.png", array('title' => 'Modify', 'border' => 0)), "/computers/edit/{$computer['computers']['id']}", array('escape'=>false, 'class'=>'navTools'))
				;
	echo '</div>';
	} //end if

	//Start HTML Form
	echo $form->create('Computer', array('action' => 'edit'));

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
			echo $computer['computers']['created'];
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Last Modified: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $computer['computers']['modified'];
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Name: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $computer['computers']['name'];
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Type: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $computer['computers']['type'];
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Serial Number: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $computer['computers']['serial_number'];
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Assigned To: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			foreach($admins as $admin) :
				if($admin['admins']['computer_id'] == $computer['computers']['id']) {
					//Check if technician or administrator
					if($admin['admins']['type'] == 1) {
						echo $admin['admins']['username'] . ' (administrator) ';
						//Check if current logged in User is a tech or admin, hide the modify button if a technician
						if($sessionAdmin[0]['admins']['type'] == 1) {
							echo $html->link($html->image("icons/user_edit.png", array('title' => 'Modify Administrator', 'border' => 0)), 
												 "/admins/edit/{$admin['admins']['id']}", array('escape'=>false, 'class'=>'navTools'));
						} //end if
					} else if($admin['admins']['type'] == 2){
						echo $admin['admins']['username'] . ' (technician) ';
						//Check if current logged in User is a tech or admin, hide the modify button if a technician
						if($sessionAdmin[0]['admins']['type'] == 1) {
							echo $html->link($html->image("icons/user_edit.png", array('title' => 'Modify Technician', 'border' => 0)), 
												 "/admins/edit/{$admin['admins']['id']}", array('escape'=>false, 'class'=>'navTools'));
						} //end if
					} //end if
				} //end if
			endforeach;

			foreach($users as $user) :
				if($user['users']['computer_id'] == $computer['computers']['id']) {
					echo $user['users']['username'] . ' (employee) ';
					//Check if current logged in User is a tech or admin, hide the modify button if a technician
					if($sessionAdmin[0]['admins']['type'] == 1) {
						echo $html->link($html->image("icons/user_edit.png", array('title' => 'Modify Employee', 'border' => 0)), 
												  "/users/edit/{$user['users']['id']}", array('escape'=>false, 'class'=>'navTools'));
					} //end if
				} //end if
			endforeach;
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		O/S: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $computer['computers']['operating_system'];
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Memory: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $computer['computers']['memory'] .'GB';
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		HDD Space: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $computer['computers']['hdd_space'] .'GB';
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Processor: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $computer['computers']['processor'];
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