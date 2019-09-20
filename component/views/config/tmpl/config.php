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

if(!$user->getAuth()) {
    $app->redirect($config->site);
}

$tab = $app->getVar('tab', 'profile', 'get');
$model = $app->getModel();
?>

<script>
//save image in database
function saveImatge () {
	var imageData = $('#image-cropper').cropit('export');
	$('.hidden-image-data').val(imageData);
	$('#logo').val(0);
	$('#avatar').submit();
}
function saveLogo() {
	var imageData = $('#image-cropper').cropit('export');
	$('.hidden-image-data').val(imageData);
	$('#logo').val(1);
	$('#avatar').submit();
}
function openImatge() {
	$('.cropit-image-input').click();
}
$(document).ready(function() {
	$('#uploadModal').click(function(e) {
		e.preventDefault();
		var options = {
        url: this.href,
        title:'Pujar imatge',
        size: eModal.size.md,
        subtitle: "<br>Puja la teva imatge d'usuari o fes-te una foto",
        buttons: [
        	{text: 'Obrir', style: 'success open-image', close: false, click: openImatge },
            {text: 'Guardar', style: 'success save-image', close: true, click: saveImatge }
        ]
    };
		eModal.ajax(options);
	});
	$('#logoModal').click(function(e) {
		e.preventDefault();
		var options = {
        url: this.href,
        title:'Pujar logo',
        size: eModal.size.md,
        subtitle: "<br>Puja el logo de la teva empresa per mostrar al ticket",
        buttons: [
        	{text: 'Obrir', style: 'success open-image', close: false, click: openImatge },
            {text: 'Guardar', style: 'success save-logo', close: true, click: saveLogo }
        ]
    };
		eModal.ajax(options);
	});
});
</script>

