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
 * Add additonal fields to the checkout process
 **/

/**
 * Define Volunteer Checkbox fields which are used in
 * several places throughout the custom checkout process.
 *
 * @return mixed - array of meta fields and labels
 */
function gl_checkout_volunteer_checkbox_fields()
{
    return array(
        array(
            'meta' => 'gl_bod_interest',
            'label' => __('Board of Directors', 'woocommerce'),
            'desc' => __('(Officers, Committees, etc.)', 'woocommerce'),
        ),
        array(
            'meta' => 'gl_team_parent_manager',
            'label' => __('Team Parent / Manager', 'woocommerce'),
            'desc' => '',
        ),
        array(
            'meta' => 'gl_sports_concessions',
            'label' => __('Sports Concessions', 'woocommerce'),
            'desc' => __('(fall or spring)', 'woocommerce'),
        ),
        array(
            'meta' => 'gl_dance_concessions',
            'label' => __('Dance Concessions', 'woocommerce'),
            'desc' => __('(3 - Oct, Feb, May)', 'woocommerce'),
        ),
        array(
            'meta' => 'gl_end_of_season_reception',
            'label' => __('End of Season Reception', 'woocommerce'),
            'desc' => __('(3 - Nov, Feb, May)', 'woocommerce'),
        ),
    ) ;
}
add_action('woocommerce_after_order_notes', 'gl_booster_club_custom_checkout_field');
 
function gl_booster_club_custom_checkout_field( $checkout ) {
 
    echo '<div id="gl-booster-student-information">' .
        __('<h4>Student Information</h4><small>Required for delivery to school office</small>', 'woocommerce');

    woocommerce_form_field( 'gl_student_name', array(
        'type'          => 'text',
        'class'         => array('gl-field-class form-row-wide'),
        'label'         => __('Student Name'),
        'placeholder'   => __('Enter Student\'s Name'),
        'required'      => true,
        ), $checkout->get_value( 'gl_student_name' ));
 
    woocommerce_form_field( 'gl_student_grade', array(
        'type'          => 'select',
        'class'         => array('input-select'),
        'label'         => __('Student\'s Grade'),
        'required'      => true,
        'options'       => array(
            'select' => '-- Please select grade --',
            '6' => __('6th Grade', 'woocommerce'),
            '7' => __('7th Grade', 'woocommerce'),
            '8' => __('8th Grade', 'woocommerce')
        ),
        ), $checkout->get_value( 'gl_student_grade' ));
 
    woocommerce_form_field( 'gl_student_track', array(
        'type'          => 'select',
        'class'         => array('input-select'),
        'label'         => __('Student\'s Track'),
        'required'  => true,
        'options'       => array(
            'select' => '-- Please select track --',
            '1' => __('Track 1', 'woocommerce'),
            '2' => __('Track 2', 'woocommerce'),
            '3' => __('Track 3', 'woocommerce'),
            '4' => __('Track 4', 'woocommerce')
        ),
        ), $checkout->get_value( 'gl_student_track' ));
 
    echo '</div>';

    echo '<div id="gl-booster-club-help-wanted">' .
        __('<h4>The Booster Club needs your help!</h4><small>Please indicate your areas of interest</small>', 'woocommerce') ;
 
    $volunteer_checkbox_fields = gl_checkout_volunteer_checkbox_fields() ;

    foreach ($volunteer_checkbox_fields as $cb)
    {
        woocommerce_form_field( $cb['meta'], array(
            'type'          => 'checkbox',
            'class'         => array('input-checkbox'),
            'label'         => sprintf('%s <i><small>%s</small></i>', $cb['label'], $cb['desc']),
            'required'  => false,
            ), $checkout->get_value( $cb['meta'] ));
    }
 
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
    if ('' === $_POST['gl_student_name'])
         $woocommerce->add_error( __('Please provide Student\'s name.', 'woocommerce') );
    if ('select' === $_POST['gl_student_grade'])
         $woocommerce->add_error( __('Please provide Student\'s grade.', 'woocommerce') );
    if ('select' === $_POST['gl_student_track'])
         $woocommerce->add_error( __('Please provide Student\'s track.', 'woocommerce') );
}
 
/**
 * Update the order meta with field value
 *
 */
add_action('woocommerce_checkout_update_order_meta', 'gl_booster_club_custom_checkout_field_update_order_meta');
 
function gl_booster_club_custom_checkout_field_update_order_meta( $order_id ) {
    error_log(print_r($_POST, true)) ;
    if ($_POST['gl_student_name']) update_post_meta( $order_id, 'Student Name', esc_attr($_POST['gl_student_name']));
    if ($_POST['gl_student_grade']) update_post_meta( $order_id, 'Student Grade', esc_attr($_POST['gl_student_grade']));
    if ($_POST['gl_student_track']) update_post_meta( $order_id, 'Student Track', esc_attr($_POST['gl_student_track']));

    //  Scan checkboxes for volunteer interest which is all stored in one meta field
    $volunteer_checkbox_fields = gl_checkout_volunteer_checkbox_fields() ;
    $volunteer_interest = array() ;

    foreach ($volunteer_checkbox_fields as $cb)
    {
        if ($_POST[$cb['meta']])
            $volunteer_interest[] = $cb['label'] ;
    }

    error_log(print_r($volunteer_interest, true)) ;
    error_log(print_r(implode(', ', $volunteer_interest), true)) ;

    if (!empty($volunteer_interest))
        update_post_meta( $order_id, 'Volunteer Interest', esc_attr(implode(', ', $volunteer_interest)));
}

/**
 * Add the field to order emails
 **/
add_filter('woocommerce_email_order_meta_keys', 'gl_custom_checkout_field_order_meta_keys');
 
function gl_custom_checkout_field_order_meta_keys( $keys ) {
	$keys[] = 'Student Name';
	$keys[] = 'Student Grade';
	$keys[] = 'Student Track';
	$keys[] = 'Volunteer Interest';
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
