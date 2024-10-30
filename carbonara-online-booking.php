<?php
/*
Plugin Name: Carbonara Online Reservation
Description: Carbonara online reservation integration
Version: 1.1.0
License: GPLv2
*/

// helper functions:
function carbonara_online_reservation_getEmbedUrl ($email){
	$endpointBaseUrl = 'https://us-central1-carbonara-1a538.cloudfunctions.net/getBookingUrl/get';
	return file_get_contents($endpointBaseUrl.'?embed=true&email='.urlencode($email));
}


// Shortcode
function carbonara_online_reservation_shortcode() {
    $user_email = get_option('carbonara_user_email');
    $iframe_url = carbonara_online_reservation_getEmbedUrl($user_email);
    $iframe = '<iframe src="' . esc_url($iframe_url) . '" width="430" height="1000" style="border: none; height: 1000px; width: 430px;"></iframe>';
    return $iframe;
}

function carbonara_online_reservation_init() {
    add_shortcode('carbonara_reservation', 'carbonara_online_reservation_shortcode');
}
add_action('init', 'carbonara_online_reservation_init');

// Add a settings page
function carbonara_online_reservation_menu() {
    add_options_page('Carbonara Online Reservation Settings', 'Carbonara Reservation', 'manage_options', 'carbonara-online-reservation', 'carbonara_online_reservation_settings_page');
}
add_action('admin_menu', 'carbonara_online_reservation_menu');

// settings page
function carbonara_online_reservation_settings_page() {
    ?>
    <div class="wrap">
        <h1>Carbonara Online Reservation Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('carbonara_online_reservation_settings');
            do_settings_sections('carbonara-online-reservation');
            submit_button();
            ?>
        </form>
        <br><br>
        <p style="font-size: 16px">Use this short-code on any page or post to make your booking widget appear: <b style="border: 3px solid red; padding: 4px;">[carbonara_reservation]</b></a></p>
        <br>
        <h3>Don't have a Carbonara account? Create one here: <a href="https://restaurant-manager.carbonaraapp.com/#/sign-up/create-account" target="_blank"> carbonaraapp.com</a></h3>
        <br>
        <h3>See tutorial on how to set up and use the plugin here: <a href="https://www.carbonaraapp.com/wordpress-reservations-plugin/" target="_blank"> carbonaraapp.com</a></h3>
    </div>
    <?php
}

// Register the settings
function carbonara_online_reservation_settings_init() {
    register_setting('carbonara_online_reservation_settings', 'carbonara_user_email');

    add_settings_section(
        'carbonara_online_reservation_section',
        'Connect your Carbonara account',
        null,
        'carbonara-online-reservation'
    );

    add_settings_field(
        'carbonara_user_email',
        'Your Carbonara Account Email (use the same email as your Carbonara account)',
        'carbonara_online_reservation_user_email_field',
        'carbonara-online-reservation',
        'carbonara_online_reservation_section'
    );
}
add_action('admin_init', 'carbonara_online_reservation_settings_init');

// Display the User Email field
function carbonara_online_reservation_user_email_field() {
    $user_email = get_option('carbonara_user_email');
    echo '<input type="email" name="carbonara_user_email" value="' . esc_attr($user_email) . '" />';
}
?>
