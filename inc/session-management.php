<?php
/**
 * Session Management - Prevent Login Sharing
 * 
 * Detects when a user attempts to login from a different IP address
 * and requires confirmation before ending the existing session.
 */

if (!defined('ABSPATH')) {
    exit;
}

class iHowz_Session_Management {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Hook into authentication process
        add_filter('authenticate', array($this, 'check_existing_session'), 30, 3);
        
        // Handle session confirmation actions
        add_action('init', array($this, 'handle_session_confirmation'));
        
        // Store session info on successful login
        add_action('wp_login', array($this, 'store_session_info'), 10, 2);
        
        // Clean up session info on logout
        add_action('wp_logout', array($this, 'clear_session_info'));
        
        // Add custom page template for session confirmation
        add_filter('template_include', array($this, 'session_confirmation_template'));
    }
    
    /**
     * Get client IP address.
     *
     * Handles proxied requests by parsing X-Forwarded-For and using the
     * rightmost valid, non-private IP (the one added by the closest trusted
     * proxy). Falls back to REMOTE_ADDR when no forwarded header is present.
     */
    private function get_client_ip() {
        $ip = '';

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = array_map('trim', explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
            // Use the rightmost valid, non-private/reserved IP in the chain.
            foreach (array_reverse($ips) as $candidate) {
                if (filter_var($candidate, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    $ip = $candidate;
                    break;
                }
            }
        }

        if (empty($ip) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
            $candidate = $_SERVER['HTTP_CLIENT_IP'];
            if (filter_var($candidate, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                $ip = $candidate;
            }
        }

        if (empty($ip) && !empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }
    
    /**
     * Get user agent
     */
    private function get_user_agent() {
        return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    }
    
    /**
     * Parse user agent to get browser/device info
     */
    private function parse_user_agent($user_agent) {
        $browser = 'Unknown Browser';
        $device = 'Unknown Device';
        
        // Simple browser detection
        if (strpos($user_agent, 'Chrome') !== false && strpos($user_agent, 'Edg') === false) {
            $browser = 'Chrome';
        } elseif (strpos($user_agent, 'Firefox') !== false) {
            $browser = 'Firefox';
        } elseif (strpos($user_agent, 'Safari') !== false && strpos($user_agent, 'Chrome') === false) {
            $browser = 'Safari';
        } elseif (strpos($user_agent, 'Edg') !== false) {
            $browser = 'Edge';
        } elseif (strpos($user_agent, 'MSIE') !== false || strpos($user_agent, 'Trident') !== false) {
            $browser = 'Internet Explorer';
        }
        
        // Simple device detection
        if (strpos($user_agent, 'Mobile') !== false || strpos($user_agent, 'Android') !== false) {
            $device = 'Mobile';
        } elseif (strpos($user_agent, 'Tablet') !== false || strpos($user_agent, 'iPad') !== false) {
            $device = 'Tablet';
        } else {
            $device = 'Desktop';
        }
        
        return $browser . ' on ' . $device;
    }
    
    /**
     * Check for existing session on different IP
     */
    public function check_existing_session($user, $username, $password) {
        // Skip if already a WP_Error or empty username
        if (is_wp_error($user) || empty($username)) {
            return $user;
        }
        
        // Skip if this is a session confirmation attempt
        if (isset($_POST['ihowz_session_confirm']) || isset($_GET['ihowz_session_confirm'])) {
            return $user;
        }
        
        // Get user by username
        $user_obj = get_user_by('login', $username);
        if (!$user_obj) {
            return $user;
        }
        
        // Get stored session info
        $session_info = get_user_meta($user_obj->ID, '_ihowz_active_session', true);
        
        if (!empty($session_info)) {
            $current_ip = $this->get_client_ip();
            $stored_ip = isset($session_info['ip']) ? $session_info['ip'] : '';
            
            // Check if IP is different
            if (!empty($stored_ip) && $stored_ip !== $current_ip) {
                // Store pending login info in transient (never the password).
                $confirm_token = wp_generate_password(32, false);
                set_transient('ihowz_pending_login_' . $user_obj->ID, array(
                    'user_id' => $user_obj->ID,
                    'confirm_token' => $confirm_token,
                    'new_ip' => $current_ip,
                    'new_user_agent' => $this->get_user_agent(),
                    'existing_session' => $session_info
                ), 600); // 10 minutes

                // Redirect to confirmation page
                wp_redirect(add_query_arg(array(
                    'ihowz_session_confirm' => 'required',
                    'user_id' => $user_obj->ID,
                    'token' => $confirm_token
                ), wp_login_url()));
                exit;
            }
        }
        
        return $user;
    }
    
    /**
     * Handle session confirmation
     */
    public function handle_session_confirmation() {
        if (!isset($_REQUEST['ihowz_session_confirm']) || $_REQUEST['ihowz_session_confirm'] !== 'required') {
            return;
        }
        
        if (!isset($_REQUEST['user_id'])) {
            return;
        }
        
        $user_id = intval($_REQUEST['user_id']);
        $pending_login = get_transient('ihowz_pending_login_' . $user_id);
        
        if (!$pending_login) {
            wp_redirect(wp_login_url());
            exit;
        }

        // Display confirmation page on GET request (template_include does not fire on wp-login.php)
        if ($_SERVER["REQUEST_METHOD"] === "GET" && !isset($_POST["confirm_session"]) && !isset($_POST["cancel_session"])) {
            $this->display_confirmation_page($user_id, $pending_login);
            exit;
        }

        // Verify confirmation token from URL (GET) and form (POST).
        $request_token = '';
        if (isset($_REQUEST['token'])) {
            $request_token = sanitize_text_field(wp_unslash($_REQUEST['token']));
        }
        if (empty($request_token) || !isset($pending_login['confirm_token']) || !hash_equals($pending_login['confirm_token'], $request_token)) {
            delete_transient('ihowz_pending_login_' . $user_id);
            wp_redirect(wp_login_url());
            exit;
        }

        // Handle confirmation
        if (isset($_POST['confirm_session'])) {
            check_admin_referer('ihowz_session_confirm_' . $user_id);

            // Destroy old session
            $this->destroy_user_sessions($user_id);

            // The user already authenticated successfully before reaching the
            // confirmation page; we just need to create the session for them.
            wp_set_current_user($user_id);
            wp_set_auth_cookie($user_id, true);

            // Clear transient
            delete_transient('ihowz_pending_login_' . $user_id);

            // Redirect safely to admin or the originally requested location.
            $redirect_to = isset($_REQUEST['redirect_to']) ? wp_validate_redirect($_REQUEST['redirect_to'], admin_url()) : admin_url();
            wp_redirect($redirect_to);
            exit;
        }
        
        // Handle cancellation
        if (isset($_POST['cancel_session'])) {
            delete_transient('ihowz_pending_login_' . $user_id);
            wp_redirect(wp_login_url());
            exit;
        }
    }
    
    /**
     * Store session info on successful login
     */
    public function store_session_info($user_login, $user) {
        $session_info = array(
            'ip' => $this->get_client_ip(),
            'timestamp' => current_time('U'),
            'user_agent' => $this->get_user_agent()
        );
        
        update_user_meta($user->ID, '_ihowz_active_session', $session_info);
    }
    
    /**
     * Clear session info on logout
     */
    public function clear_session_info() {
        $user_id = get_current_user_id();
        if ($user_id) {
            delete_user_meta($user_id, '_ihowz_active_session');
        }
    }
    
    /**
     * Destroy all user sessions
     */
    private function destroy_user_sessions($user_id) {
        $sessions = WP_Session_Tokens::get_instance($user_id);
        $sessions->destroy_all();
        delete_user_meta($user_id, '_ihowz_active_session');
    }
    
    /**
     * Custom template for session confirmation page
     */
    public function session_confirmation_template($template) {
        if (!isset($_REQUEST['ihowz_session_confirm']) || $_REQUEST['ihowz_session_confirm'] !== 'required') {
            return $template;
        }
        
        if (!isset($_REQUEST['user_id'])) {
            return $template;
        }
        
        $user_id = intval($_REQUEST['user_id']);
        $pending_login = get_transient('ihowz_pending_login_' . $user_id);
        
        if (!$pending_login) {
            return $template;
        }
        
        // Display confirmation page
        $this->display_confirmation_page($user_id, $pending_login);
        exit;
    }
    
    /**
     * Display confirmation page
     */
    private function display_confirmation_page($user_id, $pending_login) {
        $existing_session = $pending_login['existing_session'];
        $existing_ip = isset($existing_session['ip']) ? $existing_session['ip'] : 'Unknown';
        $existing_timestamp = isset($existing_session['timestamp']) ? $existing_session['timestamp'] : 0;
        $existing_user_agent = isset($existing_session['user_agent']) ? $existing_session['user_agent'] : '';
        
        $browser_info = $this->parse_user_agent($existing_user_agent);
        $formatted_time = $existing_timestamp ? wp_date('F j, Y g:i A', (int) $existing_timestamp) : 'Unknown time';
        
        ?>
        <!DOCTYPE html>
        <html <?php language_attributes(); ?>>
        <head>
            <meta charset="<?php bloginfo('charset'); ?>">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Session Confirmation - <?php bloginfo('name'); ?></title>
            <?php wp_head(); ?>
            <style>
                body {
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
                    background: #f0f0f1;
                    margin: 0;
                    padding: 20px;
                }
                .session-confirm-container {
                    max-width: 500px;
                    margin: 100px auto;
                    background: #fff;
                    padding: 40px;
                    border-radius: 8px;
                    box-shadow: 0 1px 3px rgba(0,0,0,0.13);
                }
                h1 {
                    margin-top: 0;
                    color: #1d2327;
                    font-size: 24px;
                }
                .warning-message {
                    background: #fcf8e3;
                    border-left: 4px solid #dba617;
                    padding: 15px;
                    margin: 20px 0;
                    line-height: 1.6;
                }
                .session-details {
                    background: #f6f7f7;
                    padding: 15px;
                    border-radius: 4px;
                    margin: 20px 0;
                }
                .session-details strong {
                    display: block;
                    margin-bottom: 5px;
                    color: #1d2327;
                }
                .session-details p {
                    margin: 5px 0;
                    color: #50575e;
                }
                .button-group {
                    display: flex;
                    gap: 10px;
                    margin-top: 30px;
                }
                button {
                    flex: 1;
                    padding: 12px 24px;
                    border: none;
                    border-radius: 4px;
                    font-size: 14px;
                    cursor: pointer;
                    transition: all 0.2s;
                }
                .btn-confirm {
                    background: #2271b1;
                    color: #fff;
                }
                .btn-confirm:hover {
                    background: #135e96;
                }
                .btn-cancel {
                    background: #dcdcde;
                    color: #2c3338;
                }
                .btn-cancel:hover {
                    background: #c3c4c7;
                }
            </style>
        </head>
        <body>
            <div class="session-confirm-container">
                <h1>⚠️ Session Confirmation Required</h1>
                
                <div class="warning-message">
                    <strong>You are already logged in from a different location.</strong>
                    <p>Please confirm you wish to login. This will automatically end the session currently connected from:</p>
                </div>
                
                <div class="session-details">
                    <strong>Existing Session Details:</strong>
                    <p><strong>IP Address:</strong> <?php echo esc_html($existing_ip); ?></p>
                    <p><strong>Last Active:</strong> <?php echo esc_html($formatted_time); ?></p>
                    <p><strong>Browser/Device:</strong> <?php echo esc_html($browser_info); ?></p>
                </div>
                
                <p style="color: #50575e; font-size: 14px;">
                    If this wasn't you, someone else may have your password. Consider changing it after logging in.
                </p>
                
                <form method="post">
                    <?php wp_nonce_field('ihowz_session_confirm_' . $user_id); ?>
                    <input type="hidden" name="ihowz_session_confirm" value="required">
                    <input type="hidden" name="user_id" value="<?php echo esc_attr($user_id); ?>">
                    <input type="hidden" name="token" value="<?php echo esc_attr($pending_login['confirm_token']); ?>">
                    <?php if (isset($_REQUEST['redirect_to'])) : ?>
                        <input type="hidden" name="redirect_to" value="<?php echo esc_attr($_REQUEST['redirect_to']); ?>">
                    <?php endif; ?>
                    
                    <div class="button-group">
                        <button type="submit" name="confirm_session" class="btn-confirm">
                            ✓ Confirm & Login
                        </button>
                        <button type="submit" name="cancel_session" class="btn-cancel">
                            ✗ Cancel
                        </button>
                    </div>
                </form>
            </div>
            <?php wp_footer(); ?>
        </body>
        </html>
        <?php
    }
}

// Initialize
iHowz_Session_Management::get_instance();
