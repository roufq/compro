---
type: "query"
date: "2026-09-28T03:49:37.016430+00:00"
question: "cek lagi sistem middleware auth ketika session habis sudah auto logout"
contributor: "graphify"
outcome: "useful"
source_nodes: ["app.php", "session.php", "AuthenticationTest.php", "Logout"]
---

# Q: cek lagi sistem middleware auth ketika session habis sudah auto logout

## Answer

Expanded from original query via graph vocab: [auth, middleware, session, logout, app, fortify]. Verified from source: admin routes use auth middleware in routes/web.php; bootstrap/app.php handles non-JSON 419 by logging out the web guard, invalidating the session, regenerating the CSRF token, and redirecting to login. config/session.php sets idle lifetime from SESSION_LIFETIME, currently 120 minutes. The focused Pest test passed with 7 assertions, and combined auth plus expiration tests passed: 8 tests, 27 assertions.

## Outcome

- Signal: useful

## Source Nodes

- app.php
- session.php
- AuthenticationTest.php
- Logout