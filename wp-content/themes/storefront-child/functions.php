<?php

function enqueue_custom_styles_scripts() {
    // Styles and Scripts for components
    wp_enqueue_style('components-style', get_stylesheet_directory_uri() . '/assets/css/style.css');
    wp_enqueue_script('components-script', get_stylesheet_directory_uri() . '/assets/js/components-script.js', array('jquery'), null, true);

    // Добавляем локализованные данные для AJAX и nonce
    wp_localize_script('components-script', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('advanced_search_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_custom_styles_scripts');

function save_email_to_database() {
    global $wpdb;

    // Проверка nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'advanced_search_nonce')) {
        error_log('Nonce verification failed.');
        wp_send_json_error('Security check failed');
    }

    // Проверка безопасности запроса
    if (!isset($_POST['email'])) {
        error_log('Email address is missing.');
        wp_send_json_error('Email address is required');
    }

    $email = sanitize_email($_POST['email']);

    if (!is_email($email)) {
        error_log('Invalid email address: ' . $email);
        wp_send_json_error('Invalid email address');
    }

    // Проверка на дублирование email
    $table_name = $wpdb->prefix . 'subscribed_emails'; // Имя таблицы
    $email_exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE email = %s", $email));

    if ($email_exists > 0) {
        error_log('Duplicate email address: ' . $email);
        wp_send_json_error('This email is already subscribed.');
    }

    // Сохранение email в базу данных
    $result = $wpdb->insert($table_name, array('email' => $email));

    if ($result === false) {
        error_log('Failed to insert email into database: ' . $wpdb->last_error);
        wp_send_json_error('Failed to save email');
    }

    // Отправка успешного ответа
    wp_send_json_success('Email saved successfully');
}
add_action('wp_ajax_save_email', 'save_email_to_database'); // Для авторизованных пользователей
add_action('wp_ajax_nopriv_save_email', 'save_email_to_database'); // Для неавторизованных пользователей

// Функция для создания таблицы в базе данных, если её нет
function create_subscribed_emails_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'subscribed_emails';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        email varchar(255) NOT NULL,
        PRIMARY KEY  (id),
        UNIQUE KEY unique_email (email)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Хук init для создания таблицы при загрузке WordPress
add_action('init', 'create_subscribed_emails_table');

// Функция для добавления меню подписанных email-адресов в админку
function add_subscribed_emails_menu() {
    add_menu_page(
        'Subscribed Emails', // Название страницы
        'Subscribed Emails', // Название меню
        'manage_options', // Способности (права доступа)
        'subscribed-emails', // Уникальный идентификатор меню
        'display_subscribed_emails_list', // Функция отображения страницы
        'dashicons-email', // Иконка меню
        6 // Позиция меню
    );
}
add_action('admin_menu', 'add_subscribed_emails_menu');

function display_subscribed_emails_list() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'subscribed_emails';
    $emails = $wpdb->get_results("SELECT * FROM $table_name");

    if ($emails) {
        echo '<div class="wrap"><h2>Subscribed Emails</h2><table class="widefat"><thead><tr><th>ID</th><th>Email</th></tr></thead><tbody>';
        foreach ($emails as $email) {
            echo '<tr><td>' . esc_html($email->id) . '</td><td>' . esc_html($email->email) . '</td></tr>'; // Используем esc_html для безопасности
        }
        echo '</tbody></table></div>';
    } else {
        echo '<div class="wrap"><h2>Subscribed Emails</h2><p>No subscribed emails found.</p></div>';
    }
}
?>
