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

$model = $app->getModel();
$tocadors = $model->getTocadorsOberts();
$total = 0.00;
?>

<style>
.btn-key { padding: 30px; }
</style>

<script>
var scrolled = 0;
var isClient = 0;
var idClient = 0;

jQuery(document).ready(function() {
	//per saber si hi han clients seleccionats i permetre afegir articles al carro
	$('#client_id').val() != '' ? isClient = 1 : isClient = 0;
	$.ajaxSetup({
        async: false
    });
    //typeahead clients
	$('input.typeahead').typeahead({
		onSelect: function(item) {
        	var clients_url = '<?= $config->site; ?>/index.php?view=clients&task=getItemById&id='+item.value+'&mode=raw';
        	$.getJSON(clients_url, function(json) {
        		$('#client_id').append('<option value="'+item.value+'">'+json.nom+'</option>');
        		$('#clientModal').modal('hide');
        		if(item.value != '') { isClient = 1; }
        	});
        	var total = $('#total').html();
        	$.get('<?= $config->site; ?>/index.php?view=ticket&task=saveTocador&client='+item.value+'&total='+total+'&mode=raw', function(data) {
        		var jsonData = $.parseJSON(data);
				if(jsonData.idTocador > 0) {
					toastr.error("Aquest client ja te un tocador obert", 'Error', {timeOut: 7000});
				}
        	});
    	},
	    ajax: {
	    	url: '<?= $config->site; ?>/api/clients.php',
			timeout: 500,
			displayField: "nom",
			valueField: 'id',
			triggerLength: 1,
			method: "get",
			loadingClass: "loading-circle"
    	}
	});
	//clicar familia canvia a articles
	$('.familia').on('click', function(e) {
		e.preventDefault();
		if(isClient == 1) {
			var familia_id = $(this).attr('data-id');
			var articles_url = '<?= $config->site; ?>/index.php?view=ticket&task=getArticlesByFamilia&id='+familia_id+'&mode=raw';
			$.getJSON(articles_url, function(json) {
				$('#articles_box').append('<div class="col-md-1"><a class="btn btn-lg btn-danger articleBack"><span><i class="fa fa-chevron-left"></i></span></a></div>');
				$.each(json, function(index, obj) {
					$('#articles_box').append('<div class="col-md-1"><a data-id="'+obj.id+'" class="btn btn-lg btn-block btn-success article"><span>'+obj.descripcio+'</span></a></div>');
				});
				$('#families').css('display', 'none');
				$('#articles').css('display', 'block');
			});
    	} else {
    		toastr.error("Selecciona un client abans", 'Error', {timeOut: 7000});
    	}
	});
	//al clicar un article crear nou row
	$('body').on('click', '.article', function(e) {
		e.preventDefault();
		//sols si hi ha clients seleccionats es pot afegir items
		if(isClient == 1) {
			//spinner show
			$('.loading').show();
			var article_id = $(this).attr('data-id');
			//Registrar detall a la db
			var tocador = $('#client_id option:selected').attr('data-tocador');
			$.get('<?= $config->site; ?>/index.php?view=ticket&task=saveDetall&article='+article_id+'&tocador='+tocador+'&mode=raw', function(data) {
				var jsonData = $.parseJSON(data);
				var idDetall = jsonData.idDetall;
				var article_url = '<?= $config->site; ?>/index.php?view=ticket&task=getDetallTocador&id='+idDetall+'&mode=raw';
				$.getJSON(article_url, function(json) {
					$('#table-ticket tbody').append('<tr class="row'+idDetall+'"><td>'+json.descripcio+'</td><td>'+json.quantitat+'</td><td>'+json.preu+'</td><td>'+json.percDte+'</td><td>'+json.importDte+'</td><td>'+json.importBase+'</td><td>'+json.percIVA+'</td><td>'+json.importIVA+'</td><td>'+json.importNet+'</td><td class="hidden-print"><a class="delete" data-id="'+idDetall+'"><i class="fa fa-times"></a></i></td></tr>');
					var total = $('#total').html();
					$('#total').html(json.suma);

				});
			});
			//spinner hide
			$('.loading').hide();
    	} else {
    		toastr.error("Selecciona un client abans", 'Error', {timeOut: 7000});
    	}
	});
	//al esborrar un article
	$('body').on('click', '.delete', function(e) {
		e.preventDefault();
		//sols si hi ha clients seleccionats es poden esborrar items
		if(isClient == 1) {
			var id_detall = $(this).attr('data-id');
			//esborrem article, recalculem i canviem el total
			$.get('<?= $config->site; ?>/index.php?view=ticket&task=deleteArticle&id='+id_detall+'&mode=raw', function(data) {
				var jsonData = $.parseJSON(data);
				$('.row'+id_detall).remove();
				var total = $('#total').html();
				$('#total').html(jsonData.suma);
			});
		} else {
    		toastr.error("Selecciona un client abans", 'Error', {timeOut: 7000});
    	}
	});
	//al anar enrere en un article
	$('body').on('click', '.articleBack', function(e) {
		e.preventDefault();
		$('#articles_box').empty();
		$('#articles').css('display', 'none');
    	$('#families').css('display', 'block');
	});
	//blur on ocultar
	$('.ocultar').on('click', function() {
		$('#ticket').toggleClass('blur');
	});
	//scroll down families i articles
	$(".scrollDown").on("click" ,function(){
        scrolled=scrolled+300;
		$(".scroller").animate({
			scrollTop:  scrolled
		});
	});
	//scroll up families i articles
    $(".scrollUp").on("click" ,function(){
		scrolled=scrolled-300;
		$(".scroller").animate({
			scrollTop:  scrolled
		});
	});
	//print ticket
	$('body').on('click', '.print', function() {
  		printDiv('ticket');
	});
	//si canviem el tocador
	$("#client_id").on("change", function() {
  		idClient = $(this).val();
  		if(idClient != '') {
	  		isClient = 1;
	  		//obtenim tot el detall del tocador obert
	  		var tocador = $('#client_id option:selected').attr('data-tocador');
			var tocador_url = '<?= $config->site; ?>/index.php?view=ticket&task=getDetallTocador&tocador='+tocador+'&mode=raw';
			$.getJSON(tocador_url, function(json) {
				$('#table-ticket tbody').empty();
				$('#total').html();
				$.each(json, function(index, obj) {
					$('#table-ticket tbody').append('<tr class="row'+obj.id+'"><td>'+obj.descripcio+'</td><td>'+obj.quantitat+'</td><td>'+obj.preu+'</td><td>'+obj.percDte+'</td><td>'+obj.importDte+'</td><td>'+obj.importBase+'</td><td>'+obj.percIVA+'</td><td>'+obj.importIVA+'</td><td>'+obj.importNet+'</td><td class="hidden-print"><a class="delete" data-id="'+obj.id+'"><i class="fa fa-times"></a></i></td></tr>');
					$('#total').html(obj.suma);
				});
			});
		} else {
			//hem deseleccionat per tant buidem ticket i fem saber que no hi ha client actiu
			$('#table-ticket tbody').empty();
			$('#total').html();
			isClient = 0;
		}
	});
});
function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;
     window.print();
     document.body.innerHTML = originalContents;
}
</script>

