# iHowz Session Management - Test Plan

## Implementation Summary

**Files Modified:**
- `/var/www/html/wp-content/themes/ihowz/inc/session-management.php` (NEW)
- `/var/www/html/wp-content/themes/ihowz/functions.php` (UPDATED - added require statement)

**Features Implemented:**
1. Session tracking with IP address, timestamp, and user agent
2. Automatic detection of logins from different IP addresses
3. Custom confirmation page showing existing session details
4. Session termination when user confirms new login
5. Ability to cancel new login and keep existing session

**How It Works:**
1. When a user logs in, their IP, timestamp, and user agent are stored in user meta
2. On next login attempt, system checks if IP address is different
3. If different IP detected, redirect to confirmation page showing:
   - Existing session's IP address
   - Last active timestamp
   - Browser/device information
4. User can either:
   - **Confirm:** Terminates old session, allows new login
   - **Cancel:** Aborts new login, keeps existing session active
5. On logout, session info is cleared

## Test User Credentials

**Username:** testuser
**Password:** Test123!
**Email:** testuser@ihowz.test

## Testing Steps

### Test 1: Normal Login (Same IP)
1. Open browser and go to https://ihowz.greatnew.site/wp-login.php
2. Login with testuser / Test123!
3. Should login successfully without any confirmation

### Test 2: Different IP Login Detection
1. Login from first browser/IP (use testuser)
2. Open different browser OR use VPN to change IP
3. Attempt to login again with same user
4. **Expected:** Confirmation page should appear showing:
   - Warning message
   - Existing session IP address
   - Timestamp of existing session
   - Browser/device information
   - Two buttons: "Confirm & Login" and "Cancel"

### Test 3: Confirm New Login
1. Follow Test 2 steps to get confirmation page
2. Click "Confirm & Login" button
3. **Expected:** 
   - Old session should be terminated
   - New login should succeed
   - First browser should be logged out

### Test 4: Cancel New Login
1. Follow Test 2 steps to get confirmation page
2. Click "Cancel" button
3. **Expected:**
   - New login should be aborted
   - Redirected back to login page
   - Original session remains active

### Test 5: Session Cleanup on Logout
1. Login with testuser
2. Logout
3. Login again from same IP
4. **Expected:** Should login without confirmation (session info was cleared)

## Technical Details

**User Meta Key:** `_ihowz_active_session`
**Transient Key:** `ihowz_pending_login_{user_id}`
**Transient TTL:** 10 minutes

**Stored Session Data:**
```php
array(
    'ip' => '192.168.1.1',
    'timestamp' => 1707848400,
    'user_agent' => 'Mozilla/5.0...'
)
```

## Browser/Device Detection

The system can identify:
- Chrome, Firefox, Safari, Edge, Internet Explorer
- Mobile, Tablet, Desktop devices

Example output: "Chrome on Desktop" or "Safari on Mobile"

## Security Considerations

1. **IP-based detection:** May have issues with users on dynamic IPs or VPNs
2. **Session tokens:** Old session tokens are destroyed on confirmation
3. **Timeout:** Confirmation must be completed within 10 minutes
4. **CSRF protection:** Uses WordPress nonces for form submission

## Troubleshooting

**Issue:** Confirmation page not showing
- Check if session info is being stored: `wp user meta list {user_id} --allow-root`
- Check WordPress error log for PHP errors

**Issue:** Old session not terminating
- Verify `WP_Session_Tokens::destroy_all()` is being called
- Check user meta after confirmation

**Issue:** Infinite redirect loop
- Clear transients: `wp transient delete ihowz_pending_login_{user_id} --allow-root`
- Clear browser cookies

## Production Considerations

1. **User Experience:** Consider adding email notifications when session is terminated
2. **Logging:** Add logging for security audits
3. **Exceptions:** May want to whitelist certain IPs (e.g., office network)
4. **Timing:** Consider allowing shorter grace period for IP changes
5. **Mobile users:** May need to be more lenient with mobile IPs

## Files to Review

```bash
# View session management code
sudo docker exec ihowz-wordpress cat /var/www/html/wp-content/themes/ihowz/inc/session-management.php

# Check if file is included
sudo docker exec ihowz-wordpress grep -n "session-management" /var/www/html/wp-content/themes/ihowz/functions.php

# Test for syntax errors
sudo docker exec ihowz-wordpress php -l /var/www/html/wp-content/themes/ihowz/inc/session-management.php
```

## Future Enhancements

1. **Email notifications:** Send email when session is terminated
2. **Session history:** Keep log of all login attempts and terminations
3. **Admin dashboard:** Show active sessions and allow manual termination
4. **Grace period:** Allow short time window for IP changes (e.g., 5 minutes)
5. **Geolocation:** Show approximate location of existing session
6. **2FA integration:** Require additional verification for new IP logins

