/*
 * prettyPhoto Media admin functions
 */
jQuery(function($) {
	$('.ui-tabs').tabs({
		fx : {
			opacity : 'toggle',
			duration : 80
		},
		cookie : {},
		show : onSelect
	});
	function onSelect(event, ui) {
		$('.ui-tabs-nav li a').removeClass('nav-tab-active');
		$('.ui-tabs-selected a').addClass('nav-tab-active');
	}

});
