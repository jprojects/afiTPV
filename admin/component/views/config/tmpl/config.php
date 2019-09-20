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
?>

<div class="wrap">

<div id="page-wrapper">
		<div class="col-md-12">
		<?php include('template/'.$config->admin_template.'/message.php'); ?>
		</div>
            <div class="row border-bottom">
                <div class="col-lg-6">
                    <h1 class="page-header"><i class="fa fa-user"></i> <?php echo $lang->get('Configuració'); ?></h1>
                </div>
		<div class="col-lg-6">
			<div class="page-header pull-right">
                   <a onclick="configForm.submit();" href="#" class="btn btn-success"><i class="fa fa-floppy-o"></i> Guardar</a>
			</div>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
		<form name="configForm" id="configForm" method="post" action="index.php?view=config&task=saveConfig">
                <div class="col-xs-12 col-lg-6">
			
			<!-- site -->
		        <?php echo $html->getTextField('config', 'site', $config->site); ?>
			<!-- sitename -->
		        <?php echo $html->getTextField('config', 'sitename', $config->sitename); ?>
			<!-- description -->
		        <?php echo $html->getTextField('config', 'description', $config->description); ?>
			<!-- email -->
		        <?php echo $html->getTextField('config', 'email', $config->email); ?>
			<!-- token time -->
		        <?php echo $html->getTextField('config', 'token_time', $config->token_time); ?>
 			<!-- template -->
		        <?php echo $html->getTextField('config', 'template', $config->template); ?>
			<!-- admin_template -->
		        <?php echo $html->getTextField('config', 'admin_template', $config->admin_template); ?>
			<!-- cookie -->
		        <?php echo $html->getTextField('config', 'cookie', $config->cookie); ?>
			<!-- inactive -->
		        <?php echo $html->getTextField('config', 'inactive', $config->inactive); ?>
			<!-- domain -->
		        <?php echo $html->getTextField('config', 'domain', $config->domain); ?>
			<!-- log -->
		        <?php echo $html->getTextField('config', 'log', $config->log); ?>
			
                </div>
		<div class="col-xs-12 col-lg-6">

			<!-- driver -->
		        <?php echo $html->getListField('config', 'driver', $config->driver); ?>
 			<!-- host -->
		        <?php echo $html->getTextField('config', 'host', $config->host); ?>
			<!-- user -->
		        <?php echo $html->getTextField('config', 'user', $config->user); ?>
			<!-- pass -->
		        <?php echo $html->getTextField('config', 'pass', $config->pass); ?>
			<!-- database -->
		        <?php echo $html->getTextField('config', 'database', $config->database); ?>
			<!-- dbprefix -->
		        <?php echo $html->getTextField('config', 'dbprefix', $config->dbprefix); ?>
			<!-- offline -->
		        <?php echo $html->getRadioField('config', 'offline', $config->offline); ?>
			<!-- debug -->
		        <?php echo $html->getRadioField('config', 'debug', $config->debug); ?>
			<!-- admin_mails -->
		        <?php echo $html->getRadioField('config', 'admin_mails', $config->admin_mails); ?>

		</div>
		</form>
            </div>

		
            </div>
        </div>
        <!-- /#page-wrapper -->
</div>
