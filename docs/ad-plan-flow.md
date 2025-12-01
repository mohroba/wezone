# Ad plan lifecycle walkthrough

This guide shows how a client can attach promotion plans to an ad, pay through a single gateway transaction, and see the active promotions reflected on ad detail and listing endpoints. The flow assumes authenticated API calls under the `/api/monetization` namespace.

## 1. Discover available plans
- **Endpoint:** `GET /api/monetization/plans`
- **Purpose:** Populate the UI with purchasable plans (`motemayez`, `nardeban`, etc.). Each plan exposes price, duration, feature flags (e.g., `bump`, `highlight`), and any `bump_cooldown_minutes`.

## 2. Create purchases for the selected ad
- **Endpoint:** `POST /api/monetization/purchases/bulk`
- **Purpose:** Create multiple purchases for the same ad in a single request. The backend enforces a single gateway for the batch when `pay_with_wallet` is `false` on any item.
- **Request payload example:**
  ```json
  {
    "ad_id": 412,
    "advertisable_type_id": 8,
    "plans": [
      {
        "plan_slug": "motemayez",
        "gateway": "payping",
        "pay_with_wallet": false
      },
      {
        "plan_slug": "nardeban",
        "gateway": "payping",
        "pay_with_wallet": false
      }
    ],
    "ad_category_id": 131
  }
  ```
- **Result:** The response returns created purchases and, when any item requires a gateway payment, a single aggregated `payment` entry containing the total amount and a `redirect_url` for the chosen gateway.

## 3. Redirect to the gateway once
- Use the `redirect_url` from the aggregated payment to send the user to the gateway checkout page.
- Preserve the `id` (payment identifier) so it can be validated after the gateway redirects back.

## 4. Verify the gateway callback and activate plans
- **Automatic callback handling:** Forward the gateway payload to `POST /api/monetization/payments/verify` with `gateway` and `payload` fields. Verification activates all purchases tied to the aggregated payment and triggers automatic bumps for bump-capable plans.
- **Manual validation (if the user returns via redirect):** `POST /api/monetization/payments/{payment}/validate` with the callback payload. This path also activates the related purchases and applies bumps.
- After verification, purchases move from `draft` to `active`, and `payment.status` becomes `paid`.

## 5. Optional: trigger additional bumps later
- **Endpoint:** `POST /api/monetization/purchases/{purchase}/bump`
- **Purpose:** Manually bump an ad again after the cooldown expires. The backend enforces per-plan cooldowns and bump allowance.

## 6. Inspect monetization status on ads
- **Ad detail:** `GET /api/ads/{id}` returns `monetization.active_promotions_count` and the list of purchases (with `plan`, `payment_status`, `payments`, `bump_cooldown_minutes`, and validity window).
- **Ad listing / explore:** Monetization data is also included when ads are returned by listing endpoints so the client can highlight promoted items without extra calls.

## Example: ad with two active plans
See [`docs/examples/ad-plan-interaction.json`](examples/ad-plan-interaction.json) for a full payload that shows an ad with both `motemayez` and `nardeban` active, paid through a single gateway transaction, with auto-bump metadata recorded.
