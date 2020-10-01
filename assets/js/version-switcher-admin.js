(function ($) {
	"use strict";

	$(document).ready(function ($) {

		// Ajax
		function ajaxClickHandlerfunction(e) {
			e.preventDefault();

			var pluginSlug = e.currentTarget.options[e.currentTarget.options.selectedIndex].value;

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
				beforeSend: function () {
					versionWrap.append(loadingIcon);
				},
				success: function (response) {

					// console.log(typeof response);
					// console.log(response.length);
					// console.log(response);

					// var html = $.parseHTML( response );
					if (response.length > 0) {
						versionSelect.html(response);
						versionSelect.removeAttr('disabled');
					}
					self.removeAttr('disabled');
					versionWrap.find('.dashicons-before').remove();

					if ('object' == typeof response && response.success == false) {
						notice.text(response.data);
						notice.css({ "display": "block" });
						setTimeout(function () {
							notice.slideUp();
						}, 2000);
					}

				},
				error: function (error) { }
			});
		}
		var select = $('.wpvs-plugin-name-select select');
		select.on("change", ajaxClickHandlerfunction);

	});

})(jQuery);
