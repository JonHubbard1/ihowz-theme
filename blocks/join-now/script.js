/**
 * Join Now Block - Frontend Script
 *
 * Handles form validation, payment method toggle,
 * Stripe Card Elements, and Bacs Direct Debit via Stripe.
 *
 * @package iHowz Theme
 */

(function () {
    'use strict';

    document.querySelectorAll('.join-now-form').forEach(function (form) {
        initJoinForm(form);
    });

    function initJoinForm(form) {
        var formId = form.id;
        if (!formId) return;

        var dataKey = 'ihowzJoinData_' + formId;
        var config = window[dataKey] || {};
        var stripe = null;
        var cardElement = null;
        var currentMethod = 'card';
        var appliedPromo = null; // { code, final_amount, discount_amount, is_free } once a code is validated

        // ==========================================
        // Payment Method Toggle (always active)
        // ==========================================
        var methodToggle = form.querySelectorAll('.payment-method-option');
        var cardSection = document.getElementById(formId + '-payment-card');
        var bacsSection = document.getElementById(formId + '-payment-bacs');
        var methodInput = document.getElementById(formId + '-payment-method');

        methodToggle.forEach(function (btn) {
            btn.addEventListener('click', function () {
                methodToggle.forEach(function (b) { b.classList.remove('active'); });
                btn.classList.add('active');
                currentMethod = btn.dataset.method;
                if (methodInput) methodInput.value = currentMethod;

                if (currentMethod === 'card') {
                    if (cardSection) cardSection.style.display = '';
                    if (bacsSection) bacsSection.style.display = 'none';
                } else {
                    if (cardSection) cardSection.style.display = 'none';
                    if (bacsSection) bacsSection.style.display = '';
                }
            });
        });

        // ==========================================
        // Membership type selection (always active)
        // ==========================================
        var typeCards = form.querySelectorAll('.membership-type-card');
        var amountDisplays = [
            document.getElementById(formId + '-payment-amount'),
            document.getElementById(formId + '-payment-amount-bacs')
        ];

        // Conditional sections toggled by membership type.
        var companySection = form.querySelector('.join-now-company-section');
        var secondarySection = form.querySelector('.join-now-secondary-member-section');
        var companyInput = document.getElementById(formId + '-company');
        var secondaryInputs = {
            title: document.getElementById(formId + '-secondary-title'),
            first_name: document.getElementById(formId + '-secondary-first-name'),
            last_name: document.getElementById(formId + '-secondary-last-name'),
            email: document.getElementById(formId + '-secondary-email'),
            phone: document.getElementById(formId + '-secondary-phone')
        };

        typeCards.forEach(function (card) {
            card.addEventListener('click', function () {
                var radio = card.querySelector('input[type="radio"]');
                if (radio) {
                    radio.checked = true;
                    updateSelection();
                }
            });
        });

        function updateSelection() {
            var selected = form.querySelector('input[name="membership_type_id"]:checked');
            if (!selected) return;

            // Switching membership type changes the price, so any previously
            // applied promo (computed against the old price) no longer applies.
            if (appliedPromo) {
                clearAppliedPromo();
            }

            typeCards.forEach(function (card) {
                var radio = card.querySelector('input[type="radio"]');
                if (radio && radio.checked) {
                    card.classList.add('selected');
                } else {
                    card.classList.remove('selected');
                }
            });

            var selectedCard = form.querySelector('.membership-type-card.selected');
            if (selectedCard) {
                var price = parseFloat(selectedCard.dataset.price || 0);
                var formatted = '\u00a3' + price.toFixed(2);
                amountDisplays.forEach(function (el) {
                    if (el) el.textContent = formatted;
                });
            }

            // Show/hide conditional sections based on the selected type.
            var selectedTypeId = parseInt(selected.value, 10);
            var isDual = config.dual_type_ids.indexOf(selectedTypeId) !== -1;
            var isCorporate = config.corporate_type_ids.indexOf(selectedTypeId) !== -1;

            if (companySection) {
                companySection.style.display = isCorporate ? 'block' : 'none';
            }
            if (companyInput) {
                companyInput.required = isCorporate;
            }

            if (secondarySection) {
                secondarySection.style.display = isDual ? 'block' : 'none';
            }
            Object.keys(secondaryInputs).forEach(function (key) {
                var input = secondaryInputs[key];
                if (input) {
                    // First and last name are required for dual; title/email/phone are optional.
                    input.required = isDual && (key === 'first_name' || key === 'last_name');
                }
            });
        }
        updateSelection();

        // ==========================================
        // Promotional code
        // ==========================================
        var promoInput = document.getElementById(formId + '-promo-code');
        var promoApplyBtn = document.getElementById(formId + '-promo-apply');
        var promoMessage = document.getElementById(formId + '-promo-message');

        function formatMoney(n) {
            return '\u00a3' + parseFloat(n).toFixed(2);
        }

        function setAmountDisplays(amount) {
            var formatted = formatMoney(amount);
            amountDisplays.forEach(function (el) {
                if (el) el.textContent = formatted;
            });
        }

        // The message may contain the "Remove" link, so render as HTML. Content
        // is built from server-controlled values (discount label + amount) plus
        // a fixed Remove anchor - no user input - so innerHTML is safe here.
        function showPromoMessage(html, type) {
            if (!promoMessage) return;
            promoMessage.innerHTML = html || '';
            promoMessage.className = 'promo-message' + (type ? ' promo-message-' + type : '');
        }

        function clearAppliedPromo() {
            appliedPromo = null;
            if (promoInput) promoInput.value = '';
            showPromoMessage('');
            updateAmountFromSelection();
        }

        // Recompute the (full-price) display from the selected type card.
        function updateAmountFromSelection() {
            var selectedCard = form.querySelector('.membership-type-card.selected');
            if (selectedCard) {
                setAmountDisplays(parseFloat(selectedCard.dataset.price || 0));
            }
        }

        function applyPromoCode() {
            if (!promoInput) return;
            var code = promoInput.value.trim();

            if (!code) {
                if (appliedPromo) {
                    clearAppliedPromo();
                } else {
                    showPromoMessage('Please enter a promo code.', 'error');
                }
                return;
            }

            var selected = form.querySelector('input[name="membership_type_id"]:checked');
            if (!selected) {
                showPromoMessage('Please choose a membership type first.', 'error');
                return;
            }

            promoApplyBtn.disabled = true;
            var body = new FormData();
            body.append('action', 'ihowz_validate_promo_code');
            body.append('nonce', config.nonce);
            body.append('membership_type_id', selected.value);
            body.append('promo_code', code);

            fetch(config.ajax_url, { method: 'POST', body: body })
                .then(function (r) { return r.json(); })
                .then(function (result) {
                    if (!result.success) {
                        appliedPromo = null;
                        updateAmountFromSelection();
                        showPromoMessage((result.data && result.data.message) || 'That promo code is not valid.', 'error');
                        return;
                    }
                    var d = result.data;
                    appliedPromo = {
                        code: d.code,
                        final_amount: parseFloat(d.final_amount),
                        discount_amount: parseFloat(d.discount_amount),
                        is_free: !!d.is_free
                    };
                    setAmountDisplays(d.final_amount);
                    showPromoMessage(
                        (d.message || 'Code applied.') + ' <a href="#" class="promo-remove-link">Remove</a>',
                        'success'
                    );
                })
                .catch(function () {
                    showPromoMessage('Could not validate the promo code. Please try again.', 'error');
                })
                .finally(function () {
                    promoApplyBtn.disabled = false;
                });
        }

        if (promoApplyBtn) {
            promoApplyBtn.addEventListener('click', function (e) {
                e.preventDefault();
                applyPromoCode();
            });
        }

        if (promoInput) {
            // Editing the code after applying clears the applied state.
            promoInput.addEventListener('input', function () {
                if (appliedPromo && promoInput.value.toUpperCase() !== appliedPromo.code) {
                    clearAppliedPromo();
                }
            });
            promoInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    applyPromoCode();
                }
            });
        }

        // Delegate clicks on the "Remove" link inside the promo message.
        form.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('promo-remove-link')) {
                e.preventDefault();
                clearAppliedPromo();
            }
        });

        // Pre-fill + auto-apply a promo code resolved server-side from the page
        // URL / query param (see render.php). Runs after updateSelection() so a
        // membership type is already selected when the validation call fires.
        if (config.preselected_promo_code && promoInput) {
            promoInput.value = config.preselected_promo_code;
            applyPromoCode();
        }

        // ==========================================
        // Stripe-dependent setup (bail gracefully if no key)
        // ==========================================
        if (!config.stripe_key) {
            console.warn('iHowz Join Now: Stripe key not configured for form', formId);
            showMessage(form, 'Payment processing is not configured. Please contact support.', 'error');
            disableSubmit(form);
            return;
        }

        stripe = Stripe(config.stripe_key);

        // Card Elements
        var elements = stripe.elements();
        var cardContainer = document.getElementById(formId + '-card-element');
        var cardErrors = document.getElementById(formId + '-card-errors');

        if (cardContainer) {
            cardElement = elements.create('card', {
                style: {
                    base: {
                        fontSize: '16px',
                        fontFamily: '"Mona Sans", "Helvetica Neue", Helvetica, Arial, sans-serif',
                        color: '#263238',
                        '::placeholder': { color: '#BDBDBD' }
                    },
                    invalid: { color: '#D32F2F', iconColor: '#D32F2F' }
                },
                hidePostalCode: true
            });
            cardElement.mount('#' + formId + '-card-element');
            cardElement.on('change', function (event) {
                if (event.error) {
                    cardErrors.textContent = event.error.message;
                } else {
                    cardErrors.textContent = '';
                }
            });
        }

        // ==========================================
        // Form Submission
        // ==========================================
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            clearMessages(form);

            if (!validateForm(form)) return;

            setLoading(form, true);

            // A promo code that brings the total below the Stripe minimum skips
            // Stripe entirely and completes a free signup server-side.
            if (appliedPromo && appliedPromo.is_free) {
                submitFreeFlow(form, config);
            } else if (currentMethod === 'bacs_debit' && config.bacs_debit_enabled) {
                submitBacsFlow(form, stripe, config);
            } else {
                submitCardFlow(form, stripe, config, cardElement);
            }
        });

        // ==========================================
        // FREE SIGNUP FLOW (100% / sub-minimum promo code)
        // ==========================================
        function submitFreeFlow(form, config) {
            var formData = new FormData(form);
            formData.append('action', 'ihowz_complete_free_join');
            formData.append('nonce', config.nonce);

            fetch(config.ajax_url, { method: 'POST', body: formData })
                .then(function (r) { return r.json(); })
                .then(function (result) {
                    if (!result.success) {
                        throw new Error(result.data && result.data.message ? result.data.message : 'Failed to complete signup.');
                    }
                    handleSuccess(form, config, result);
                })
                .catch(function (error) {
                    showMessage(form, error.message, 'error');
                })
                .finally(function () {
                    setLoading(form, false);
                });
        }

        // ==========================================
        // CARD FLOW
        // ==========================================
        function submitCardFlow(form, stripe, config, cardElement) {
            var formData = new FormData(form);
            formData.append('action', 'ihowz_create_join_intent');
            formData.append('nonce', config.nonce);

            fetch(config.ajax_url, { method: 'POST', body: formData })
            .then(function (r) { return r.json(); })
            .then(function (result) {
                if (!result.success) {
                    throw new Error(result.data && result.data.message ? result.data.message : 'Failed to initialise payment.');
                }

                return stripe.confirmCardPayment(result.data.client_secret, {
                    payment_method: {
                        card: cardElement,
                        billing_details: {
                            name: formData.get('first_name') + ' ' + formData.get('last_name'),
                            email: formData.get('email'),
                            phone: formData.get('phone'),
                            address: {
                                line1: formData.get('address'),
                                city: formData.get('town'),
                                postal_code: formData.get('postcode'),
                                country: 'GB'
                            }
                        }
                    }
                }).then(function (stripeResult) {
                    if (stripeResult.error) throw new Error(stripeResult.error.message);
                    if (stripeResult.paymentIntent.status !== 'succeeded') {
                        throw new Error('Payment was not successful. Please try again.');
                    }

                    var confirmData = new FormData(form);
                    confirmData.append('action', 'ihowz_confirm_join_payment');
                    confirmData.append('nonce', config.nonce);
                    confirmData.append('payment_intent_id', stripeResult.paymentIntent.id);
                    confirmData.append('stripe_payment_method_id', stripeResult.paymentIntent.payment_method);

                    return fetch(config.ajax_url, { method: 'POST', body: confirmData })
                        .then(function (r) { return r.json(); });
                });
            })
            .then(function (confirmResult) {
                if (!confirmResult.success) {
                    throw new Error(confirmResult.data && confirmResult.data.message ? confirmResult.data.message : 'Failed to complete signup.');
                }
                handleSuccess(form, config, confirmResult);
            })
            .catch(function (error) {
                showMessage(form, error.message, 'error');
            })
            .finally(function () {
                setLoading(form, false);
            });
        }

        // ==========================================
        // BACS DIRECT DEBIT FLOW
        // ==========================================
        function submitBacsFlow(form, stripe, config) {
            var formData = new FormData(form);
            formData.append('action', 'ihowz_create_join_bacs_intent');
            formData.append('nonce', config.nonce);

            fetch(config.ajax_url, { method: 'POST', body: formData })
            .then(function (r) { return r.json(); })
            .then(function (result) {
                if (!result.success) {
                    throw new Error(result.data && result.data.message ? result.data.message : 'Failed to set up Direct Debit.');
                }

                var clientSecret = result.data.client_secret;
                var setupIntentId = result.data.setup_intent_id;

                var sortCode = document.getElementById(formId + '-sort-code').value.replace(/[^0-9]/g, '');
                var accountNumber = document.getElementById(formId + '-account-number').value.replace(/[^0-9]/g, '');
                var accountName = document.getElementById(formId + '-account-name').value.trim();

                return stripe.createPaymentMethod({
                    type: 'bacs_debit',
                    bacs_debit: { sort_code: sortCode, account_number: accountNumber },
                    billing_details: {
                        name: accountName || (formData.get('first_name') + ' ' + formData.get('last_name')),
                        email: formData.get('email'),
                        address: {
                            line1: formData.get('address'),
                            city: formData.get('town'),
                            postal_code: formData.get('postcode'),
                        country: 'GB'
                        }
                    }
                }).then(function (pmResult) {
                    if (pmResult.error) throw new Error(pmResult.error.message);

                    var paymentMethodId = pmResult.paymentMethod.id;

                    return stripe.confirmBacsDebitSetup(clientSecret, {
                        payment_method: paymentMethodId
                    }).then(function (setupResult) {
                        if (setupResult.error) throw new Error(setupResult.error.message);

                        var confirmData = new FormData(form);
                        confirmData.append('action', 'ihowz_confirm_join_bacs_payment');
                        confirmData.append('nonce', config.nonce);
                        confirmData.append('setup_intent_id', setupIntentId);
                        confirmData.append('payment_method_id', paymentMethodId);

                        return fetch(config.ajax_url, { method: 'POST', body: confirmData })
                            .then(function (r) { return r.json(); });
                    });
                });
            })
            .then(function (confirmResult) {
                if (!confirmResult.success) {
                    throw new Error(confirmResult.data && confirmResult.data.message ? confirmResult.data.message : 'Failed to complete Direct Debit setup.');
                }
                handleSuccess(form, config, confirmResult);
            })
            .catch(function (error) {
                showMessage(form, error.message, 'error');
            })
            .finally(function () {
                setLoading(form, false);
            });
        }

        function handleSuccess(form, config, confirmResult) {
            showMessage(form, config.success_message || 'Thank you for joining iHowz! Your membership is now active.', 'success');
            form.reset();
            updateSelection();
            if (cardElement) cardElement.clear();

            if (confirmResult.data && confirmResult.data.redirect_url) {
                setTimeout(function () {
                    window.location.href = confirmResult.data.redirect_url;
                }, 2000);
            }
        }
    }

    // ==========================================
    // Validation (shared)
    // ==========================================
    function validateForm(form) {
        var valid = true;
        var activeToggle = form.querySelector('.payment-method-option.active');
        var currentMethod = activeToggle ? activeToggle.dataset.method : 'card';

        form.querySelectorAll('[required]').forEach(function (field) {
            // Skip required bacs fields when card is selected, and vice versa
            if (currentMethod === 'card' && field.closest('.payment-section-bacs')) return;
            if (currentMethod === 'bacs_debit' && field.closest('.payment-section-card')) return;

            field.classList.remove('input-error');
            if (!field.value.trim()) {
                field.classList.add('input-error');
                valid = false;
            }
            if (field.type === 'email' && field.value) {
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(field.value)) {
                    field.classList.add('input-error');
                    valid = false;
                }
            }
        });

        // UK postcode — only enforced when the country is the UK.
        var postcode = form.querySelector('input[name="postcode"]');
        var country = form.querySelector('input[name="country"]');
        if (postcode && postcode.value.trim()) {
            var isUK = !country || !country.value.trim() || /united kingdom|^gb$|^uk$|great britain/i.test(country.value.trim());
            if (isUK) {
                var pcVal = postcode.value.replace(/\s/g, '').toUpperCase();
                if (!pcVal.match(/^[A-Z]{1,2}[0-9R][0-9A-Z]?[0-9][ABD-HJLNP-UW-Z]{2}$/)) {
                    postcode.classList.add('input-error');
                    showMessage(form, 'Please enter a valid UK postcode.', 'error');
                    valid = false;
                }
            }
        }

        // Phone — leading 0 must pass UK format; leading + accepts international.
        var phone = form.querySelector('input[name="phone"]');
        if (phone && phone.value.trim()) {
            var pv = phone.value.trim();
            var phoneOk = false;
            if (pv.charAt(0) === '+') {
                phoneOk = !!pv.replace(/[^0-9]/g, '').match(/^[1-9][0-9]{6,14}$/);
            } else if (pv.charAt(0) === '0') {
                phoneOk = !!pv.replace(/[^0-9]/g, '').match(/^0[1-9][0-9]{8,9}$/);
            }
            if (!phoneOk) {
                phone.classList.add('input-error');
                showMessage(form, 'Please enter a valid phone number. UK numbers start with 0 (e.g. 07123 456789); international numbers start with +.', 'error');
                valid = false;
            }
        }

        var password = form.querySelector('input[name="password"]');
        var passwordConfirm = form.querySelector('input[name="password_confirm"]');
        if (password && passwordConfirm) {
            if (password.value !== passwordConfirm.value) {
                passwordConfirm.classList.add('input-error');
                showMessage(form, 'Passwords do not match.', 'error');
                valid = false;
            }
            if (password.value.length < 12) {
                password.classList.add('input-error');
                showMessage(form, 'Password must be at least 12 characters long.', 'error');
                valid = false;
            } else if (!/[A-Z]/.test(password.value)) {
                password.classList.add('input-error');
                showMessage(form, 'Password must contain at least one uppercase letter.', 'error');
                valid = false;
            } else if (!/[0-9]/.test(password.value)) {
                password.classList.add('input-error');
                showMessage(form, 'Password must contain at least one number.', 'error');
                valid = false;
            }
        }

        var terms = form.querySelector('input[name="terms_accepted"]');
        if (terms && !terms.checked) {
            showMessage(form, 'Please accept the Terms & Conditions and Privacy Policy.', 'error');
            valid = false;
        }

        var membershipType = form.querySelector('input[name="membership_type_id"]:checked');
        if (!membershipType) {
            showMessage(form, 'Please select a membership type.', 'error');
            valid = false;
        }

        if (currentMethod === 'bacs_debit') {
            var sortCode = document.getElementById(form.id + '-sort-code');
            var accountNumber = document.getElementById(form.id + '-account-number');
            var accountName = document.getElementById(form.id + '-account-name');

            if (!sortCode || !sortCode.value.replace(/[^0-9]/g, '').match(/^\d{6}$/)) {
                if (sortCode) sortCode.classList.add('input-error');
                showMessage(form, 'Please enter a valid sort code (6 digits).', 'error');
                valid = false;
            }
            if (!accountNumber || !accountNumber.value.replace(/[^0-9]/g, '').match(/^\d{6,8}$/)) {
                if (accountNumber) accountNumber.classList.add('input-error');
                showMessage(form, 'Please enter a valid account number (6-8 digits).', 'error');
                valid = false;
            }
            if (!accountName || accountName.value.trim().length < 2) {
                if (accountName) accountName.classList.add('input-error');
                showMessage(form, 'Please enter the account holder name.', 'error');
                valid = false;
            }
        }

        if (!valid) {
            var firstError = form.querySelector('.input-error');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
        }

        return valid;
    }

    function setLoading(form, loading) {
        var submitBtn = form.querySelector('.join-now-submit-btn');
        if (!submitBtn) return;
        var btnText = submitBtn.querySelector('.btn-text');
        var btnSpinner = submitBtn.querySelector('.btn-spinner');
        submitBtn.disabled = loading;
        if (btnText) btnText.style.display = loading ? 'none' : 'inline';
        if (btnSpinner) btnSpinner.style.display = loading ? 'inline-flex' : 'none';
    }

    function disableSubmit(form) {
        var submitBtn = form.querySelector('.join-now-submit-btn');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.6';
        }
    }

    function showMessage(form, message, type) {
        var messagesContainer = form.querySelector('.join-now-messages');
        if (!messagesContainer) return;
        var messageEl = document.createElement('div');
        messageEl.className = 'join-now-message join-now-message-' + type;
        messageEl.textContent = message;
        messagesContainer.appendChild(messageEl);
        if (type === 'error') {
            messagesContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }

    function clearMessages(form) {
        var messagesContainer = form.querySelector('.join-now-messages');
        if (messagesContainer) messagesContainer.innerHTML = '';
        form.querySelectorAll('.input-error').forEach(function (el) {
            el.classList.remove('input-error');
        });
    }
})();
