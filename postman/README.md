# Postman - Pendataan_IGD API

This folder contains a Postman collection to test the Pendataan_IGD API which uses Laravel Sanctum with session-based authentication.

Files:
- `Pendataan_IGD.postman_collection.json` - Postman collection to import

Quick steps to use:

1. Open Postman -> Import -> Choose `Pendataan_IGD.postman_collection.json`.
2. Create an environment in Postman with the variable `base_url` (default `http://localhost:8000`).
3. Steps to authenticate and test protected endpoints:
   - Run `Get CSRF Cookie (sanctum)` (this sets the `XSRF-TOKEN` cookie in Postman cookie jar).
   - Manually copy the `XSRF-TOKEN` cookie value from Postman cookies (or use a test script) into the environment variable `XSRF-TOKEN`.
   - Run `Login (session)` to create a session (uses `X-XSRF-TOKEN` header from environment).
   - Run `Get Authenticated User` or `Protected: Dashboard Stats` to confirm authentication.
   - Use `Logout (session)` to destroy the session.

Notes and tips:
- Postman does not automatically copy the `XSRF-TOKEN` cookie to headers. After the `Get CSRF Cookie` request completes, open the cookie manager in Postman and copy `XSRF-TOKEN` into the environment variable `XSRF-TOKEN`.
- Ensure Postman is sending cookies to your server (cookie domain must match `base_url`).
- If using a non-localhost domain, add it to `SANCTUM_STATEFUL_DOMAINS` in `.env` and restart your app.

Advanced automation (optional):
- You may add a `Tests` script on the `Get CSRF Cookie` request to automatically set the `XSRF-TOKEN` environment variable from `pm.cookies.get('XSRF-TOKEN')`.

Example test script for `Get CSRF Cookie` (Postman Tests tab):

```javascript
const xsrf = pm.cookies.get('XSRF-TOKEN');
if (xsrf) {
  pm.environment.set('XSRF-TOKEN', xsrf);
}
```

After that, the `Login (session)` request will pick the value from `{{XSRF-TOKEN}}` and send the `X-XSRF-TOKEN` header.

Enjoy testing the API!