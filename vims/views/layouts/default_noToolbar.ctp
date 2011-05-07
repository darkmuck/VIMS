<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo $title_for_layout?></title>
	<?php echo $html->css('default.css', 'stylesheet', array("media"=>"all" )); ?>
	<?php echo $javascript->link(array('prototype','scriptaculous')); ?>
	<?php echo $scripts_for_layout ?>
</head>
<body>

<div id="container">

	<div id="header">
		
	</div>

	<div id="content">
	  <table height="100%" width="860px">
		<tr>
		  <td width="860px">
			<table class="tblGeneral">
			  <tr>
				<td align="left" width="100%" colspan="2">
				  <?php echo $html->image('vims_logo.PNG'); ?>
				</td>
			  </tr>
				<tr>
				  <td width="100%" colspan="2">
					<table cellspacing="0" cellpadding="0" width="100%">
						<tr>
						  <td>
						  <div class="nav">
							<!-- NO TOOLBAR -->
						  </div>
						  </td>
						</tr>
					</table>
				  </td>
				</tr>
			  <tr>
				<td align="left" width="95%">
				  <div id="breadCrumb" class="breadCrumb">
					<?php echo $html->getCrumbs(' > ','Home'); ?>
				  </div>
				<td align="right" width="5%">
					&nbsp;
				</td>
				</tr><tr>
				<td width="100%" colspan="2">
				  <div id="mainContent">
					<?php echo $content_for_layout ?>
				  </div>
				</td>
			  </tr>
			</table>
		  </td>
		</tr>
		<tr>
		  <td width="860px" valign="bottom" align="center" height="50px">
			<?php echo $html->image('voyager_software.PNG'); ?>
		  </td>
		</tr>
	  </table>

	</div>

	<div id="footer">
		
	</div>

</div>

</body>
</html>