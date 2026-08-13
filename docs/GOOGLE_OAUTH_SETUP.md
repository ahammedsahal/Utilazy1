# Google Sign-In Setup Guide

## 1. Google Cloud Console Configuration
1. Go to Google Cloud Console (console.cloud.google.com).
2. Create a new project called **Utilazy**.
3. Navigate to **APIs & Services -> OAuth consent screen**. Configure User Type as External, input brand emails.
4. Navigate to **Credentials -> Create Credentials -> OAuth client ID**.
5. Select Application Type: **Web application**.
6. Under **Authorized redirect URIs**, input:
   `https://utilazy.com/auth/google/callback` (or your localhost equivalent).
7. Retrieve **Client ID** and **Client Secret**.

## 2. Environment Setup
Update `.env` configuration keys:
```ini
GOOGLE_CLIENT_ID=your_google_id
GOOGLE_CLIENT_SECRET=your_google_secret
GOOGLE_REDIRECT_URI=https://utilazy.com/auth/google/callback
```
