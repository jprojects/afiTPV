var domain   = location.origin+location.pathname;

function setCookie(c_name,value,exdays) {
    var exdate=new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value=escape(value) + ((exdays===null) ? "" : "; expires="+exdate.toUTCString());
    document.cookie=c_name + "=" + c_value;
}

function getCookie(c_name) {
    var name = c_name + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i].trim();
        if (c.indexOf(name)===0) return c.substring(name.length,c.length);
    }
    return "";
}

$(document).ready(function() {

	//tooltips
	$(".hasTip").tooltip();

	//colorpickers
	if ($('#color').length) {
		$('#color').colorpicker();
	}

	if ($('.editor').length) {
	 	$(".editor").Editor();
	}

	//cookie policy
	$(document).herbyCookie({
		style: "light",
		btnText: "Got it!",
		policyText: "Privacy policy",
		text: "This site uses cookies to store information on your computer.",
		scroll: false,
		expireDays: 30,
		link: "/policy.html"
	});

	//save cookie with language
	$('.lang').click(function() {
		var lang = $(this).attr('data-lang');
		setCookie('language', lang, 10);
	});

	$('.delete').focus(function(e) {
		e.preventDefault();

		var pageURL = $(this).attr('href');

		$('.delete').confirmModal({
			confirmTitle     : 'ATENCIÓ',
			confirmMessage   : 'Estas segur de que vols borrar?',
			confirmOk        : 'Si',
			confirmCancel    : 'No',
			confirmDirection : domain+pageURL,
			confirmStyle     : 'primary',
			confirmDismiss   : true,
			confirmAutoOpen  : false
		});

	});

	eModal.setEModalOptions({
        loadingHtml: '<div class="center"><span class="fa fa-cog fa-spin fa-3x text-primary"></span></div>'
	});

	$('#selectAll').click(function() {
		var checkboxes = $(this).closest('form').find(':checkbox');
		checkboxes.prop('checked', $(this).is(':checked'));
	});

	//sortable list
	$("#sortable").sortable({
		opacity: 0.6,
		cursor: 'move',
		handle: '.handle',
		update: function() {

			var view = $(this).attr('data-view');

			if($('#filter_list').val() != '*') {  eModal.alert('Please show all items before reorder'); return false;}

			var items = [];

			$('#sortable .item').each(function(){
				var id    = $(this).attr('data-id');
				items.push(id);
			});

			var list = JSON.stringify(items);

			$.ajax({
				url: domain+"?view="+view+"&task=reorderItems&mode=raw",
				type: "post",
				datatype: 'json',
				data: {'items': list},
				success: function(data){
					//msg success
				},
				error: function(data){
					//msg failure
				}
			});
		}
	});

	//delete
	$('#btn_delete').click(function(e) {

		e.preventDefault();
		var items = [];

		$(':checkbox').each(function() {
		if(this.checked) {
				var id    = $(this).attr('data-id');
				items.push(id);
			}
		});

		var view = $(this).attr('data-view');
		var pageURL = $(this).attr("href");

		if(items == 0) { eModal.alert('Please check one item at least'); return false; } else { if(!confirm('Are you sure you want to delete this item?')) return false; }

		var list = JSON.stringify(items);

		$.ajax({
	            url: pageURL,
	            type: "post",
	            datatype: 'json',
	            data: {'items': list},
	            success: function(data){
					toastr.success(view +' success deleted');
					setTimeout(function(){
	                document.location.href = domain+'?view='+view; }, 500);

	            },
	            error: function(data){
	                toastr.error('An error occurs trying to delete this '+view);
	            }
	        });
	 });

	 $('#btn_edit').click(function(e) {

		e.preventDefault();
		var items = [];

		$(':checkbox').each(function() {
		if(this.checked) {
				var id    = $(this).attr('data-id');
				items.push(id);
			}
		});		

		if(items.length == 0) { eModal.alert("Sel·leciona un camp"); return false; }
		else if(items.length >= 2) { eModal.alert("Sel·leciona només un camp"); return false; }

		var view = $(this).attr('data-view');
		var pageURL = domain+'?view='+view+'&task=getItemById&id='+items[0]+'&mode=raw';
		var index = [];

		var elements = document.getElementById("my_form").elements;
		for (var i = 0; i < elements.length; i++) {
			if (elements[i].type !== "hidden" && elements[i].type !== "submit"){
				index.push(i);
			}
		}

		$.getJSON(pageURL, function(json){
			index.forEach(i => {
				atribute = elements[i].id.split("_");
				$('#'+elements[i].id).val(json.atribute[1]);
				console.log(atribute[1]);
			})
		});

		

		document.getElementById("form_disabled").removeAttribute("disabled");
		document.getElementById("form_button").innerHTML="Desa";
		//Fer patro a la vista perque sigui generica
	 });

	$('.order').click(function(e) {

		e.preventDefault();

    	var field  = $(this).attr('data-field');
		var column = $('#list_column').val(field);
    	var dir    = $('#list_dir').val();
 		if(dir == 'DESC') { dir = 'ASC'; } else { dir = 'DESC'; }
    	var dir    = $('#list_dir').val(dir);
		$('#itemsList').submit();

	});

	$('#btn_new').click(function(e) {
		e.preventDefault();
		document.getElementById("form_disabled").removeAttribute("disabled");
	});

});

function deleteAccount(username, domain) {
    if($('#proceed').val().toLowerCase() == username) {
        document.location.href=domain+'?view=config&task=deleteAccount';
    } else {
        return false;
    }
}
