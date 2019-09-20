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

if($user->getAuth()) {
    $app->redirect($config->site.'/index.php?view=register&layout=step');
}

?>

<div class="wrap">
    
    <div class="container">

        <div class="col-xs-12 col-md-6 col-md-offset-3">
    	    <form class="form-signin" name="login-form" id="login-form" action="<?= $config->site; ?>/index.php?view=register&amp;task=login" method="post">
        		<h2><?= $lang->replace('CW_LOGIN_TITLE', $config->sitename); ?></h2>
        		<hr>
        		
        		<!-- Username-->
                <?= $html->getEmailField('login', 'email'); ?>
                <!-- Password -->
                <?= $html->getPasswordField('login', 'password'); ?>
                <!-- Last visit -->
                <?= $html->getTextField('login', 'lastvisitDate', date('Y-m-d H:i:s')); ?>    
                <!-- Language -->
                <?= $html->getTextField('login', 'language', 'en-gb'); ?>               
				<!-- Token -->
    	        <?= $html->getTextField('login', 'auth_token', $app->setToken()); ?>
    	        <!-- Submit -->
    	        <?= $html->getButton('login', 'submit'); ?> 
    	    </form>
	        <p style="margin-top:10px;"><a href="index.php?view=register&amp;layout=reset"><i class="fa fa-question hasTip" title="<?= $lang->get('CW_LOGIN_FORGOT_PASSWORD'); ?>"></i> <?= $lang->get('CW_LOGIN_FORGOT_PASSWORD'); ?></a></p>
	        <p style="margin-top:10px;"><a href="index.php?view=register"><i class="fa fa-user hasTip" title="<?= $lang->get('CW_LOGIN_CREATE_ACCOUNT'); ?>"></i> <?= $lang->get('CW_LOGIN_CREATE_ACCOUNT'); ?></a></p>
	    </div>
    </div>
    <hr>
    
</div> <!-- /container -->
    
</div> <!-- /wrap -->
