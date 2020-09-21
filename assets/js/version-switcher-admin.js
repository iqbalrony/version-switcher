(function ($) {
	"use strict";

	$(document).ready(function($){
		console.log('its work');

		//Notice hover image
		function mouseMovement(e){
			var self = $(this);
			if( 'mouseenter' === e.type){
				self.find('img').addClass('active');
			}else{
				self.find('img').removeClass('active');
			}
		}
		var notice = $('.wpvs-settings .notice-info a');
		notice.mouseenter( mouseMovement);
		notice.mouseleave( mouseMovement);

		// Ajax
		function ajaxClickHandlerfunction(e) {
			e.preventDefault();
			// console.log('on change');
			var pluginSlug = e.currentTarget.options[e.currentTarget.options.selectedIndex].value;
			// console.log(e);
			// console.log(pluginSlug);
			// return;
			var self = $(this);
			var versionWrap = $('.wpvs-plugin-version-select');
			var versionSelect = versionWrap.find('select');
			var notice = versionWrap.find('.notice');
			var loadingIcon = $('<i class="dashicons-before dashicons-update-alt"></i>');
			
			self.attr("disabled", true);
			versionSelect.attr("disabled", true);
			
			$.ajax({
				url: wpvs_admin_localize.ajax_url,
				type: 'POST',
				data: {
					action: "wpvs_get_all_version",
					security: wpvs_admin_localize.wpvs_nonce,
					plugin_slug: pluginSlug,
				},
				beforeSend: function() {
					versionWrap.append( loadingIcon );
				},
				success: function(response) {
					// console.log(typeof response);
					// console.log(response.length);
					// console.log(response);

					// var html = $.parseHTML( response );
					if( response.length > 0 ){
						versionSelect.html( response );
						versionSelect.removeAttr('disabled');
					}
					self.removeAttr('disabled');
					versionWrap.find('.dashicons-before').remove();
					
					if( 'object' == typeof response && response.success == false ){
						notice.text( response.data );
						notice.css({"display": "block"});
						setTimeout( function(){
							notice.slideUp();
						},2000);
					}
					

					// if( response ){
					// 	$main_wrapper.find('.wpvs-masonry-container').append( html )
					// 	$main_wrapper.find('.wpvs-masonry-container').isotope( 'appended', html ).isotope('layout');
					// 	$self.removeAttr('disabled');
						
					// 	if( !isLoadMore ){
					// 		$button_wrap.css({"display": "block"});
					// 	}
						
					// }else{
					// 	//$self.text('All Loaded').addClass('loaded');
					// 	setTimeout( function(){
					// 		$button_wrap.css({"display": "none"});
					// 	},800);
					// }

				},
				error: function(error) {}
			});
		}
		var select = $('.wpvs-plugin-name-select select');
		select.on("change",ajaxClickHandlerfunction);

	});

})(jQuery);
