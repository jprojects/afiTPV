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

if(!$user->getAuth()) {
    $app->redirect($config->site);
}
if($user->level != 1) {
    $app->redirect('index.php?view=ticket');
}

$model = $app->getModel();
$total_pages = $_SESSION['total_pages'];
$page  = $app->getVar('page', 1, 'get');
$id    = $app->getVar('id', 0, 'get');
$dir   = $app->getVar('list_dir', 'DESC');
?>

<script>
$(document).ready(function() {
	$.ajaxSetup({
        async: false
    });
	$('.edit').click(function(e) {
		e.preventDefault();
		var id = $(this).attr('data-id');		
		var url = '<?= $config->site; ?>/index.php?view=articles&task=getItemById&id='+id+'&mode=raw';
		$.getJSON(url, function(json){
			//console.log(json.id);
			$('#article_id').val(json.id);
			$('#article_idTipusIVA').val(json.idTipusIVA);
			$('#article_idSalo').val(json.idSalo);
			$('#article_idFamilia').val(json.idFamilia);
			$('#article_descripcio').val(json.descripcio);
			$('#article_preuBase').val(json.preuBase);
			$('#article_importIVA').val(json.importIVA);
			$('#article_preuIVAInclos').val(json.preuIVAInclos);
			$('#article_codiBarres').val(json.codiBarres);
			$('#article_especificat').val(json.especificat);
			$('#article_servei').val(json.servei);
			$('#article_inventariable').val(json.inventariable);
			$('#article_estoc').val(json.estoc);
			$('#article_minim').val(json.minim);
			$('#article_preuCompra').val(json.preuCompra);
			$('#article_percMarge').val(json.percMarge);
			$('#article_actiu').val(json.actiu);
			$('#esborrar').html('<a href="index.php?view=articles&task=deleteItem&id='+json.id+'" class="btn btn-danger btn-block">Esborrar</a>');
			$('.submit').html('Editar');			
		});

			document.getElementById("form_disabled").removeAttribute("disabled");
			document.getElementById("form_button").innerHTML="Desa";
	});
	$('#article_preuIVAInclos').keyup(function() {
		var id 		  = $('#article_idTipusIVA').val();
		var url       = '<?= $config->site; ?>/index.php?view=articles&task=getIVAProductes&id='+id+'&mode=raw';
		$.getJSON(url, function(json) {
			//console.log(json.percIVA);
       		$('#article_percIVA').val(json.percIVA);
    	});
		var iva		  = $('#article_percIVA').val();
		var percIVA   = 1+(iva/100);
		var gross     = $(this).val();
		var preuBase  = gross / percIVA;
		var importIVA = gross - preuBase;				
		$('#article_preuBase').val(preuBase.toFixed(4));
		$('#article_importIVA').val(importIVA.toFixed(4));
	});
	$('#article_idTipusIVA').change(function() {
		var gross     = $('#article_preuIVAInclos').val();
		if(gross == '') { return; }
		var id 		  = $(this).val();
		var url       = '<?= $config->site; ?>/index.php?view=articles&task=getIVAProductes&id='+id+'&mode=raw';
		$.getJSON(url, function(json) {
			//console.log(json.percIVA);
       		$('#article_percIVA').val(json.percIVA);
    	});
		var iva		  = $('#article_percIVA').val();
		console.log(iva);
		var percIVA   = 1+(iva/100);
		var preuBase  = gross / percIVA;
		var importIVA = gross - preuBase;				
		$('#article_preuBase').val(preuBase.toFixed(4));
		$('#article_importIVA').val(importIVA.toFixed(4));
	});
	$('#article_servei').click(function() {
		if($(this).val() == 1) {
			$('#article_inventariable').val(0);
		}
	});
	$('#article_idSalo').change(function() {
		var id = $(this).val();
		var url = '<?= $config->site; ?>/index.php?view=articles&task=getFamiliesBySalo&id='+id+'&mode=raw';
		$.getJSON(url, function(json) {
			$('#article_idfamilia').empty();
			$('#article_idfamilia').append('<option value="">Escull familia</option>');
			$.each(json, function(k, v) {
				$('#article_idfamilia').append('<option value="'+v.id+'">'+v.descripcio+'</option>');
			});
		});
	});
});
</script>

