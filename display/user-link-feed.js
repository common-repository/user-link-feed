/**
 * This file is part of User Link Feed. Please see the user-link-feed.php file for copyright
 *
 * @author Sulaeman
 * @version 1.2.0
 * @package user-link-feed
 */
 
jQuery(function() {
	var loading_dom = jQuery('#ulf_loading');
	var url_dom = jQuery('#ulf_url');
	var url_attach_dom = jQuery('#ulf_url_attach');
		
	jQuery('#ulf_url_attach').click(function(){
		loading_dom.show();
		url_dom.hide();
		url_attach_dom.hide();
		
		jQuery.ajax({
			type: "POST",
			dataType: "json",
			url: ULF_URL,
			data: {
				ulf_url: url_dom.val()
			},
			error: function(http, error){
				loading_dom.hide();
				url_dom.show();
				url_attach_dom.show();
			},
			success: function(json){
				if (json) {
					if (json.error.code != 0) {
						loading_dom.hide();
						url_dom.show();
						url_attach_dom.show();
					} else {
						
						if (json.payload != null) {
							jQuery('#ulf_url_container_form').hide();
							jQuery('#ulf_attached_url_title').html('<a href="' + json.payload.url + '" target="_blank">' + json.payload.title + '</a>');
							jQuery('#ulf_title').val(json.payload.title);
							jQuery('#ulf_attached_url').html(json.payload.url);
							jQuery('#ulf_attached_description').html('<a href="' + json.payload.url + '" target="_blank">' + json.payload.description + '</a>');
							jQuery('#ulf_description').val(json.payload.description);
							
							if (json.payload.images.length > 0) {
								var images_container_dom = jQuery('#ulf_attached_images');
								images_container_dom.html('');
								var display = 'none';
								var selected = '';
								var i_image = 0;
								for (i_image in json.payload.images) {
									display = 'none';
									selected = '';
									if (i_image == 0) {
										display = 'inline';
										selected = 'ulf_img_inline';
										jQuery('#ulf_image').val(json.payload.images[i_image]);
									}
									images_container_dom.append('<img class="ulf_image_item ' + selected + '" src="' + json.payload.images[i_image] + '" alt="' + json.payload.title + '" style="display:' + display + '"/>');
								}
								delete images_container_dom;
								delete display;
								delete selected;
								delete i_image;
							}
							
							jQuery('#ulf_url_attach_container').show();
						}
					}
				}
				
				delete json;
			}
		});
	});
});

function ulf_prev_image() {
	var current = jQuery('img.ulf_img_inline');
	var prev = current.prev(':first');
	if (prev[0] != null) {
		current.removeClass('ulf_img_inline').css('display', 'none');
		prev.addClass('ulf_img_inline').css('display', 'inline');
		jQuery('#ulf_image').val(prev.attr('src'));
	}
}

function ulf_next_image() {
	var current = jQuery('img.ulf_img_inline');
	var next = current.next(':first');
	if (next[0] != null) {
		current.removeClass('ulf_img_inline').css('display', 'none');
		next.addClass('ulf_img_inline').css('display', 'inline');
		jQuery('#ulf_image').val(next.attr('src'));
	}
}

function ulf_change_link() {
	jQuery('#ulf_loading').hide();
	jQuery('#ulf_url_attach_container').hide();
	jQuery('#ulf_url_container_form').show();
	jQuery('#ulf_url').show();
	jQuery('#ulf_url_attach').show();
}

function ulf_without_image_func(dom)
{
	if (dom.checked == true) {
		jQuery('#ulf_attached_images').hide();
		jQuery('#ulf_prev_image').hide();
		jQuery('#ulf_next_image').hide();
	} else {
		jQuery('#ulf_attached_images').show();
		jQuery('#ulf_prev_image').show();
		jQuery('#ulf_next_image').show();
	}
}