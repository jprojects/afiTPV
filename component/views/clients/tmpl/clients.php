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
	$('.edit').click(function(e) {
		e.preventDefault();
		var id = $(this).attr('data-id');		
		var url = '<?= $config->site; ?>/index.php?view=clients&task=getItemById&id='+id+'&mode=raw';
		$.getJSON(url, function(json){
			
			$('#client_id').val(json.id);
			$('#client_idSalo').val(json.idSalo);
			$('#client_nif').val(json.NIF);
			$('#client_nom').val(json.nom);
			$('#client_adreca').val(json.adreca);
			$('#client_cp').val(json.cp);
			$('#client_telefon').val(json.telefon);
			$('#client_dataNaixement').val(json.dataNaixement);
			$('#client_mobil').val(json.mobil);
			$('#client_email').val(json.eMail);
			$('#client_onomastica').val(json.onomastica);
			$('#client_alta').val(json.alta);
			$('#client_baixa').val(json.baixa);
			$('#client_sexe').val(json.sexe);
			$('#client_publicitatEmail').val(json.publicitatEmail);
			$('#client_publicitatPostal').val(json.publicitatPostal);
			$('#client_observacions').val(json.observacions);
			$('#esborrar').html('<a href="index.php?view=clients&task=deleteItem&id='+json.id+'" class="btn btn-danger btn-block">Esborrar</a>');
			$('.submit').html('Editar');

		});
		
			document.getElementById("form_disabled").removeAttribute("disabled");
			document.getElementById("form_button").innerHTML="Desa";
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
						 	<h2>Clients</h2>
					 	</div>
					 	<div class="col-md-6 text-right">
							<?= $html->renderButtons('clients', 'clients'); ?>
						</div>
					</div>

					<hr>

					<div class="row">
					 	<div class="col-md-12">
					 	<?= $html->renderFilters('clients', 'clients'); ?>
					 	</div>
					</div>

					<?php $get = $_GET; ?>

					<div class="padding"></div>
					
					<form action="" method="get" id="itemsList" name="itemsList">
            		<input type="hidden" name="view" value="clients">
            		<input type="hidden" name="list_column" id="list_column">
            		<input type="hidden" name="list_dir" id="list_dir" value="<?= $dir; ?>">
						<div class="row">
							<!-- BEGIN TICKET CONTENT -->
							<div class="col-md-12">
								<table class="table table-striped">
									<thead>
										<tr>
											<th width="1%"><input type="checkbox" id="selectAll"></th>
											<th width="25%">
												<a href="#" class="text-primary order" data-field="nom">Client</a>
												<i class="fa <?= $dir == 'DESC' ? 'fa-caret-up' : 'fa-caret-down'; ?> fa-lg"></i>
											</th>	
											<th width="25%">
												<a href="#" class="text-primary order" data-field="telefon">Telèfon</a>
												<i class="fa <?= $dir == 'DESC' ? 'fa-caret-up' : 'fa-caret-down'; ?> fa-lg"></i>
											</th>	
											<th width="25%">
												<a href="#" class="text-primary order" data-field="mobil">Mobil</a>
												<i class="fa <?= $dir == 'DESC' ? 'fa-caret-up' : 'fa-caret-down'; ?> fa-lg"></i>
											</th>			
											<th class="text-right" width="24%">Drag</th>
										</tr>
									</thead>
									<tbody id="sortable" data-view="clients">
									<?php
									$i = 1;
									$clients = $model->getList();
									if(count($clients) > 0) :
									foreach($clients as $client) :
									?>
									<tr class="item ui-state-default" data-id="<?= $client->id; ?>">
										<td>
											<input type="checkbox" name="cd" data-id="<?= $client->id; ?>">
										</td>
										<td>
											<p><a class="edit" data-id="<?= $client->id; ?>" href="#"><?= $client->nom; ?></a></p>
										</td>
										<td>
											<p><a class="edit" data-id="<?= $client->id; ?>" href="#"><?= $client->telefon; ?></a></p>
										</td>
										<td>
											<p><a class="edit" data-id="<?= $client->id; ?>" href="#"><?= $client->mobil; ?></a></p>
										</td>
										<td align="right">
											<a href="#" class="edit icon" data-id="<?= $client->id; ?>"><i class="fa fa-pencil"></i></a>
											<a href="?view=clients&task=deleteItem&id=<?= $client->id; ?>" class="delete icon" data-message="Confirm?">
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
									No hi ha cap client
									<?php endif; ?>
									</tbody>
								</table>
							
								<?= $model->pagination($get); ?>

							</div>
						</div>
						<!-- END LIST CONTENT -->
					</form>
				</div>
			</div>
		</div>
		<!-- END LIST -->
		
		<!-- BEGIN FORM -->
		<div class="col-md-6">
		
			<div class="grid support">
				<div class="grid-body">
					
					<i class="fa fa-question-circle"></i> Aquí podràs gestionar els teus clients.
				</div>
			</div>
			
			<div class="grid support">
				<div class="grid-body">
					
					<h2>Client <a href="index.php?view=clients"><i class="fa fa-recycle pull-right"></i></a></h2>
					
					<fieldset id="form_disabled" disabled="disabled">
						<form action="index.php?view=clients&task=saveItem" method="post">	
							<input type="hidden" name="id" id="client_id" value="" />				
							<div class="col-md-6">													
							<?= $html->getListField('client', 'idSalo', $user->cf_salo, $model->getSalons(), 'nom', 'id'); ?>
							<?= $html->getTextField('client', 'NIF'); ?>
							<?= $html->getTextField('client', 'nom'); ?>
							<?= $html->getTextField('client', 'adreca'); ?>
							<?= $html->getTextField('client', 'cp'); ?>
							<?= $html->getTextField('client', 'telefon'); ?>
							<?= $html->getDateField('client', 'dataNaixement'); ?>
							<?= $html->getTextField('client', 'mobil'); ?>
							</div>
							<div class="col-md-6">					
							<?= $html->getEmailField('client', 'eMail'); ?>
							<?= $html->getDateField('client', 'onomastica'); ?>
							<?= $html->getDateField('client', 'alta'); ?>
							<?= $html->getDateField('client', 'baixa'); ?>
							<?= $html->getListField('client', 'sexe'); ?>
							<?= $html->getListField('client', 'publicitatEmail'); ?>
							<?= $html->getListField('client', 'publicitatPostal'); ?>
							<?= $html->getTextareaField('client', 'observacions'); ?>
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
