<?php
/**********
View Name: edit
Description: This view will display detailed information about a particular article and allow modifications
Access Control: Administrators (yes) | Technicians (yes) | Users (no)
Last Modified: 2009/03/24 03:02 PM
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


foreach ($selectedKbentry as $kbentry):
//BREADCRUMB//
$breadCrumbText = 'Knowledge Base';
$breadCrumbUrl = '/kbentries/';
$html->addCrumb($breadCrumbText, $breadCrumbUrl);

$breadCrumbText = 'Modify - ID: ' . $kbentry['kbentries']['id'];
$breadCrumbUrl = '/kbentries/edit/' . $kbentry['kbentries']['id'];
$html->addCrumb($breadCrumbText, $breadCrumbUrl);
//////////////
endforeach;

//HEADING TEXT//
echo '<h2> Modify Knowledge Base Article </h2>';
////////////////


	//Check administrator type
	if($sessionAdmin[0]['admins']['type'] == 2) {


		if (!empty($errorMessage)) {
			echo '<br /> <center>'. $html->image('icons/exclamation2.png', array('style'=>'vertical-align: middle')) .' <strong>'. $errorMessage . '</strong> </center> ';
		} //end if


foreach ($selectedKbentry as $kbentry):
	echo '<div class="navTools">';
				echo 
					'&nbsp;' .
					$html->link($html->image("buttons/button_cancel.png", array('title' => 'Cancel', 'border' => 0)), "/kbentries/", array('escape'=>false, 'class'=>'navTools')) .

					$html->link($html->image("buttons/button_article-delete.png", array('title' => 'Delete', 'border' => 0)), 
															 "/kbentries/delete/{$kbentry['kbentries']['id']}", array('escape'=>false), 
															 "Are you sure you want to permanently delete KnowledgeBase Entry: ". 
															 $kbentry['kbentries']['title'] )
				;
	echo '</div>';
endforeach;


foreach ($selectedKbentry as $kbentry):
	//Start HTML Form
	echo $form->create('Kbentry', array('action' => 'edit'));

	//We need to have the ID present so cakephp fills in most form fields automatically
	echo $form->input('id', array('type'=>'hidden'));

	//We need to have this admin_id here (but hidden) so it is added to the database upon submitting the new article
	echo $form->input('admin_id', array('type'=>'hidden', 'value' => $kbentry['kbentries']['admin_id']));

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
			foreach($selectedKbentry as $kbentry):
				echo $kbentry['kbentries']['created'];
			endforeach;
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Last Modified: &nbsp;&nbsp;
		</td>
		
		<td valign="top">';
			foreach($selectedKbentry as $kbentry) :
				echo $kbentry['kbentries']['modified'];
			endforeach;
	echo'</td></tr><tr>

		<td align="right" valign="top" width="100">
		Author: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			foreach($admins as $admin) :
				if($admin['admins']['id'] == $kbentry['kbentries']['admin_id']) {
					echo $admin['admins']['last_name'] . ', ' . $admin['admins']['first_name'];
					if (!empty($admin['admins']['middle_name'])) {  //show the middle initial
					  echo ' ' . substr($admin['admins']['middle_name'], 0, 1) . '.';
					} //end if
					echo ' ('. $admin['admins']['username'] .')';
				} //end if
			endforeach;
	echo'</td></tr><tr>
	
		<td align="right" valign="top" width="100">
		Title: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $form->input('Kbentry.title', array('maxlength' => '50','size' => '113', 'label' => ''));
	echo'</td></tr><tr>


	
		<td align="right" valign="top" width="100">
		Content: &nbsp;&nbsp;
		</td>
		<td valign="top">';

			echo $form->input('Kbentry.content', array('label' => '', 'rows' => 20, 'cols' => 85));

		echo'</td>
		
		
		
		
		</tr></table>
	</td></tr>
	 <tr>
		<td>&nbsp;</td>
		<td align="center" valign="top">';
		echo '</font>' . $form->end('Update'); //end the form
	echo'</td></tr>
	</table>';
endforeach;
		;

	}else if($sessionAdmin[0]['admins']['type'] == 1) {


foreach ($selectedKbentry as $kbentry):
	echo '<div class="navTools">';
				echo 
					'&nbsp;' .
					$html->link($html->image("buttons/button_cancel.png", array('title' => 'Cancel', 'border' => 0)), "/kbentries/", array('escape'=>false, 'class'=>'navTools')) .

					$html->link($html->image("buttons/button_article-delete.png", array('title' => 'Delete', 'border' => 0)), 
															 "/kbentries/delete/{$kbentry['kbentries']['id']}", array('escape'=>false), 
															 "Are you sure you want to permanently delete KnowledgeBase Entry: ". 
															 $kbentry['kbentries']['title'] )
				;
	echo '</div>';
endforeach;

		if (!empty($errorMessage)) {
			echo '<br /> <center>'. $html->image('icons/exclamation2.png', array('style'=>'vertical-align: middle')) .' <strong>'. $errorMessage . '</strong> </center> ';
		} //end if


foreach ($selectedKbentry as $kbentry):
	//Start HTML Form
	echo $form->create('Kbentry', array('action' => 'edit'));

	//We need to have the ID present so cakephp fills in most form fields automatically
	echo $form->input('id', array('type'=>'hidden'));

	//We need to have this admin_id here (but hidden) so it is added to the database upon submitting the new article
	echo $form->input('admin_id', array('type'=>'hidden', 'value' => $kbentry['kbentries']['admin_id']));

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
			foreach($selectedKbentry as $kbentry):
				echo $kbentry['kbentries']['created'];
			endforeach;
	echo'</td></tr><tr>
		<td align="right" valign="top" width="100">
		Last Modified: &nbsp;&nbsp;
		</td>
		
		<td valign="top">';
			foreach($selectedKbentry as $kbentry) :
				echo $kbentry['kbentries']['modified'];
			endforeach;
	echo'</td></tr><tr>

		<td align="right" valign="top" width="100">
		Author: &nbsp;&nbsp;
		</td>
		
		<td valign="top">';
			foreach($admins as $admin) :
				if($admin['admins']['id'] == $kbentry['kbentries']['admin_id']) {
					echo $admin['admins']['last_name'] . ', ' . $admin['admins']['first_name'];
					if (!empty($admin['admins']['middle_name'])) {  //show the middle initial
					  echo ' ' . substr($admin['admins']['middle_name'], 0, 1) . '.';
					} //end if
					echo ' ('. $admin['admins']['username'] .')';
				} //end if
			endforeach;
	echo'</td></tr><tr>
	
		<td align="right" valign="top" width="100">
		Title: &nbsp;&nbsp;
		</td>
		<td valign="top">';
			echo $form->input('Kbentry.title', array('maxlength' => '50', 'size' => '113', 'label' => ''));
	echo'</td></tr><tr>


	
		<td align="right" valign="top" width="100">
		Content: &nbsp;&nbsp;
		</td>
		<td valign="top">';

			echo $form->input('Kbentry.content', array('label' => '', 'rows' => 20, 'cols' => 85));

		echo'</td>
		
		
		
		
		</tr></table>
	</td></tr>
	 <tr>
		<td>&nbsp;</td>
		<td align="center" valign="top">';
		echo '</font>' . $form->end('Update'); //end the form
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