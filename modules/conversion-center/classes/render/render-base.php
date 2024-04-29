<?php

namespace Elementor\Modules\ConversionCenter\Classes\Render;

use Elementor\Icons_Manager;
use Elementor\Modules\ConversionCenter\Base\Widget_Link_In_Bio_Base;
use Elementor\Modules\ConversionCenter\Classes\Providers\Social_Network_Provider;
use Elementor\Utils;

/**
 * Class Render_Base.
 *
 * This is the base class that will hold shared functionality that will be needed by all the various widget versions.
 *
 * @since 3.23.0
 */
abstract class Render_Base {

	protected Widget_Link_In_Bio_Base $widget;

	protected array $settings;

	abstract public function render(): void;

	public function __construct( Widget_Link_In_Bio_Base $widget ) {
		$this->widget = $widget;
		$this->settings = $widget->get_settings_for_display();
	}

	protected function render_ctas(): void {
		$ctas_props_corners = $this->settings['cta_links_corners'] ?? 'rounded';
		$ctas_props_show_border = $this->settings['cta_links_show_border'] ?? false;
		$ctas_props_type = $this->settings['cta_links_type'] ?? 'button';
		$ctas_value = $this->settings['cta_link'] ?? [];

		$has_ctas = ! empty( $ctas_value );
		if ( ! $has_ctas ) {
			return;
		}

		$this->widget->add_render_attribute( 'ctas', [
			'class' => 'e-link-in-bio__ctas has-type-' . $ctas_props_type,
		] );
		?>

		<div <?php echo $this->widget->get_render_attribute_string( 'ctas' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php
			foreach ( $ctas_value as $key => $cta ) {
				// Bail if no text
				if ( empty( $cta['cta_link_text'] ) ) {
					break;
				}

				$formatted_link = $this->get_formatted_link_based_on_type_for_cta( $cta );
				$cta_image = $cta['cta_link_image'] ?? [];
				$cta_has_image = ! empty( $cta_image ) &&
					( ! empty( $cta_image['url'] || ! empty( $cta_image['id'] ) ) ) &&
					'button' === $ctas_props_type;

				$ctas_classnames = 'e-link-in-bio__cta is-type-' . $ctas_props_type;

				if ( 'button' === $ctas_props_type && $ctas_props_show_border ) {
					$ctas_classnames .= ' has-border';
				}

				if ( $cta_has_image ) {
					$ctas_classnames .= ' has-image';
				}

				if ( 'button' === $ctas_props_type ) {
					$ctas_classnames .= ' has-corners-' . $ctas_props_corners;
				}

				$this->widget->add_render_attribute( 'cta-' . $key, [
					'class' => $ctas_classnames,
					'href' => esc_url( $formatted_link ),
				] );

				if ( 'File Download' === $cta['cta_link_type'] ) {
					$this->widget->add_render_attribute( 'cta-' . $key, [
						'download' => 'download',
					] );
				}
				?>
				<a <?php echo $this->widget->get_render_attribute_string( 'cta-' . $key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<?php if ( $cta_has_image ) : ?>
						<span class="e-link-in-bio__cta-image">
							<?php if ( ! empty( $cta_image['id'] ) ) {
								echo wp_get_attachment_image( $cta_image['id'], 'thumbnail', false, [
									'class' => 'e-link-in-bio__cta-image-el',
								] );
							} else {
								$this->widget->add_render_attribute( 'cta-link-image' . $key, [
									'alt' => '',
									'class' => 'e-link-in-bio__cta-image-el',
									'src' => esc_url( $cta_image['url'] ),
								] );
								?>
								<img <?php echo $this->widget->get_render_attribute_string( 'cta-link-image' . $key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> />
							<?php }; ?>
						</span>
					<?php endif; ?>
					<span class="e-link-in-bio__cta-text">
						<?php echo esc_html( $cta['cta_link_text'] ); ?>
					</span>
				</a>
			<?php } ?>
		</div>
		<?php
	}

	protected function render_icons(): void {
		$icons_props_size = $this->settings['icons_size'] ?? 'small';
		$icons_value = $this->settings['icon'] ?? [];

		$has_icons = ! empty( $icons_value );
		if ( ! $has_icons ) {
			return;
		}

		?>
		<div class="e-link-in-bio__icons">
			<?php
			foreach ( $icons_value as $key => $icon ) {

				$formatted_link = $this->get_formatted_link_for_icon( $icon );

				$this->widget->add_render_attribute( 'icon-' . $key, [
					'class' => 'e-link-in-bio__icon has-size-' . $icons_props_size,
				] );

				$this->widget->add_render_attribute( 'icon-link-' . $key, [
					'aria-label' => esc_attr( $icon['icon_platform'] ),
					'class' => 'e-link-in-bio__icon-link',
					'href' => esc_url( $formatted_link ),
					'rel' => 'noopener noreferrer',
					'target' => '_blank',
				] );

				?>
				<div <?php echo $this->widget->get_render_attribute_string( 'icon-' . $key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<a <?php echo $this->widget->get_render_attribute_string( 'icon-link-' . $key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<?php
						$mapping = Social_Network_Provider::get_icon_mapping( $icon['icon_platform'] );
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
					</a>
				</div>
			<?php } ?>
		</div>
		<?php
	}

	protected function render_bio(): void {
		$bio_heading_props_tag = $this->settings['bio_heading_tag'] ?? 'h2';
		$bio_heading_value = $this->settings['bio_heading'] ?? '';

		$bio_title_props_tag = $this->settings['bio_title_tag'] ?? 'h2';
		$bio_title_value = $this->settings['bio_title'] ?? '';

		$bio_description_value = $this->settings['bio_description'] ?? '';

		$has_bio_description = ! empty( $bio_description_value );
		$has_bio_heading = ! empty( $bio_heading_value );
		$has_bio_title = ! empty( $bio_title_value );

		if ( $has_bio_title || $has_bio_description || $has_bio_heading ) {
			?>
			<div class="e-link-in-bio__bio">
				<?php if ( $has_bio_heading ) {
					$this->widget->add_render_attribute( 'heading', 'class', 'e-link-in-bio__heading' );
					$bio_heading_output = sprintf( '<%1$s %2$s>%3$s</%1$s>', Utils::validate_html_tag( $bio_heading_props_tag ), $this->widget->get_render_attribute_string( 'heading' ), $bio_heading_value );
					Utils::print_unescaped_internal_string( $bio_heading_output );
				} ?>
				<?php if ( $has_bio_title ) {
					$this->widget->add_render_attribute( 'title', 'class', 'e-link-in-bio__title' );
					$bio_title_output = sprintf( '<%1$s %2$s>%3$s</%1$s>', Utils::validate_html_tag( $bio_title_props_tag ), $this->widget->get_render_attribute_string( 'title' ), $bio_title_value );
					Utils::print_unescaped_internal_string( $bio_title_output );
				} ?>
				<?php if ( $has_bio_description ) {
					$this->widget->add_render_attribute( 'description', 'class', 'e-link-in-bio__description' );
					$bio_description_output = sprintf( '<p %1$s>%2$s</p>', $this->widget->get_render_attribute_string( 'description' ), $bio_description_value );
					Utils::print_unescaped_internal_string( $bio_description_output );
				} ?>
			</div>
			<?php
		}
	}

	protected function render_identity_image(): void {
		$identity_image_props_shape = $this->settings['identity_image_shape'] ?? 'circle';
		$identity_image_props_style = $this->settings['identity_image_style'] ?? 'profile';
		$identity_image_props_show_border = $this->settings['identity_image_show_border'] ?? false;
		$identity_image_props_show_bottom_border = $this->settings['identity_image_show_bottom_border'] ?? false;
		$identity_image_value = $this->settings['identity_image'] ?? [];

		$has_identity_image = ! empty( $identity_image_value ) && ( ! empty( $identity_image_value['url'] || ! empty( $identity_image_value['id'] ) ) );

		if ( ! $has_identity_image ) {
			return;
		}

		$identity_image_classnames = 'e-link-in-bio__identity-image e-link-in-bio__identity-image-' . $identity_image_props_style;
		if ( $identity_image_props_show_border || $identity_image_props_show_bottom_border ) {
			$identity_image_classnames .= ' has-border';
		}
		if ( $identity_image_props_show_border || $identity_image_props_show_bottom_border ) {
			$identity_image_classnames .= ' has-border';
		}
		if ( ! empty( $identity_image_props_shape ) && 'profile' === $identity_image_props_style ) {
			$identity_image_classnames .= ' has-style-' . $identity_image_props_shape;
		}
		$this->widget->add_render_attribute( 'identity_image', [
			'class' => $identity_image_classnames,
		] );
		?>
		<div class="e-link-in-bio__identity">
			<div <?php echo $this->widget->get_render_attribute_string( 'identity_image' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<?php if ( ! empty( $identity_image_value['id'] ) ) {
					echo wp_get_attachment_image( $identity_image_value['id'], 'medium', false, [
						'class' => 'e-link-in-bio__identity-image-el',
					] );
				} else {
					$this->widget->add_render_attribute( 'identity_image_src', [
						'alt' => '',
						'class' => 'e-link-in-bio__identity-image-el',
						'src' => esc_url( $identity_image_value['url'] ),
					] );
					?>
					<img <?php echo $this->widget->get_render_attribute_string( 'identity_image_src' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> />
				<?php }; ?>
			</div>
		</div>
		<?php
	}

	protected function get_formatted_link_based_on_type_for_cta( array $cta ): string {

		$formatted_link = $cta['cta_link_url']['url'] ?? '';

		// Ensure we clear the default link value if the matching type value is empty
		switch ( $cta['cta_link_type'] ) {
			case Social_Network_Provider::EMAIL:
				$formatted_link = $this->build_email_link( $cta, 'cta_link' );
				break;
			case Social_Network_Provider::TELEPHONE:
				$formatted_link = ! empty( $cta['cta_link_number'] ) ? 'tel:' . $cta['cta_link_number'] : '';
				break;
			case Social_Network_Provider::MESSENGER:
				$formatted_link = ! empty( $icon['cta_link_username'] ) ?
					'https://www.facebook.com/messages/t/' . $icon['icon_username'] :
					'';
				break;
			case Social_Network_Provider::WAZE:
				$formatted_link = ! empty( $cta['cta_link_location'] ) ? 'https://www.waze.com/ul?ll=' . $cta['cta_link_location'] . '&navigate=yes' : '';
				break;
			case Social_Network_Provider::WHATSAPP:
				$formatted_link = ! empty( $cta['cta_link_number'] ) ? 'https://wa.me/' . $cta['cta_link_number'] : '';
				break;
			case Social_Network_Provider::FILE_DOWNLOAD:
				$formatted_link = ! empty( $cta['cta_link_file']['url'] ) ? $cta['cta_link_file']['url'] : '';
				break;
			default:
				break;
		}

		return $formatted_link;
	}

	protected function build_email_link( array $data, string $prefix ) {
		$email = $data[ $prefix . '_mail' ] ?? '';
		$subject = $data[ $prefix . '_mail_subject' ] ?? '';
		$body = $data[ $prefix . '_mail_body' ] ?? '';
		if ( ! $email ) {
			return '';
		}
		$link = 'mailto:' . $email;
		if ( $subject ) {
			$link .= '?subject=' . $subject;
		}
		if ( $body ) {
			$link .= $subject ? '&' : '?';
			$link .= 'body=' . $body;
		}

		return $link;

	}

	protected function get_formatted_link_for_icon( array $icon ): string {

		$formatted_link = $icon['icon_url']['url'] ?? '';

		// Ensure we clear the default link value if the matching type value is empty
		switch ( $icon['icon_platform'] ) {
			case Social_Network_Provider::EMAIL:
				$formatted_link = $this->build_email_link( $icon, 'icon' );
				break;
			case Social_Network_Provider::TELEPHONE:
				$formatted_link = ! empty( $icon['icon_number'] ) ? 'tel:' . $icon['icon_number'] : '';
				break;
			case Social_Network_Provider::MESSENGER:
				$formatted_link = ! empty( $icon['icon_username'] ) ?
					'https://www.facebook.com/messages/t/' . $icon['icon_username'] :
					'';
				break;
			case Social_Network_Provider::WAZE:
				$formatted_link = ! empty( $icon['icon_location'] ) ? 'https://www.waze.com/ul?ll=' . $icon['icon_location'] . '&navigate=yes' : '';
				break;
			case Social_Network_Provider::WHATSAPP:
				$formatted_link = ! empty( $icon['icon_number'] ) ? 'https://wa.me/' . $icon['icon_number'] : '';
				break;
			default:
				break;
		}

		return $formatted_link;
	}

	protected function build_layout_render_attribute(): void {
		$layout_props_full_height = $this->settings['advanced_layout_full_screen_height'] ?? '';
		$layout_props_full_height_controls = $this->settings['advanced_layout_full_screen_height_controls'] ?? '';
		$layout_props_full_width = $this->settings['advanced_layout_full_width_custom'] ?? '';
		$layout_props_show_border = $this->settings['background_show_border'] ?? '';
		$custom_classes = $this->settings['advanced_custom_css_classes'] ?? '';

		$layout_classnames = 'e-link-in-bio e-' . $this->widget->get_name();

		if ( 'yes' === $layout_props_show_border ) {
			$layout_classnames .= ' has-border';
		}

		if ( 'yes' === $layout_props_full_height ) {
			$layout_classnames .= ' is-full-height';
		}

		if ( 'yes' === $layout_props_full_width ) {
			$layout_classnames .= ' is-full-width';
		}

		if ( ! empty( $layout_props_full_height_controls ) ) {
			foreach ( $layout_props_full_height_controls as $breakpoint ) {
				$layout_classnames .= ' is-full-height-' . $breakpoint;
			}
		}

		if ( $custom_classes ) {
			$layout_classnames .= ' ' . $custom_classes;
		}

		$this->widget->add_render_attribute( 'layout', [
			'class' => $layout_classnames,
			'id' => $this->settings['advanced_custom_css_id'],
		] );
	}
}
