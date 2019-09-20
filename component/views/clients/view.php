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

if(!$model->isAdmin()) { $app->redirect('index.php?view=home', 'Ho sentim no tens el privilegi', 'danger'); }

$app->addScript('template/clean/js/moment.js');
$app->addScript('template/clean/js/bootstrap-datetimepicker.min.js');
$app->addStylesheet('template/clean/css/bootstrap-datetimepicker.min.css');
$app->addScript('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/locale/ca.js');
