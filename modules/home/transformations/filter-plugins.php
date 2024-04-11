<?php
namespace Elementor\Modules\Home\Transformations;

use Elementor\Modules\Home\Transformations\Base\Transformations_Abstract;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Filter_Plugins extends Transformations_Abstract {

	public function transform( array $home_screen_data ): array {
		$home_screen_data['add_ons']['repeater'] = $this->get_add_ons_installation_status( $home_screen_data['add_ons']['repeater'] );

		return $home_screen_data;
	}

	private function is_plugin( $add_on ): bool {
		return 'link' !== $add_on['type'];
	}

	private function remove_ampersand_from_url( $url ): string {
		return str_replace( '&amp;', '&', $url );
	}

	private function get_add_ons_installation_status( array $add_ons ): array {
		$transformed_add_ons = [];

		foreach ( $add_ons as $add_on ) {

			if ( $this->is_plugin( $add_on ) ) {
				$transformed_add_ons = $this->handle_plugin_add_on( $add_on, $transformed_add_ons );
			} else {
				$transformed_add_ons[] = $add_on;
			}
		}

		return $transformed_add_ons;
	}

	private function get_plugin_installation_status( $add_on ): string {
		$plugin_path = $add_on['file_path'];

		if ( ! $this->elementor_adapter->is_plugin_installed( $plugin_path ) ) {

			if ( 'wporg' === $add_on['type'] ) {
				return 'not-installed-wporg';
			}

			return 'not-installed-not-wporg';
		}

		if ( $this->elementor_adapter->is_plugin_activated( $plugin_path ) ) {
			return 'activated';
		}

		return 'installed-not-activated';
	}

	private function handle_plugin_add_on( $add_on, $transformed_add_ons ): array {
		$installation_status = $this->get_plugin_installation_status( $add_on );

		if ( 'activated' === $installation_status ) {
			return $transformed_add_ons;
		}

		switch ( $this->get_plugin_installation_status( $add_on ) ) {
			case 'not-installed-not-wporg':
				break;
			case 'not-installed-wporg':
				$installation_url = $this->elementor_adapter->get_install_plugin_url( $add_on['file_path'] );
				$add_on['url'] = $this->remove_ampersand_from_url( $installation_url );
				$add_on['target'] = '_self';
				break;
			case 'installed-not-activated':
				$activation_url = $this->elementor_adapter->get_activate_plugin_url( $add_on['file_path'] );
				$add_on['url'] = $this->remove_ampersand_from_url( $activation_url );
				$add_on['button_label'] = esc_html__( 'Activate', 'elementor' );
				$add_on['target'] = '_self';
				break;
		}

		$transformed_add_ons[] = $add_on;

		return $transformed_add_ons;
	}
}
