<?php
/**********
View Name: add
Description: This view allows an administrator or technician to add a new article to the database
Access Control: Administrators (yes) | Technicians (yes) | Users (no)
Last Modified: 2009/03/24 03:03 PM
Last Modified By: William DiStefano
**********/

//The following is required to use the tinyMCE editor
echo'
	<script type="text/javascript">
	    tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		skin : "o2k7"
	    });
	</script>
';


//BREADCRUMB//
$breadCrumbText = 'Knowledge Base';
$breadCrumbUrl = '/kbentries/';
$html->addCrumb($breadCrumbText, $breadCrumbUrl);

$breadCrumbText = 'Add';
$breadCrumbUrl = '/kbentries/add/';
$html->addCrumb($breadCrumbText, $breadCrumbUrl);
//////////////

//HEADING TEXT//
echo '<h2> Add New Knowledge Base Article </h2>';
////////////////

//Technician starts

	//Check administrator type
	if($sessionAdmin[0]['admins']['type'] == 2) {

//////////////// tech start


		if (!empty($errorMessage)) {
			echo '<br /> <center>'. $html->image('icons/exclamation2.png', array('style'=>'vertical-align: middle')) .' <strong>'. $errorMessage . '</strong> </center> ';
		} //end if


	echo '<div class="navTools">';
				echo 
					'&nbsp;' .
					$html->link($html->image("buttons/button_cancel.png", array('title' => 'Cancel', 'border' => 0)), "/kbentries/", array('escape'=>false, 'class'=>'navTools'))
				;
	echo '</div>';



	//Start HTML Form
	echo $form->create('Kbentry', array('action' => 'add'));

	//We need to have this admin_id here (but hidden) so it is added to the database upon submitting the new Knowledge Base Entry
	echo $form->input('admin_id', array('type'=>'hidden', 'value' => $sessionAdmin[0]['admins']['id']));


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
		Title: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $form->input('Kbentry.title', array('maxlength' => '50', 'size' => 119, 'label' => ''));
	echo'</td></tr><tr>
	
	
		<td align="right" valign="top" width="100">
		Content: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $form->input('Kbentry.content', array('label' => '', 'rows' => 20, 'cols' => 90));
		echo'</td>
		</tr></table>
	</td></tr>
	 <tr>
		<td>&nbsp;</td>
		<td align="center" valign="top">';
		echo '</font>' . $form->end('Create'); //end the form
	echo'</td></tr>
	</table>';
////////// tech end




		;

//Technician ends

//Admin begins

	}else if($sessionAdmin[0]['admins']['type'] == 1) {


		if (!empty($errorMessage)) {
			echo '<br /> <center>'. $html->image('icons/exclamation2.png', array('style'=>'vertical-align: middle')) .' <strong>'. $errorMessage . '</strong> </center> ';
		} //end if


	echo '<div class="navTools">';
				echo 
					'&nbsp;' .
					$html->link($html->image("buttons/button_cancel.png", array('title' => 'Cancel', 'border' => 0)), "/kbentries/", array('escape'=>false, 'class'=>'navTools'))
				;
	echo '</div>';



	//Start HTML Form
	echo $form->create('Kbentry', array('action' => 'add'));

	//We need to have this admin_id here (but hidden) so it is added to the database upon submitting the new Knowledge Base Entry
	echo $form->input('admin_id', array('type'=>'hidden', 'value' => $sessionAdmin[0]['admins']['id']));


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
		Title: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $form->input('Kbentry.title', array('maxlength' => '50', 'size' => 119, 'label' => ''));
	echo'</td></tr><tr>
	
	
		<td align="right" valign="top" width="100">
		Content: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $form->input('Kbentry.content', array('label' => '', 'rows' => 20, 'cols' => 90));
		echo'</td>
		</tr></table>
	</td></tr>
	 <tr>
		<td>&nbsp;</td>
		<td align="center" valign="top">';
		echo '</font>' . $form->end('Create'); //end the form
	echo'</td></tr>
	</table>';

//Admin ends

	} else {
	
		echo'<div class="divContentCenter">
				You do not have access to this area.
			 </div>'
		;
	
	}

?>