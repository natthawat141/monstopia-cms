#!/usr/bin/env bash
set -euo pipefail
BASE="${1:-http://127.0.0.1:8001}"
COOKIE="/tmp/monstopia-cms.cookies"
LOGIN_HTML="/tmp/monstopia-login.html"
rm -f "$COOKIE"
curl -sS -c "$COOKIE" "$BASE/login" > "$LOGIN_HTML"
TOKEN=$(grep -o 'name="_token" value="[^"]*"' "$LOGIN_HTML" | head -1 | sed -E 's/.*value="([^"]*)"/\1/')
curl -sS -b "$COOKIE" -c "$COOKIE" -o /tmp/monstopia-after-login.html -w "login_status=%{http_code}\n" \
  -H 'Accept: text/html' -X POST "$BASE/login" \
  --data-urlencode "_token=$TOKEN" --data-urlencode 'email=admin@monstopia.co.th' --data-urlencode 'password=password'
printf 'dashboard_status='
curl -sS -b "$COOKIE" -o /tmp/monstopia-dashboard.html -w '%{http_code}\n' "$BASE/admin/dashboard"
CSRF=$(grep -o 'name="csrf-token" content="[^"]*"' /tmp/monstopia-dashboard.html | sed -E 's/.*content="([^"]*)"/\1/')
printf 'stats_status='
curl -sS -b "$COOKIE" -H 'Accept: application/json' -o /tmp/monstopia-stats.json -w '%{http_code}\n' "$BASE/api/dashboard/stats"
printf 'create_status='
curl -sS -b "$COOKIE" -H 'Accept: application/json' -H 'Content-Type: application/json' -H "X-CSRF-TOKEN: $CSRF" -X POST "$BASE/api/services" -o /tmp/monstopia-created-service.json -w '%{http_code}\n' -d '{"name":"QA Service","description":"Smoke test service","icon":"check","status":"active","sort_order":99}'
cat /tmp/monstopia-stats.json
printf '\n'
cat /tmp/monstopia-created-service.json
printf '\n'
