<?php
/**
 * Plugin Name: RPG-随机密码生成器
 * Plugin URI: https://www.baad.in/rpg
 * Description: 一个可自定义的随机密码生成器插件,支持中英文。
 * Version: 1.0
 * Author: Baadin
 * Author URI: https://www.baad.in
 * Text Domain: rpg-random-password-generator
 * Domain Path: /languages
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

// 加载翻译文件
function rpg_load_textdomain() {
    load_plugin_textdomain('rpg-random-password-generator', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'rpg_load_textdomain');

// 添加设置页面
function rpg_add_settings_page() {
    add_options_page(
        __('RPG Random Password Generator Settings', 'rpg-random-password-generator'),
        __('RPG Password Generator', 'rpg-random-password-generator'),
        'manage_options',
        'rpg-settings',
        'rpg_settings_page'
    );
}
add_action('admin_menu', 'rpg_add_settings_page');

// 设置页面内容
function rpg_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('rpg_options');
            do_settings_sections('rpg-settings');
            submit_button(__('Save Settings', 'rpg-random-password-generator'));
            ?>
        </form>
    </div>
    <?php
}

// 注册设置
function rpg_register_settings() {
    register_setting('rpg_options', 'rpg_min_length', 'intval');
    register_setting('rpg_options', 'rpg_max_length', 'intval');

    add_settings_section(
        'rpg_main_section',
        __('Main Settings', 'rpg-random-password-generator'),
        'rpg_main_section_cb',
        'rpg-settings'
    );

    add_settings_field(
        'rpg_min_length',
        __('Minimum Password Length', 'rpg-random-password-generator'),
        'rpg_min_length_cb',
        'rpg-settings',
        'rpg_main_section'
    );

    add_settings_field(
        'rpg_max_length',
        __('Maximum Password Length', 'rpg-random-password-generator'),
        'rpg_max_length_cb',
        'rpg-settings',
        'rpg_main_section'
    );
}
add_action('admin_init', 'rpg_register_settings');

// 设置部分回调函数
function rpg_main_section_cb() {
    echo '<p>' . __('Configure the password generator settings.', 'rpg-random-password-generator') . '</p>';
}

function rpg_min_length_cb() {
    $min_length = get_option('rpg_min_length', 8);
    echo "<input type='number' name='rpg_min_length' value='$min_length' min='8' max='64'>";
}

function rpg_max_length_cb() {
    $max_length = get_option('rpg_max_length', 64);
    echo "<input type='number' name='rpg_max_length' value='$max_length' min='8' max='64'>";
}

// 添加短代码
function rpg_shortcode() {
    wp_enqueue_style('rpg-styles', plugins_url('css/rpg-styles.css', __FILE__));
    wp_enqueue_script('rpg-script', plugins_url('js/rpg-script.js', __FILE__), array('jquery'), '1.0', true);

    $min_length = get_option('rpg_min_length', 8);
    $max_length = get_option('rpg_max_length', 64);

    ob_start();
    ?>
    <div id="rpg-password-generator" class="rpg-container">
        <h2><?php _e('随机密码生成器', 'rpg-random-password-generator'); ?></h2>
        <div class="rpg-options">
            <label for="rpg-length"><?php _e('密码长度:', 'rpg-random-password-generator'); ?></label>
            <input type="number" id="rpg-length" min="<?php echo $min_length; ?>" max="<?php echo $max_length; ?>" value="12">
        </div>
        <div class="rpg-options">
            <label><input type="checkbox" id="rpg-lowercase" checked> <?php _e('小写字母', 'rpg-random-password-generator'); ?></label>
            <label><input type="checkbox" id="rpg-uppercase" checked> <?php _e('大写字母', 'rpg-random-password-generator'); ?></label>
            <label><input type="checkbox" id="rpg-numbers" checked> <?php _e('数字', 'rpg-random-password-generator'); ?></label>
            <label><input type="checkbox" id="rpg-symbols"> <?php _e('特殊字符', 'rpg-random-password-generator'); ?></label>
        </div>
        <button id="rpg-generate"><?php _e('生成密码', 'rpg-random-password-generator'); ?></button>
        <div class="rpg-result">
            <input type="text" id="rpg-password" readonly>
            <button id="rpg-copy"><?php _e('一键复制', 'rpg-random-password-generator'); ?></button>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('rpg_password_generator', 'rpg_shortcode');

// 添加CSS和JS文件
function rpg_enqueue_scripts() {
    wp_enqueue_style('rpg-styles', plugins_url('css/rpg-styles.css', __FILE__));
    wp_enqueue_script('rpg-script', plugins_url('js/rpg-script.js', __FILE__), array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'rpg_enqueue_scripts');