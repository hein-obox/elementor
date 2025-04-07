<?php

namespace Elementor\Modules\AtomicWidgets\PropsResolver\Transformers\Styles;

use Elementor\Modules\AtomicWidgets\PropsResolver\Props_Resolver_Context;
use Elementor\Modules\AtomicWidgets\PropsResolver\Transformer_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Transform_Transformer extends Transformer_Base {

	public function transform( $value, Props_Resolver_Context $context ): string {
		$transform_styles = [];

		if ( ! empty( $value['translate-x'] ) ) {
			$transform_styles[] = 'translateX(' . $value['translate-x'] . ')';
		}

		if ( ! empty( $value['translate-y'] ) ) {
			$transform_styles[] = 'translateY(' . $value['translate-y'] . ')';
		}

		if ( empty( $transform_styles ) ) {
			return '';
		}

		return implode( ' ', $transform_styles );
	}
}
