<?php
namespace Elementor\Modules\EyalDemo\Widgets;

use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class EyalDemo extends Widget_Base {

	public function get_name() {
		return 'eyal-demo';
	}

	public function get_title() {
		return esc_html__( 'Eyal Demo', 'elementor' );
	}

	public function get_icon() {
		return 'eicon-tabs';
	}

	public function get_keywords() {
		return [ 'eyal', 'mrejen', 'tdd', 'design patterns', 'wix', 'elementor', 'r&d', 'vp' ];
	}

	public function get_style_depends(): array {
		return [ 'widget-eyal-demo' ];
	}

	public function show_in_panel(): bool {
		return true;
	}

	protected function register_controls() {
		$this->start_controls_section( 'section_content', [
			'label' => esc_html__( 'Content', 'elementor' ),
		] );

		$this->add_control( 'title', [
			'label' => esc_html__( 'Title', 'elementor' ),
			'type' => Controls_Manager::TEXT,
			'default' => esc_html__( 'Title', 'elementor' ),
			'placeholder' => esc_html__( 'Title', 'elementor' ),
			'label_block' => true,
			'dynamic' => [
				'active' => true,
			],
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'section__style', [
			'label' => esc_html__( 'Style', 'elementor' ),
			'tab' => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'color', [
			'label' => esc_html__( 'Title Color', 'elementor' ),
			'type' => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}}' => '--eyal-color: {{VALUE}}',
			],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display(); ?>
        	<h1><?php echo $settings['title']; ?></h1>
		<?php
	}

	protected function content_template() {
		?>
        	<h1>{{ settings.title }}</h1>
		<?php
	}
}
