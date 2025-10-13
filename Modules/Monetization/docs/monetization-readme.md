# Monetization & Payments Module

This module introduces a promotion purchase workflow for WeZone listings. It follows a layered architecture with dedicated domain services, repositories, and value objects to keep the payment lifecycle extensible.

## Highlights

- Promotion plans with configurable feature flags and durations.
- Purchases track payment state transitions and emit domain events.
- Pluggable payment gateways (Zarinpal, IDPay, Stripe) via a registry.
- Wallet support with double-entry transactions.
- RESTful API under `/api/monetization` secured via `auth:api` middleware.

## Key Flows

1. **Plan selection** via `GET /api/monetization/plans`.
2. **Purchase creation** via `POST /api/monetization/purchases`.
3. **Payment initiation** with `POST /api/monetization/purchases/{purchase}/payments/initiate`.
4. **Gateway verification** via `POST /api/monetization/payments/verify`.
5. **Wallet management** via `GET|POST /api/monetization/wallet` endpoints.

Refer to `docs/monetization-openapi.yaml` for request and response structures.
