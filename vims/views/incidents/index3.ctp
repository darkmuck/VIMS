<?php
/**********
View Name: index3
Description: This view is the first page that the user or administrator will see for the incidents component, it shows a listing of the incidents from the database.
				For users: This index shows all closed incidents that the logged in user submitted
				For admins: This index shows all closed incidents
Access Control: Administrators (yes) | Technicians (yes) | Users (yes)
Last Modified: 2009/03/24 03:01 PM
Last Modified By: William DiStefano
**********/

//BREADCRUMB//
$breadCrumbText = 'Incidents';
$breadCrumbUrl = '/incidents/index3';
$html->addCrumb($breadCrumbText, $breadCrumbUrl);
//////////////


//HEADING TEXT//
echo '<h2> Incidents </h2>';
////////////////

	//Check if administrator or not
	if(!empty($sessionAdmin) == true) {


	/////
	//They are a technician or administrator
	/////

		//The paginator sets the div to update (mainContent) and the indicator div (ajaxLoaderGeneral) to show while it is updating
		//$paginator->options(array('url'=>array('controller'=>'Incidents', 'action'=>'index')));
		$paginator->options(array('update' => 'mainContent', 'indicator' => 'ajaxLoaderGeneral', 'url'=>array('controller'=>'Incidents', 'action'=>'index3')));

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
						.  $html->link('All Assigned Incidents', '/incidents/index2') . ' | 
						<b>'.  $html->link('All Closed Incidents', '/incidents/index3') .'</b>
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
						'. $paginator->sort('ID', 'id') .'
					  </th>
					  <th>
						'. $paginator->sort('Created', 'created') .'
					  </th>
					  <th>
						'. $paginator->sort('Employee', 'user_id') .'
					  </th>
					  <th>
						'. $paginator->sort('Category', 'category_id') .'
					  </th>
					  <th>
						'. $paginator->sort('Priority', 'priority') .'
					  </th>
					  <th>
						'. $paginator->sort('Status', 'status') .'
					  </th>
					  <th>
						'. $paginator->sort('Tech/Admin', 'admin_id') .'
					  </th>
					  <th>
						'. $paginator->sort('Description', 'description') .'
					  </th>
					  <td width="20px">
							<div id="ajaxLoaderGeneral" style="display: none;">';
								echo $html->image('ajax-loader.gif') .'
							</div>
					  </td>
					</tr>';


				foreach ($incidents as $incident):
					echo '
						<tr>
						  <td> <font size="-2">
							'.
							////ID////
							  $incident['Incident']['id']
							//////////
							.'
						  </font> </td>
						  <td> <font size="-2">
							'.
							////CREATED////
							  $incident['Incident']['created']
							///////////////
							.'
						  </font> </td>
						  <td> <font size="-2">
							';
							////EMPLOYEE////
									foreach($users as $user) :
										if($user['users']['id'] == $incident['Incident']['user_id']) {

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
										if($category['categories']['id'] == $incident['Incident']['category_id']) {
											echo $category['categories']['name'];
										} //end if
									endforeach;
							////////////////
							echo'
						  </font> </td>
						  <td>
							';
							////Priority////
							  SWITCH($incident['Incident']['priority']) {
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
							  SWITCH($incident['Incident']['status']) {
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
							if(!empty($incident['Incident']['admin_id']) || ($incident['Incident']['admin_id'] != 0)) {
								foreach($admins as $admin) :
									if($admin['admins']['id'] == $incident['Incident']['admin_id']) {
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
									if (strlen($incident['Incident']['description']) >= 30) {  
									  echo ' ' . substr($incident['Incident']['description'], 0, 30) . '...';
									} else {
									  echo $incident['Incident']['description'];
									} //end if
							//////////////////
							echo'
						  </font> </td>
						  <td>'; 
								////FUNCTIONS////
								echo '<table cellpadding="0" cellspacing="0">
										<tr>
										  <td>';
											echo $html->link($html->image("icons/application_form_magnify.png", array('title' => 'View', 'border' => 0)),"/incidents/view/{$incident['Incident']['id']}", array('escape'=>false));
								echo'	  </td>
										  <td>';
											echo $html->link($html->image("icons/application_form_edit.png", array('title' => 'Edit', 'border' => 0)), "/incidents/edit/{$incident['Incident']['id']}", array('escape'=>false));
								echo'	  </td>
										</tr>
									  </table>';
								//////////////////
						echo'</td>
						</tr>
					';
				endforeach;

				echo'</table>';

				echo $paginator->numbers(); //show the other pages (if multiple pages exist)

	echo	'</div>'
		;


		//Check if user or not
	} else if(!empty($sessionUser) == true) {

		/////
		//They are a user
		/////

		//The paginator sets the div to update (mainContent) and the indicator div (ajaxLoaderGeneral) to show while it is updating
		//$paginator->options(array('url'=>array('controller'=>'Incidents', 'action'=>'index')));
		$paginator->options(array('update' => 'mainContent', 'indicator' => 'ajaxLoaderGeneral', 'url'=>array('controller'=>'Incidents', 'action'=>'index3')));

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
								array('title' => 'Add a New Incident', 'border' => 0)), "add", array('escape'=>false, 'class' => 'navTools')).'
								&nbsp;&nbsp;';
				echo		$html->link($html->image('buttons/button_search-incident.png',
								array('title' => 'Search Incidents', 'border' => 0)), "search", array('escape'=>false, 'class' => 'navTools')) .
								'&nbsp;&nbsp;';
				echo	  '</span>';
				echo'	</td>
						<td colspan="4" align="left">
						<font size="-2">
						<b> View: </b>
						'. $html->link('Your Open Incidents', '/incidents/index') .' | 
						<b>'.  $html->link('Your Closed Incidents', '/incidents/index3') .'</b> 
						</font>
						</td>
						<td width="20">';
							echo '<span id="ajaxLoaderGeneral" style="display: none;">';
								echo $html->image('ajax-loader.gif');
							echo '</span>';
				echo'	</td>
					</tr>';
				echo'<tr>
					  <td colspan="9">';
						$html->image('dot_clear.gif', array('height' => '5'));
				echo'</td>
					</tr>';


				echo '
					<tr>
					  <th>
						'. $paginator->sort('ID', 'id') .'
					  </th>
					  <th>
						'. $paginator->sort('Created', 'created') .'
					  </th>
					  <th>
						'. $paginator->sort('Category', 'category_id') .'
					  </th>
					  <th>
						'. $paginator->sort('Priority', 'priority') .'
					  </th>
					  <th>
						'. $paginator->sort('Status', 'status') .'
					  </th>
					  <th>
						'. $paginator->sort('Tech/Admin', 'admin_id') .'
					  </th>
					  <th>
						'. $paginator->sort('Description', 'description') .'
					  </th>
					  <td width="40px">
						&nbsp;
					  </td>
					</tr>';


		foreach ($incidents as $incident):

			//Only show the incident if it belongs to the user
			if( ($sessionUser[0]['users']['id'] == $incident['Incident']['user_id']) ) {

					echo '
						<tr>
						  <td> <font size="-2">
							'.
							////ID////
							  $incident['Incident']['id']
							//////////
							.'
						  </font> </td>
						  <td> <font size="-2">
							'.
							////CREATED////
							  $incident['Incident']['created']
							///////////////
							.'
						  </font> </td>
						  <td> <font size="-2">
							';
							////Category////
									foreach($categories as $category) :
										if($category['categories']['id'] == $incident['Incident']['category_id']) {
											echo $category['categories']['name'];
										} //end if
									endforeach;
							////////////////
							echo'
						  </font> </td>
						  <td>
							';
							////Priority////
							  SWITCH($incident['Incident']['priority']) {
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
							  SWITCH($incident['Incident']['status']) {
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
									foreach($admins as $admin) :
										if($admin['admins']['id'] == $incident['Incident']['admin_id']) {

											echo $admin['admins']['username'];

										} //end if
									endforeach;
							////////////
							echo'
						  </font> </td>
						  <td> <font size="-2">
							';
							////DESCRIPTION////
									//If the incident's description is longer than 30 shorten it
									if (strlen($incident['Incident']['description']) >= 30) {  
									  echo ' ' . substr($incident['Incident']['description'], 0, 30) . '...';
									} else {
									  echo $incident['Incident']['description'];
									} //end if
							//////////////////
							echo'
						  </font> </td>
						  <td>'; 
								////FUNCTIONS////
								echo '<table cellpadding="0" cellspacing="0">
										<tr>
										  <td>';
											echo $html->link($html->image("icons/application_form_magnify.png", array('title' => 'View', 'border' => 0)),"/incidents/view/{$incident['Incident']['id']}", array('escape'=>false));
								echo'	  </td>
										  <td>';
											echo $html->link($html->image("icons/application_form_edit.png", array('title' => 'Worklog', 'border' => 0)), "/incidents/edit/{$incident['Incident']['id']}", array('escape'=>false));
								echo'	  </td>
										</tr>
									  </table>';
								//////////////////
						echo'</td>
						</tr>
					';

			} //end if

				endforeach;

				echo'</table>';

				echo $paginator->numbers(); //show the other pages (if multiple pages exist)

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