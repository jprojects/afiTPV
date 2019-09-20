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

?>

<div class="wrap">

    <div class="container">
        <div class="col-md-12">
	    <form  class="form-signin" name="register-form" id="register-form" action='<?= $config->site; ?>/index.php?view=register&amp;task=reset' method="post">
	        <h2><?= $lang->get('CW_RESET_TITLE'); ?></h2>
	        <hr>
	        <?= $lang->get('CW_RESET_DESC'); ?>
	        <hr>

    	    <?= $html->getEmailField('reset', 'email'); ?>

    	    <!-- Security token -->
    	    <input type="hidden" name="auth_token" value="<?= $app->setToken(); ?>" />
    	    <button onclick="this.form.submit();" id="resetBtn" class="btn btn-success"><?= $lang->get('CW_SEND'); ?></button>
    	   
	    </form>
	    </div>
	</div>
