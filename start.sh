#!/usr/bin/env bash
# Honey Beauty — one command to run the whole store.
set -e
cd "$(dirname "$0")"
PORT="${1:-8000}"
echo ""
echo "  Honey Beauty is starting..."
echo "  Open http://localhost:$PORT in your browser"
echo "  Admin login -> username: admin   password: admin"
echo "  Press Ctrl+C to stop the server"
echo ""
php -S "0.0.0.0:$PORT" -t public public/router.php
