<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class Superaddons_WPForms_Restrict_Date_Field extends WPForms_Field {
	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		// Define field type information.
		$this->name  = esc_html__( 'Restrict Date', 'restrict-dates-for-wpforms' );
		$this->type  = 'restrict_date';
		$this->icon  = 'fa-calendar-o';
		$this->order = 130;
		$this->defaults = [
			'date_limit_days_sun'         => '1',
			'date_limit_days_mon'         => '1',
			'date_limit_days_tue'         => '1',
			'date_limit_days_wed'         => '1',
			'date_limit_days_thu'         => '1',
			'date_limit_days_fri'         => '1',
			'date_limit_days_sat'         => '1',
		];
		add_action( 'wpforms_frontend_js', array( $this, 'frontend_js' ) );
		add_action( 'wpforms_frontend_css', array( $this, 'frontend_css' ) );
		add_action( 'wpforms_builder_enqueues', array( $this, 'addmin_js' ) );
	}
	function addmin_js(){
		wp_enqueue_script(
				'wpforms-date-restrict',
				SUPERADDONS_WPFORMS_RESTRICT_DATE_PLUGIN_URL . 'libs/js/date_restrict_admin.js',
				array("jquery")
			);
		wp_enqueue_style(
				'wpforms-date-restrict',
				SUPERADDONS_WPFORMS_RESTRICT_DATE_PLUGIN_URL . 'libs/css/admin.css',
			);
	}
	function frontend_js(){
		wp_enqueue_script( 'jquery-ui-datepicker' ); 
		wp_localize_jquery_ui_datepicker();
		wp_enqueue_script(
			'wpforms-restrict-date',
			SUPERADDONS_WPFORMS_RESTRICT_DATE_PLUGIN_URL . 'libs/js/date_restrict.js',
			array("jquery","moment"),
			time(),
			true
		);
	}
	function frontend_css(){
		wp_enqueue_style(
			'jquery-ui',
			SUPERADDONS_WPFORMS_RESTRICT_DATE_PLUGIN_URL . 'libs/css/jquery-ui.css',
			array(),
			'1.9.0'
		);
	}
	/**
	 * Field options panel inside the builder.
	 *
	 * @since 1.0.0
	 *
	 * @param array $field Field data.
	 */
	public function field_options( $field ) {
		/*
		 * Basic field options.
		 */
		// Options open markup.
		$args = array(
			'markup' => 'open',
		);
		$this->field_option( 'basic-options', $field, $args );
		// Label.
		$this->field_option( 'label', $field );
		$this->add_options( 'format', $field );
		// Description.
		$this->field_option( 'description', $field );
		// Required toggle.
		$this->field_option( 'required', $field );
		// Options close markup.
		$this->field_option(
			'basic-options',
			$field,
			[
				'markup' => 'close',
			]
		);

		/*
		 * Advanced field options
		 */

		// Options open markup.
		$this->field_option(
			'advanced-options',
			$field,
			[
				'markup' => 'open',
			]
		);
		$this->field_option( 'size', $field );
		$this->field_option( 'css', $field );
		// Options close markup.
		$this->field_option(
			'advanced-options',
			$field,
			[
				'markup' => 'close',
			]
		);
	}
	function add_options($type,$field){
		$check_pro = get_option( '_redmuber_item_2646');
		$output ="";
		$lbl = $this->field_element(
			'label',
			$field,
			array(
				'slug'    => 'date_format',
				'value'   => esc_html__( 'Format', 'restrict-dates-for-wpforms' ),
			),
			false
		);
		$fld = $this->field_element(
			'text',
			$field,
			array(
				'slug'  => 'date_format',
				'value' => ! empty( $field['date_format'] ) ? esc_attr( $field['date_format'] ) : 'yy-mm-dd',
			),
			false
		);
		$output .= $this->field_element(
			'row',
			$field,
			array(
				'slug'    => 'date_format',
				'content' => $lbl . $fld,
			)
		);
		//min
		$lbl = $this->field_element(
			'label',
			$field,
			array(
				'slug'    => 'date_min',
				'value'   => esc_html__( 'Min', 'restrict-dates-for-wpforms' ),
			),
			false
		);
		$date_min   = ! empty( $field['date_min'] ) ? esc_attr( $field['date_min'] ) : '';
		$fld = $this->field_element(
			'select',
			$field,
			array(
				'slug'  => 'date_min',
				'options' => array(""=>"None","current_date"=>"Current date","special"=>"Set date"),
				'value' => $date_min,
			),
			false
		);
		$output .= $this->field_element(
			'row',
			$field,
			array(
				'slug'    => 'date_min',
				'content' => $lbl . $fld,
			)
		);
		//min type Current date
		$current_date_class = "hidden";
		$special_class = "hidden";
		if( $date_min == "current_date"){
			$current_date_class ="";
		}elseif( $date_min == "special"){
			$special_class = "";
		}
		$fld = $this->field_element(
			'select',
			$field,
			array(
				'slug'  => 'current_date_min_plus',
				'options' => array("+"=>"+","-"=>"-"),
				'value' => ! empty( $field['min_plus'] ) ? esc_attr( $field['min_plus'] ) : '-',
			),
			false
		);
		$fld .= $this->field_element(
			'text',
			$field,
			array(
				'slug'  => 'current_date_min_number',
				'value' => ! empty( $field['current_date_min_number'] ) ? esc_attr( $field['current_date_min_number'] ) : '0',
			),
			false
		);
		$fld .= $this->field_element(
			'select',
			$field,
			array(
				'slug'  => 'current_date_min_type',
				'options' => array("d"=>"Day(s)","m"=>"Month(s)","y"=>"Year(s)"),
				'value' => ! empty( $field['current_date_min_type'] ) ? esc_attr( $field['current_date_min_type'] ) : 'd',
			),
			false
		);
		$columns ='<div class="wpforms-field-options-columns-3 wpforms-field-options-columns">';
		$output .= $this->field_element(
			'row',
			$field,
			array(
				'slug'    => 'min_current_date',
				'class'  => array('wpforms-field-options-columns',$current_date_class),
				'content' => $fld,
			)
		);
		//min set date
		$lbl = $this->field_element(
			'label',
			$field,
			array(
				'slug'    => 'min_pick',
				'value'   => esc_html__( 'Min set date', 'restrict-dates-for-wpforms' ),
			),
			false
		);
		$min_pick = ! empty( $field['min_pick'] ) ? esc_attr( $field['min_pick'] ) : '';
		$fld = '<input type="date" class="" id="wpforms-field-option-'.$field['id'].'-min_pick" name="fields['.$field['id'].'][min_pick]" value="'.$min_pick.'" placeholder="YYYY-MM-DD">';
		$output .= $this->field_element(
			'row',
			$field,
			array(
				'slug'    => 'min_pick',
				'class'  => $special_class,
				'content' => $lbl . $fld,
			)
		);
		
		
		//---------------------------max---------------------
		$lbl = $this->field_element(
			'label',
			$field,
			array(
				'slug'    => 'date_max',
				'value'   => esc_html__( 'Max', 'restrict-dates-for-wpforms' ),
			),
			false
		);
		$date_max   = ! empty( $field['date_max'] ) ? esc_attr( $field['date_max'] ) : '';
		$current_date_class = "hidden";
		$special_class = "hidden";
		if( $date_max == "current_date"){
			$current_date_class ="";
		}elseif( $date_max == "special"){
			$special_class = "";
		}
		$fld = $this->field_element(
			'select',
			$field,
			array(
				'slug'  => 'date_max',
				'options' => array(""=>"None","current_date"=>"Current date","special"=>"Set date"),
				'value' => $date_max,
			),
			false
		);
		$output .= $this->field_element(
			'row',
			$field,
			array(
				'slug'    => 'date_max',
				'content' => $lbl . $fld,
			)
		);
		//min type Current date
		$fld = $this->field_element(
			'select',
			$field,
			array(
				'slug'  => 'current_date_max_plus',
				'options' => array("+"=>"+","-"=>"-"),
				'value' => ! empty( $field['current_date_max_plus'] ) ? esc_attr( $field['current_date_max_plus'] ) : '+',
			),
			false
		);
		$fld .= $this->field_element(
			'text',
			$field,
			array(
				'slug'  => 'current_date_max_number',
				'value' => ! empty( $field['current_date_max_number'] ) ? esc_attr( $field['current_date_max_number'] ) : '0',
			),
			false
		);
		$fld .= $this->field_element(
			'select',
			$field,
			array(
				'slug'  => 'current_date_max_type',
				'options' => array("d"=>"Day(s)","m"=>"Month(s)","y"=>"Year(s)"),
				'value' => ! empty( $field['current_date_max_type'] ) ? esc_attr( $field['current_date_max_type'] ) : 'd',
			),
			false
		);
		$output .= $this->field_element(
			'row',
			$field,
			array(
				'slug'    => 'max_current_date',
				'class'  => array($current_date_class,'wpforms-field-options-columns'),
				'content' => $fld,
			)
		);
		//min set date
		$lbl = $this->field_element(
			'label',
			$field,
			array(
				'slug'    => 'max_pick',
				'value'   => esc_html__( 'Max set date', 'restrict-dates-for-wpforms' ),
			),
			false
		);
		$max_pick = ! empty( $field['max_pick'] ) ? esc_attr( $field['max_pick'] ) : '';
		$fld = '<input type="date" class="" id="wpforms-field-option-'.$field['id'].'-max_pick" name="fields['.$field['id'].'][max_pick]" value="'.$max_pick.'" placeholder="YYYY-MM-DD">';
		$output .= $this->field_element(
			'row',
			$field,
			array(
				'slug'    => 'max_pick',
				'content' => $lbl . $fld,
				'class'=> $special_class,
			)
		);
		//Link min field
		$toggle  = '<a href="#" class="toggle-smart-tag-display toggle-unfoldable-cont" data-type="fields"><i class="fa fa-tags"></i><span>' . esc_html__( 'Show Smart Tags', 'restrict-dates-for-wpforms' ) . '</span></a>';
		$lbl = $this->field_element(
			'label',
			$field,
			array(
				'slug'    => 'sync_min',
				'after_tooltip' => $toggle,
				'value'   => esc_html__( 'Link min field', 'restrict-dates-for-wpforms' ),
			),
			false
		);
		$fld = $this->field_element(
			'text',
			$field,
			array(
				'slug'  => 'sync_min',
				'value' => ! empty( $field['sync_min'] ) ? esc_attr( $field['sync_min'] ) : '',
			),
			false
		);
		if($check_pro != "ok"){
			$output .= $this->field_element(
				'row',
				$field,
				array(
					'slug'    => 'sync_min',
					'class'   => 'pro_disable',
					'content' => $lbl . '<div class="pro_disable_padding pro_text_style">Upgrade to pro version</div>',
				)
			);
		}else{
			$output .= $this->field_element(
				'row',
				$field,
				array(
					'slug'    => 'sync_min',
					'content' => $lbl . $fld,
				)
			);
		}
		//Link min field
		$toggle  = '<a href="#" class="toggle-smart-tag-display toggle-unfoldable-cont" data-type="fields"><i class="fa fa-tags"></i><span>' . esc_html__( 'Show Smart Tags', 'restrict-dates-for-wpforms' ) . '</span></a>';
		$lbl = $this->field_element(
			'label',
			$field,
			array(
				'slug'    => 'sync_max',
				'after_tooltip' => $toggle,
				'value'   => esc_html__( 'Link max field', 'restrict-dates-for-wpforms' ),
			),
			false
		);
		$fld = $this->field_element(
			'text',
			$field,
			array(
				'slug'  => 'sync_max',
				'value' => ! empty( $field['sync_max'] ) ? esc_attr( $field['sync_max'] ) : '',
			),
			false
		);
		if($check_pro != "ok"){
			$output .= $this->field_element(
				'row',
				$field,
				array(
					'slug'    => 'sync_max',
					'class'   => 'pro_disable',
					'content' => $lbl . '<div class="pro_disable_padding pro_text_style">Upgrade to pro version</div>',
				)
				);
		}else{
			$output .= $this->field_element(
				'row',
				$field,
				array(
					'slug'    => 'sync_max',
					'content' => $lbl . $fld,
				)
			);
		}
		$lbl = $this->field_element(
			'label',
			$field,
			array(
				'slug'    => 'sync_min_number',
				'value'   => esc_html__( 'Min number of dependent days', 'restrict-dates-for-wpforms' ),
			),
			false
		);
		$fld = $this->field_element(
			'text',
			$field,
			array(
				'slug'  => 'sync_min_number',
				'value' => ! empty( $field['sync_min_number'] ) ? esc_attr( $field['sync_min_number'] ) : '',
			),
			false
		);
		if($check_pro != "ok"){
			$output .= $this->field_element(
				'row',
				$field,
				array(
					'slug'    => 'sync_min_number',
					'class'   => 'pro_disable',
					'content' => $lbl . '<div class="pro_disable_padding pro_text_style">Upgrade to pro version</div>',
				)
				);
		}else{
			$output .= $this->field_element(
				'row',
				$field,
				array(
					'slug'    => 'sync_min_number',
					'content' => $lbl . $fld,
				)
			);
		}
		$lbl = $this->field_element(
			'label',
			$field,
			array(
				'slug'    => 'sync_max_number',
				'value'   => esc_html__( 'Max number of dependent days', 'restrict-dates-for-wpforms' ),
			),
			false
		);
		$fld = $this->field_element(
			'text',
			$field,
			array(
				'slug'  => 'sync_max_number',
				'value' => ! empty( $field['sync_max_number'] ) ? esc_attr( $field['sync_max_number'] ) : '',
			),
			false
		);
		if($check_pro != "ok"){
			$output .= $this->field_element(
				'row',
				$field,
				array(
					'slug'    => 'sync_max_number',
					'class'   => 'pro_disable',
					'content' => $lbl . '<div class="pro_disable_padding pro_text_style">Upgrade to pro version</div>',
				)
				);
		}else{
			$output .= $this->field_element(
				'row',
				$field,
				array(
					'slug'    => 'sync_max_number',
					'content' => $lbl . $fld,
				)
			);
		}
		//Weeks
		$lbl = $this->field_element(
			'label',
			$field,
			array(
				'slug'    => 'weeks',
				'value'   => esc_html__( 'Weeks', 'restrict-dates-for-wpforms' ),
			),
			false
		);
		$output .= $this->field_element(
			'row',
			$field,
			array(
				'slug'    => 'weeks',
				'content' => $lbl,
			)
		);
		$output .= $this->field_options_limit_days($field);
		$lbl = $this->field_element(
			'label',
			$field,
			array(
				'slug'    => 'exceptions',
				'value'   => esc_html__( 'Exceptions (yyyy-mm-dd 2024-05-25)', 'restrict-dates-for-wpforms' ),
				'tooltip' => esc_html__( 'Enter each option in a separate line (format : y-m-d). For example 2024-12-30', 'restrict-dates-for-wpforms' ),
			),
			false
		);
		$fld = $this->field_element(
			'textarea',
			$field,
			array(
				'slug'  => 'exceptions',
				'value' => ! empty( $field['exceptions'] ) ? esc_textarea( $field['exceptions'] ) : '',
			),
			false
		);
		$output .= $this->field_element(
			'row',
			$field,
			array(
				'slug'    => 'exceptions',
				'content' => $lbl .$fld,
			)
		);
		echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	/**
	 * Field preview inside the builder.
	 *
	 * @since 1.0.0
	 *
	 * @param array $field Field data.
	 */
	private function field_options_limit_days( $field ) {
		$output = '<div class="wpforms-clear"></div>';
		$week_days = [
			'sun' => esc_html__( 'Sun', 'restrict-dates-for-wpforms' ),
			'mon' => esc_html__( 'Mon', 'restrict-dates-for-wpforms' ),
			'tue' => esc_html__( 'Tue', 'restrict-dates-for-wpforms' ),
			'wed' => esc_html__( 'Wed', 'restrict-dates-for-wpforms' ),
			'thu' => esc_html__( 'Thu', 'restrict-dates-for-wpforms' ),
			'fri' => esc_html__( 'Fri', 'restrict-dates-for-wpforms' ),
			'sat' => esc_html__( 'Sat', 'restrict-dates-for-wpforms' ),
		];
		// Rearrange days array according to the Start of Week setting.
		$start_of_week = get_option( 'start_of_week' );
		$start_of_week = ! empty( $start_of_week ) ? (int) $start_of_week : 0;
		if ( $start_of_week > 0 ) {
			$days_after = $week_days;
			$days_begin = array_splice( $days_after, 0, $start_of_week );
			$days       = array_merge( $days_after, $days_begin );
		} else {
			$days = $week_days;
		}
		// Limit Days body.
		$output = '';
		$defaults = array();
		foreach ( $days as $day => $day_translation ) {
			$day_slug = 'date_limit_days_' . $day;
			// Set defaults.
			if ( ! isset( $field['date_format'] ) ) {
				$field[ $day_slug ] = $this->defaults[ $day_slug ];
			}
			$output .= '<label class="sub-label">';
			$output .= $this->field_element(
				'checkbox',
				$field,
				[
					'slug'   => $day_slug,
					'value'  => ! empty( $field[ $day_slug ] ) ? '1' : '0',
					'nodesc' => '1',
					'class'  => 'wpforms-field-options-column',
				],
				false
			);
			$output .= '<br>' . $day_translation . '</label>';
		}
		printf(
			'<div
				class="wpforms-field-option-row wpforms-field-option-row-date_limit_days_options wpforms-panel-field-toggle-body wpforms-field-options-columns wpforms-field-options-columns-7 checkboxes-row"
				id="wpforms-field-option-row-%1$d-date_limit_days_options"
				data-toggle="%2$s"
				data-toggle-value="1"
				data-field-id="%1$d">%3$s</div>',
			esc_attr( $field['id'] ),
			esc_attr( 'fields[' . (int) $field['id'] . '][date_limit_days]' ),
			$output // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
	}
	public function field_preview( $field ) {
		// Define data.
		$placeholder = ! empty( $field['input_lable'] ) ? $field['input_lable'] : '';
		// Label.
		$this->field_preview_option( 'label', $field );
		// Primary input.
		printf('<input value="" type="text" placeholder="%s" class="primary-input" readonly>',esc_attr( $placeholder ) );
		$this->field_preview_option( 'description', $field );
	}
	public function field_display( $field, $deprecated, $form_data ) {
		// Define data.
		$primary = $field['properties']['inputs']['primary'];
		$class = $primary['class'];
		$class[] = "wpforms-restrict_date-field";
		$attr = $primary['attr'];
		$date_format = ! empty( $field['date_format'] ) ? esc_attr( $field['date_format'] ) : '';
		$min = ! empty( $field['date_min'] ) ? esc_attr( $field['date_min'] ) : '';
		$current_date_min_plus = ! empty( $field['current_date_min_plus'] ) ? esc_attr( $field['current_date_min_plus'] ) : '';
		$current_date_min_number = ! empty( $field['current_date_min_number'] ) ? esc_attr( $field['current_date_min_number'] ) : '';
		$current_date_min_type = ! empty( $field['current_date_min_type'] ) ? esc_attr( $field['current_date_min_type'] ) : '';
		$min_pick = ! empty( $field['min_pick'] ) ? esc_attr( $field['min_pick'] ) : '';
		$date_max = ! empty( $field['date_max'] ) ? esc_attr( $field['date_max'] ) : '';
		$current_date_max_plus = ! empty( $field['current_date_max_plus'] ) ? esc_attr( $field['current_date_max_plus'] ) : '';
		$current_date_max_number = ! empty( $field['current_date_max_number'] ) ? esc_attr( $field['current_date_max_number'] ) : '';
		$current_date_max_type = ! empty( $field['current_date_max_type'] ) ? esc_attr( $field['current_date_max_type'] ) : '';
		$max_pick = ! empty( $field['max_pick'] ) ? esc_attr( $field['max_pick'] ) : '';
		$date_limit_days_wed = ! empty( $field['date_limit_days_wed'] ) ? esc_attr( $field['date_limit_days_wed'] ) : '';
		$exceptions = ! empty( $field['exceptions'] ) ? esc_attr( $field['exceptions'] ) : '';
		$sync_min_number = ! empty( $field['sync_min_number'] ) ? esc_attr( $field['sync_min_number'] ) : '';
		$sync_min = ! empty( $field['sync_min'] ) ? esc_attr( $field['sync_min'] ) : '';
		$sync_min = (int) filter_var($sync_min, FILTER_SANITIZE_NUMBER_INT);
		if($sync_min == 0){
			$sync_min = "";
		}
		$sync_max_number = ! empty( $field['sync_max_number'] ) ? esc_attr( $field['sync_max_number'] ) : '';
		$sync_max = ! empty( $field['sync_max'] ) ? esc_attr( $field['sync_max'] ) : '';
		$sync_max = (int) filter_var($sync_max, FILTER_SANITIZE_NUMBER_INT);
		if($sync_max == 0){
			$sync_max = "";
		}
		$attr["data-min"] = $min;
		$attr["data-max_plus_min"] = $current_date_min_plus;
		$attr["data-max_number_min"] = $current_date_min_number;
		$attr["data-max_type_min"] = $current_date_min_type;
		$attr["data-min_pick"] = $min_pick;
		$attr["data-max"] = $date_max;
		$attr["data-max_plus"] = $current_date_max_plus;
		$attr["data-max_number"] = $current_date_max_number;
		$attr["data-max_type"] = $current_date_max_type;
		$attr["data-max_pick"] = $max_pick;
		$exceptions = array_map('trim', explode(PHP_EOL, $exceptions)); 
		$attr["data-special"] = implode("|",$exceptions);
		$attr["data-format"] = $date_format;
		$attr["data-sync_min"] = $sync_min;
		$attr["data-sync_max"] = $sync_max;
		$attr["data-sync_min_number"] = $sync_min_number;
		$attr["data-sync_max_number"] = $sync_max_number;
		$week_days = [
			'sun' => esc_html__( 'Sun', 'restrict-dates-for-wpforms' ),
			'mon' => esc_html__( 'Mon', 'restrict-dates-for-wpforms' ),
			'tue' => esc_html__( 'Tue', 'restrict-dates-for-wpforms' ),
			'wed' => esc_html__( 'Wed', 'restrict-dates-for-wpforms' ),
			'thu' => esc_html__( 'Thu', 'restrict-dates-for-wpforms' ),
			'fri' => esc_html__( 'Fri', 'restrict-dates-for-wpforms' ),
			'sat' => esc_html__( 'Sat', 'restrict-dates-for-wpforms' ),
		];
		$week_days_datas = array();
		$i=0;
		foreach( $week_days as $key => $vl ){
			if(!empty( $field['date_limit_days_'.$key] )){
				$week_days_datas[] =  $i;
			}
			$i++;
		}
		$attr["data-weekdays"] = implode("|",$week_days_datas);
		printf(
			'<input type="text" %s %s>',
			wpforms_html_attributes( $primary['id'], $class, $primary['data'], $attr ),
			$primary['required'] // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
	}
}
new Superaddons_WPForms_Restrict_Date_Field();