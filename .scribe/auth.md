# Authenticating requests

Most endpoints require a valid OAuth access token issued by Laravel Passport.

Send the token in the Authorization header using the Bearer scheme: Authorization: Bearer {token}. Obtain tokens by completing the mobile OTP verification flow.
