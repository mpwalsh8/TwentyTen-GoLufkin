<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * TwentyTen-ACME AdRotate
 *
 * $Id$
 *
 * (c) 2013 by Mike Walsh
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package TwentyTen-ACME
 * @subpackage AdRotate
 * @version $Revision$
 * @lastmodified $Date$
 * @lastmodifiedby $Author$
 *
 */

/**
 * twentyten_acme_adrotate_wp_footer()
 *
 * By default TwentyTen doesn't have an easy way to add the advertising
 * blocks we want nor are there any obvious hooks to use.  Instead of copy
 * and modifying one of TwentyTen template files (which means updates would
 * never be incorporated automatically), we'll use the standard WordPress
 * wp_footer action to construct a DIV elements for the advertising blocks.
 * The DIVs for the advertising blocks are hidden initially.  When the page
 * loads, a jQuery script will "move" the blocks to their proper locations
 * and then make them visible.
 *
 */
function twentyten_acme_adrotate_wp_footer()
{
	//
	//  Only insert the Ad Blocks if AdManager is enabled ...
	//
	//  Define three DIVs, one for each block.  The first block will
	//  be inserted above the content of the loop, the second block is
	//  added to the bottom of the primary widget area, and the third
	//  block is added to the bottom of the secondary widget area.
	//

	if (function_exists('adrotate_group')) {
		printf('<div id="acme-adblock-1" style="display:none;" class="acme-adblock">%s</div>', adrotate_group(1));
		printf('<div id="acme-adblock-2" style="display:none;" class="acme-adblock">%s</div>', adrotate_group(2));
		printf('<div id="acme-adblock-3" style="display:none;" class="acme-adblock">%s</div>', adrotate_group(3));
?>
<!--  Move the DIVs to their respective locations and make them visible -->
<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('#acme-adblock-1').prependTo($('#content'));
        $('#acme-adblock-2').appendTo($('#primary'));
        $('#acme-adblock-3').appendTo($('#secondary'));
        $('#acme-adblock-1').show() ;
        $('#acme-adblock-2').show() ;
        //$('#acme-adblock-3').show() ;
    }) ;
</script>
    
<?php
	}
}

add_action('wp_footer', 'twentyten_acme_adrotate_wp_footer');

?>
