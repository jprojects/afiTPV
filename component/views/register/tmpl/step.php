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
$model = $app->getModel();
?>

<div class="main" style="background-image: url('assets/img/home.jpg')">

    <div class="cover black" data-color="black"></div>

    <div class="container">

        <div class="col-xs-12 col-md-6 col-md-offset-3" style="position:relative;z-index:3;text-align:center;">
        
        	<img class="logo" src="assets/img/logo.png" alt="<?= $config->sitename; ?>" />      
        
        	<?php if($user->getAuth()) : ?>
    	    <form class="form-signin" name="login-form" id="login-form" action="<?= $config->site; ?>/index.php?view=register&amp;task=custom.saveStep" method="post">
        		<h2><?= $lang->replace('CW_LOGIN_STEP', $user->name); ?></h2>
        		<hr>
        		
        		<!-- Salo-->
                <?= $html->getListField('step', 'cf_salo', $user->cf_salo, $model->getSalons(), 'nom', 'id'); ?>
                
                <!-- Terminal-->
                <?= $html->getListField('step', 'cf_terminal', $user->cf_terminal, $model->getTerminals(), 'nom', 'id'); ?>
                
                <button type="submit" class="btn btn-success btn-block btn-lg">Seguent</button>

    	    </form>    	    	        
	    	<?php endif; ?>
	        
	    </div>
    
    </div>
 </div>
