<?php

namespace Elementor\Modules\LinkInBio;

use Elementor\Core\Base\Module as BaseModule;
use Elementor\Core\Experiments\Manager;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends BaseModule {

	const EXPERIMENT_NAME = 'link-in-bio';

	const HAS_WIDGET_CUSTOM_BREAKPOINTS = true;

	const HAS_WIDGET_NO_CUSTOM_BREAKPOINTS = false;

	public function get_name(): string {
		return static::EXPERIMENT_NAME;
	}

	public function get_widgets(): array {
		return [
			'Link_In_Bio',
		];
	}

	// TODO: This is a hidden experiment which needs to remain enabled like this until 3.26 for pro compatibility.
	public static function get_experimental_data() {
		return [
			'name' => self::EXPERIMENT_NAME,
			'title' => esc_html__( 'Link In Bio', 'elementor' ),
			'hidden' => true,
			'default' => Manager::STATE_ACTIVE,
			'release_status' => Manager::RELEASE_STATUS_STABLE,
			'mutable' => false,
		];
	}

	public function __construct() {
		parent::__construct();

		add_action( 'elementor/frontend/after_register_styles', [ $this, 'register_styles' ] );
	}

	/**
	 * Register styles.
	 *
	 * At build time, Elementor compiles `/modules/link-in-bio/assets/scss/widgets/*.scss`
	 * to `/assets/css/widget-*.min.css`.
	 *
	 * @return void
	 */
	public function register_styles() {
		$widget_styles = self::get_widget_style_list();
		$has_custom_breakpoints = Plugin::$instance->breakpoints->has_custom_breakpoints();

		foreach ( $widget_styles as $widget_style_name => $has_widget_custom_breakpoints ) {
			$custom_breakpoints = $has_widget_custom_breakpoints ? $has_custom_breakpoints : false;

			wp_register_style(
				$widget_style_name,
				$this->get_css_assets_url( $widget_style_name, null, true, $custom_breakpoints ),
				[ 'elementor-frontend' ],
				$custom_breakpoints ? null : ELEMENTOR_PRO_VERSION
			);
		}
	}

	private function get_widget_style_list() {
		return [
			'widget-link-in-bio' => self::HAS_WIDGET_CUSTOM_BREAKPOINTS, // TODO: Remove in v3.27.0 [ED-15717]
			'widget-link-in-bio-base' => self::HAS_WIDGET_CUSTOM_BREAKPOINTS,
			'widget-link-in-bio-var-2' => self::HAS_WIDGET_NO_CUSTOM_BREAKPOINTS,
			'widget-link-in-bio-var-3' => self::HAS_WIDGET_NO_CUSTOM_BREAKPOINTS,
			'widget-link-in-bio-var-4' => self::HAS_WIDGET_NO_CUSTOM_BREAKPOINTS,
			'widget-link-in-bio-var-5' => self::HAS_WIDGET_NO_CUSTOM_BREAKPOINTS,
			'widget-link-in-bio-var-7' => self::HAS_WIDGET_NO_CUSTOM_BREAKPOINTS,
		];
	}
}
