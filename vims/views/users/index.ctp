<?php
/**********
View Name: index
Description: This view is the first page that the administrator will see for the users component, it shows a listing of all users from the database
Access Control: Administrators (yes) | Technicians (no) | Users (no)
Last Modified: 2009/03/24 03:07 PM
Last Modified By: William DiStefano
**********/

//BREADCRUMB//
$breadCrumbText = 'Users';
$breadCrumbUrl = '/users/';
$html->addCrumb($breadCrumbText, $breadCrumbUrl);
//////////////

//HEADING TEXT//
echo '<h2> Users </h2>';
////////////////

	//Check administrator type
	if($sessionAdmin[0]['admins']['type'] == 1) {


	echo'<div class="divContentCenter">';

				//Display any error messages if they exist
				if (!empty($errorMessage)) {
					echo '<br /> <center>'. $html->image('icons/exclamation2.png', array('style'=>'vertical-align: middle')) .' <strong>'. $errorMessage . '</strong> </center> ';
				} //end if


				//The paginator sets the div to update (mainContent) and the indicator div (ajaxLoaderGeneral) to show while it is updating
				//$paginator->options(array('url'=>array('controller'=>'Users', 'action'=>'index')));
				$paginator->options(array('update' => 'mainContent', 'indicator' => 'ajaxLoaderGeneral', 'url'=>array('controller'=>'Users', 'action'=>'index')));

				echo'<table width="100% align="center">
					<tr>
						<td colspan="8" align="left">
						  <span id="navTools">';
				echo		$html->link($html->image('buttons/button_add-new-user.png',
								array('title' => 'Add a New Employee', 'border' => 0)), "add", array('escape'=>false, 'class' => 'navTools')) .
								'&nbsp;&nbsp;&nbsp;' .
							$html->link($html->image('buttons/button_manage-locations.png',
								array('title' => 'Manage Locations', 'border' => 0)), "/locations/", array('escape'=>false, 'class' => 'navTools'));
				echo	  '</span>';
				echo'	</td>
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
						'. $paginator->sort('Modified', 'modified') .'
					  </th>
					  <th>
						'. $paginator->sort('Name', 'last_name') .'
					  </th>
					  <th>
						'. $paginator->sort('Username', 'username') .'
					  </th>
					  <th>
						'. $paginator->sort('Email', 'email') .'
					  </th>
					  <th>
						'. $paginator->sort('Location', 'location_id') .'
					  </th>
					  <th>
						'. $paginator->sort('Enabled', 'enabled') .'
					  </th>
					  <td width="40px">
						&nbsp;
					  </td>
					</tr>';


				foreach ($users as $user):
					echo '
						<tr>
						  <td>
							'.
							////ID////
							  $user['User']['id']
							//////////
							.'
						  </td>
						  <td>
							'.
							////MODIFIED////
							  $user['User']['modified']
							////////////////
							.'
						  </td>
						  <td>
							';
							////FIRST AND LAST NAME////
								echo $user['User']['last_name'] . ', ' . $user['User']['first_name'];
								if (!empty($user['User']['middle_name'])) {  //show the middle initial
								  echo ' ' . substr($user['User']['middle_name'], 0, 1) . '.';
								}
							echo'
						  </td>
						  <td>
							' .
							////USERNAME////
							  $user['User']['username']
							////////////////
						.'
						  </td>
						  <td>
							';
							////Email////
							  echo $user['User']['email'];
							///////////////////
					 echo'
						  </td>
						  <td>
							';
							////Location////
							  foreach($locations as $location) :
								if( $user['User']['location_id'] == $location['locations']['id'] ) {
									$locationName = $location['locations']['name'];
								}
							  endforeach;
							  
							  if( !empty($locationName) ) {
								echo $locationName;
							  } else {
								echo 'NONE';
							  } //end if
							  
							  //Must unset the variable $locationName so it can be used again for the next user in the list
							  unset($locationName);
							/////////////////
							echo '
						  </td>
						  <td>
							';
							////ENABLED////
							echo '<span class="smalltxt">';
							  if ($user['User']['enabled'] == 0) {
								echo 'no';
							  }else if($user['User']['enabled'] == 1) {
								echo 'yes';
							  }else {
								echo 'Error';
							  } //end if
							echo '</span>';
							////////////////
							echo '
						  </td>
						  <td>'; 
								////FUNCTIONS////
								echo '<table cellpadding="0" cellspacing="0">
										<tr>
										  <td>';
											echo $html->link($html->image("icons/user_go.png", array('title' => 'View', 'border' => 0)),"/users/view/{$user['User']['id']}", array('escape'=>false));
								echo'	  </td>
										  <td>';
											echo $html->link($html->image("icons/user_edit.png", array('title' => 'Edit', 'border' => 0)), "/users/edit/{$user['User']['id']}", array('escape'=>false));
								echo'	  </td>
										  <td>';
											if($user['User']['enabled'] == 1) {
												echo $html->link($html->image("icons/user_delete.png", array('title' => 'Disable', 'border' => 0)), 
															 "/users/disable/{$user['User']['id']}", array('escape'=>false), 
															 "Are you sure you want to disable the user: ". $user['User']['username'] );
											} else {
												echo $html->link($html->image("icons/user_green.png", array('title' => 'Enable', 'border' => 0)), 
															 "/users/enable/{$user['User']['id']}", array('escape'=>false), 
															 "Are you sure you want to enable the user: ". $user['User']['username'] );
											} //end if
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

	} else {
	
		echo'<div class="divContentCenter">
				You do not have access to this area.
			 </div>'
		;
	
	} //end if
?>