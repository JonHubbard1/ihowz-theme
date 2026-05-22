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

// Get membership types from the plugin
$membership_types = array();
if (function_exists('IHowz_Membership_Module') || class_exists('IHowz_Membership_Module')) {
    global $wpdb;
    $membership_types = $wpdb->get_results(
        "SELECT id, name, slug, price, duration_months, description, benefits FROM {$wpdb->prefix}ihowz_membership_types WHERE is_active = 1 AND is_join_visible = 1 ORDER BY price ASC"
    );
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

// Localize data for frontend JS
$join_data = array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('ihowz_join_nonce'),
    'stripe_key' => $stripe_publishable_key,
    'stripe_mode' => $stripe_mode,
    'form_id' => $form_id,
    'success_message' => $success_message,
    'bacs_debit_enabled' => $bacs_debit_enabled,
    'membership_types' => array_map(function($type) {
        return array(
            'id' => $type->id,
            'name' => $type->name,
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

                <!-- Membership Type Selection -->
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

                <!-- Payment Method Selector -->
                <?php if ($bacs_debit_enabled) : ?>
                <div class="join-now-field join-now-field-full">
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

                <!-- Card Payment Section -->
                <div class="payment-section payment-section-card" id="<?php echo esc_attr($form_id); ?>-payment-card">
                    <div class="join-now-payment-section">
                        <div class="join-now-payment-summary">
                            <span class="payment-label"><?php _e('Total to pay:', 'ihowz-theme'); ?></span>
                            <span class="payment-amount" id="<?php echo esc_attr($form_id); ?>-payment-amount">&pound;0.00</span>
                        </div>
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

                <!-- Bacs Direct Debit Section -->
                <?php if ($bacs_debit_enabled && $stripe_publishable_key) : ?>
                <div class="payment-section payment-section-bacs" id="<?php echo esc_attr($form_id); ?>-payment-bacs" style="display:none;">
                    <div class="join-now-payment-section">
                        <div class="join-now-payment-summary">
                            <span class="payment-label"><?php _e('Total to pay:', 'ihowz-theme'); ?></span>
                            <span class="payment-amount" id="<?php echo esc_attr($form_id); ?>-payment-amount-bacs">&pound;0.00</span>
                        </div>
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

                <!-- Personal Details -->
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

                    <div class="join-now-field">
                        <label for="<?php echo esc_attr($form_id); ?>-company">
                            <?php _e('Company Name', 'ihowz-theme'); ?>
                        </label>
                        <input type="text"
                               id="<?php echo esc_attr($form_id); ?>-company"
                               name="company_name"
                               class="join-now-input"
                               placeholder="<?php esc_attr_e('Optional', 'ihowz-theme'); ?>"
                               autocomplete="organization">
                    </div>
                </div>

                <!-- Address -->
                <div class="join-now-field join-now-field-full">
                    <label for="<?php echo esc_attr($form_id); ?>-address">
                        <?php _e('Address', 'ihowz-theme'); ?> <span class="required">*</span>
                    </label>
                    <input type="text"
                           id="<?php echo esc_attr($form_id); ?>-address"
                           name="address"
                           class="join-now-input"
                           placeholder="<?php esc_attr_e('Street address', 'ihowz-theme'); ?>"
                           required
                           autocomplete="street-address">
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
                </div>

                <!-- Password -->
                <div class="join-now-field-group">
                    <div class="join-now-field">
                        <label for="<?php echo esc_attr($form_id); ?>-password">
                            <?php _e('Password', 'ihowz-theme'); ?> <span class="required">*</span>
                        </label>
                        <input type="password"
                               id="<?php echo esc_attr($form_id); ?>-password"
                               name="password"
                               class="join-now-input"
                               placeholder="<?php esc_attr_e('Min 8 characters', 'ihowz-theme'); ?>"
                               required
                               minlength="8"
                               autocomplete="new-password">
                    </div>

                    <div class="join-now-field">
                        <label for="<?php echo esc_attr($form_id); ?>-password-confirm">
                            <?php _e('Confirm Password', 'ihowz-theme'); ?> <span class="required">*</span>
                        </label>
                        <input type="password"
                               id="<?php echo esc_attr($form_id); ?>-password-confirm"
                               name="password_confirm"
                               class="join-now-input"
                               placeholder="<?php esc_attr_e('Re-enter password', 'ihowz-theme'); ?>"
                               required
                               minlength="8"
                               autocomplete="new-password">
                    </div>
                </div>

                <!-- Consent -->
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

                <!-- Submit Button -->
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