<div class="container-fluid">
<section class="content">
	<div class="row">
		
		<!-- BEGIN LIST -->
		<div class="col-md-6">
			<div class="grid support-content">
				<div class="grid-body">

					<div class="row">
					 	<div class="col-md-6">
						 	<h2>Articles</h2>
					 	</div>
					 	<div class="col-md-6 text-right">
							<?= $html->renderButtons('articles', 'articles'); ?>
						</div>
					</div>

					<hr>

					<div class="row">
					 	<div class="col-md-12">
					 	<?= $html->renderFilters('articles', 'articles'); ?>
					 	</div>
					</div>

					<?php $get = $_GET; ?>

					<div class="padding"></div>

					<form action="" method="get" id="itemsList" name="itemsList">
            		<input type="hidden" name="view" value="articles">
            		<input type="hidden" name="list_column" id="list_column">
            		<input type="hidden" name="list_dir" id="list_dir" value="<?= $dir; ?>">
						<div class="row">
							<!-- BEGIN TICKET CONTENT -->
							<div class="col-md-12">
								<table class="table table-striped">
									<thead>
										<tr>
											<th width="1%"><input type="checkbox" id="selectAll"></th>
											<th width="75%">
												<a href="#" class="text-primary order" data-field="descripcio">Article</a>
												<i class="fa <?= $dir == 'DESC' ? 'fa-caret-up' : 'fa-caret-down'; ?> fa-lg"></i>
											</th>											
											<th class="text-right" width="24%">Drag</th>
										</tr>
									</thead>
									<tbody id="sortable" data-view="articles">
									<?php
									$i = 1;
									$articles = $model->getList();
									if(count($articles) > 0) :
									foreach($articles as $article) :
									?>
									<tr class="item ui-state-default" data-id="<?= $article->id; ?>">
										<td>
											<input type="checkbox" name="cd" data-id="<?= $article->id; ?>">
										</td>
										<td>
											<p> <a class="edit" data-id="<?= $article->id; ?>" href="#"><?= $article->descripcio; ?></a></p>
										</td>
										
										<td align="right">
											<a href="#" class="edit icon" data-id="<?= $article->id; ?>"><i class="fa fa-pencil"></i></a>
											<a href="?view=articles&task=deleteItem&id=<?= $article->id; ?>" class="delete icon" data-message="Confirm?">
												<i class="fa fa-trash"></i>
											</a>
											<a href="#" class="opacity hasTip handle icon" title="Drag and drop to reorder">
                                            	<i class="fa fa-list"></i>
                                            </a>
										</td>
									</tr>
									<?php
									$i++;
									endforeach; ?>
									<?php else : ?>
									Ho hi han Articles
									<?php endif; ?>
									</tbody>
								</table>

								<?= $model->pagination($get); ?>

							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- END LIST -->
		
		<!-- BEGIN FORM -->
		<div class="col-md-6">
		
			<div class="grid support">
				<div class="grid-body">
					
					<i class="fa fa-question-circle"></i> Aquí podràs gestionar els teus articles.
				</div>
			</div>
			
			<div class="grid support">
				<div class="grid-body">
					
					<h2>Article <a href="index.php?view=articles"><i class="fa fa-recycle pull-right"></i></a></h2>					

					<fieldset id="form_disabled" disabled="disabled">
						<form action="index.php?view=articles&task=saveItem" method="post">	
							<input type="hidden" name="id" id="article_id" value="" />
							<input type="hidden" id="article_percIVA" value="" />	
							<div class="col-md-6">
							<?= $html->getListField('article', 'idSalo', $user->cf_salo, $model->getSalons(), 'nom', 'id'); ?>
							<?= $html->getListField('article', 'servei'); ?>
							<?= $html->getListField('article', 'inventariable'); ?>		
							<?= $html->getTextField('article', 'preuIVAInclos'); ?>
							<?= $html->getTextField('article', 'importIVA'); ?>
							<?= $html->getRadioField('article', 'especificat', 0); ?>
							<?= $html->getTextField('article', 'codiBarres'); ?>
							<?= $html->getTextField('article', 'minim'); ?>
							</div>	
							<div class="col-md-6">
							<?= $html->getListField('article', 'idFamilia', '', $model->getFamilies(), 'descripcio', 'id'); ?>
							<?= $html->getListField('article', 'idTipusIVA', $model->configParam('iva_productes'), $model->getPercIVAs(), 'percIVA', 'id'); ?>									
							<?= $html->getTextField('article', 'descripcio'); ?>					
							<?= $html->getTextField('article', 'preuBase'); ?>	
							<?= $html->getTextField('article', 'estoc'); ?>	
							<?= $html->getRadioField('article', 'actiu', 0); ?>	
							<?= $html->getTextField('article', 'preuCompra'); ?>
							<?= $html->getTextField('article', 'percMarge'); ?>										
							</div>	
							<button id="form_button" type="submit" class="btn btn-success btn-block" style="margin-bottom: 8px;">Crear</button>																			
						</form>
						<button onClick="window.location.reload();" class="btn btn-danger btn-block">Cancel·lar</button>
					</fieldset>
				</div>
			</div>
		</div>
		<!-- END FORM -->
		
	</div>
</section>
</div>
