# Sign In with Apple Setup Guide

## 1. Apple Developer Portal Configuration
1. Navigate to Apple Developer Account portal (developer.apple.com).
2. Under **Certificates, Identifiers & Profiles**, create an App ID.
3. Enable **Sign In with Apple** capability.
4. Create a Service ID and enter your primary redirect URI:
   `https://utilazy.com/auth/apple/callback`.
5. Create an Apple Sign-In Private Key and download the `.p8` file.

## 2. Environment Setup
Update `.env` configuration keys:
```ini
APPLE_CLIENT_ID=your_service_id
APPLE_TEAM_ID=your_apple_team_id
APPLE_KEY_ID=your_apple_key_id
APPLE_REDIRECT_URI=https://utilazy.com/auth/apple/callback
```
Supports random "Hide My Email" relayed addresses.
