<?php
/**
 * Plugin Name: Restrict Dates for WPForms
 * Description: Restrict Dates is a WPForms add-on that restricts users from selecting specific dates in your WPForms date picker field.
 * Version: 1.0.5
 * Author: add-ons.org
 * Text Domain: restrict-dates-for-wpforms
 * Domain Path: /languages
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
define( 'SUPERADDONS_WPFORMS_RESTRICT_DATE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'SUPERADDONS_WPFORMS_RESTRICT_DATE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
class Superaddons_WPForms_Restrict_Date_Fields { 
    function __construct(){ 
        add_action( 'wpforms_loaded', array($this,'loads') );
    }
    function loads(){
        include SUPERADDONS_WPFORMS_RESTRICT_DATE_PLUGIN_PATH."fields/restrict_date.php";
        include SUPERADDONS_WPFORMS_RESTRICT_DATE_PLUGIN_PATH."superaddons/check_purchase_code.php";
        new Superaddons_Check_Purchase_Code( 
            array(
                "plugin" => "restrict-date-for-wpforms/restrict-date-for-wpforms.php",
                "id"=>"2646",
                "pro"=>"https://add-ons.org/plugin/wpforms-restrict-date/",
                "plugin_name"=> "WPForms Restrict Date field",
                "document"=>"https://add-ons.org/restrict-dates-field/"
            )
        );
    }
}
new Superaddons_WPForms_Restrict_Date_Fields;
if(!class_exists('Superaddons_List_Addons')) {  
    include SUPERADDONS_WPFORMS_RESTRICT_DATE_PLUGIN_PATH."add-ons.php"; 
}