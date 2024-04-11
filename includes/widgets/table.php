<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

/**
 * Elementor table widget.
 *
 *
 * @since 1.0.0
 */
class Widget_Table extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve table widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'table';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve table widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Table', 'elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve table widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-table';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the table widget belongs to.
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
		return [ 'table'];
	}

	/**
	 * Register table widget controls.
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
				'label' => esc_html__( 'Table content', 'elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_title',
			[
				'label' => esc_html__( 'Show Columns Titles', 'elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'elementor' ),
				'label_off' => esc_html__( 'Hide', 'elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'column_one',
			[
				'label' => esc_html__( 'Title column 1', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => esc_html__( 'Enter column title', 'elementor' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'column_two',
			[
				'label' => esc_html__( 'Title column 2', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => esc_html__( 'Enter column title', 'elementor' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);
		$this->add_control(
			'column_three',
			[
				'label' => esc_html__( 'Title column 3', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => esc_html__( 'Enter column title', 'elementor' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		
		$this->add_control(
			'rows',
			[
				'label' => esc_html__( "Content rows", 'elementor' ),
				'show_label' => true,
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => [
					[
						'name' => 'column_content_one',
						'label' => esc_html__( 'Content column 1', 'elementor' ),
						'type' => \Elementor\Controls_Manager::TEXT,
						'label_block' => false,
					],
					[
						'name' => 'column_content_two',
						'label' => esc_html__( 'column content 2', 'elementor' ),
						'type' => \Elementor\Controls_Manager::TEXT,
						'label_block' => false,
					],
					[
						'name' => 'column_content_three',
						'label' => esc_html__( 'column content 3', 'elementor' ),
						'type' => \Elementor\Controls_Manager::TEXT,
						'label_block' => false,
					],
				],
				'title_field' => esc_html__( 'row', 'elementor'),
				
			]
			);

		$this->end_controls_section();
	}

	/**
	 * Render table widget output on the frontend.
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
					echo '<thead>';
						echo '<tr>';
							echo '<th>' . $settings['column_one'] . '</th>';
							echo '<th>' . $settings['column_two'] . '</th>';
							echo '<th>' . $settings['column_three'] . '</th>';
						echo '</tr>';
					echo '</thead>';
				}
			
			if ( $settings['rows'] ) {
					echo '<tbody>';
					$rows = $settings['rows'];
					for ($i=0; $i <= count($rows)-1; $i++) { 
						echo '<tr>';
								echo '<td class="elementor-repeater-item-' . esc_attr( $rows[$i]['_id'] ) . '">' .$rows[$i]['column_content_one'] . '</td>';
								echo '<td class="elementor-repeater-item-' . esc_attr( $rows[$i]['_id'] ) . '">' .$rows[$i]['column_content_two'] . '</td>';
								echo '<td class="elementor-repeater-item-' . esc_attr( $rows[$i]['_id'] ) . '">' .$rows[$i]['column_content_three'] . '</td>';
						echo '<tr>';
					}
					
					echo '</tbody>';
				};
				echo '</table>';
		}
	

			protected function content_template() {
				?>
				
					<table>
							<# if ( 'yes' === settings.show_title ) { #>
							<thead>
								<tr>
									<th>{{{ settings.column_one }}}</th>
									<th>{{{ settings.column_two }}}</th>
									<th>{{{ settings.column_three }}}</th>
								</tr>
							</thead>
						<# } #>
						
						<# if ( settings.rows.length ) { #>
							<tbody>
								<# let rows = settings.rows #>
								<# for( let i = 0; i <= rows.length - 1; i++ ){ #>
									<tr>
										<td class="elementor-repeater-item-{{ rows[i]._id }}">{{{ rows[i].column_content_one }}}</td>
										<td class="elementor-repeater-item-{{ rows[i]._id }}">{{{ rows[i].column_content_two }}}</td>
										<td class="elementor-repeater-item-{{ rows[i]._id }}">{{{ rows[i].column_content_three }}}</td>
									</tr>
								<# } #>
							</tbody>
						<# } #>
					</table>
				<?php
			}

}
