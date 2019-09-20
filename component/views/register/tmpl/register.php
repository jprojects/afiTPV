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
    $app->redirect($config->site);
}

?>

<div class="main" style="background-image: url('assets/img/video_bg.jpg')">
    <video id="video_background" preload="auto" autoplay="true" loop="loop" muted="muted" volume="0">
        <source src="<?= $config->site; ?>/template/<?= $config->template; ?>/video/time.webm" type="video/webm">
        <source src="<?= $config->site; ?>/template/<?= $config->template; ?>/video/time.mp4" type="video/mp4">
        <?= $lang->get('CW_VIDEO_NO_SUPORTED'); ?>
    </video>

    <div class="cover black" data-color="black"></div>

    <div class="container">

        <div class="col-xs-12 col-md-6 col-md-offset-3" style="position:relative;z-index:3;text-align:center;">
        
        	<img class="logo" src="assets/img/logo.png" alt="<?= $config->sitename; ?>" />
        
        	<?php if(!$user->getAuth()) : ?>
    	    <form autocomplete="off"  class="form-signin" name="register-form" id="registerForm" action="index.php?view=register&amp;task=register" method="post" data-toggle="validator">    
        	    <!-- E-mail -->
        	    <?php echo $html->getEmailField('register', 'email', ''); ?>
        	    <!-- Password-->
        	    <?php echo $html->getPasswordField('register', 'password', ''); ?>
        	    <!-- Password2 -->
        	    <?php echo $html->getPasswordField('register', 'password2', ''); ?>
        	    <!-- Security token -->
        	    <?php echo $html->getTextField('register', 'auth_token', $app->setToken()); ?>
        	    <!-- Submit button -->
    	        <?php echo $html->getButton('register', 'submit'); ?> 
        	   
    	    </form>  
    	    <p style="margin-top:5px;"> 	    	        
	        <a href="index.php?view=home" class="btn btn-success btn-block btn-lg">Login</a>
	        </p>
	        <?php else : ?>
	        <a href="<?= $config->site; ?>/index.php?view=register&task=logout" class="btn btn-success btn-block"><?= $lang->get('CW_MENU_LOGOUT'); ?></a>
	        <?php endif; ?>
	        
	    </div>
    
    </div>
 </div>

