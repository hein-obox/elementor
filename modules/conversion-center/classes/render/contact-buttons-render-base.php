<?php

namespace Elementor\Modules\ConversionCenter\Classes\Render;

use Elementor\Icons_Manager;
use Elementor\Modules\ConversionCenter\Classes\Providers\Social_Network_Provider;
use Elementor\Modules\ConversionCenter\Widgets\Contact_Buttons;
use Elementor\Utils;

/**
 * Class Contact_Buttons_Render_Base.
 *
 * This is the base class that will hold shared functionality that will be needed by all the various widget versions.
 *
 * @since 3.23.0
 */
abstract class Contact_Buttons_Render_Base {

	protected Contact_Buttons $widget;

	protected array $settings;


	abstract public function render(): void;

	public function __construct( Contact_Buttons $widget ) {
		$this->widget   = $widget;
		$this->settings = $widget->get_settings_for_display();
	}

	protected function render_chat_button(): void {
		$platform = $this->settings['chat_button_platform'] ?? '';
		$display_dot = $this->settings['chat_button_show_dot'];
		$button_size = $this->settings['style_chat_button_size'];
		$hover_animation = $this->settings['style_button_color_hover_animation'];

		$button_classnames = 'e-contact-buttons__chat-button';

		if ( ! empty( $button_size ) ) {
			$button_classnames .= ' has-size-' . $button_size;
		}

		if ( ! empty( $hover_animation ) ) {
			$button_classnames .= ' elementor-animation-' . $hover_animation;
		}

		if ( 'yes' === $display_dot ) {
			$button_classnames .= ' has-dot';
		}

		$this->widget->add_render_attribute( 'button-', [
			'class' => $button_classnames,
		] );

		?>
		<div class="e-contact-buttons__chat-button-container">
			<button <?php echo $this->widget->get_render_attribute_string( 'button-' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> type="button" aria-label="Open">
				<?php
				$mapping = Social_Network_Provider::get_icon_mapping( $platform );
				$icon_lib = explode( ' ', $mapping )[0];
				$library = 'fab' === $icon_lib ? 'fa-brands' : 'fa-solid';
				Icons_Manager::render_icon(
					[
						'library' => $library,
						'value' => $mapping,
					],
					[ 'aria-hidden' => 'true' ]
				);
				?>
			</button>
		</div>
		<?php
	}

	protected function render_top_bar(): void {
		$profile_image_value = $this->settings['top_bar_image'] ?? [];
		$has_profile_image = ! empty( $profile_image_value ) && ( ! empty( $profile_image_value['url'] || ! empty( $profile_image_value['id'] ) ) );
		$profile_image_size = $this->settings['style_top_bar_image_size'];
		$display_profile_dot = $this->settings['top_bar_show_dot'];

		$profile_image_classnames = 'e-contact-buttons__profile-image';

		if ( ! empty( $profile_image_size ) ) {
			$profile_image_classnames .= ' has-size-' . $profile_image_size;
		}

		if ( 'yes' === $display_profile_dot ) {
			$profile_image_classnames .= ' has-dot';
		}

		$top_bar_name = $this->settings['top_bar_name'] ?? '';
		$top_bar_title = $this->settings['top_bar_title'] ?? '';

		$has_top_bar_name = ! empty( $top_bar_name );
		$has_top_bar_title = ! empty( $top_bar_title );

		$this->widget->add_render_attribute( 'profile-image', [
			'class' => $profile_image_classnames,
		] );
		?>
		<div class="e-contact-buttons__top-bar">
			<button type="button" class="e-contact-buttons__close-button" aria-label="Close Contact Buttons">
				<i class="eicon-close"></i>
			</button>
			<div <?php echo $this->widget->get_render_attribute_string( 'profile-image' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<?php if ( ! empty( $profile_image_value['id'] ) ) {
					echo wp_get_attachment_image( $profile_image_value['id'], 'medium', false, [
						'class' => 'e-contact-buttons__profile-image-el',
					] );
				} else {
					$this->widget->add_render_attribute( 'profile-image-src', [
						'alt'   => '',
						'class' => 'e-contact-buttons__profile-image-el',
						'src'   => esc_url( $profile_image_value['url'] ),
					] );
					?>
					<img <?php echo $this->widget->get_render_attribute_string( 'profile-image-src' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> />
				<?php }; ?>
			</div>

			<div class="e-contact-buttons__top-bar-details">
				<?php if ( $has_top_bar_name ) {
					$this->widget->add_render_attribute( 'top-bar-name', 'class', 'e-contact-buttons__top-bar-name' );
					$top_bar_name_output = sprintf( '<p %1$s>%2$s</p>', $this->widget->get_render_attribute_string( 'top-bar-name' ), $top_bar_name );
					Utils::print_unescaped_internal_string( $top_bar_name_output );
				} ?>
				<?php if ( $has_top_bar_title ) {
					$this->widget->add_render_attribute( 'top-bar-title', 'class', 'e-contact-buttons__top-bar-title' );
					$top_bar_title_output = sprintf( '<p %1$s>%2$s</p>', $this->widget->get_render_attribute_string( 'top-bar-title' ), $top_bar_title );
					Utils::print_unescaped_internal_string( $top_bar_title_output );
				} ?>
			</div>
		</div>
		<?php
	}

	protected function build_layout_render_attribute(): void {
		$layout_classnames = 'e-contact-buttons';
		$platform = $this->settings['chat_button_platform'] ?? '';
		$custom_classes = $this->settings['advanced_custom_css_classes'] ?? '';

		$icon_name_mapping = Social_Network_Provider::get_name_mapping( $platform );

		if ( ! empty( $platform ) ) {
			$layout_classnames .= ' has-platform-' . $icon_name_mapping;
		}

		if ( $custom_classes ) {
			$layout_classnames .= ' ' . $custom_classes;
		}

		$this->widget->add_render_attribute( 'layout', [
			'class' => $layout_classnames,
			'id'    => $this->settings['advanced_custom_css_id'],
		] );
	}
}
