$("a[rel^='prettyPhoto']").prettyPhoto({
    hook : 'rel', /* the attribute tag to use for prettyPhoto hooks. default: 'rel'. For HTML5, use "data-rel" or similar. */
    animation_speed : 'fast', /* fast/slow/normal */
    ajaxcallback : function() {
    },
    slideshow : 5000, /* false OR interval time in ms */
    autoplay_slideshow : false, /* true/false */
    opacity : 0.80, /* Value between 0 and 1 */
    show_title : true, /* true/false */
    allow_resize : true, /* Resize the photos bigger than viewport. true/false */
    allow_expand : true, /* Allow the user to expand a resized image. true/false */
    default_width : 500,
    default_height : 344,
    counter_separator_label : '/', /* The separator for the gallery counter 1 "of" 2 */
    theme : 'pp_default', /* light_rounded / dark_rounded / light_square / dark_square / facebook */
    horizontal_padding : 20, /* The padding on each side of the picture */
    hideflash : false, /* Hides all the flash object on a page, set to TRUE if flash appears over prettyPhoto */
    wmode : 'opaque', /* Set the flash wmode attribute */
    autoplay : true, /* Automatically start videos: True/False */
    modal : false, /* If set to true, only the close button will close the window */
    deeplinking : true, /* Allow prettyPhoto to update the url to enable deeplinking. */
    overlay_gallery : true, /* If set to true, a gallery will overlay the fullscreen image on mouse over */
    overlay_gallery_max : 30, /* Maximum number of pictures in the overlay gallery */
    keyboard_shortcuts : true, /* Set to false if you open forms inside prettyPhoto */
    changepicturecallback : function() {
    }, /* Called everytime an item is shown/changed */
    callback : function() {
    }, /* Called when prettyPhoto is closed */
    ie6_fallback : true
});
