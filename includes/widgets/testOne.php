<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

/**
 * Elementor testOne widget.
 *
 * Elementor widget that displays an eye-catching headlines.
 *
 * @since 1.0.0
 */
class Widget_testOne extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve testOne widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'testOne';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve testOne widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'testOne', 'elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve testOne widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-code';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the testOne widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'basic' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'testOne', 'title', 'text' ];
	}

	/**
	 * Get widget upsale data.
	 *
	 * Retrieve the widget promotion data.
	 *
	 * @since 3.18.0
	 * @access protected
	 *
	 * @return array Widget promotion data.
	 */
	protected function get_upsale_data() {
		return [
			'condition' => ! Utils::has_pro(),
			'image' => esc_url( ELEMENTOR_ASSETS_URL . 'images/go-pro.svg' ),
			'image_alt' => esc_attr__( 'Upgrade', 'elementor' ),
			'description' => esc_html__( 'Create captivating testOnes that rotate with the Animated Headline Widget.', 'elementor' ),
			'upgrade_url' => esc_url( 'https://go.elementor.com/go-pro-testOne-widget/' ),
			'upgrade_text' => esc_html__( 'Upgrade Now', 'elementor' ),
		];
	}

	/**
	 * Register testOne widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 3.1.0
	 * @access protected
	 */
	protected function register_controls() {
		
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'table content', 'textdomain' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_title',
			[
				'label' => esc_html__( 'Show Columns Titles', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'textdomain' ),
				'label_off' => esc_html__( 'Hide', 'textdomain' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'columnOne',
			[
				'label' => esc_html__( 'Title column 1', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => esc_html__( 'Enter column title', 'elementor' ),
				'default' => esc_html__( 'Column title', 'elementor' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'columnTwo',
			[
				'label' => esc_html__( 'Title column 2', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => esc_html__( 'Enter column title', 'elementor' ),
				'default' => esc_html__( 'Column title', 'elementor' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);
		$this->add_control(
			'columnThree',
			[
				'label' => esc_html__( 'Title column 3', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => esc_html__( 'enter column title', 'elementor' ),
				'default' => esc_html__( 'Column title', 'elementor' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater = new repeater();

		$repeater->add_control(
			'text',
			[
				'label' => esc_html__( 'Text', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => esc_html__( 'List Item', 'elementor' ),
				'default' => esc_html__( 'List Item', 'elementor' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'list',
			[
				'label' => esc_html__( 'Columns contents', 'textdomain' ),
				'show_label' => esc_html__( true, 'textdomain' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'list_title' => esc_html__( 'Content column 1', 'textdomain' ),
						'list_content' => esc_html__( 'Item content. Click the edit button to change this text.', 'textdomain' ),
					],
					[
						'list_title' => esc_html__( 'Content column 2', 'textdomain' ),
						'list_content' => esc_html__( 'Item content. Click the edit button to change this text.', 'textdomain' ),
					],
					[
						'list_title' => esc_html__( 'Content column 3', 'textdomain' ),
						'list_content' => esc_html__( 'Item content. Click the edit button to change this text.', 'textdomain' ),
					],
				],
				'title_field' => '{{{ list_title }}}',
			]
			);

		

		$this->end_controls_section();
	}

	/**
	 * Render testOne widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	
	protected function render() {
		$settings = $this->get_settings_for_display();

		echo '<table>';
		if ( 'yes' === $settings['show_title'] ) {
		
				echo '<tr>';
					echo '<th>' . $settings['columnOne'] . '</th>';
					echo '<th>' . $settings['columnTwo'] . '</th>';
					echo '<th>' . $settings['columnThree'] . '</th>';
				echo '</tr>';
			}
			
			if ( $settings['list'] ) {
				echo '<tr>';
				foreach (  $settings['list'] as $item ) {
					echo '<td class="elementor-repeater-item-' . esc_attr( $item['_id'] ) . '">' . $item['list_title'] . '</td>';
					// echo '<dd>' . $item['list_content'] . '</dd>';
				}
				echo '</tr>';
			}
			echo '</table>';
	}

	protected function content_template() {
		?>
		
			<table>
				<# if ( 'yes' === settings.show_title ) { #>
					<tr>
						<th>{{{ settings.columnOne }}}</th>
						<th>{{{ settings.columnTwo }}}</th>
						<th>{{{ settings.columnThree }}}</th>
					</tr>
				
				<# } #>
				
				<# if ( settings.list.length ) { #>
					<tr>
						<# _.each( settings.list, function( item ) { #>
							<td class="elementor-repeater-item-{{ item._id }}">{{{ item.list_title }}}</td>
							<!-- <dd>{{{ item.list_content }}}</dd> -->
						<# }); #>
					</tr>
				<# } #>
			</table>
		<?php
	}

	
}
