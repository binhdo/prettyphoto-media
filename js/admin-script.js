/*
 * prettyPhoto Media admin functions
 */
jQuery(function($) {
	$('.ui-tabs').tabs({show: 60, hide: 60, activate: onSelect});
	function onSelect(event, ui) {
		$('.ui-tabs-nav li a').removeClass('nav-tab-active');
		$('.ui-tabs-selected a').addClass('nav-tab-active');
	}

});
