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
//if(!$user->getAuth()) { $app->redirect($config->domain); }
$model = $app->getModel();
$data  = $model->getUsergroupData();
?>

<div class="wrap">

<div id="page-wrapper">
		<div class="col-md-12">
		<?php include('template/'.$config->admin_template.'/message.php'); ?>
		</div>
            <div class="row border-bottom">
                <div class="col-lg-6">
                    <h1 class="page-header"><i class="fa fa-user"></i> <?<?=  $lang->get('CW_MENU_USERGROUP_EDIT'); ?></h1>
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
		<form name="userForm" id="userForm" method="post" action="<?=  $config->site; ?>/admin/index.php?view=usergroups&task=saveUsergroup">
                <div class="col-lg-12">
			
					<!-- name -->
		        	<?= $html->getTextField('usergroup', 'usergroup', $data->usergroup); ?>
			
                </div>

		</form>
            </div>

		
            </div>
        </div>
        <!-- /#page-wrapper -->
</div>
