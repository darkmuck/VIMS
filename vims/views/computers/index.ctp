<?php
/**********
View Name: index
Description: This view is the first page that the administrator will see for the computers component, it shows a listing of the computers from the database
Access Control: Administrators (yes) | Technicians (no) | Users (no)
Last Modified: 2009/03/24 02:57 PM
Last Modified By: William DiStefano
**********/

//BREADCRUMB//
$breadCrumbText = 'Computers';
$breadCrumbUrl = '/computers/';
$html->addCrumb($breadCrumbText, $breadCrumbUrl);
//////////////

//HEADING TEXT//
echo '<h2> Computers </h2>';
////////////////

	//Check administrator type
	if($sessionAdmin[0]['admins']['type'] == 1) {

	echo'<div class="divContentCenter">';

				//Display any error messages if they exist
				if (!empty($errorMessage)) {
					echo '<br /> <center>'. $html->image('icons/exclamation2.png', array('style'=>'vertical-align: middle')) .' <strong>'. $errorMessage . '</strong> </center> ';
				} //end if

				//The paginator sets the div to update (mainContent) and the indicator div (ajaxLoaderGeneral) to show while it is updating
				//$paginator->options(array('url'=>array('controller'=>'Computers', 'action'=>'index')));
				$paginator->options(array('update' => 'mainContent', 'indicator' => 'ajaxLoaderGeneral', 'url'=>array('controller'=>'Computers', 'action'=>'index')));

				echo'<table width="100% align="center">
					<tr>
						<td colspan="8" align="left">
						  <span id="navTools">';
				echo		$html->link($html->image('buttons/button_add-new-computer.png',
								array('title' => 'Add a New Computer', 'border' => 0)), "add", array('escape'=>false, 'class' => 'navTools'));
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
						'. $paginator->sort('Created', 'created') .'
					  </th>
					  <th>
						'. $paginator->sort('Modified', 'modified') .'
					  </th>
					  <th>
						'. $paginator->sort('Name', 'name') .'
					  </th>
					  <th>
						'. $paginator->sort('S/N', 'serial_number') .'
					  </th>
					  <th>
						'. $paginator->sort('Memory', 'memory') .'
					  </th>
					  <th>
						'. $paginator->sort('HDD', 'hdd_space') .'
					  </th>
					  <th>
						Assigned To
					  </th>
					  <td width="40px">
						&nbsp;
					  </td>
					</tr>';


				foreach ($computers as $computer):
					echo '
						<tr>
						  <td>
							'.
							////ID////
							  $computer['Computer']['id']
							//////////
							.'
						  </td>
						  <td>
							'.
							////CREATED////
							  $computer['Computer']['created']
							///////////////
							.'
						  </td>
						  <td>
							'.
							////MODIFIED////
							  $computer['Computer']['modified']
							////////////////
							.'
						  </td>
						  <td>
							'.
							////NAME////
							  $computer['Computer']['name']
							////////////
							.'
						  </td>
						  <td>
							'.
							////SERIAL////
							  $computer['Computer']['serial_number']
							//////////////
							.'
						  </td>
						  <td>
							'.
							////MEMORY////
							  $computer['Computer']['memory'] . 'GB'
							//////////////
							.'
						  </td>
						  <td>
							'.
							////HDD SPACE////
							  $computer['Computer']['hdd_space'] . 'GB'
							////////////////
							.'
						  </td>
						  <td>
							';
							////ASSIGNED TO////
							  foreach($admins as $admin) :
								//Check if an administrator/technician is using the computer
								if($admin['admins']['computer_id'] == $computer['Computer']['id']) {

									//Check if they are a technician or administrator
									if($admin['admins']['type'] == 1) {
										echo $admin['admins']['username'] . ' (administrator)';
									} else if($admin['admins']['type'] == 2) {
										echo $admin['admins']['username'] . ' (technician)';
									} //end if
								} //end if
							  endforeach;

							  foreach($users as $user) :
								//Check if an employee/user is using the computer
								if($user['users']['computer_id'] == $computer['Computer']['id']) {
									echo $user['users']['username'] . ' (employee)';
								} //end if
							  endforeach;
							//////////////////
							echo'
						  </td>
						  <td>'; 
								////FUNCTIONS////

								echo '<table cellpadding="0" cellspacing="0">
										<tr>
										  <td>';
											echo $html->link($html->image("icons/computer_go.png", array('title' => 'View', 'border' => 0)),"/computers/view/{$computer['Computer']['id']}", array('escape'=>false));
								echo'	  </td>
										  <td>';
											echo $html->link($html->image("icons/computer_edit.png", array('title' => 'Edit', 'border' => 0)), "/computers/edit/{$computer['Computer']['id']}", array('escape'=>false));
								echo'	  </td>
										  <td>';

								//Setup the delete button
								$assigned = false;

								  foreach($admins as $admin) :
									//Check if an administrator/technician is using the computer
									if($admin['admins']['computer_id'] == $computer['Computer']['id']) {

										//Check if they are a technician or administrator
										if($admin['admins']['type'] == 1) {
											echo $html->link($html->image("icons/computer_delete.png", array('title' => 'Delete', 'border' => 0)), 
															 "/computers/delete/{$computer['Computer']['id']}", array('escape'=>false),  
															 "WARNING: This computer (". $computer['Computer']['name'] .") is assigned to someone. If you continue they will no longer have a computer assigned");
											$assigned = true;
										} else if($admin['admins']['type'] == 2) {
											echo $html->link($html->image("icons/computer_delete.png", array('title' => 'Delete', 'border' => 0)), 
															 "/computers/delete/{$computer['Computer']['id']}", array('escape'=>false), 
															 "WARNING: This computer (". $computer['Computer']['name'] .") is assigned to someone. If you continue they will no longer have a computer assigned");
											$assigned = true;
										} //end if
									} //end if
								  endforeach;

								  foreach($users as $user) :
									//Check if an employee/user is using the computer
									if($user['users']['computer_id'] == $computer['Computer']['id']) {
										echo $html->link($html->image("icons/computer_delete.png", array('title' => 'Delete', 'border' => 0)), 
															 "/computers/delete/{$computer['Computer']['id']}", array('escape'=>false), 
															 "WARNING: This computer (". $computer['Computer']['name'] .") is assigned to someone. If you continue they will no longer have a computer assigned");
										$assigned = true;
									} //end if
								  endforeach;

								  if($assigned == false) {
								  echo $html->link($html->image("icons/computer_delete.png", array('title' => 'Delete', 'border' => 0)), 
															 "/computers/delete/{$computer['Computer']['id']}", array('escape'=>false), 
															 "Are you sure you want to permanently delete computer: ". $computer['Computer']['name']);
								  } //end if

								echo'	  </td>
										</tr>
									  </table>';
								//////////////////
						echo'</td>
						</tr>
					';
				//We must unset the $assigned message so the variable can be used again for the next computer
				unset($assigned);
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