/*
 * prettyPhoto Media admin functions
 */
(function($) {
	$(function() {
		$('.ui-tabs').tabs({show: 60, hide: 60, create: onActivate, activate: onActivate});
		function onActivate(event, ui) {
			$('.ui-state-default a').removeClass('nav-tab-active');
			$('.ui-tabs-active a').addClass('nav-tab-active');
		}
	});
})(jQuery);
