<?php
/**********
View Name: index
Description: This view will display the search results from a search term given by either a user or admin/tech
Access Control: Administrators (yes) | Technicians (yes) | Users (yes)
Last Modified: 2009/03/25 01:44 PM
Last Modified By: William DiStefano
**********/

//BREADCRUMB//
$breadCrumbText = 'Incidents';
$breadCrumbUrl = '/incidents/';
$html->addCrumb($breadCrumbText, $breadCrumbUrl);

$breadCrumbText = 'Search';
$breadCrumbUrl = '/incidents/search';
$html->addCrumb($breadCrumbText, $breadCrumbUrl);
//////////////

//HEADING TEXT//
echo '<h2> Incidents Search </h2>';
////////////////

//ADVANCED SEARCH BOX//
echo'<table width="100%" cellpadding="0" cellspacing="0"><tr><td width="100%" align="center">
	<table cellpadding="0" cellspacing="0" width="230">
	  <tr>
		<td align="center">
		  <form id="IncidentSearchForm" method="post" action="/dev/mis450/incidents/search"  STYLE="margin: 0px; padding: 0px;>
		  <fieldset style="display:none;">
		  <input type="hidden" name="_method" value="POST" />
		  </fieldset>
		  <input name="data[Incident][searchterm]" type="text" maxlength="25" size="15" id="Incidentsearchterm" 
				style="margin: 0px; padding: 0px;"/>
		  <input type="submit" value="Search" style="margin: 0px; padding: 0px;"/>
		</td>
	  </tr>
	  <tr>
		<td align="left" colspan="2">
		  <font size="-2">
		  <input type="radio" name="data[Incident][searchtype]" value="1" checked="checked">Description &nbsp;
		  <input type="radio" name="data[Incident][searchtype]" value="2">Problem &nbsp;
		  <input type="radio" name="data[Incident][searchtype]" value="3">Worklogs
		  </font>
		  </form>
		</td>
	  </tr>
	</table>
