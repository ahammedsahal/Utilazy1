# Lemon Squeezy Payments Setup Guide

## 1. Create Store & Products
1. Register a merchant store at Lemon Squeezy (lemonsqueezy.com).
2. Create 4 products/variants representing the token packages:
   - Starter Pack: $4.99 (500 tokens)
   - Popular Pack: $9.99 (1500 tokens)
   - Pro Pack: $19.99 (4000 tokens)
   - Power Pack: $39.99 (10000 tokens)

## 2. API Credentials
1. Navigate to **Settings -> API**.
2. Create an API Key and retrieve your **Store ID**.
3. Update `.env` keys `LEMON_STORE_ID` and `LEMON_API_KEY`.

## 3. Webhook Integration
1. Navigate to **Developer -> Webhooks**.
2. Click **Add Webhook**.
3. Input target URL: `https://utilazy.com/api/payments/webhook`.
4. Enter a secure custom signing secret. Update `.env` key `LEMON_WEBHOOK_SECRET` with it.
5. Under **Trigger events**, select **order_created**.
6. Save and test checkout order sandbox flow.
