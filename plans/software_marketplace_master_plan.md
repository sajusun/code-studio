# Master Architecture Plan: Developer Showcase, Lead Generation & Custom Software Platform

A specialized software showcase and agency marketing platform designed for software developers and teams to showcase ready-made products, provide live previews, estimate custom project costs, and collect leads via dynamic contact channels (WhatsApp, Email, Fiverr, Upwork).

---

## 1. Core Modules Overview

```
 ┌─────────────────────────────────────────────────────────────────────────┐
 │                   VISITOR / CLIENT LANDING FRONTEND                     │
 └──────┬──────────────────────┬────────────────────┬──────────────────────┘
        │                      │                    │
 ┌──────▼─────────────┐ ┌──────▼────────────┐ ┌─────▼────────────────┐
 │  PRODUCT SHOWCASE  │ │ CUSTOM COST       │ │ DYNAMIC CONTACTS    │
 │  - Live Web Demo   │ │ ESTIMATOR         │ │ - WhatsApp Direct   │
 │  - Demo APK / iOS  │ │ - Feature Tiers   │ │ - Email / Phone     │
 │  - Admin Preview   │ │ - Estimated Price │ │ - Fiverr / Upwork   │
 │  - Video & Screens │ │ - Custom Quote    │ │ - Inquiry Form      │
 └────────────────────┘ └───────────────────┘ └─────────────────────┘
```

---

## 2. Dynamic Contact & Inquiry Management System

### A. Dynamic Contact Channels (Admin Configurable)
- Admins can add/edit multiple contact channels:
  - **Type**: `whatsapp`, `phone`, `email`, `fiverr`, `upwork`, `telegram`, `custom_link`.
  - **Label**: e.g., *"Chat on WhatsApp for Instant Demo"*, *"View Fiverr Profile"*, *"Technical Consultation"*.
  - **Value / URL**: `https://wa.me/8801700000000?text=I+want+to+buy+your+Laravel+App`.
  - **Is Primary / Product Assignment**: Assign specific contacts to specific products.

### B. Project Inquiry & Custom Quote Request Form
- Visitors can submit project inquiries:
  - Selected Product (Optional)
  - Name, Email, Phone/WhatsApp
  - Project Type (Pre-built App Customization, New Web App, New Mobile App)
  - Estimated Budget & Deadline
  - Requirements description
- Action: Saves inquiry to Admin Panel DB + triggers instant Email / WhatsApp notification to team.

---

## 3. Custom App Feature & Cost Estimator

Help visitors calculate estimated development costs based on features they select:

- **Features Tiers**:
  - *Core Features* (included in base price).
  - *Add-on Modules* (e.g. Push Notifications, Payment Gateways, Multi-language, Dark Mode, Admin Dashboard).
- **Interactive Calculator**:
  - Visitors select desired features $\rightarrow$ Live estimate summary $\rightarrow$ One-click "Send Inquiry via WhatsApp / Form".

---

## 4. Database Schema Overview

```mermaid
erDiagram
    CONTACT_CHANNELS ||--o{ PRODUCT_CONTACTS : assigned_to
    PRODUCTS ||--o{ PRODUCT_CONTACTS : has
    PRODUCTS ||--o{ PRODUCT_FEATURES : lists
    PRODUCTS ||--o{ INQUIRIES : receives
    PRODUCTS ||--o{ PRODUCT_PREVIEWS : contains

    PRODUCTS {
        bigint id
        string title
        string slug
        enum type "web_app|android|ios|custom"
        decimal base_price
        string demo_url
        string admin_demo_url
        string admin_demo_credentials
        string apk_download_url
        json tech_stack
    }

    PRODUCT_PREVIEWS {
        bigint id
        bigint product_id
        enum preview_type "screenshot|video|apk|testflight"
        string file_or_url
    }

    CONTACT_CHANNELS {
        bigint id
        string channel_name "WhatsApp|Email|Fiverr|Upwork|Phone"
        string channel_type "whatsapp|email|phone|fiverr|upwork|link"
        string contact_value
        string display_text
        boolean is_active
    }

    PRODUCT_CONTACTS {
        bigint id
        bigint product_id
        bigint contact_channel_id
    }

    PRODUCT_FEATURES {
        bigint id
        bigint product_id
        string feature_title
        text feature_description
        decimal additional_cost "default 0.00"
        boolean is_core_feature
    }

    INQUIRIES {
        bigint id
        bigint product_id
        string client_name
        string client_email
        string client_phone
        text message
        decimal client_budget
        enum status "new|contacted|quoted|closed"
    }
```

---

## 5. Phased Implementation Roadmap

### Phase 1: Core Showcase & Preview Engine
- Products CRUD (Title, Type, Tech Stack, Base Price, Demo URLs, Admin Credentials, Video Embeds, Screenshot Gallery).
- Public Showcase Frontend with live demo buttons and preview tabs.

### Phase 2: Dynamic Contact System & Lead Inquiries
- Admin Contact Channels management (WhatsApp, Phone, Email, Fiverr, Upwork).
- Public Contact Widget on product pages & Inquiry Submission Form.

### Phase 3: Cost Estimator & Feature Calculator
- Feature Tiers & Additional Modules setup per product.
- Interactive Estimator UI for visitors to calculate estimated costs and submit custom project requests.

### Phase 4: Future E-commerce Integration (When Ready)
- Shopping Cart, Stripe / SSLCommerz / bKash Payment Gateways, ZIP download links, and automated License Engine.