</td></tr></table> <br />';
/////////////////////


	//Check if administrator is logged in
	if( (!empty($sessionAdmin) == true)){

	echo'<div class="divContentCenter">';

		//Display any error messages if they exist
		if (!empty($errorMessage)) {
			echo '<br /> <center>'. $html->image('icons/exclamation2.png', array('style'=>'vertical-align: middle')) .' <strong>'. $errorMessage . '</strong> </center> ';
		} //end if

				echo'<table width="100% align="center">
					<tr>
						<td colspan="4" align="left">
						
						  <span id="navTools">';
				echo		$html->link($html->image('buttons/button_add-new-incident.png',
								array('title' => 'Add a New Incident', 'border' => 0)), "add", array('escape'=>false, 'class' => 'navTools')) .
								'&nbsp;&nbsp;';
				echo		$html->link($html->image('buttons/button_search-incident.png',
								array('title' => 'Search Incidents', 'border' => 0)), "search", array('escape'=>false, 'class' => 'navTools')) .
								'&nbsp;&nbsp;';
							if($sessionAdmin[0]['admins']['type'] == 1) {
							echo $html->link($html->image('buttons/button_manage-categories.png',
								array('title' => 'Manage Categories', 'border' => 0)), "/categories/", array('escape'=>false, 'class' => 'navTools'));
							} //end if
				echo	  '</span>';
				echo'	</td>
						<td colspan="5" align="center">
						<font size="-2">
						<b> View: </b>
						'. $html->link('Pending & Your Incidents', '/incidents/index') .' | '
						.  $html->link('All Assigned Incidents', '/incidents/index2') . ' | '
						.  $html->link('All Closed Incidents', '/incidents/index3') .'
						</font>
						</td>';
				echo'</tr>';
				echo'<tr>
					  <td colspan="9">';
						$html->image('dot_clear.gif', array('height' => '5'));
				echo'</td>
					</tr></form>';


				echo '
					<tr>
					  <th>
						<font size="-2">ID</font>
					  </th>
					  <th>
						<font size="-2">Created</font>
					  </th>
					  <th>
						<font size="-2">Employee</font>
					  </th>
					  <th>
						<font size="-2">Category</font>
					  </th>
					  <th>
						<font size="-2">Priority</font>
					  </th>
					  <th>
						<font size="-2">Status</font>
					  </th>
					  <th>
						<font size="-2">Tech/Admin</font>
					  </th>
					  <th>
						<font size="-2">Description</font>
					  </th>
					  <td width="20px">
						&nbsp;
					  </td>
					</tr>';


			if(!empty($incidents)) {


				foreach ($incidents as $incident):
					echo '
						<tr>
						  <td> <font size="-2">
							'.
							////ID////
							  $incident['incidents']['id']
							//////////
							.'
						  </font> </td>
						  <td> <font size="-2">
							'.
							////CREATED////
							  $incident['incidents']['created']
							///////////////
							.'
						  </font> </td>
						  <td> <font size="-2">
							';
							////EMPLOYEE////
									foreach($users as $user) :
										if($user['users']['id'] == $incident['incidents']['user_id']) {

											echo $html->link($user['users']['username'], '/users/view/' . $user['users']['id']);

										} //end if
									endforeach;
							///////////////
							echo'
						  </font> </td>
						  <td> <font size="-2">
							';
							////Category////
									foreach($categories as $category) :
										if($category['categories']['id'] == $incident['incidents']['category_id']) {
											echo $category['categories']['name'];
										} //end if
									endforeach;
							////////////////
							echo'
						  </font> </td>
						  <td>
							';
							////Priority////
							  SWITCH($incident['incidents']['priority']) {
								CASE 0:
									echo '<font size="-2" color="darkgreen"> <b> LOW </b> </font>';
									break;
								CASE 1:
									echo '<font size="-2" color="#FFCC66"> <b> MEDIUM </b> </font>';
									break;
								CASE 2:
									echo '<font size="-2" color="#FF9900"> <b> HIGH </b> </font>';
									break;
								CASE 3:
									echo '<font size="-2" color="red"> <b> WORK-STOPPAGE </b> </font>';
									break;
								DEFAULT:
									echo '<font size="-2" color="darkgreen"> --- </font>';
							  } //end switch
							///////////////
							echo'
						  </font> </td>
						  <td> <font size="-2">
							';
							////Status////
							  SWITCH($incident['incidents']['status']) {
								CASE 0:
									echo '<font size="-2"> pending </font>';
									break;
								CASE 1:
									echo '<font size="-2"> accepted </font>';
									break;
								CASE 2:
									echo '<font size="-2"> resolved </font>'; //This status level will be archived
									break;
								CASE 3:
									echo '<font size="-2"> duplicate </font>'; //This status level will be archived
									break;
								CASE 4:
									echo '<font size="-2"> inaccurate </font>'; //This status level will be archived
									break;
								CASE 5:
									echo '<font size="-2"> unresolvable </font>'; //This status level will be archived
									break;
								DEFAULT:
									echo '<font size="-2"> --- </font>';
							  }//end switch
							/////////////
							echo'
						  </font> </td>
						  <td> <font size="-2">
							';
							////ADMIN////
							if(!empty($incident['incidents']['admin_id']) || ($incident['incidents']['admin_id'] != 0)) {
								foreach($admins as $admin) :
									if($admin['admins']['id'] == $incident['incidents']['admin_id']) {
											echo $html->link($admin['admins']['username'], '/admins/view/'. $admin['admins']['id']);
									} //end if
								endforeach;
							} //end if
							////////////
							echo'
						  </font> </td>
						  <td> <font size="-2">
							';
							////DESCRIPTION////
									//If the incident's description is longer than 30 shorten it
									if (strlen($incident['incidents']['description']) >= 30) {  
									  echo ' ' . substr($incident['incidents']['description'], 0, 30) . '...';
									} else {
									  echo $incident['incidents']['description'];
									} //end if
							//////////////////
							echo'
						  </font> </td>
						  <td>'; 
								////FUNCTIONS////
								echo '<table cellpadding="0" cellspacing="0">
										<tr>
										  <td>';
											echo $html->link($html->image("icons/application_form_magnify.png", array('title' => 'View', 'border' => 0)),"/incidents/view/{$incident['incidents']['id']}", array('escape'=>false));
								echo'	  </td>
										  <td>';
											echo $html->link($html->image("icons/application_form_edit.png", array('title' => 'Edit', 'border' => 0)), "/incidents/edit/{$incident['incidents']['id']}", array('escape'=>false));
								echo'	  </td>
										</tr>
									  </table>';
								//////////////////
						echo'</td>
						</tr>
					';
				endforeach;

			} //end if

				echo'</table>';

	echo	'</div>'
		;

	//CHECK IF USER IS LOGGED IN
	} else if (!empty($sessionUser) == true) {
		////THEY ARE A USER

	echo'<div class="divContentCenter">';

		//Display any error messages if they exist
		if (!empty($errorMessage)) {
			echo '<br /> <center>'. $html->image('icons/exclamation2.png', array('style'=>'vertical-align: middle')) .' <strong>'. $errorMessage . '</strong> </center> ';
		} //end if

				echo'<table width="100% align="center">
					<tr>
						<td colspan="4" align="left">
						
						  <span id="navTools">';
				echo		$html->link($html->image('buttons/button_add-new-incident.png',
								array('title' => 'Add a New Incident', 'border' => 0)), "add", array('escape'=>false, 'class' => 'navTools')) .
								'&nbsp;&nbsp;';
				echo		$html->link($html->image('buttons/button_search-incident.png',
								array('title' => 'Search Incidents', 'border' => 0)), "search", array('escape'=>false, 'class' => 'navTools')) .
								'&nbsp;&nbsp;';
							if($sessionAdmin[0]['admins']['type'] == 1) {
							echo $html->link($html->image('buttons/button_manage-categories.png',
								array('title' => 'Manage Categories', 'border' => 0)), "/categories/", array('escape'=>false, 'class' => 'navTools'));
							} //end if
				echo	  '</span>';
				echo'	</td>
						<td colspan="5" align="center">
						<font size="-2">
						<b> View: </b>
						'. $html->link('Your Open Incidents', '/incidents/index') .' | '
						.  $html->link('Your Closed Incidents', '/incidents/index3') .'
						</font>
						</td>';
				echo'</tr>';
				echo'<tr>
					  <td colspan="9">';
						$html->image('dot_clear.gif', array('height' => '5'));
				echo'</td>
					</tr></form>';


				echo '
					<tr>
					  <th>
						<font size="-2">ID</font>
					  </th>
					  <th>
						<font size="-2">Created</font>
					  </th>
					  <th>
						<font size="-2">Employee</font>
					  </th>
					  <th>
						<font size="-2">Category</font>
					  </th>
					  <th>
						<font size="-2">Priority</font>
					  </th>
					  <th>
						<font size="-2">Status</font>
					  </th>
					  <th>
						<font size="-2">Tech/Admin</font>
					  </th>
					  <th>
						<font size="-2">Description</font>
					  </th>
					  <td width="20px">
						&nbsp;
					  </td>
					</tr>';


			if(!empty($incidents)) {


				foreach ($incidents as $incident):
					echo '
						<tr>
						  <td> <font size="-2">
							'.
							////ID////
							  $incident['incidents']['id']
							//////////
							.'
						  </font> </td>
						  <td> <font size="-2">
							'.
							////CREATED////
							  $incident['incidents']['created']
							///////////////
							.'
						  </font> </td>
						  <td> <font size="-2">
							';
							////EMPLOYEE////
									foreach($users as $user) :
										if($user['users']['id'] == $incident['incidents']['user_id']) {

											echo $user['users']['username'];

										} //end if
									endforeach;
							///////////////
							echo'
						  </font> </td>
						  <td> <font size="-2">
							';
							////Category////
									foreach($categories as $category) :
										if($category['categories']['id'] == $incident['incidents']['category_id']) {
											echo $category['categories']['name'];
										} //end if
									endforeach;
							////////////////
							echo'
						  </font> </td>
						  <td>
							';
							////Priority////
							  SWITCH($incident['incidents']['priority']) {
								CASE 0:
									echo '<font size="-2" color="darkgreen"> <b> LOW </b> </font>';
									break;
								CASE 1:
									echo '<font size="-2" color="#FFCC66"> <b> MEDIUM </b> </font>';
									break;
								CASE 2:
									echo '<font size="-2" color="#FF9900"> <b> HIGH </b> </font>';
									break;
								CASE 3:
									echo '<font size="-2" color="red"> <b> WORK-STOPPAGE </b> </font>';
									break;
								DEFAULT:
									echo '<font size="-2" color="darkgreen"> --- </font>';
							  } //end switch
							///////////////
							echo'
						  </font> </td>
						  <td> <font size="-2">
							';
							////Status////
							  SWITCH($incident['incidents']['status']) {
								CASE 0:
									echo '<font size="-2"> pending </font>';
									break;
								CASE 1:
									echo '<font size="-2"> accepted </font>';
									break;
								CASE 2:
									echo '<font size="-2"> resolved </font>'; //This status level will be archived
									break;
								CASE 3:
									echo '<font size="-2"> duplicate </font>'; //This status level will be archived
									break;
								CASE 4:
									echo '<font size="-2"> inaccurate </font>'; //This status level will be archived
									break;
								CASE 5:
									echo '<font size="-2"> unresolvable </font>'; //This status level will be archived
									break;
								DEFAULT:
									echo '<font size="-2"> --- </font>';
							  }//end switch
							/////////////
							echo'
						  </font> </td>
						  <td> <font size="-2">
							';
							////ADMIN////
							if(!empty($incident['incidents']['admin_id']) || ($incident['incidents']['admin_id'] != 0)) {
								foreach($admins as $admin) :
									if($admin['admins']['id'] == $incident['incidents']['admin_id']) {
											echo $admin['admins']['username'];
									} //end if
								endforeach;
							} //end if
							////////////
							echo'
						  </font> </td>
						  <td> <font size="-2">
							';
							////DESCRIPTION////
									//If the incident's description is longer than 30 shorten it
									if (strlen($incident['incidents']['description']) >= 30) {  
									  echo ' ' . substr($incident['incidents']['description'], 0, 30) . '...';
									} else {
									  echo $incident['incidents']['description'];
									} //end if
							//////////////////
							echo'
						  </font> </td>
						  <td>'; 
								////FUNCTIONS////
								echo '<table cellpadding="0" cellspacing="0">
										<tr>
										  <td>';
											echo $html->link($html->image("icons/application_form_magnify.png", array('title' => 'View', 'border' => 0)),"/incidents/view/{$incident['incidents']['id']}", array('escape'=>false));
								echo'	  </td>
										  <td>';
											echo $html->link($html->image("icons/application_form_edit.png", array('title' => 'Edit', 'border' => 0)), "/incidents/edit/{$incident['incidents']['id']}", array('escape'=>false));
								echo'	  </td>
										</tr>
									  </table>';
								//////////////////
						echo'</td>
						</tr>
					';
				endforeach;

			} //end if

				echo'</table>';

	echo	'</div>'
		;

	} else {

		/////
		//They are not a user or administrator
		/////

		echo'<div class="divContentCenter">';

			//Display any error messages if they exist
			if (!empty($errorMessage)) {
				echo '<br /> <center>'. $html->image('icons/exclamation2.png', array('style'=>'vertical-align: middle')) .' <strong>'. $errorMessage . '</strong> </center> ';
			} //end if


			echo 'You do not have access to this area!';

	echo'</div>';

	} //end if

?>