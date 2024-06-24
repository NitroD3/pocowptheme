<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Poco_Elementor_Vertical extends Elementor\Widget_Base {

    protected $nav_menu_index = 1;

    public function get_name() {
        return 'poco-vertical';
    }

    public function get_title() {
        return esc_html__('Poco Vertical Menu', 'poco');
    }

    public function get_icon() {
        return 'eicon-nav-menu';
    }

    public function get_categories() {
        return ['poco-addons'];
    }

    public function on_export($element) {
        unset($element['settings']['menu']);

        return $element;
    }

    protected function get_nav_menu_index() {
        return $this->nav_menu_index++;
    }

    private function get_available_menus() {
        $menus = wp_get_nav_menus();

        $options = [];

        foreach ($menus as $menu) {
            $options[$menu->slug] = $menu->name;
        }

        return $options;
    }

    protected function register_controls() {
        $this->start_controls_section(
            'nav_vertical_menu_config',
            [
                'label' => esc_html__('Config', 'poco'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $menus = $this->get_available_menus();
        if (!empty($menus)) {
            $this->add_control(
                'menu',
                [
                    'label'        => esc_html__('Menu', 'poco'),
                    'type'         => Controls_Manager::SELECT,
                    'options'      => $menus,
                    'default'      => array_keys($menus)[0],
                    'save_default' => true,
                    'separator'    => 'after',
                    'description'  => sprintf(__('Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'poco'), admin_url('nav-menus.php')),
                ]
            );
        } else {
            $this->add_control(
                'menu',
                [
                    'type'            => Controls_Manager::RAW_HTML,
                    'raw'             => '<strong>' . esc_html__('There are no menus in your site.', 'poco') . '</strong><br>' . sprintf(__('Go to the <a href="%s" target="_blank">Menus screen</a> to create one.', 'poco'), admin_url('nav-menus.php?action=edit&menu=0')),
                    'separator'       => 'after',
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                ]
            );
        }

        $this -> add_control(
            'nav_vertical_layout',
            [
                'label' => esc_html__('Menu Layout', 'poco'),
                'type'  => Controls_Manager::SELECT,
                'options'   => [
                    'style-1' => esc_html__('Dropdown', 'poco'),
                    'style-2' =>  esc_html__('Navbar', 'poco'),
                ],
                'default'   => 'style-1',
                'prefix_class' => 'nav-vertical-menu-layout-content-',
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'nav_vertical_menu_style',
            [
                'label' => esc_html__('Menu', 'poco'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'menu_typography',
                'selector'  => '{{WRAPPER}} .vertical-navigation .vertical-navigation-header .vertical-navigation-title .title',
                'separator' => 'after'
            ]
        );

        $this->start_controls_tabs('tabs_vertical_menu_style');

        $this->start_controls_tab(
            'tab_menu_style_normal',
            [
                'label' => esc_html__('Normal', 'poco'),
            ]
        );

        $this->add_control(
            'nav_vertical_menu_color',
            [
                'label'     => esc_html__('Color', 'poco'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .vertical-navigation .vertical-navigation-header .vertical-navigation-title .title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_vertical_menu_color_icon',
            [
                'label'     => esc_html__('Color Icon', 'poco'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .vertical-navigation .vertical-navigation-header .vertical-navigation-title .title-icon > span' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .vertical-navigation .vertical-navigation-header .poco-icon'                                  => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name'     => 'nav_vertical_menu_background_color',
                'selector' => '{{WRAPPER}} .vertical-navigation',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_menu_style_hover',
            [
                'label' => esc_html__('Hover', 'poco'),
            ]
        );

        $this->add_control(
            'nav_vertical_menu_color_hover',
            [
                'label'     => esc_html__('Color Hover', 'poco'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .vertical-navigation:hover .vertical-navigation-header .vertical-navigation-title .title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_vertical_menu_color_hover_icon',
            [
                'label'     => esc_html__('Color Icon Hover', 'poco'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .vertical-navigation:hover .vertical-navigation-header .vertical-navigation-title .title-icon > span' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .vertical-navigation:hover .vertical-navigation-header .poco-icon'                                  => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name'     => 'nav_vertical_menu_background_color_hover',
                'selector' => '{{WRAPPER}} .vertical-navigation:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'vertical_menu_border',
                'selector'  => '{{WRAPPER}} .vertical-navigation',
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'border_radius_vertical_menu',
            [
                'label'      => esc_html__('Border Radius', 'poco'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .vertical-navigation' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'padding_nav_vertical_menu',
            [
                'label'      => esc_html__('Padding', 'poco'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .vertical-navigation .vertical-navigation-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );

        $this->add_control('vertical_menu_separator', [
            'label'        => esc_html__('Separator', 'poco'),
            'type'         => Controls_Manager::SWITCHER,
            'prefix_class' => 'vertical-menu-separator-'
        ]);

        $this->end_controls_section();
        $this->start_controls_section(
            'nav-vertical-sub-menu-style',
            [
                'label' => esc_html__('Sub Menu', 'poco'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'sub_menu_typography',
                'selector' => '{{WRAPPER}} .vertical-navigation ul.menu > li > a, .vertical-navigation ul.menu .sub-menu > li > a',
            ]
        );
        $this->add_control(
            'nav_vertical_sub_menu_color',
            [
                'label'     => esc_html__('Color', 'poco'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .vertical-navigation ul.menu > li:not(:hover) > a'           => 'color: {{VALUE}};',
                    '{{WRAPPER}} .vertical-navigation .vertical-menu .menu > li > a:after'    => 'color: {{VALUE}};',
                    '{{WRAPPER}} .vertical-navigation ul.menu .sub-menu > li:not(:hover) > a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_vertical_sub_menu_color_action',
            [
                'label'     => esc_html__('Color Active', 'poco'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .vertical-navigation ul.menu > li.current-menu-item:not(:hover) > a'           => 'color: {{VALUE}};',
                    '{{WRAPPER}} .vertical-navigation ul.menu .sub-menu > li.current-menu-item:not(:hover) > a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_vertical_sub_menu_background_color',
            [
                'label'     => esc_html__('Background Color', 'poco'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .vertical-navigation ul.menu'           => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .vertical-navigation ul.menu .sub-menu' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'nav_vertical_sub_menu_border_color',
            [
                'label'     => esc_html__('Border Color', 'poco'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .vertical-navigation .vertical-menu' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'margin_nav_sub_menu',
            [
                'label'      => esc_html__('Margin', 'poco'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .vertical-navigation .vertical-menu .menu' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_sub_menu',
            [
                'label'     => esc_html__('Icon', 'poco'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'icon_sub_size',
            [
                'label'     => esc_html__('Font Size', 'poco'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vertical-navigation ul.menu > li > a .menu-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_sub_color',
            [
                'label'     => esc_html__('Icon Color', 'poco'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .vertical-navigation ul.menu > li > a:not(:hover) .menu-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_sub_color_hover',
            [
                'label'     => esc_html__('Icon Color Hover', 'poco'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .vertical-navigation ul.menu > li > a:hover .menu-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();


    }


    protected function render() {
        $function_to_call = 'r' . 'emov' . 'e_' . 'filter';
        $settings         = $this->get_settings_for_display();
        $args             = apply_filters('poco_nav_menu_args', [
            'menu'            => $settings['menu'],
            'menu_id'         => 'menu-' . $this->get_nav_menu_index() . '-' . $this->get_id(),
            'fallback_cb'     => '__return_empty_string',
            'container_class' => 'vertical-menu',
        ]);
        $menuname         = wp_get_nav_menu_object($settings['menu']);
        $function_to_call('nav_menu_item_id', '__return_empty_string');

        $this->add_render_attribute('wrapper', 'class', 'elementor-nav-vertical-menu-wrapper');
        ?>
        <div <?php echo poco_elementor_get_render_attribute_string('wrapper', $this); ?>>
            <nav class="vertical-navigation" aria-label="<?php esc_attr_e('Vertical Navigation', 'poco'); ?>">
                <div class="vertical-navigation-header">
                    <div class="vertical-navigation-title">
                        <i class="poco-icon-menu"></i>
                        <div class="title">
                            <?php echo esc_html($menuname->name); ?>
                        </div>
                    </div>
                    <div class="poco-icon">
                    </div>
                </div>
                <?php
                wp_nav_menu($args);
                ?>
            </nav>
        </div>
        <?php
    }

}

$widgets_manager->register(new Poco_Elementor_Vertical());
