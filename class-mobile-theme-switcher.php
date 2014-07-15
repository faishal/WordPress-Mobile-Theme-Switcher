<?php

Class Mobile_Theme_Switcher {

	protected $mobile_theme_stylesheet;

	protected $mobile_theme_template;

	function __construct() {
		add_filter( 'option_mobile_switch_option', array( &$this, 'option_mobile_switch_option' ) );

		$options                       = get_option( 'mobile_switch_option' );
		$this->mobile_theme_stylesheet = $options[ 'mobile_theme' ];
		$this->mobile_theme_template   = $options[ 'mobile_theme_template' ];

		$this->is_mobile = wp_is_mobile();

		add_action( 'admin_init', array( &$this, 'admin_init' ) );
		add_action( 'admin_menu', array( &$this, 'add_admin' ) );
		add_action( 'setup_theme', array( &$this, 'setup_theme' ) );
	}

	function setup_theme() {

		add_filter( 'stylesheet', array( &$this, 'stylesheet' ) );
		add_filter( 'template', array( &$this, 'template' ) );
	}

	function option_mobile_switch_option( $value ) {
		if ( $value === false ) {
			return array(
				'mobile_theme'          => get_stylesheet(),
				'mobile_theme_template' => get_template(),
			);
		}

		return $value;
	}

	public function init() {

	}

	public function stylesheet( $stylesheet ) {

		if ( $this->is_mobile ) {
			return $this->mobile_theme_stylesheet;
		}

		return $stylesheet;
	}

	public function template( $template ) {

		if ( $this->is_mobile ) {
			return $this->mobile_theme_template;
		}

		return $template;
	}

	public function admin_init() {
		$this->theme_option();
	}

	function add_admin() {
		add_theme_page( 'Mobile Theme Switch', 'Mobile Theme Switch', 'edit_theme_options', 'mobile-switch-theme', array(
			$this,
			'theme_option_view'
		) );
	}

	function theme_option_view() {
		?>
		<div class="wrap">
			<div class="icon32" id="icon-options-general"></div>
			<h2>Theme Option</h2>

			<form action="options.php" method="post">
				<?php
				settings_fields( 'mobile_switch_option' );
				do_settings_sections( 'mobile-switch-theme' );

				submit_button();
				?>
			</form>
			<script>
				jQuery('#mobile_theme_selector').change(function (e) {
					jQuery('#mobile_theme_template').val(jQuery(this).find(':selected').data('template'));
				});
			</script>
		</div><!-- wrap -->
	<?php
	}


	function theme_option() {
		register_setting( 'mobile_switch_option', 'mobile_switch_option' );
		add_settings_section( 'mobile_theme', 'Mobile Theme', array(
			$this,
			'mobile_theme_text'
		), 'mobile-switch-theme' );
		add_settings_field( 'mobile_theme_stylesheet', 'Mobile Theme', array(
			&$this,
			'mobile_theme'
		), 'mobile-switch-theme', 'mobile_theme' );
	}

	function mobile_theme_text() {
		echo '';
	}

	function mobile_theme() {
		$options = get_option( 'mobile_switch_option' );
		$themes  = wp_get_themes( array(
			'errors'  => false,
			'allowed' => 'site',
			'blog_id' => get_current_blog_id()
		) ); ?>
		<select id="mobile_theme_selector" name="mobile_switch_option[mobile_theme]">
			<?php
			foreach ( $themes as $theme ) {
				?>
				<option value="<?php echo esc_attr( $theme->stylesheet ); ?>"
				        data-template="<?php echo esc_attr( $theme->template ); ?>" <?php selected( $options[ 'mobile_theme' ], $theme->stylesheet ); ?> ><?php echo esc_html( $theme->name ); ?></option>
			<?php } ?>
		</select>
		<input type="hidden" id="mobile_theme_template" name="mobile_switch_option[mobile_theme_template]"
		       value="<?php echo esc_attr( $options[ 'mobile_theme_template' ] ); ?>"/>
	<?php
	}
}