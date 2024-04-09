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
			'description' => esc_html__( 'Create captivating tables.', 'elementor' ),
			'upgrade_url' => esc_url( 'https://go.elementor.com/go-pro-table-widget/' ),
			'upgrade_text' => esc_html__( 'Upgrade Now', 'elementor' ),
		];
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

		$repeater = new repeater();

		$repeater->add_control(
			'text',
			[
				'label' => esc_html__( 'Text', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
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
				'label' => esc_html__( 'Columns contents', 'elementor' ),
				'show_label' => esc_html__( true, 'elementor' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'list_title' => esc_html__( 'Content column 1', 'elementor' ),
						'list_content' => esc_html__( 'Item content. Click the edit button to change this text.', 'elementor' ),
					],
					[
						'list_title' => esc_html__( 'Content column 2', 'elementor' ),
						'list_content' => esc_html__( 'Item content. Click the edit button to change this text.', 'elementor' ),
					],
					[
						'list_title' => esc_html__( 'Content column 3', 'elementor' ),
						'list_content' => esc_html__( 'Item content. Click the edit button to change this text.', 'elementor' ),
					],
				]
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
			
			if ( $settings['list'] ) {
					$list = $settings['list'];
					echo '<tbody>';
					$count;
					if (count($list) % 3 == 0) {
						for ($i = 0; $i < count($list)-1; $i += 3) {
								$count=$i + 3;
								
									echo '<tr>';
										for ($j = $i; $j < $count; $j++) {
												echo '<td class="elementor-repeater-item-' . esc_attr( $list[$j]['_id'] ) . '">' . $list[$j]['text'] . '</td>';
											}
									echo '</tr>';
							}
						
					} else {
							if (count($list) % 3 == 1) {
								for ($i = 0; $i < count($list) - 2; $i += 3){
									$count=$i + 3;
									
										echo '<tr>';
										for ($j = $i; $j < $count; $j++){
											echo '<td class="elementor-repeater-item-' . esc_attr( $list[$j]['_id'] ) . '">' . $list[$j]['text'] . '</td>';
										}
										echo '</tr>';
								}
									echo '<tr>';
										echo '<td class="elementor-repeater-item-' . esc_attr( $list[$count]['_id'] ) . '">' . $list[$count]['text'] . '</td>';
									echo '</tr>';
									
							} else {
								for ($i = 0; $i < count($list) - 3; $i += 3){
									$count=$i + 3;
									
										echo '<tr>';
										for ($j = $i; $j < $count; $j++){
											echo '<td class="elementor-repeater-item-' . esc_attr( $list[$j]['_id'] ) . '">' . $list[$j]['text'] . '</td>';
										}
										echo '</tr>';
								}
									echo '<tr>';
										echo '<td class="elementor-repeater-item-' . esc_attr( $list[$count]['_id'] ) . '">' . $list[$count]['text'] . '</td>';
										echo '<td class="elementor-repeater-item-' . esc_attr( $list[$count + 1]['_id'] ) . '">' . $list[$count + 1]['text'] . '</td>';
									echo '</tr>';
							};
						};
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
						
						<# if ( settings.list.length ) { #>
							<# let list = settings.list #>
							<# let count; #>
							<tbody>
								<# if(list.length % 3 == 0){ #>

									<# for(let i = 0; i < list.length - 1; i+=3){ #>
										<# count = i + 3 #>
										<tr>
											<# for(let j = i; j < count; j++){ #>
												<td class="elementor-repeater-item-{{ list[j]._id }}">{{{list[j].text}}}</td>
											<# } #>	
										</tr>
									<# } #>	

									<# } else { #>

									<# if (list.length % 3 == 1){ #>
										<# for(let i = 0; i < list.length - 2; i+= 3){ #>
										<# count = i + 3 #>
										<tr>
											<# for(let j = i; j < count; j++){ #>
												<td class="elementor-repeater-item-{{ list[j]._id }}">{{{list[j].text}}}</td>
											<# } #>	
										</tr>
										<# } #>	
										<tr>
											<td class="elementor-repeater-item-{{ list[count]._id }}">{{{list[count].text}}}</td>
										</tr>		
										<# } else { #>
											<# for(let i = 0; i < list.length - 3; i+= 3){ #>
											<# count = i + 3 #>
											<tr>
												<# for(let j = i; j < count; j++){ #>
													<td class="elementor-repeater-item-{{ list[j]._id }}">{{{list[j].text}}}</td>
												<#}#>	
											</tr>
											<# } #>	
											<tr>
												<td class="elementor-repeater-item-{{ list[count]._id }}">{{{list[count].text}}}</td>
												<td class="elementor-repeater-item-{{ list[count + 1]._id }}">{{{list[count + 1].text}}}</td>
											</tr>	
										<# } #>
								<# } #>

								
							</tbody>
						<# } #>
					</table>
				<?php
			}

}
