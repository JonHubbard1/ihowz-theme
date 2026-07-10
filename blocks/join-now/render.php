<?php
/**
 * Join Now Block - Server-side rendering
 *
 * @package iHowz Theme
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Detect if rendering in a widget area
$is_widget_context = did_action('dynamic_sidebar_before') > did_action('dynamic_sidebar_after');

// Get attributes with defaults
$eyebrow_text = isset($attributes['eyebrowText']) ? esc_html($attributes['eyebrowText']) : 'Become a Member';
$heading = isset($attributes['heading']) ? esc_html($attributes['heading']) : 'Join iHowz Today';
$description = isset($attributes['description']) ? esc_html($attributes['description']) : '';
$selected_type_id = isset($attributes['membershipTypeId']) ? sanitize_text_field($attributes['membershipTypeId']) : '';
$background_color = isset($attributes['backgroundColor']) ? esc_attr($attributes['backgroundColor']) : '#F7FCF0';
$show_pricing = isset($attributes['showPricing']) ? $attributes['showPricing'] : true;
$success_message = isset($attributes['successMessage']) ? esc_html($attributes['successMessage']) : 'Thank you for joining iHowz! Your membership is now active.';

// Get membership types from the plugin (cached for 5 minutes to avoid a DB hit on every render).
$membership_types = array();
if (function_exists('IHowz_Membership_Module') || class_exists('IHowz_Membership_Module')) {
    $cache_key = 'ihowz_join_membership_types';
    $membership_types = wp_cache_get($cache_key, 'ihowz');

    if (false === $membership_types) {
        global $wpdb;
        $membership_types = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT id, name, slug, price, duration_months, description, benefits FROM {$wpdb->prefix}ihowz_membership_types WHERE is_active = %d AND is_join_visible = %d ORDER BY price ASC",
                1,
                1
            )
        );
        wp_cache_set($cache_key, $membership_types, 'ihowz', 300);
    }
}

// Build wrapper classes
$wrapper_classes = 'wp-block-ihowz-join-now ihowz-join-now';
if ($is_widget_context) {
    $wrapper_classes .= ' in-widget';
}
if (!empty($attributes['className'])) {
    $wrapper_classes .= ' ' . esc_attr($attributes['className']);
}
if (!empty($attributes['align'])) {
    $wrapper_classes .= ' align' . esc_attr($attributes['align']);
}

// Generate unique form ID for multiple blocks on same page
$form_id = 'ihowz-join-form-' . uniqid();

// Get Stripe publishable key
$stripe_mode = get_option('ihowz_stripe_mode', 'test');
$stripe_publishable_key = $stripe_mode === 'live'
    ? get_option('ihowz_stripe_live_publishable_key', '')
    : get_option('ihowz_stripe_test_publishable_key', '');

// Bacs Direct Debit flag (via Stripe)
$bacs_debit_enabled = get_option('ihowz_bacs_debit_enabled', true);

// Resolve a promo code to pre-fill: either from a ?promo= (/?promocode= /?code=)
// query parameter, or by matching the current page URL against the codes'
// share_url values (supports pretty-permalink landing pages like
// /register/show-special-20-discount/). Only valid, redeemable codes are used.
$preselected_promo_code = '';
if (class_exists('IHowz_Promotional_Codes_Module')) {
    $promo_module = IHowz_Promotional_Codes_Module::get_instance();
    if ($promo_module && method_exists($promo_module, 'get_service')) {
        $promo_svc = $promo_module->get_service();
        if ($promo_svc) {
            $query_code = '';
            if (isset($_GET['promo']))      { $query_code = sanitize_text_field($_GET['promo']); }
            elseif (isset($_GET['promocode'])) { $query_code = sanitize_text_field($_GET['promocode']); }
            elseif (isset($_GET['code']))   { $query_code = sanitize_text_field($_GET['code']); }
            $current_url = home_url(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/');
            $preselected_promo_code = $promo_svc->get_preselected_code($query_code, $current_url);
        }
    }
}

// Categorise membership types for conditional field display on the frontend.
$dual_type_ids = array();
$corporate_type_ids = array();
foreach ($membership_types as $type) {
    if ($type->slug === 'dual-membership' || stripos($type->name, 'dual') !== false) {
        $dual_type_ids[] = (int) $type->id;
    }
    if ($type->slug === 'corporate-membership' || stripos($type->name, 'corporate') !== false) {
        $corporate_type_ids[] = (int) $type->id;
    }
}

function ihowz_join_membership_category($type) {
    if ($type->slug === 'dual-membership' || stripos($type->name, 'dual') !== false) {
        return 'dual';
    }
    if ($type->slug === 'corporate-membership' || stripos($type->name, 'corporate') !== false) {
        return 'corporate';
    }
    return 'single';
}

// Localize data for frontend JS
$join_data = array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('ihowz_join_nonce'),
    'stripe_key' => $stripe_publishable_key,
    'stripe_mode' => $stripe_mode,
    'form_id' => $form_id,
    'success_message' => $success_message,
    'bacs_debit_enabled' => $bacs_debit_enabled,
    'preselected_promo_code' => $preselected_promo_code,
    'dual_type_ids' => $dual_type_ids,
    'corporate_type_ids' => $corporate_type_ids,
    'membership_types' => array_map(function($type) {
        return array(
            'id' => $type->id,
            'name' => $type->name,
            'slug' => $type->slug,
            'price' => floatval($type->price),
            'duration' => intval($type->duration_months),
            'description' => $type->description,
            'benefits' => $type->benefits,
        );
    }, $membership_types),
);

// Enqueue Stripe.js if key is available
if ($stripe_publishable_key && !wp_script_is('stripe-js', 'enqueued')) {
    wp_enqueue_script('stripe-js', 'https://js.stripe.com/v3/', array(), null, true);
}

// Ensure the block's frontend script + style load even when this template is
// rendered via the [ihowz_join_now] shortcode (which does not auto-enqueue them).
wp_enqueue_script('ihowz-join-now-frontend');
wp_enqueue_style('ihowz-join-now-style');

?>

<script>window["ihowzJoinData_<?php echo esc_js($form_id); ?>"] = <?php echo wp_json_encode($join_data); ?>;</script>

<section class="<?php echo esc_attr($wrapper_classes); ?>" style="background-color: <?php echo esc_attr($background_color); ?>;">
    <div class="join-now-container">
        <!-- Header -->
        <div class="join-now-header">
            <?php if ($eyebrow_text) : ?>
                <span class="join-now-eyebrow ihowz-eyebrow"><?php echo esc_html($eyebrow_text); ?></span>
            <?php endif; ?>
            <?php if ($heading) : ?>
                <h2 class="join-now-heading ihowz-heading"><?php echo esc_html($heading); ?></h2>
            <?php endif; ?>
            <?php if ($description) : ?>
                <p class="join-now-description"><?php echo esc_html($description); ?></p>
            <?php endif; ?>
        </div>

        <!-- Form Container -->
        <div class="join-now-form-wrapper">
            <form id="<?php echo esc_attr($form_id); ?>" class="join-now-form" method="post" novalidate>
                <?php wp_nonce_field('ihowz_join_nonce', 'ihowz_join_nonce_field'); ?>

                <!-- 1. Membership Type Selection -->
                <?php if (!empty($membership_types)) : ?>
                    <div class="join-now-field join-now-field-full">
                        <label><?php _e('Membership Type', 'ihowz-theme'); ?> <span class="required">*</span></label>
                        <div class="membership-type-options">
                            <?php foreach ($membership_types as $index => $type) : ?>
                                <?php
                                $is_selected = ($selected_type_id && $selected_type_id == $type->id) || (!$selected_type_id && $index === 0);
                                $price = number_format(floatval($type->price), 2);
                                $benefits = !empty($type->benefits) ? array_filter(array_map('trim', explode("\n", $type->benefits))) : array();
                                ?>
                                <div class="membership-type-card <?php echo $is_selected ? 'selected' : ''; ?>"
                                     data-type-id="<?php echo esc_attr($type->id); ?>"
                                     data-category="<?php echo esc_attr(ihowz_join_membership_category($type)); ?>"
                                     data-price="<?php echo esc_attr($type->price); ?>"
                                     data-name="<?php echo esc_attr($type->name); ?>">
                                    <input type="radio"
                                           name="membership_type_id"
                                           id="<?php echo esc_attr($form_id); ?>-type-<?php echo esc_attr($type->id); ?>"
                                           value="<?php echo esc_attr($type->id); ?>"
                                           <?php checked($is_selected); ?>
                                           required>
                                    <div class="membership-type-info">
                                        <span class="membership-type-name"><?php echo esc_html($type->name); ?></span>
                                        <?php if ($show_pricing) : ?>
                                            <span class="membership-type-price">&pound;<?php echo esc_html($price); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!empty($type->description)) : ?>
                                        <p class="membership-type-desc"><?php echo esc_html($type->description); ?></p>
                                    <?php endif; ?>
                                    <?php if (!empty($benefits)) : ?>
                                        <ul class="membership-type-benefits">
                                            <?php foreach ($benefits as $benefit) : ?>
                                                <li><?php echo esc_html($benefit); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <hr class="join-now-divider" aria-hidden="true">

                <!-- 2. Personal Details (name / contact / address) -->
                <div class="join-now-field-group">
                    <div class="join-now-field">
                        <label for="<?php echo esc_attr($form_id); ?>-first-name">
                            <?php _e('First Name', 'ihowz-theme'); ?> <span class="required">*</span>
                        </label>
                        <input type="text"
                               id="<?php echo esc_attr($form_id); ?>-first-name"
                               name="first_name"
                               class="join-now-input"
                               placeholder="<?php esc_attr_e('Enter your first name', 'ihowz-theme'); ?>"
                               required
                               autocomplete="given-name">
                    </div>

                    <div class="join-now-field">
                        <label for="<?php echo esc_attr($form_id); ?>-last-name">
                            <?php _e('Last Name', 'ihowz-theme'); ?> <span class="required">*</span>
                        </label>
                        <input type="text"
                               id="<?php echo esc_attr($form_id); ?>-last-name"
                               name="last_name"
                               class="join-now-input"
                               placeholder="<?php esc_attr_e('Enter your last name', 'ihowz-theme'); ?>"
                               required
                               autocomplete="family-name">
                    </div>
                </div>

                <div class="join-now-field join-now-field-full">
                    <label for="<?php echo esc_attr($form_id); ?>-email">
                        <?php _e('Email Address', 'ihowz-theme'); ?> <span class="required">*</span>
                    </label>
                    <input type="email"
                           id="<?php echo esc_attr($form_id); ?>-email"
                           name="email"
                           class="join-now-input"
                           placeholder="<?php esc_attr_e('your@email.com', 'ihowz-theme'); ?>"
                           required
                           autocomplete="email">
                </div>

                <div class="join-now-field-group">
                    <div class="join-now-field">
                        <label for="<?php echo esc_attr($form_id); ?>-phone">
                            <?php _e('Phone Number', 'ihowz-theme'); ?> <span class="required">*</span>
                        </label>
                        <input type="tel"
                               id="<?php echo esc_attr($form_id); ?>-phone"
                               name="phone"
                               class="join-now-input"
                               placeholder="<?php esc_attr_e('e.g. 07123 456789', 'ihowz-theme'); ?>"
                               required
                               autocomplete="tel">
                    </div>
                </div>

                <!-- Company details (Corporate memberships only) -->
                <div class="join-now-conditional-section join-now-company-section" style="display: none;">
                    <div class="join-now-field join-now-field-full">
                        <label for="<?php echo esc_attr($form_id); ?>-company">
                            <?php _e('Company Name', 'ihowz-theme'); ?> <span class="required">*</span>
                        </label>
                        <input type="text"
                               id="<?php echo esc_attr($form_id); ?>-company"
                               name="company_name"
                               class="join-now-input"
                               placeholder="<?php esc_attr_e('Enter company name', 'ihowz-theme'); ?>"
                               autocomplete="organization">
                    </div>
                </div>

                <!-- Secondary member (Dual memberships only) -->
                <div class="join-now-conditional-section join-now-secondary-member-section" style="display: none;">
                    <hr class="join-now-divider" aria-hidden="true">

                    <div class="join-now-field join-now-field-full">
                        <p class="join-now-section-title"><?php _e('Secondary Member', 'ihowz-theme'); ?></p>
                        <p class="join-now-section-description"><?php _e('The additional person included on this dual membership.', 'ihowz-theme'); ?></p>
                    </div>

                    <div class="join-now-field-group">
                        <div class="join-now-field">
                            <label for="<?php echo esc_attr($form_id); ?>-secondary-title">
                                <?php _e('Title', 'ihowz-theme'); ?>
                            </label>
                            <input type="text"
                                   id="<?php echo esc_attr($form_id); ?>-secondary-title"
                                   name="secondary_title"
                                   class="join-now-input"
                                   placeholder="<?php esc_attr_e('e.g. Mr, Mrs, Dr', 'ihowz-theme'); ?>"
                                   autocomplete="honorific-prefix">
                        </div>

                        <div class="join-now-field">
                            <label for="<?php echo esc_attr($form_id); ?>-secondary-first-name">
                                <?php _e('First Name', 'ihowz-theme'); ?> <span class="required">*</span>
                            </label>
                            <input type="text"
                                   id="<?php echo esc_attr($form_id); ?>-secondary-first-name"
                                   name="secondary_first_name"
                                   class="join-now-input"
                                   placeholder="<?php esc_attr_e('Enter first name', 'ihowz-theme'); ?>"
                                   autocomplete="given-name">
                        </div>
                    </div>

                    <div class="join-now-field-group">
                        <div class="join-now-field">
                            <label for="<?php echo esc_attr($form_id); ?>-secondary-last-name">
                                <?php _e('Last Name', 'ihowz-theme'); ?> <span class="required">*</span>
                            </label>
                            <input type="text"
                                   id="<?php echo esc_attr($form_id); ?>-secondary-last-name"
                                   name="secondary_last_name"
                                   class="join-now-input"
                                   placeholder="<?php esc_attr_e('Enter last name', 'ihowz-theme'); ?>"
                                   autocomplete="family-name">
                        </div>

                        <div class="join-now-field">
                            <label for="<?php echo esc_attr($form_id); ?>-secondary-email">
                                <?php _e('Email Address', 'ihowz-theme'); ?>
                            </label>
                            <input type="email"
                                   id="<?php echo esc_attr($form_id); ?>-secondary-email"
                                   name="secondary_email"
                                   class="join-now-input"
                                   placeholder="<?php esc_attr_e('secondary@email.com', 'ihowz-theme'); ?>"
                                   autocomplete="email">
                        </div>
                    </div>

                    <div class="join-now-field join-now-field-full">
                        <label for="<?php echo esc_attr($form_id); ?>-secondary-phone">
                            <?php _e('Phone Number', 'ihowz-theme'); ?>
                        </label>
                        <input type="tel"
                               id="<?php echo esc_attr($form_id); ?>-secondary-phone"
                               name="secondary_phone"
                               class="join-now-input"
                               placeholder="<?php esc_attr_e('e.g. 07123 456789', 'ihowz-theme'); ?>"
                               autocomplete="tel">
                    </div>
                </div>

                <!-- Address -->
                <div class="join-now-field join-now-field-full">
                    <label for="<?php echo esc_attr($form_id); ?>-address">
                        <?php _e('Address Line 1', 'ihowz-theme'); ?> <span class="required">*</span>
                    </label>
                    <input type="text"
                           id="<?php echo esc_attr($form_id); ?>-address"
                           name="address"
                           class="join-now-input"
                           placeholder="<?php esc_attr_e('Street address', 'ihowz-theme'); ?>"
                           required
                           autocomplete="street-address">
                </div>

                <div class="join-now-field join-now-field-full">
                    <label for="<?php echo esc_attr($form_id); ?>-address2">
                        <?php _e('Address Line 2', 'ihowz-theme'); ?>
                    </label>
                    <input type="text"
                           id="<?php echo esc_attr($form_id); ?>-address2"
                           name="address2"
                           class="join-now-input"
                           placeholder="<?php esc_attr_e('Flat, floor, building (optional)', 'ihowz-theme'); ?>"
                           autocomplete="address-line2">
                </div>

                <div class="join-now-field join-now-field-full">
                    <label for="<?php echo esc_attr($form_id); ?>-address3">
                        <?php _e('Address Line 3', 'ihowz-theme'); ?>
                    </label>
                    <input type="text"
                           id="<?php echo esc_attr($form_id); ?>-address3"
                           name="address3"
                           class="join-now-input"
                           placeholder="<?php esc_attr_e('District, neighbourhood (optional)', 'ihowz-theme'); ?>"
                           autocomplete="address-line3">
                </div>

                <div class="join-now-field-group">
                    <div class="join-now-field">
                        <label for="<?php echo esc_attr($form_id); ?>-town">
                            <?php _e('Town / City', 'ihowz-theme'); ?> <span class="required">*</span>
                        </label>
                        <input type="text"
                               id="<?php echo esc_attr($form_id); ?>-town"
                               name="town"
                               class="join-now-input"
                               placeholder="<?php esc_attr_e('Town or city', 'ihowz-theme'); ?>"
                               required
                               autocomplete="address-level2">
                    </div>

                    <div class="join-now-field">
                        <label for="<?php echo esc_attr($form_id); ?>-county">
                            <?php _e('County', 'ihowz-theme'); ?>
                        </label>
                        <input type="text"
                               id="<?php echo esc_attr($form_id); ?>-county"
                               name="county"
                               class="join-now-input"
                               placeholder="<?php esc_attr_e('e.g. Wiltshire', 'ihowz-theme'); ?>"
                               autocomplete="address-level1">
                    </div>
                </div>

                <div class="join-now-field-group">
                    <div class="join-now-field">
                        <label for="<?php echo esc_attr($form_id); ?>-postcode">
                            <?php _e('Postcode', 'ihowz-theme'); ?> <span class="required">*</span>
                        </label>
                        <input type="text"
                               id="<?php echo esc_attr($form_id); ?>-postcode"
                               name="postcode"
                               class="join-now-input"
                               placeholder="<?php esc_attr_e('e.g. SW1A 1AA', 'ihowz-theme'); ?>"
                               required
                               autocomplete="postal-code">
                    </div>

                    <div class="join-now-field">
                        <label for="<?php echo esc_attr($form_id); ?>-country">
                            <?php _e('Country', 'ihowz-theme'); ?>
                        </label>
                        <input type="text"
                               id="<?php echo esc_attr($form_id); ?>-country"
                               name="country"
                               class="join-now-input"
                               value="<?php esc_attr_e('United Kingdom', 'ihowz-theme'); ?>"
                               autocomplete="country-name">
                    </div>
                </div>

                <hr class="join-now-divider" aria-hidden="true">

                <!-- 3. Password -->
                <div class="join-now-field-group">
                    <div class="join-now-field">
                        <label for="<?php echo esc_attr($form_id); ?>-password">
                            <?php _e('Password', 'ihowz-theme'); ?> <span class="required">*</span>
                        </label>
                        <div class="password-input-wrapper">
                            <input type="password"
                                   id="<?php echo esc_attr($form_id); ?>-password"
                                   name="password"
                                   class="join-now-input"
                                   placeholder="<?php esc_attr_e('Min 12 characters, 1 uppercase & 1 number', 'ihowz-theme'); ?>"
                                   required
                                   minlength="12"
                                   autocomplete="new-password">
                            <button type="button" class="password-toggle-btn" aria-label="<?php esc_attr_e('Show password', 'ihowz-theme'); ?>" aria-pressed="false" aria-controls="<?php echo esc_attr($form_id); ?>-password">
                                <svg class="password-toggle-icon password-toggle-icon-show" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <svg class="password-toggle-icon password-toggle-icon-hide" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-10-8-10-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 10 8 10 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="join-now-field">
                        <label for="<?php echo esc_attr($form_id); ?>-password-confirm">
                            <?php _e('Confirm Password', 'ihowz-theme'); ?> <span class="required">*</span>
                        </label>
                        <div class="password-input-wrapper">
                            <input type="password"
                                   id="<?php echo esc_attr($form_id); ?>-password-confirm"
                                   name="password_confirm"
                                   class="join-now-input"
                                   placeholder="<?php esc_attr_e('Re-enter password', 'ihowz-theme'); ?>"
                                   required
                                   minlength="12"
                                   autocomplete="new-password">
                            <button type="button" class="password-toggle-btn" aria-label="<?php esc_attr_e('Show password', 'ihowz-theme'); ?>" aria-pressed="false" aria-controls="<?php echo esc_attr($form_id); ?>-password-confirm">
                                <svg class="password-toggle-icon password-toggle-icon-show" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <svg class="password-toggle-icon password-toggle-icon-hide" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-10-8-10-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 10 8 10 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <hr class="join-now-divider" aria-hidden="true">

                <!-- 4. Privacy Preferences (terms consent + marketing/newsletter opt-ins + preferred contact) -->
                <div class="join-now-field join-now-field-full">
                    <label class="join-now-checkbox-label">
                        <input type="checkbox" name="terms_accepted" value="1" required>
                        <span>
                            <?php _e('I agree to the', 'ihowz-theme'); ?>
                            <a href="/terms-and-conditions/" target="_blank"><?php _e('Terms & Conditions', 'ihowz-theme'); ?></a>
                            <?php _e('and', 'ihowz-theme'); ?>
                            <a href="/privacy-policy/" target="_blank"><?php _e('Privacy Policy', 'ihowz-theme'); ?></a>
                        </span>
                    </label>
                </div>

                <!-- Privacy preferences (optional marketing/newsletter consent + contact method) -->
                <div class="join-now-field join-now-field-full">
                    <p class="join-now-field-label"><?php _e('Your privacy preferences (optional)', 'ihowz-theme'); ?></p>
                    <p style="font-size:0.875rem;color:#666;margin:0 0 12px 0;">
                        <?php _e('Tick any you would like to opt in to. You can change these any time in the Privacy tab of your member portal.', 'ihowz-theme'); ?>
                    </p>
                    <label class="join-now-checkbox-label">
                        <input type="checkbox" name="consents[MARKETING]" value="1">
                        <span><?php _e('Send me marketing communications and promotional offers by email.', 'ihowz-theme'); ?></span>
                    </label>
                    <label class="join-now-checkbox-label">
                        <input type="checkbox" name="consents[NEWSLETTER]" value="1">
                        <span><?php _e('Send me the regular newsletter with updates and news.', 'ihowz-theme'); ?></span>
                    </label>
                    <label class="join-now-checkbox-label">
                        <input type="checkbox" name="consents[THIRD_PARTY]" value="1">
                        <span><?php _e('Share my contact details with trusted third parties so they can send me marketing offers.', 'ihowz-theme'); ?></span>
                    </label>
                </div>

                <!-- Preferred contact method -->
                <div class="join-now-field">
                    <label for="<?php echo esc_attr($form_id); ?>-preferred-contact" class="join-now-field-label"><?php _e('Preferred contact method', 'ihowz-theme'); ?></label>
                    <select name="preferences[preferred_contact_method]" id="<?php echo esc_attr($form_id); ?>-preferred-contact" class="join-now-input">
                        <option value="email" selected><?php _e('Email', 'ihowz-theme'); ?></option>
                        <option value="phone"><?php _e('Phone', 'ihowz-theme'); ?></option>
                        <option value="sms"><?php _e('SMS', 'ihowz-theme'); ?></option>
                    </select>
                </div>

                <hr class="join-now-divider" aria-hidden="true">

                <!-- 5. Total To Pay (standalone summary; updated by script.js via the -payment-amount id) -->
                <div class="join-now-field join-now-field-full join-now-total-summary">
                    <div class="join-now-payment-summary">
                        <span class="payment-label"><?php _e('Total to pay:', 'ihowz-theme'); ?></span>
                        <span class="payment-amount" id="<?php echo esc_attr($form_id); ?>-payment-amount">&pound;0.00</span>
                    </div>
                </div>

                <!-- 6. Payment Method Selector -->
                <?php if ($bacs_debit_enabled) : ?>
                <div class="join-now-field join-now-field-full join-now-payment-method-field">
                    <label><?php _e('Payment Method', 'ihowz-theme'); ?> <span class="required">*</span></label>
                    <div class="payment-method-toggle">
                        <button type="button" class="payment-method-option active" data-method="card">
                            <span class="payment-method-icon">&#x1f4b3;</span>
                            <span class="payment-method-label"><?php _e('Credit / Debit Card', 'ihowz-theme'); ?></span>
                        </button>
                        <button type="button" class="payment-method-option" data-method="bacs_debit">
                            <span class="payment-method-icon">&#x1f3e6;</span>
                            <span class="payment-method-label"><?php _e('Direct Debit', 'ihowz-theme'); ?></span>
                            <span class="payment-method-note"><?php _e('Pay directly from your bank account', 'ihowz-theme'); ?></span>
                        </button>
                    </div>
                    <input type="hidden" name="payment_method_type" id="<?php echo esc_attr($form_id); ?>-payment-method" value="card">
                </div>
                <?php endif; ?>

                <!-- Card Payment Section (details only; total shown above) -->
                <div class="payment-section payment-section-card" id="<?php echo esc_attr($form_id); ?>-payment-card">
                    <div class="join-now-payment-section">
                        <?php if ($stripe_publishable_key) : ?>
                        <div class="join-now-field join-now-field-full">
                            <label><?php _e('Card Details', 'ihowz-theme'); ?></label>
                            <div id="<?php echo esc_attr($form_id); ?>-card-element" class="stripe-card-element">
                                <!-- Stripe Element will be mounted here -->
                            </div>
                            <div id="<?php echo esc_attr($form_id); ?>-card-errors" class="stripe-card-errors" role="alert"></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Bacs Direct Debit Section (details only; total shown above) -->
                <?php if ($bacs_debit_enabled && $stripe_publishable_key) : ?>
                <div class="payment-section payment-section-bacs" id="<?php echo esc_attr($form_id); ?>-payment-bacs" style="display:none;">
                    <div class="join-now-payment-section">
                        <p class="bacs-instruction"><?php _e('Enter your bank details to set up a Direct Debit. Your payment will be processed within 3 working days.', 'ihowz-theme'); ?></p>
                        <div class="join-now-field-group">
                            <div class="join-now-field">
                                <label><?php _e('Sort Code', 'ihowz-theme'); ?> <span class="required">*</span></label>
                                <input type="text" id="<?php echo esc_attr($form_id); ?>-sort-code" class="join-now-input bacs-field" placeholder="12-34-56" maxlength="8" autocomplete="off">
                            </div>
                            <div class="join-now-field">
                                <label><?php _e('Account Number', 'ihowz-theme'); ?> <span class="required">*</span></label>
                                <input type="text" id="<?php echo esc_attr($form_id); ?>-account-number" class="join-now-input bacs-field" placeholder="12345678" maxlength="8" autocomplete="off">
                            </div>
                        </div>
                        <div class="join-now-field join-now-field-full">
                            <label><?php _e('Account Holder Name', 'ihowz-theme'); ?> <span class="required">*</span></label>
                            <input type="text" id="<?php echo esc_attr($form_id); ?>-account-name" class="join-now-input bacs-field" placeholder="<?php esc_attr_e('Name on bank account', 'ihowz-theme'); ?>" autocomplete="off">
                        </div>
                        <div id="<?php echo esc_attr($form_id); ?>-bacs-errors" class="stripe-card-errors" role="alert"></div>
                        <div class="bacs-mandate-notice">
                            <p><?php _e('By submitting this form you authorise iHowz to send instructions to your bank to debit your account. Your Direct Debit will be collected on or shortly after the anniversary of your membership.', 'ihowz-theme'); ?></p>
                            <div class="bacs-guarantee">
                                <strong><?php _e('The Direct Debit Guarantee', 'ihowz-theme'); ?></strong>
                                <ul>
                                    <li><?php _e('This Guarantee is offered by all banks and building societies', 'ihowz-theme'); ?></li>
                                    <li><?php _e('If the amounts to be paid or the payment dates change, you will be notified in advance', 'ihowz-theme'); ?></li>
                                    <li><?php _e('You can cancel a Direct Debit at any time by contacting your bank', 'ihowz-theme'); ?></li>
                                    <li><?php _e('If an error is made, your bank must refund you immediately', 'ihowz-theme'); ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- 7. Promotional Code -->
                <div class="join-now-field join-now-field-full">
                    <label for="<?php echo esc_attr($form_id); ?>-promo-code"><?php _e('Promo Code', 'ihowz-theme'); ?></label>
                    <div class="promo-code-row">
                        <input type="text"
                               id="<?php echo esc_attr($form_id); ?>-promo-code"
                               name="promo_code"
                               class="join-now-input promo-code-input"
                               placeholder="<?php esc_attr_e('Enter code if you have one', 'ihowz-theme'); ?>"
                               autocomplete="off"
                               style="text-transform:uppercase;">
                        <button type="button" class="promo-apply-btn" id="<?php echo esc_attr($form_id); ?>-promo-apply"><?php _e('Apply', 'ihowz-theme'); ?></button>
                    </div>
                    <div class="promo-message" id="<?php echo esc_attr($form_id); ?>-promo-message" role="status" aria-live="polite"></div>
                </div>

                <!-- 8. Submit Button -->
                <div class="join-now-submit-wrapper">
                    <button type="submit" class="join-now-submit-btn ihowz-btn-cta" id="<?php echo esc_attr($form_id); ?>-submit">
                        <span class="btn-text"><?php _e('Join Now & Pay', 'ihowz-theme'); ?></span>
                        <span class="btn-spinner" style="display:none;">
                            <svg class="spinner-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                            </svg>
                        </span>
                    </button>
                </div>

                <!-- Messages -->
                <div id="<?php echo esc_attr($form_id); ?>-messages" class="join-now-messages" role="status" aria-live="polite"></div>
            </form>
        </div>
    </div>
</section>