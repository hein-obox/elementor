<?php
namespace Elementor\Modules\EyalDemo;

use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Module extends \Elementor\Core\Base\Module {

	public static function is_active(): true {
		return true;
	}

	public function get_name(): string {
		return 'eyal-demo';
	}

	public function __construct() {
		parent::__construct();

		add_action( 'elementor/frontend/after_register_styles', [ $this, 'register_styles' ] );
	}

	/**
	 * Register styles.
	 *
	 * At build time, Elementor compiles `/modules/eyal-demo/assets/scss/frontend.scss`
	 * to `/assets/css/widget-eyal-demo.min.css`.
	 *
	 * @return void
	 */
	public function register_styles(): void {
		$direction_suffix = is_rtl() ? '-rtl' : '';
		$has_custom_breakpoints = Plugin::$instance->breakpoints->has_custom_breakpoints();

		wp_register_style(
			'widget-eyal-demo',
			$this->get_frontend_file_url( "widget-eyal-demo{$direction_suffix}.min.css", $has_custom_breakpoints ),
			[ 'elementor-frontend' ],
			$has_custom_breakpoints ? null : ELEMENTOR_VERSION
		);
	}
}
