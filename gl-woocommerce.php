<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * TwentyTen-GoLufkin WooCommerce
 *
 * $Id$
 *
 * (c) 2013 by Mike Walsh
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package TwentyTen-GoLufkin
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
add_action('woocommerce_after_order_notes', 'gl_booster_club_custom_checkout_field');
 
function gl_booster_club_custom_checkout_field( $checkout ) {
 
    echo '<div id="gl-booster-club-field"><h4>'.__('The Booster Club needs your help!').'</h4><small>Please indicate your areas of interest</small>';
 
    woocommerce_form_field( 'gl_bod_interest', array(
        'type'          => 'select',
        'class'         => array('input-select'),
        'label'         => __('Board of Directors<i><br/><small>(Officers, Committees, etc.)</small></i>'),
        'required'  => true,
        'options'       => array(
            'select' => '-- Please indicate interest --',
            'No' => __('I am not interested at this time.', 'woocommerce'),
            'Yes' => __('I would like to volunteer, please contact me.', 'woocommerce')
        ),
        ), $checkout->get_value( 'gl_bod_interest' ));
 
    woocommerce_form_field( 'gl_team_parent_manager', array(
        'type'          => 'select',
        'class'         => array('input-select'),
        'label'         => __('Team Parent / Manager'),
        'required'  => true,
        'options'       => array(
            'select' => '-- Please indicate interest --',
            'No' => __('I am not interested at this time.', 'woocommerce'),
            'Yes' => __('I would like to volunteer, please contact me.', 'woocommerce')
        ),
        ), $checkout->get_value( 'gl_volunteer_interest' ));
 
    woocommerce_form_field( 'gl_sports_concessions', array(
        'type'          => 'select',
        'class'         => array('input-select'),
        'label'         => __('Concessions at Home Sporting Events<i><br/><small>(fall or spring)</small></i>'),
        'required'  => true,
        'options'       => array(
            'select' => '-- Please indicate interest --',
            'No' => __('I am not interested at this time.', 'woocommerce'),
            'Yes' => __('I would like to volunteer, please contact me.', 'woocommerce')
        ),
        ), $checkout->get_value( 'gl_volunteer_interest' ));
 
    woocommerce_form_field( 'gl_dance_concessions', array(
        'type'          => 'select',
        'class'         => array('input-select'),
        'label'         => __('Concessions at Dances<i><br/><small>(3 - Oct, Feb, May)</small></i>'),
        'required'  => true,
        'options'       => array(
            'select' => '-- Please indicate interest --',
            'No' => __('I am not interested at this time.', 'woocommerce'),
            'Yes' => __('I would like to volunteer, please contact me.', 'woocommerce')
        ),
        ), $checkout->get_value( 'gl_volunteer_interest' ));
 
    woocommerce_form_field( 'gl_end_of_season_reception', array(
        'type'          => 'select',
        'class'         => array('input-select'),
        'label'         => __('End of Season Reception<i><br/><small>(3 - Nov, Feb, May)</small></i>'),
        'required'  => true,
        'options'       => array(
            'select' => '-- Please indicate interest --',
            'No' => __('I am not interested at this time.', 'woocommerce'),
            'Yes' => __('I would like to volunteer, please contact me.', 'woocommerce')
        ),
        ), $checkout->get_value( 'gl_volunteer_interest' ));
 
    echo '</div>';
}
 
/**
 * Process the checkout
 *
 */
add_action('woocommerce_checkout_process', 'gl_booster_club_custom_checkout_field_process');
 
function gl_booster_club_custom_checkout_field_process() {
    global $woocommerce;
 
    // Check if set, if its not set add an error.
    if ('select' === $_POST['gl_volunteer_interest'])
         $woocommerce->add_error( __('Please indicate Volunteer interest.') );
    if ('select' === $_POST['gl_bod_interest'])
         $woocommerce->add_error( __('Please indicate Board of Directors interest.') );
}
 
/**
 * Update the order meta with field value
 *
 */
add_action('woocommerce_checkout_update_order_meta', 'gl_booster_club_custom_checkout_field_update_order_meta');
 
function gl_booster_club_custom_checkout_field_update_order_meta( $order_id ) {
    if ($_POST['gl_volunteer_interest']) update_post_meta( $order_id, 'Volunteer Interest', esc_attr($_POST['gl_volunteer_interest']));
    if ($_POST['gl_bod_interest']) update_post_meta( $order_id, 'Board of Directors Interest', esc_attr($_POST['gl_bod_interest']));
}

/**
 * Add the field to order emails
 **/
add_filter('woocommerce_email_order_meta_keys', 'gl_custom_checkout_field_order_meta_keys');
 
function gl_custom_checkout_field_order_meta_keys( $keys ) {
	$keys[] = 'Volunteer Interest';
	$keys[] = 'Board of Directors Interest';
	return $keys;
}
?>
