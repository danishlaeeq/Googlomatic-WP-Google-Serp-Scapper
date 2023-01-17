<?php

/**
 * Plugin option page
 */

class GooglomaticOption {
	private $googlomatic_options; 

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'googlomatic_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'googlomatic_page_init' ) );
	}

	public function googlomatic_add_plugin_page() {
		add_menu_page(
			'Googlomatic', // page_title
			'Googlomatic', // menu_title
			'manage_options', // capability
			'googlomatic_settings', // menu_slug
			array( $this, 'googlomatic_create_admin_page' ), // function
			'dashicons-welcome-view-site', // icon_url
			3 // position
		);
	}

	public function googlomatic_create_admin_page() {
		$this->googlomatic_options = get_option( 'googlomatic_option_name' ); ?>

		<div class="wrap">
            <h2><b>Googlomatic plugin</b></h2>
            <div class="googlomatic__options">
                <div class="googlomatic__options-right">
                    <div class="googlomatic__options-message">
                        <h1>Instructions</h1>
                        <p>Add the shortcode <code>[serp_list]</code> to a page with city name, to show the Top 10 Serp Results.</p>
                        <p>Add the shortcode <code>[serp_list city="city name here"]</code> to any page, to show the Top 10 Serp Results.</p>
						<ul>
							<li>Get Your Google Custom Search API <a href="https://developers.google.com/custom-search/v1/overview" style="color:blue;font-weight:700">( Here )</a></li>
							<li>Get Your Search Enginer ID <a href="https://programmablesearchengine.google.com/u/0/controlpanel/all" style="color:blue;font-weight:700">( Here )</a></li>
						</ul>
                    </div>
                </div>
				<div class="googlomatic__options-left">
                    <?php settings_errors(); ?>
                    <form method="post" action="options.php">
                        <?php
                            settings_fields( 'googlomatic_option_group' );
                            do_settings_sections( 'googlomatic-admin' );
                            submit_button();
                        ?>
                    </form>
                </div>
            </div>
		</div>
	<?php }

	public function googlomatic_page_init() {
		register_setting(
			'googlomatic_option_group', // option_group
			'googlomatic_option_name', // option_name
			array( $this, 'googlomatic_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'googlomatic_setting_section', // id
			'Settings', // title
			array( $this, 'googlomatic_section_info' ), // callback
			'googlomatic-admin' // page
		);

        add_settings_field(
			'googlomatic_api', // id
			'API Key', // title
			array( $this, 'googlomatic_api_callback' ), // callback
			'googlomatic-admin', // page
			'googlomatic_setting_section' // section
		);

		add_settings_field(
			'googlomatic_seid', // id
			'Search Engine ID', // title
			array( $this, 'googlomatic_seid_callback' ), // callback
			'googlomatic-admin', // page
			'googlomatic_setting_section' // section
		);

		add_settings_field(
			'search_term_0', // id
			'Search term ', // title
			array( $this, 'search_term_0_callback' ), // callback
			'googlomatic-admin', // page
			'googlomatic_setting_section' // section
		);

		add_settings_field(
			'googlo_lang', // id
			'Language ', // title
			array( $this, 'googlo_lang_callback' ), // callback
			'googlomatic-admin', // page
			'googlomatic_setting_section' // section
		);

		add_settings_field(
			'googlomatic_limit', // id
			'Results Limit', // title
			array( $this, 'googlomatic_limit_callback' ), // callback
			'googlomatic-admin', // page
			'googlomatic_setting_section' // section
		);

	}

	public function googlomatic_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['googlo_lang'] ) ) {
			$sanitary_values['googlo_lang'] = sanitize_text_field( $input['googlo_lang'] );
		}
		if ( isset( $input['search_term_0'] ) ) {
			$sanitary_values['search_term_0'] = sanitize_text_field( $input['search_term_0'] );
		}
		if ( isset( $input['googlomatic_limit'] ) ) {
			$sanitary_values['googlomatic_limit'] = sanitize_text_field( $input['googlomatic_limit'] );
		}
		if ( isset( $input['googlomatic_seid'] ) ) {
			$sanitary_values['googlomatic_seid'] = sanitize_text_field( $input['googlomatic_seid'] );
		}

        if ( isset( $input['googlomatic_api'] ) ) {
			$sanitary_values['googlomatic_api'] = sanitize_text_field( $input['googlomatic_api'] );
		}

		return $sanitary_values;
	}

	public function googlomatic_section_info() {
		
	}

	public function search_term_0_callback() {
		printf(
			'<input class="regular-text" type="text" name="googlomatic_option_name[search_term_0]" id="search_term_0" value="%s">',
			isset( $this->googlomatic_options['search_term_0'] ) ? esc_attr( $this->googlomatic_options['search_term_0']) : ''
		);
	}

	public function googlomatic_limit_callback() {
		printf(
			'<small>( Max 10 results Only )</small></br><input class="regular-text" type="number" min="1" max="10" name="googlomatic_option_name[googlomatic_limit]" id="googlomatic_limit" value="%s">',
			isset( $this->googlomatic_options['googlomatic_limit'] ) ? esc_attr( $this->googlomatic_options['googlomatic_limit']) : ''
		);
	}
	
	public function googlo_lang_callback()
	{
	?>
			<select name="googlomatic_option_name[googlo_lang]" id="googlo_lang" style="width:inherit;">
				<option value="lang_en" <?php selected(esc_attr( $this->googlomatic_options['googlo_lang']), "lang_en"); ?>>English</option>
				<option value="lang_sv" <?php selected(esc_attr( $this->googlomatic_options['googlo_lang']), "lang_sv"); ?>>Swedish</option>
				<option value="lang_cs" <?php selected(esc_attr( $this->googlomatic_options['googlo_lang']), "lang_cs"); ?>>Czech</option>
				<option value="lang_da" <?php selected(esc_attr( $this->googlomatic_options['googlo_lang']), "lang_da"); ?>>Danish</option>
				<option value="lang_de" <?php selected(esc_attr( $this->googlomatic_options['googlo_lang']), "lang_de"); ?>>German</option>
				<option value="lang_el" <?php selected(esc_attr( $this->googlomatic_options['googlo_lang']), "lang_el"); ?>>Greek</option>
				<option value="lang_es" <?php selected(esc_attr( $this->googlomatic_options['googlo_lang']), "lang_es"); ?>>Spanish</option>
				<option value="lang_et" <?php selected(esc_attr( $this->googlomatic_options['googlo_lang']), "lang_et"); ?>>Estonian</option>
				<option value="lang_fr" <?php selected(esc_attr( $this->googlomatic_options['googlo_lang']), "lang_fr"); ?>>French</option>
				<option value="lang_ar" <?php selected(esc_attr( $this->googlomatic_options['googlo_lang']), "lang_ar"); ?>>Arabic</option>
				<option value="lang_bg" <?php selected(esc_attr( $this->googlomatic_options['googlo_lang']), "lang_bg"); ?>>Bulgarian</option>
				<option value="lang_ca" <?php selected(esc_attr( $this->googlomatic_options['googlo_lang']), "lang_ca"); ?>>Catalan</option>
			</select>
	<?php
	isset( $this->googlomatic_options['googlo_lang'] ) ? esc_attr( $this->googlomatic_options['googlo_lang']) : '';
	}

	public function googlomatic_seid_callback() {
		printf(
			'<input class="regular-text" type="text" name="googlomatic_option_name[googlomatic_seid]" id="googlomatic_seid" value="%s">
            ',
			isset( $this->googlomatic_options['googlomatic_seid'] ) ? esc_attr( $this->googlomatic_options['googlomatic_seid']) : ''
		);
	}

    public function googlomatic_api_callback() {
		printf(
			'<input class="regular-text" type="text" name="googlomatic_option_name[googlomatic_api]" id="googlomatic_api" value="%s">
            ',
			isset( $this->googlomatic_options['googlomatic_api'] ) ? esc_attr( $this->googlomatic_options['googlomatic_api']) : ''
		);
	}

}
if ( is_admin() )
	$googlomaticOption = new googlomaticOption();

/* 
 * Retrieve this value with:
 * $googlomatic_options = get_option( 'googlomatic_option_name' ); // Array of All Options
 * $search_term_0 = $googlomatic_options['search_term_0']; // Search term 
 * $googlomatic_seid = $googlomatic_options['googlomatic_seid']; // Number of serp
 */
