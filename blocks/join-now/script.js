/**
 * Join Now Block - Frontend Script
 *
 * Handles form validation, Stripe card element, and AJAX submission.
 *
 * @package iHowz Theme
 */

(function () {
    'use strict';

    // Find all join-now forms on the page
    document.querySelectorAll('.join-now-form').forEach(function (form) {
        initJoinForm(form);
    });

    function initJoinForm(form) {
        var formId = form.id;
        if (!formId) return;

        // Get localized data
        var dataKey = 'ihowzJoinData_' + formId;
        var config = window[dataKey] || {};

        if (!config.stripe_key) {
            console.warn('iHowz Join Now: Stripe key not configured for form', formId);
            showMessage(form, 'Payment processing is not configured. Please contact support.', 'error');
            disableSubmit(form);
            return;
        }

        // Initialize Stripe
        var stripe = Stripe(config.stripe_key);
        var elements = stripe.elements();
        var cardElement = elements.create('card', {
            style: {
                base: {
                    fontSize: '16px',
                    fontFamily: '"Mona Sans", "Helvetica Neue", Helvetica, Arial, sans-serif',
                    color: '#263238',
                    '::placeholder': {
                        color: '#BDBDBD'
                    }
                },
                invalid: {
                    color: '#D32F2F',
                    iconColor: '#D32F2F'
                }
            },
            hidePostalCode: true
        });

        var cardContainer = document.getElementById(formId + '-card-element');
        var cardErrors = document.getElementById(formId + '-card-errors');

        if (cardContainer) {
            cardElement.mount('#' + formId + '-card-element');
        }

        cardElement.on('change', function (event) {
            if (event.error) {
                cardErrors.textContent = event.error.message;
            } else {
                cardErrors.textContent = '';
            }
        });

        // Membership type selection
        var typeCards = form.querySelectorAll('.membership-type-card');
        var amountDisplay = document.getElementById(formId + '-payment-amount');

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

            typeCards.forEach(function (card) {
                var radio = card.querySelector('input[type="radio"]');
                if (radio && radio.checked) {
                    card.classList.add('selected');
                } else {
                    card.classList.remove('selected');
                }
            });

            var selectedCard = form.querySelector('.membership-type-card.selected');
            if (selectedCard && amountDisplay) {
                var price = parseFloat(selectedCard.dataset.price || 0);
                amountDisplay.textContent = '£' + price.toFixed(2);
            }
        }

        // Initial selection
        updateSelection();

        // Form submission
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            clearMessages(form);

            if (!validateForm(form)) {
                return;
            }

            setLoading(form, true);

            // Step 1: Create payment intent via AJAX
            var formData = new FormData(form);
            formData.append('action', 'ihowz_create_join_intent');
            formData.append('nonce', config.nonce);

            fetch(config.ajax_url, {
                method: 'POST',
                body: formData
            })
            .then(function (response) { return response.json(); })
            .then(function (result) {
                if (!result.success) {
                    throw new Error(result.data?.message || 'Failed to initialize payment.');
                }

                var clientSecret = result.data.client_secret;

                // Step 2: Confirm card payment with Stripe
                return stripe.confirmCardPayment(clientSecret, {
                    payment_method: {
                        card: cardElement,
                        billing_details: {
                            name: formData.get('first_name') + ' ' + formData.get('last_name'),
                            email: formData.get('email'),
                            phone: formData.get('phone'),
                            address: {
                                line1: formData.get('address'),
                                city: formData.get('town'),
                                postal_code: formData.get('postcode')
                            }
                        }
                    }
                });
            })
            .then(function (stripeResult) {
                if (stripeResult.error) {
                    throw new Error(stripeResult.error.message);
                }

                if (stripeResult.paymentIntent.status !== 'succeeded') {
                    throw new Error('Payment was not successful. Please try again.');
                }

                // Step 3: Confirm signup on server
                var confirmData = new FormData(form);
                confirmData.append('action', 'ihowz_confirm_join_payment');
                confirmData.append('nonce', config.nonce);
                confirmData.append('payment_intent_id', stripeResult.paymentIntent.id);

                return fetch(config.ajax_url, {
                    method: 'POST',
                    body: confirmData
                });
            })
            .then(function (response) { return response.json(); })
            .then(function (confirmResult) {
                if (!confirmResult.success) {
                    throw new Error(confirmResult.data?.message || 'Failed to complete signup.');
                }

                // Success
                showMessage(form, config.success_message || 'Thank you for joining iHowz! Your membership is now active.', 'success');
                form.reset();
                updateSelection();
                cardElement.clear();

                // Redirect if provided
                if (confirmResult.data?.redirect_url) {
                    setTimeout(function () {
                        window.location.href = confirmResult.data.redirect_url;
                    }, 2000);
                }
            })
            .catch(function (error) {
                showMessage(form, error.message, 'error');
            })
            .finally(function () {
                setLoading(form, false);
            });
        });
    }

    function validateForm(form) {
        var valid = true;
        var requiredFields = form.querySelectorAll('[required]');

        requiredFields.forEach(function (field) {
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

        // Password match
        var password = form.querySelector('input[name="password"]');
        var passwordConfirm = form.querySelector('input[name="password_confirm"]');
        if (password && passwordConfirm) {
            if (password.value !== passwordConfirm.value) {
                passwordConfirm.classList.add('input-error');
                showMessage(form, 'Passwords do not match.', 'error');
                valid = false;
            }
            if (password.value.length < 8) {
                password.classList.add('input-error');
                showMessage(form, 'Password must be at least 8 characters.', 'error');
                valid = false;
            }
        }

        // Terms checkbox
        var terms = form.querySelector('input[name="terms_accepted"]');
        if (terms && !terms.checked) {
            showMessage(form, 'Please accept the Terms & Conditions and Privacy Policy.', 'error');
            valid = false;
        }

        // Membership type selected
        var membershipType = form.querySelector('input[name="membership_type_id"]:checked');
        if (!membershipType) {
            showMessage(form, 'Please select a membership type.', 'error');
            valid = false;
        }

        if (!valid) {
            // Scroll to first error
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

        // Auto-remove info messages, keep error/success
        if (type === 'info') {
            setTimeout(function () {
                messageEl.remove();
            }, 5000);
        }

        // Scroll to message if error
        if (type === 'error') {
            messagesContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }

    function clearMessages(form) {
        var messagesContainer = form.querySelector('.join-now-messages');
        if (messagesContainer) {
            messagesContainer.innerHTML = '';
        }

        // Clear input errors
        form.querySelectorAll('.input-error').forEach(function (el) {
            el.classList.remove('input-error');
        });
    }
})();
