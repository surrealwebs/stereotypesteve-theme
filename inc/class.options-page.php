<?php
class SSteveOptionsPage
{
	/**
	 * Holds the values to be used in the fields callbacks
	 */
	private $options;

	/**
	 * Start up
	 */
	public function __construct()
	{
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}

	/**
	 * Add options page
	 */
	public function add_plugin_page()
	{
		// This page will be under "Settings"
		add_options_page(
			'Settings Admin',
			'SterotypeSteve Settings',
			'manage_options',
			'ssteve-setting-admin',
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Options page callback
	 */
	public function create_admin_page()
	{
		// Set class property
		$this->options = get_option( 'ssteve_options' );
		?>
		<div class="wrap">
			<h1>SterotypeSteve Settings</h1>
			<form method="post" action="options.php">
				<?php
				// This prints out all hidden setting fields
				settings_fields( 'ssteve_option_group' );
				do_settings_sections( 'ssteve-setting-admin' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register and add settings
	 */
	public function page_init()
	{
		register_setting(
			'ssteve_option_group', // Option group
			'ssteve_options', // Option name
			array( $this, 'sanitize' ) // Sanitize
		);

		add_settings_section(
			'setting_section_id', // ID
			'StereotypeSteve Custom Settings', // Title
			array( $this, 'print_section_info' ), // Callback
			'ssteve-setting-admin' // Page
		);

		add_settings_field(
			'show-top-thread',
			'Show Top Thread',
			array( $this, 'top_thread_callback' ),
			'ssteve-setting-admin',
			'setting_section_id'
		);
	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 */
	public function sanitize( $input )
	{
		$new_input = array();
		if( isset( $input['show_top_thread'] ) )
			$new_input['show_top_thread'] = 1 == intval( $input['show_top_thread'] ) ? true : false;

		return $new_input;
	}

	/**
	 * Print the Section text
	 */
	public function print_section_info()
	{
		print 'Enter your settings below:';
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function top_thread_callback()
	{
		echo '<input type="radio" id="show_top_thread_yes" name="ssteve_options[show_top_thread]" value="1" ' . ( empty( $this->options['show_top_thread'] ) ? '' : ' checked="checked"' ) . '/><label for="show_top_thread_yes">Yes</label><br/>
<input type="radio" id="show_top_thread_no" name="ssteve_options[show_top_thread]" value="0" ' . ( empty( $this->options['show_top_thread'] ) ? ' checked="checked"' : '' ) . ' /><label for="show_top_thread_np">No</label><br/>';
	}
}

if( is_admin() )
	$ssteve_options_page = new SSteveOptionsPage();