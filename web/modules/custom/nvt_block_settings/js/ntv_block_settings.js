jQuery(document).ready(function() {

	jQuery('.block-bg').each(function(index, el) {
		if(jQuery(this).attr('data-bg-image')){
			$bg_img = jQuery(this).attr('data-bg-image');
			jQuery(this).css('background-image', 'url('+$bg_img+')');
			if(jQuery(this).attr('data-bg-position')) {
				$bg_position = jQuery(this).attr('data-bg-position');
				jQuery(this).css('background-position', $bg_position);
			}
			if(jQuery(this).attr('data-bg-size')) {
				$bg_size = jQuery(this).attr('data-bg-size');
				jQuery(this).css('background-size', $bg_size);
			}
			if(jQuery(this).attr('data-bg-overlay')) {
				$bg_overlay = jQuery(this).attr('data-bg-overlay');
				jQuery(this).prepend('<div class="block-bg-overlay"></div>');
				jQuery(this).css('position', 'relative');
				jQuery(this).find('.block-bg-overlay').css('background-color', $bg_overlay);
			}
			removeDataAttributes(jQuery(this));
		} else if (jQuery(this).attr('data-bg-color')) {
			$bg_color = jQuery(this).attr('data-bg-color');
			jQuery(this).css('background-color', $bg_color);
		}
	});
	/*vixax-update*/
	if(jQuery('.home-week').length){
	  	var pageScrollerWrap = jQuery('<div id="page-scroller-wrap" />');
	  	jQuery('.page-scroller').wrapAll(pageScrollerWrap);
	  	jQuery('#page-scroller-wrap').onepage_scroll();
	}
	/*vixax-update*/


});

function removeDataAttributes(target) {

    var i,
        $target = jQuery(target),
        attrName,
        dataAttrsToDelete = [],
        dataAttrs = $target.get(0).attributes,
        dataAttrsLen = dataAttrs.length;

    // loop through attributes and make a list of those
    // that begin with 'data-'
    for (i=0; i<dataAttrsLen; i++) {
        if ( 'data-' === dataAttrs[i].name.substring(0,5) ) {
            // Why don't you just delete the attributes here?
            // Deleting an attribute changes the indices of the
            // others wreaking havoc on the loop we are inside
            // b/c dataAttrs is a NamedNodeMap (not an array or obj)
            dataAttrsToDelete.push(dataAttrs[i].name);
        }
    }
    // delete each of the attributes we found above
    // i.e. those that start with "data-"
    jQuery.each( dataAttrsToDelete, function( index, attrName ) {
        $target.removeAttr( attrName );
    })
};