# Linux Course Submissions (Server B / Week 4)

This repository contains the backup script, cron schedule, MariaDB replication configuration, and read-only Week 4 web page for `nginx-serverb'.

## Info about Contents

- `index.php` – Week 4 (`viikko4`) read-only web page that reads the visitor counter from `viikko2_db` without modifying it and links back to this repository.
- `scripts/backup-www-html.sh` – Script that archives `/var/www/html` into a timestamped `.tar.gz` file, transfers it to `/var/backups/www-html/` on Server B, keeps the two newest backups, and logs the status.
- `cron/crontab` – Scheduled cron job (`0 * * * *`) running the backup script every hour.
- `replication/60-replica.cnf` and `replication/99-replica-readonly.cnf` – MariaDB replica and read-only mode settings.
