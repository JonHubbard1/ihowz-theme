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

        $theme = wp_get_theme($this->theme_slug);
        $current_version = $theme->get('Version');

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
     * Prefers the first release asset ending in .zip, otherwise falls back
     * to the auto-generated source archive.
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
