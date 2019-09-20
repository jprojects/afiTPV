<?php

/**
 * @version     1.0.0 Afi framework $
 * @package     Afi framework
 * @copyright   Copyright © 2016 - All rights reserved.
 * @license	    GNU/GPL
 * @author	    kim
 * @author mail kim@afi.cat
 * @website	    http://www.afi.cat
 *
*/

defined('_Afi') or die ('restricted access');
include_once('helper.php');
$config = factory::getConfig();
$app    = factory::getApplication();
$db     = factory::getDatabase();
$user   = factory::getUser();
$lang   = factory::getLanguage();
$url    = factory::getUrl();
$view   = $app->getVar('view', 'home', 'get', 'string');
?>

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><?= $config->sitename; ?> 
            <?php if($user->getAuth()) : ?>
            <span class="hidden-xs">· <?= $user->username; ?> · Saló <?= $user->cf_salo; ?> · Terminal <?= $user->cf_terminal; ?></span></a> 
            <?php endif; ?>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav pull-right hidden-xs">
                
                <?php if($user->getAuth()) : ?>
                <li><a href="<?= $config->site; ?>/index.php?view=register&amp;task=logout" class="logout navbar-link" title="<?= $lang->get('CW_MENU_LOGOUT'); ?>"><i class="fa fa-sign-out"></i></a></li>   
                <li <?php if($view == 'config') : ?>class="active"<?php endif; ?>><a href="index.php?view=config" title="<?=$lang->get('CW_MENU_CONFIG'); ?>"><i class="fa fa-cog"></i></a></li>
                <?php endif; ?>
                                    
            </ul>
            <ul class="nav navbar-nav pull-right">
            	<?php foreach(topmenuHelper::getItems() as $item) : ?>

				<?php if($item->level == 0) : ?>
                <li <?php if($view == $item->slug) : ?>class="active"<?php endif; ?>>
                	<a href="<?= $item->url; ?>"><?= $lang->get($item->titol); ?></a>
                </li>  
                <?php else : ?>
                	<?php if($user->getAuth()) : ?>
                	<li <?php if($view == $item->slug) : ?>class="active"<?php endif; ?>>
                		<a href="<?= $item->url; ?>"><?= $lang->get($item->titol); ?></a>
                	</li> 
                	<?php endif; ?> 
                <?php endif; ?>             
                             
				<?php endforeach; ?>
            </ul>
      </div><!--/.nav-collapse -->
    </div>
</div>
