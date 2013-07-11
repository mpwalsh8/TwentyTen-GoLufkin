<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * TwentyTen-ACC WooCommerce
 *
 * $Id$
 *
 * (c) 2013 by Mike Walsh
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package TwentyTen-ACC
 * @subpackage WooCommerce
 * @version $Revision$
 * @lastmodified $Date$
 * @lastmodifiedby $Author$
 * @see https://gist.github.com/thegdshop/3171026
 *
 */

/**
 * Add checkbox field to the checkout
 **/
add_action('woocommerce_after_order_notes', 'acme_booster_club_custom_checkout_field');
 
function acme_booster_club_custom_checkout_field( $checkout ) {
 
    echo '<div id="acme-booster-club-field"><h4>'.__('Please Help the Booster Club ').'</h4><small>Please indicate your areas of interest</small>';
 
    woocommerce_form_field( 'acme_bod_interest', array(
        'type'          => 'select',
        'class'         => array('input-select'),
        'label'         => __('Board of Directors<i><br/><small>(Officers, Committees, etc.)</small></i>'),
        'required'  => true,
        'options'       => array(
            'select' => '-- Please indicate interest --',
            'No' => __('I am not interested at this time.', 'woocommerce'),
            'Yes' => __('I would like to volunteer, please contact me.', 'woocommerce')
        ),
        ), $checkout->get_value( 'acme_bod_interest' ));
 
    woocommerce_form_field( 'acme_volunteer_interest', array(
        'type'          => 'select',
        'class'         => array('input-select'),
        'label'         => __('Volunteer Opportunities<i><br/><small>(Powder Puff, Concessions, etc.)</small></i>'),
        'required'  => true,
        'options'       => array(
            'select' => '-- Please indicate interest --',
            'No' => __('I am not interested at this time.', 'woocommerce'),
            'Yes' => __('I would like to volunteer, please contact me.', 'woocommerce')
        ),
        ), $checkout->get_value( 'acme_volunteer_interest' ));
 
    echo '</div>';
}
 
/**
 * Process the checkout
 *
 */
add_action('woocommerce_checkout_process', 'acme_booster_club_custom_checkout_field_process');
 
function acme_booster_club_custom_checkout_field_process() {
    global $woocommerce;
 
    // Check if set, if its not set add an error.
    if ('select' === $_POST['acme_volunteer_interest'])
         $woocommerce->add_error( __('Please indicate Volunteer interest.') );
    if ('select' === $_POST['acme_bod_interest'])
         $woocommerce->add_error( __('Please indicate Board of Directors interest.') );
}
 
/**
 * Update the order meta with field value
 *
 */
add_action('woocommerce_checkout_update_order_meta', 'acme_booster_club_custom_checkout_field_update_order_meta');
 
function acme_booster_club_custom_checkout_field_update_order_meta( $order_id ) {
    if ($_POST['acme_volunteer_interest']) update_post_meta( $order_id, 'Volunteer Interest', esc_attr($_POST['acme_volunteer_interest']));
    if ($_POST['acme_bod_interest']) update_post_meta( $order_id, 'Board of Directors Interest', esc_attr($_POST['acme_bod_interest']));
}

/**
 * Add the field to order emails
 **/
add_filter('woocommerce_email_order_meta_keys', 'acme_custom_checkout_field_order_meta_keys');
 
function acme_custom_checkout_field_order_meta_keys( $keys ) {
	$keys[] = 'Volunteer Interest';
	$keys[] = 'Board of Directors Interest';
	return $keys;
}

//  Set number of columns for related products
add_filter ( 'woocommerce_product_thumbnails_columns', 'acme_thumb_cols' );
function acme_thumb_cols()
{
    return 4;
}

//  Set number of columns for products on shop page
add_filter ( 'loop_shop_columns', 'acme_loop_shop_columns' );
function acme_loop_shop_columns()
{
    return 3;
}
?>
