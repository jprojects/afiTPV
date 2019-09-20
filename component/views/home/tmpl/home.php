<?php
/**
 * @version     1.0.0 Deziro $
 * @package     Deziro
 * @copyright   Copyright © 2014 - All rights reserved.
 * @license	    GNU/GPL
 * @author	    kim
 * @author mail info@dezi.ro
 * @website	    http://www.dezi.ro
 *
*/

defined('_Afi') or die ('restricted access');

if($user->getAuth()) {
    $app->redirect($config->site.'/index.php?view=register&layout=step');
}

?>

<div class="main" style="background-image: url('assets/img/home.jpg')">

    <div class="cover black" data-color="black"></div>

    <div class="container">

        <div class="col-xs-12 col-md-6 col-md-offset-3" style="position:relative;z-index:3;text-align:center;">
        
        	<img class="logo" src="assets/img/logo.png" alt="<?= $config->sitename; ?>" />      
        
        	<?php if(!$user->getAuth()) : ?>
    	    <form class="form-signin" name="login-form" id="login-form" action="<?= $config->site; ?>/index.php?view=register&task=login" method="post">       		
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
	        <?php endif; ?>
	        
	    </div>
    
    </div>
 </div>

