<?php
/**
 * @version     1.0.0 Afi Framework $
 * @package     Afi Framework
 * @copyright   Copyright © 2014 - All rights reserved.
 * @license	    GNU/GPL
 * @author	    kim
 * @author mail kim@afi.cat
 * @website	    http://www.afi.cat
 *
*/

defined('_Afi') or die ('restricted access');
//if(!$user->getAuth()) { $app->redirect('index.php?view=cpanel'); }
$model = $app->getModel();
$data  = $model->getUserData();
?>

<div class="wrap">

<div id="page-wrapper">
		<div class="col-md-12">
		<?php include('template/'.$config->admin_template.'/message.php'); ?>
		</div>
            <div class="row border-bottom">
                <div class="col-lg-6">
                    <h1 class="page-header"><i class="fa fa-user"></i> <?=  $lang->get('CW_MENU_USER_EDIT'); ?></h1>
                </div>
		<div class="col-lg-6">
			<div class="page-header pull-right">
                   <a onclick="userForm.submit();" href="#" class="btn btn-success"><i class="fa fa-floppy-o"></i> Guardar</a>
			</div>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
		<form name="userForm" id="userForm" method="post" action="index.php?view=users&task=saveUser">
                <div class="col-lg-6">
			
				<!-- username -->
		        <?=  $html->getTextField('user', 'username', $data->username); ?>
				<!-- email -->
		        <?=  $html->getTextField('user', 'email', $data->email); ?>
				<!-- usergroup -->
		        <?=  $html->getUsergroupsField('user', 'level', $data->level); ?>
			
		</div>
		<div class="col-lg-6">

				<!-- password -->
		        <?=  $html->getTextField('user', 'password'); ?>
				<!-- password2 -->
		        <?=  $html->getTextField('user', 'password2'); ?>
	
                </div>

		</form>
            </div>

		
            </div>
        </div>
        <!-- /#page-wrapper -->
</div>
