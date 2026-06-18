<?php
/**
 * GitHub Theme Updater for iHowz
 *
 * Checks the public ihowz-theme GitHub repository for new releases and
 * notifies WordPress when an update is available.
 */

if (!defined('ABSPATH')) {
    exit;
}

class IHowz_GitHub_Theme_Updater {

    /**
     * GitHub owner/repo slug for the theme.
     */
    private string $repo = 'JonHubbard1/ihowz-theme';

    /**
     * Theme directory slug on this site.
     */
    private string $theme_slug = 'ihowz';

    /**
     * Transient cache key.
     */
    private string $cache_key = 'ihowz_theme_update_data';

    /**
     * Cache duration in seconds (1 hour).
     */
    private int $cache_duration = HOUR_IN_SECONDS;

    public function __construct() {
        add_filter('site_transient_update_themes', [$this, 'check_update']);

        // Add a "Check for updates" link on the theme list and update pages.
        add_filter('theme_row_meta', [$this, 'add_check_link'], 10, 2);
        add_action('admin_init', [$this, 'handle_manual_check']);
    }

    /**
     * Inject update data into the WordPress theme update transient.
     *
     * @param object|bool $transient
     * @return object|bool
     */
    public function check_update($transient) {
        if (empty($transient) || !is_object($transient)) {
            $transient = new stdClass();
        }

        if (isset($transient->checked) && empty($transient->checked)) {
            return $transient;
        }

        // WordPress populates $transient->checked with the version it detected.
        // Use that value instead of re-reading style.css on disk.
        $current_version = $transient->checked[$this->theme_slug] ?? null;
        if (!$current_version) {
            $theme = wp_get_theme($this->theme_slug);
            $current_version = $theme->get('Version');
        }

        $release = $this->get_latest_release();
        if (!$release || is_wp_error($release)) {
            return $transient;
        }

        $latest_version = $this->normalize_version($release['tag_name']);

        if (version_compare($latest_version, $current_version, '>')) {
            $transient->response[$this->theme_slug] = [
                'theme'       => $this->theme_slug,
                'new_version' => $latest_version,
                'url'         => $release['html_url'],
                'package'     => $this->get_zip_url($release),
            ];
        } else {
            $transient->no_update[$this->theme_slug] = [
                'theme'       => $this->theme_slug,
                'new_version' => $current_version,
                'url'         => $release['html_url'],
                'package'     => $this->get_zip_url($release),
            ];
        }

        return $transient;
    }

    /**
     * Add a "Check GitHub for updates" link under the theme on the themes page.
     *
     * @param array  $links
     * @param string $theme_key
     * @return array
     */
    public function add_check_link(array $links, string $theme_key): array {
        if ($theme_key !== $this->theme_slug) {
            return $links;
        }

        $url = wp_nonce_url(
            add_query_arg([
                'ihowz_action' => 'check_theme_update',
            ], admin_url('themes.php')),
            'ihowz_check_theme_update',
            'ihowz_nonce'
        );

        $links[] = '<a href="' . esc_url($url) . '" class="ihowz-check-update">' . esc_html__('Check GitHub for updates', 'ihowz-theme') . '</a>';

        return $links;
    }

    /**
     * Handle the manual "Check for updates" action.
     */
    public function handle_manual_check(): void {
        if (!is_admin() || !current_user_can('manage_options')) {
            return;
        }

        if (!isset($_GET['ihowz_action']) || $_GET['ihowz_action'] !== 'check_theme_update') {
            return;
        }

        if (!isset($_GET['ihowz_nonce']) || !wp_verify_nonce($_GET['ihowz_nonce'], 'ihowz_check_theme_update')) {
            wp_die(esc_html__('Security check failed.', 'ihowz-theme'));
        }

        delete_transient($this->cache_key);
        set_site_transient('update_themes', new stdClass());
        wp_update_themes();

        $transient = get_site_transient('update_themes');
        $has_update = isset($transient->response[$this->theme_slug]);
        $latest = $transient->response[$this->theme_slug]['new_version'] ?? '';
        $installed = $transient->checked[$this->theme_slug] ?? wp_get_theme($this->theme_slug)->get('Version');

        $message = $has_update
            ? sprintf(
                /* translators: 1: installed version, 2: latest version */
                __('Theme update check complete. Installed: %1$s, latest on GitHub: %2$s.'),
                $installed,
                $latest
            )
            : sprintf(
                /* translators: 1: installed version */
                __('Theme update check complete. Installed version %1$s is up to date with GitHub.'),
                $installed
            );

        wp_redirect(add_query_arg([
            'ihowz_notice' => urlencode($message),
            'ihowz_notice_type' => $has_update ? 'warning' : 'success',
        ], admin_url('themes.php')));
        exit;
    }

    /**
     * Fetch the latest release from the GitHub API.
     *
     * @return array|false|WP_Error
     */
    private function get_latest_release() {
        $cached = get_transient($this->cache_key);
        if ($cached !== false) {
            return $cached;
        }

        $url = "https://api.github.com/repos/{$this->repo}/releases/latest";

        $response = wp_remote_get($url, [
            'headers' => [
                'Accept'     => 'application/vnd.github+json',
                'User-Agent' => 'iHowz-Theme-Updater/1.0',
            ],
            'timeout' => 15,
        ]);

        if (is_wp_error($response)) {
            return $response;
        }

        $status = wp_remote_retrieve_response_code($response);
        if ($status !== 200) {
            return false;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (empty($data['tag_name'])) {
            return false;
        }

        set_transient($this->cache_key, $data, $this->cache_duration);

        return $data;
    }

    /**
     * Strip a leading 'v' from version strings.
     *
     * @param string $version
     * @return string
     */
    private function normalize_version(string $version): string {
        return ltrim($version, 'vV');
    }

    /**
     * Return the best zip URL from a release payload.
     *
     * Prefers the first release asset ending in .zip.
     *
     * @param array $release
     * @return string
     */
    private function get_zip_url(array $release): string {
        if (!empty($release['assets'])) {
            foreach ($release['assets'] as $asset) {
                if (isset($asset['content_type']) && $asset['content_type'] === 'application/zip') {
                    return $asset['browser_download_url'];
                }
                if (isset($asset['name']) && str_ends_with(strtolower($asset['name']), '.zip')) {
                    return $asset['browser_download_url'];
                }
            }
        }

        return $release['zipball_url'] ?? '';
    }
}

new IHowz_GitHub_Theme_Updater();