<div class="container-fluid">
<section class="content">
	<div class="row">

		<!-- BEGIN CLIENT -->
		<div class="col-md-8">
			<div class="grid support-content">
				 <div class="grid-body">
				 	<div>
				 		<select class="form-control" id="client_id">
				 		<option value="">Selecciona un client</option>
				 		<?php if(count($tocadors)) : ?>
				 		<?php foreach($tocadors as $tocador) : ?>
				 		<option data-tocador="<?= $tocador->id; ?>" value="<?= $tocador->idClient; ?>"><?= $tocador->nom; ?></option>
				 		<?php endforeach; ?>
				 		<?php else : ?>
				 		<option value="">Escull un client</option>
				 		<?php endif; ?>
				 		</select>
				 	</div>
				</div>
			</div>
		</div>

		<!-- BEGIN LIST -->
		<div class="col-md-8">
			<div class="grid support-content">
				 <div class="grid-body">
				 	<div id="ticket">
				 		<img class="loading hidden-print" src="assets/img/loading.gif" style="display:none;">
				 		<!-- pintar capçalera ticket amb nomes visibilitat per printers .visible-print-block -->
				 		<div class="ticket-header visible-print-block text-center">
				 			<img src="assets/img/uploads/<?= $model->configParam('empresa_logo'); ?>">
				 		</div>
				 		<table class="table table-hover" id="table-ticket">
				 			<thead>
							 	<tr>
							 		<th>Desc.</th>
							 		<th>Qtat.</th>
							 		<th>Preu</th>
							 		<th>% Des</th>
							 		<th>Des</th>
							 		<th>Base</th>
							 		<th>% IVA</th>
							 		<th>IVA</th>
							 		<th>Import</th>
							 		<th class="hidden-print">#</th>
							 	</tr>
						 	</thead>
						 	<tbody>

						 	</tbody>
						</table>
						<div class="text-right"><b>Total ticket: <span id="total"><?= $total; ?></span> &euro;</b></div>
						<!-- pintar peu de ticket amb nomes visibilitat per printers .visible-print-block -->
						<div class="ticket-footer visible-print-block text-center">
						<?= $model->configParam('empresa_cif'); ?>
						</div>
				 	</div>
				</div>
			</div>
		</div>

		<!-- BOTONERES -->
		<div class="col-md-4">

			<div class="grid support-content">
				<div class="grid-body">

					<div class="row">
						<div class="col-md-3">
		    				<a class="btn btn-lg btn-success" data-toggle="modal" data-target="#clientModal">
		        				<i class="fa fa-user"></i><br><br>
		        				<span>Clients</span>
		        			</a>
		  				</div>
		  				<div class="col-md-3">
		    				<a class="btn btn-lg btn-success print"> <!-- href="index.php?view=ticket&task=printer" -->
		        				<i class="fa fa-user"></i><br><br>
		        				<span>Imprimir</span>
		        			</a>
		  				</div>
		  				<div class="col-md-3">
		    				<a class="btn btn-lg btn-success ocultar">
		        				<i class="fa fa-lock"></i><br><br>
		        				<span>Ocultar</span>
		        			</a>
		  				</div>
					</div>

				</div>
			</div>

		</div>

		<!-- FAMILIES -->
		<div class="col-md-11">
			<div class="grid support-content">
				 <div class="grid-body scroller">
				 	<div id="families">
				 		<!-- mostrem totes les families, al clicar una mostrem articles de la familia -->
				 		<div class="row">
				 			<?php foreach($model->getFamiliesBySalo() as $familia) : ?>
				 			<div class="col-md-1">
								<a data-id="<?= $familia->id; ?>" class="btn btn-lg btn-block btn-success familia">
				    				<span><?= $familia->descripcio; ?></span>
				    			</a>
		  					</div>
				 			<?php endforeach; ?>
				 		</div>
				 	</div>
				 	<div id="articles" style="display:none;">
				 		<!-- mostrem tots els articles, al clicar un l'enviem al ticket -->
				 		<div class="row" id="articles_box">

				 		</div>
				 	</div>
				 </div>
			</div>
		</div>

		<div class="col-md-1">
			<div class="grid support-content">
				 <div class="grid-body text-center text-success">
				 	<i class="fa fa-chevron-up fa-3x scrollUp"></i>
				 	<i class="fa fa-chevron-down fa-3x scrollDown"></i>
				</div>
			</div>
		</div>

	</div>
</section>
<!-- Modal clients -->
<div class="modal fade" id="clientModal" tabindex="-1" role="dialog" aria-labelledby="clientModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="clientModalLabel">Escull un client</h4>
      </div>
      <div class="modal-body">
        	<div class="form-group">
				<input type="text" name="client" autocomplete="off" class="form-control typeahead" placeholder="Escull client" />
			</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Tancar</button>
      </div>
    </div>
  </div>
</div>
</div>