<div class="container-fluid">
<section class="content">
	<div class="row">
	
		<!-- BEGIN PROJECT LIST -->
		<div class="col-md-3">
			<div class="grid support">
				<div class="grid-body">
					
					<h2>Imatge de perfil</h2>
					
					<hr>
					
					<?php $imgUrl = $config->site.'/assets/img/uploads/'.$user->image; ?>
					
					<div class="text-center">
						<a target="_blank" class="hasTip" title="Canvia el teu avatar">
							<img style="margin:0 auto;" class="img-responsive img-circle" src="<?= $imgUrl; ?>" alt="" />
						</a>
						<a href="index.php?view=config&layout=upload&mode=raw" class="btn btn-success" id="uploadModal">Pujar avatar</a>
					</div>
					
				</div>
			</div>
			
			<div class="grid support">
				<div class="grid-body">
					
					<h2>Logo d'empresa</h2>
					
					<hr>
					
					<?php $logoUrl = $config->site.'/assets/img/uploads/'.$model->configParam('empresa_logo'); ?>
					
					<div class="text-center">
						<a target="_blank" class="hasTip" title="Canvia el teu logo">
							<img style="margin:0 auto;" class="img-responsive img-circle" src="<?= $logoUrl; ?>" alt="" />
						</a>
						<a href="index.php?view=config&layout=upload&mode=raw" class="btn btn-success" id="logoModal">Pujar logo</a>
					</div>
					
				</div>
			</div>
		</div>
	
		<!-- BEGIN PROJECT LIST -->
		<div class="col-md-9">
			<div class="grid support-content">
				<div class="grid-body">
					
					<h2><?= $lang->get('CW_SETTINGS_TITLE'); ?></h2>
					
					<hr>

					<form class="form-signin" id="settings-form" action='<?=  $config->site; ?>/index.php?view=config&amp;task=saveConfig' method="post">
				
					<ul class="nav nav-tabs" style="margin-bottom: 20px;">
						<li <?php if($tab == 'profile') { echo "class='active'"; } ?>><a href="#profile" data-toggle="tab"><?=  $lang->get('CW_SETTINGS_PROFILE'); ?></a></li>
						<?php if($model->isAdmin()) : ?>
						<li <?php if($tab == 'app') { echo "class='active'"; } ?>><a href="#app" data-toggle="tab">App</a></li>
						<?php endif; ?>
						
					</ul>
			  
					<div id="myTabContent" class="tab-content top20">
						<div class="tab-pane <?php if($tab == 'profile') : ?>active in<?php endif; ?>" id="profile">
						    <!-- E-mail -->
						    <?=  $html->getTextField('config', 'email', $user->email); ?>
		
						    <!-- Old Password-->
						    <?=  $html->getTextField('config', 'old_password'); ?>
						    
						    <!-- Password-->
						    <?=  $html->getTextField('config', 'password'); ?>
					   
						    <!-- Password 2 -->
						    <?=  $html->getTextField('config', 'password2'); ?>  
						    
						    <!-- Language -->
						    <?=  $html->getListField('config', 'language', $user->language); ?>						    						    						    
						    <!-- Secretkey -->
						    <?=  $html->getTextField('config', 'secretkey', $user->token); ?> 
						    
						    <!-- Salo -->
						    <?=  $html->getListField('config', 'cf_salo', $user->cf_salo, $model->getSalons(), 'nom', 'id'); ?>
						    
						</div>
						
						<?php if($model->isAdmin()) : ?>
						<div class="tab-pane fade <?php if($tab == 'app') : ?>active in<?php endif; ?>" id="app">
														
						    
						    <a href="index.php?view=iva" class="pull-right"><i class="fa fa-plus"></i> Gestionar IVA</a>
						    								    
						    <!-- IVA Productes -->
						    <?=  $html->getListField('config', 'iva_productes', $model->configParam('iva_productes'), $model->getPercIVAs(), 'percIVA', 'id'); ?>
						    
						    <!-- IVA Serveis -->
						    <?=  $html->getListField('config', 'iva_serveis', $model->configParam('iva_serveis'), $model->getPercIVAs(), 'percIVA', 'id'); ?>
						    
						    <!-- IVA Serveis -->
						    <?=  $html->getListField('config', 'iva_serveis', $model->configParam('iva_serveis'), $model->getPercIVAs(), 'percIVA', 'id'); ?>
						    
						    <!-- Nom empresa -->
						    <?=  $html->getTextField('config', 'empresa_nom', $model->configParam('empresa_nom')); ?>
						    
						    <!-- CIF empresa -->
						    <?=  $html->getTextField('config', 'empresa_cif', $model->configParam('empresa_cif')); ?>
						    
						    <!-- Adreça empresa -->
						    <?=  $html->getTextField('config', 'empresa_adreca', $model->configParam('empresa_adreca')); ?>
						    
						    <!-- Poblacio empresa -->
						    <?=  $html->getTextField('config', 'empresa_poblacio', $model->configParam('empresa_poblacio')); ?>
						    
						    <!-- CP empresa -->
						    <?=  $html->getTextField('config', 'empresa_cp', $model->configParam('empresa_cp')); ?>
						    
						    <!-- Telefon empresa -->
						    <?=  $html->getTextField('config', 'empresa_telefon', $model->configParam('empresa_telefon')); ?>
						    
						</div>
						<?php endif; ?>
						
					</div>

					<!-- Security token -->
					<?=  $html->getTextField('config', 'auth_token', $app->setToken()); ?> 
			
					<?=  $html->getButton('config', 'submit'); ?> 
			
					<button data-target="#myModal" class="btn btn-danger pull-right" data-toggle="modal" data-original-title="<?=  $lang->get('CW_SETTINGS_DELETE_ACCOUNT'); ?>"><?=  $lang->get('CW_SETTINGS_DELETE_ACCOUNT'); ?></button>
				</form>

				<!-- modal delete -->
				<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
						    <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						        <h4 class="modal-title" id="myModalLabel"><?=  $lang->get('CW_SETTINGS_DELETE_ACCOUNT_TITLE'); ?></h4>
						    </div>
						    <div class="modal-body">
						        <?=  $lang->replace('CW_SETTINGS_DELETE_ACCOUNT_BODY', $config->sitename, $config->sitename); ?>
						        <input type="text" name="proceed" id="proceed" />
						    </div>
						    <div class="modal-footer">
						        <button class="btn" data-dismiss="modal"><?=  $lang->get('CW_CANCEL'); ?></button>
						        <button onclick="deleteAccount(<?=  $user->name; ?>,<?=  $config->site; ?>)';" class="btn btn-success" data-dismiss="modal"><?=  $lang->get('CW_DELETE'); ?></button>
						    </div>
						</div>
					</div>
				</div>
				<!-- end modal delete -->
		
			</div>
		</div>
	</div>
</section>
</div>
