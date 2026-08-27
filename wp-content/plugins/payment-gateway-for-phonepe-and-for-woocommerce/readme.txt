=== Payment Gateway for PhonePe and for Woocommerce ===  
Contributors: easypayment  
Donate link: https://profiles.wordpress.org/easypayment/  
Tags: phonepe, woocommerce, upi, payments  
Requires at least: 5.0  
Tested up to: 6.9.1
Requires PHP: 7.4  
Stable tag: 1.0.11  
License: GPLv2 or later  
License URI: https://www.gnu.org/licenses/gpl-2.0.html  

Accept payments through UPI, Cards, and Net Banking — developed by an official PhonePe Partner.

== Description ==  

🚀 Payment Gateway for PhonePe and for Woocommerce allows you to accept payments through PhonePe UPI, Wallet, Cards, and Net Banking, providing customers with a quick and secure checkout experience. **Developed by an Official PhonePe Partner**, this plugin ensures high performance and reliability.

#### Key Features:  
- Multiple Payment Options: Accept payments via UPI, Wallet, Cards, and Net Banking.  
- Seamless Checkout: Smooth, mobile-optimized checkout experience.  
- Secure Transactions: PCI DSS compliance with end-to-end encryption.  
- Real-Time Order Updates: Instant order status synchronization.  
- Fast Integration: Easy setup without coding skills.  
- PhonePe Sign-up: Highlighted PhonePe sign-up option for new customers.  

#### Why Choose PhonePe?  
- Increase Conversions: Fast UPI payments reduce cart abandonment rates.  
- Trusted & Secure: One of India’s most trusted payment methods.  
- Quick Settlements: Receive payments faster with low transaction fees.  
- Wide Reach: Accept payments from millions of PhonePe users.  
- Exclusive Benefits for PhonePe Merchants: Enjoy special offers for PhonePe business account holders.  

**PhonePe is supported for transactions within India and in INR currency only.**

---

== Frequently Asked Questions ==  

= 1. Do I need a PhonePe business account? =  
Yes, you must have a PhonePe Merchant Account to use this plugin. Additionally, new users can sign up directly through the plugin.  

= 2. Is this plugin compatible with my WooCommerce version? =  
Yes, it is compatible with the latest version of WooCommerce.  

= 3. Is the payment secure? =  
Absolutely. The plugin uses end-to-end encryption and is PCI DSS compliant.  

= 4. Does the plugin support automatic refunds? =  
Currently, automatic refunds are not supported. You can issue refunds from your PhonePe merchant dashboard.

---

== Screenshots ==  

1. Setting Page.  
2. Checkout Page.  
3. Payment Page.  

---

== Changelog ==  

= 1.0.11 =
* Added - Compatibility with WordPress 6.9.1

= 1.0.10 =
* Enhanced - Improved gateway settings panel text.

= 1.0.9 =
* Added - Compatibility with WordPress 6.9.

= 1.0.8 =
* Improved - Admin setting.

= 1.0.7 =
* Improved - Enhanced logic for better error handling and clearer user messages.

= 1.0.6 =
* Improved - setting panel UI.

= 1.0.5 =
* Added – Hide Redirect Icon option for PhonePe checkout (honored in Blocks/classic templates).

= 1.0.4 =
* Improved – Enhanced order note formatting when using webhook response.
* Improved – Added redirect icon and text for clearer visual indication.

= 1.0.3 =
* Improved – Unified PhonePe currency restriction message layout for both Classic and Checkout Block.
* Added – Admin-only guidance for enabling PhonePe on non-INR stores.
* Enhanced – Consistent CSS styling across frontend and block-based checkout.

= 1.0.2 =
* Improved – Minor UI enhancements for better admin experience.

= 1.0.1 =
* Fixed – Resolved issue related to webhook handling.

= 1.0.0 - Initial Release =  
- First release of Payment Gateway for PhonePe and for Woocommerce.  
- Supports payments via UPI, Wallet, Cards, and Net Banking.  
- Instant payment status updates with secure processing.  
- Highlighted PhonePe sign-up option for new customers.  

---

== External Services ==

This plugin connects to the official PhonePe payment gateway APIs to process payments, verify transaction statuses, and manage refunds for WooCommerce orders.

It sends the following data to PhonePe:
- Merchant ID, order ID, and transaction ID when initiating or verifying payments
- Payment amount and UPI intent when the customer proceeds with checkout
- Refund request details including merchant refund ID and amount (if applicable)

The plugin communicates with the following API endpoints:
- https://api.phonepe.com/apis/identity-manager
- https://api.phonepe.com/pg/v1/status/{merchantId}/{transactionId}
- https://api.phonepe.com/checkout/v2/order/{merchantOrderId}/status
- https://api.phonepe.com/payments/v2/refund/{merchantRefundId}/status
- https://api-preprod.phonepe.com/apis/pg-sandbox (Sandbox/testing environment)

All data transfers are securely handled over HTTPS. The plugin does not store or retain any personal user data.

External Service Provider: PhonePe  
- Website: https://www.phonepe.com  
- Merchant Registration: https://easypaymentplugins.com/phonepe-signup  
- Terms of Service: https://www.phonepe.com/termsandconditions.html  
- Privacy Policy: https://www.phonepe.com/privacy-policy.html


---