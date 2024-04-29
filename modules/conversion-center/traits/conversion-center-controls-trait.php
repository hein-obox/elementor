<?php

namespace Elementor\Modules\ConversionCenter\Traits;

use Elementor\Controls_Manager;

trait Conversion_Center_Controls_Trait {

	protected $border_width_range = [
		'min' => 0,
		'max' => 10,
		'step' => 1,
	];

	protected function add_html_tag_control( string $name ): void {
		$this->add_control(
			$name,
			[
				'label' => esc_html__( 'HTML Tag', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h2',
			]
		);
	}

	protected function add_borders_control(
		string $prefix,
		array $show_border_args = [],
		array $border_width_args = [],
		array $border_color_args = []
	): void {
		$show_border = [
			'label' => esc_html__( 'Border', 'elementor' ),
			'type' => Controls_Manager::SWITCHER,
			'label_on' => esc_html__( 'Yes', 'elementor' ),
			'label_off' => esc_html__( 'No', 'elementor' ),
			'return_value' => 'yes',
			'default' => '',
		];

		$this->add_control(
			$prefix . '_show_border',
			array_merge_recursive( $show_border, $show_border_args )
		);

		$border_width = [
			'label' => esc_html__( 'Border Width', 'elementor' ) . ' (px)',
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range' => [
				'px' => $this->border_width_range,
			],
			'condition' => [
				$prefix . '_show_border' => 'yes',
			],
			'selectors' => [
				'{{WRAPPER}} .e-link-in-bio' => '--e-link-in-bio-ctas-border-width: {{SIZE}}{{UNIT}}',
			],
		];

		$this->add_control(
			$prefix . '_border_width',
			array_merge_recursive( $border_width, $border_width_args ),
		);

		$border_color = [
			'label' => esc_html__( 'Border Color', 'elementor' ),
			'type' => Controls_Manager::COLOR,
			'condition' => [
				$prefix . '_show_border' => 'yes',
			],
			'selectors' => [
				'{{WRAPPER}} .e-link-in-bio' => '--e-link-in-bio-ctas-border-color: {{VALUE}}',
			],
		];

		$this->add_control(
			$prefix . '_border_color',
			array_merge_recursive( $border_color, $border_color_args )
		);
	}
}
