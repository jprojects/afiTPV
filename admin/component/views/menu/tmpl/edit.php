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
$data  = $model->getMenuData();
$id    = $app->getVar('id', 0, 'get', 'int');
?>

<div class="wrap">

<div id="page-wrapper">
		<div class="col-md-12">
		<?php include('template/'.$config->admin_template.'/message.php'); ?>
		</div>
            <div class="row border-bottom">
                <div class="col-lg-6">
                    <h1 class="page-header"><i class="fa fa-list"></i> <?<?=  $lang->get('CW_MENU_MENU_EDIT'); ?></h1>
                </div>
		<div class="col-lg-6">
			<div class="page-header pull-right">
            	<a onclick="menuForm.submit();" href="#" class="btn btn-success"><i class="fa fa-floppy-o"></i> Guardar</a>
			</div>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
				<form name="menuForm" id="menuForm" method="post" action="<?=  $config->site; ?>/admin/index.php?view=menu&task=saveMenuItem">
		            <div class="col-lg-12">
						<input type="hidden" name="id" value="<?= $id; ?>" />
					
						<!-- titol -->
				    	<?= $html->getTextField('menu', 'titol', $data->titol); ?>
				    	<!-- url -->
				    	<?= $html->getTextField('menu', 'url', $data->url); ?>
						<!-- slug -->
				    	<?= $html->getTextField('menu', 'slug', $data->slug); ?>
				    	<!-- level -->
				    	<?= $html->getListField('menu', 'level', $data->level); ?>
				    	<!-- lang -->
				    	<?= $html->gettextField('menu', 'lang', $data->lang); ?>
				    	<!-- create -->
				    	<?= $html->getRadioField('menu', 'create', 0); ?>
			
		            </div>
				</form>
            </div>

		
            </div>
        </div>
        <!-- /#page-wrapper -->
</div>
