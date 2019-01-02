var wpmp_page = 1;

function wpmp_show_loader(where) {
	if (where) {
		jQuery(where).after('<div class="wpmp-ajax-loader"></div>');
	}
}

function wpmp_remove_loader() {
	jQuery(".wpmp-ajax-loader").remove();
}

function wpmp_ajax_load(url, pars, where, evalonsuccess, method, cache) {

	wpmp_show_loader(where);

	if (cache === undefined) {
		cache = false;
	}
	if (method === undefined) {
		method = 'GET';
	}

	jQuery.ajax({
		url : url,
		data : pars,
		cache : cache,
		type : method,
		success : function(response) {
			if (where != "") {
				jQuery(where).append(response);
				wpmp_remove_loader();
				if (response.trim() == "") {
					jQuery('.btn--wpmp-more-items').hide();
				}
			}
			eval(evalonsuccess);
		}
	});
}


jQuery(document).ready(function() {
	jQuery("body").on("click", ".btn--wpmp-more-items", function() {

		jQuery(".btn--wpmp-more-items").blur();

		wpmp_page++;

		var wpmp_pars = "";

		wpmp_pars += "wpmp_page=" + wpmp_page;
		wpmp_pars += "&action=list_members";

		wpmp_ajax_load(wpmp_ajax_url, wpmp_pars, "#wpmp-ajax-container");
	});

	jQuery('body').on('click', '.btn--see-description', function() {
		jQuery(this).parent().find('div').toggle();
		return false;
	});

}); 