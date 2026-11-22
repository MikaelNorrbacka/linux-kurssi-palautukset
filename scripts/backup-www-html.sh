#!/usr/bin/env bash
set -euo pipefail

SERVER_B="ubuntu@nginx-serverb"
REMOTE_DIR="/var/backups/www-html"
REMOTE_LOG="/var/log/www-backup.log"
STAMP="$(date -u +%Y%m%d-%H%M%S)"
ARCHIVE="/tmp/www-html-${STAMP}.tar.gz"

log_msg() {
  local status="$1"
  local msg="$2"
  ssh -o BatchMode=yes "$SERVER_B" "printf '%s [%s] %s\n' \"\$(date -u --iso-8601=seconds)\" \"$status\" \"$msg\" >> \"$REMOTE_LOG\""
}

if tar -czf "$ARCHIVE" -C /var/www html && scp -q -o BatchMode=yes "$ARCHIVE" "${SERVER_B}:${REMOTE_DIR}/"; then
  rm -f "$ARCHIVE"
  ssh -o BatchMode=yes "$SERVER_B" "ls -1t ${REMOTE_DIR}/www-html-*.tar.gz 2>/dev/null | tail -n +3 | xargs -r rm -f --"
  log_msg "SUCCESS" "Uploaded www-html-${STAMP}.tar.gz"
else
  rm -f "$ARCHIVE"
  log_msg "FAILED" "Backup failed for www-html-${STAMP}.tar.gz"
  exit 1
fi
