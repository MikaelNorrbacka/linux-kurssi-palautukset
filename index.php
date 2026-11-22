<?php
$dbHost = '127.0.0.1';
$dbName = 'viikko2_db';
$dbUser = 'viikko4_ro';
$dbPass = 'Viikko4ReadOnly!2026';

$localConfig = __DIR__ . '/config.local.php';
if (file_exists($localConfig)) {
    require $localConfig;
}

$counterValue = 'Not read yet (check table/column or DB user)';
$dbError = '';

try {
    $pdo = new PDO(
        "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4",
        $dbUser,
        $dbPass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $tblStmt = $pdo->query("SHOW TABLES");
    $table = $tblStmt->fetchColumn();
    if ($table !== false) {
        $safeTable = str_replace('`', '``', (string)$table);
        $rowStmt = $pdo->query("SELECT * FROM `{$safeTable}` LIMIT 1");
        $row = $rowStmt->fetch(PDO::FETCH_ASSOC);
        if (is_array($row) && !empty($row)) {
            $val = array_key_exists('visits', $row) ? $row['visits'] : reset($row);
            $counterValue = htmlspecialchars((string)$val, ENT_QUOTES, 'UTF-8');
        } else {
            $counterValue = 'Scoreboard empty';
        }
    }
} catch (Throwable $e) {
    $dbError = htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Week 4 – Read-Only Replica &amp; Documentation</title>
  <style>
- { box-sizing: border-box; }
    body {
      font-family: system-ui, -apple-system, sans-serif;
     color: #f8fafc;
      margin: 0;
      padding: 2rem 1rem 4rem;
      line-height: 1.6;
      background-color: white;
      background-image:
        linear-gradient(rgba(10, 15, 28, 0.72), rgba(10, 15, 28, 0.84)),
        url('https://giffiles.alphacoders.com/224/224298.gif');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
    }
    .container {
      max-width: 920px;
      margin: 0 auto;
    }
    .hero, section {
      background: rgba(15, 23, 42, 0.82);
      backdrop-filter: blur(8px);
      border: 1px solid rgba(148, 163, 184, 0.25);
      border-radius: 14px;
      padding: 1.5rem 1.75rem;
      box-shadow: 0 12px 32px rgba(0, 0, 0, 0.45);
    }
    section {
      margin-top: 1.25rem;
    }
    h1 {
      margin: 0.75rem 0 0.5rem;
      font-size: 1.85rem;
      line-height: 1.25;
      color: #ffffff;
    }
    h2 {
      margin: 0 0 0.75rem;
      font-size: 1.25rem;
      color: #93c5fd;
    }
    p {
      margin: 0.5rem 0;
      color: #e2e8f0;
    }
    ul {
      margin: 0.5rem 0 0;
      padding-left: 1.35rem;
      color: #e2e8f0;
  }
    li {
      margin-bottom: 0.4rem;
    }
    a {
      color: #60a5fa;
      text-decoration: none;
    }
    a:hover {
      text-decoration: underline;
    }
    code {
      background: rgba(30, 41, 59, 0.9);
      border: 1px solid rgba(148, 163, 184, 0.2);
      padding: 0.1rem 0.38rem;
      border-radius: 5px;
      font-size: 0.92em;
      color: #fde68a;
    }
    .badge {
      display: inline-block;
      background: rgba(30, 58, 138, 0.75);
      color: #bfdbfe;
      border: 1px solid rgba(147, 197, 253, 0.5);
      padding: 0.25rem 0.7rem;
      border-radius: 999px;
      font-size: 0.85rem;
      font-weight: 600;
    }
    .counter {
      font-size: 1.85rem;
      font-weight: 700;
      margin: 1rem 0 0.25rem;
      color: #38bdf8;
    }
    .err {
      color: #fecaca;
      background: rgba(153, 27, 27, 0.45);
      border: 1px solid rgba(248, 113, 113, 0.5);
      padding: 0.75rem 1rem;
      border-radius: 8px;
      margin-top: 0.75rem;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="hero">
      <span class="badge">nginx-serverb</span>
      <h1>Visitor and documentations</h1>
      <p>This page reads the visitor counter from server B's own local MariaDB database. This page does not to count visits or modify the database in any way.</p>

      <div class="counter">Count of visits: <?php echo $counterValue; ?></div>
      <?php if ($dbError !== ''): ?>
        <div class="err">Database notice: <?php echo $dbError; ?></div>
      <?php endif; ?>
    </div>

    <section>
      <h2>Counter Pages and GitHub</h2>
      <ul>
        <li>Server A counter page week2, increments counter): <a href="http://195.148.31.122/viikko2/">/viikko2/ (Server A)</a></li>
        <li><a href="https://github.com/MikaelNorrbacka/linux-kurssi-palautukset" target="_blank" rel="noopener noreferrer">My Github</a></li>
   </section>

    <section>
      <h2> Creating Server B</h2>
      <p>The second server (Server B) was created from with its own IP address via gateway/jump host, while keeping Server A's earlier applications and reports running at their original URLs.</p>
    </section>

    <section>
      <h2> Storage and locations</h2>
      <p>These are timestamped (.tar.gz) backups of Server A's (/var/www/html) directory are stored outside publicly served web directories on Server B under (/var/backups/www-html), with logs and local confi>
    </section>

    <section>
      <h2> Ownership and permissions</h2>
      <p>SSH transfers and scheduled backups runs on the ubuntu user with appropriate directory ownership and permissions, while it connects to MariaDB using a dedicated user and keeps local credentials outsi>
    </section>

    <section>
      <h2> Backups and replications</h2>
      <p> Code "cron" runs hourly backups and it keeps at least two timestamped archives. MariaDB replication mirrors changes from A to B in real time, but it does not replace the historical backups because a>
    </section>

    <section>
    <h2>Test Results</h2>
    <ul>
    <li>Viikko2 and viikko4 are accessible and also verified. Both pages load over HTTP without any errors.</li>
    <li>Viikko2 increments the counter and updates. Viikko2 increases the count and viikko4 shows the new value while reloading.</li>
    <li>Viikko4 does not increment the counter. Instead of increment it leaves the visitor count unchanged.</li>
    <li>MariaDB replication and read-only mode works. Replication threads are running and B stays read-only for normal users.</li>
  <li>Two scheduled backups completed and one test file restored: Verified archives exist and a test file restored cleanly to a separate folder.</li>
    <li>Site, replication, and backups works after reboot. MariaDB, and cron still work after rebooting Server B.</li>
    </ul>
    </section>

    <section>
      <h2>Solving the problems and what I learned about them</h2>
        <h2>Problems solved while doing the assignment</h2>
        <p>While I tried to get into Server B, it took too many trials while having multiple errors with the SSH key. Public IP changing, trying to get into the viikko4 folder
and long time sessions of desperate fixing didn't help either.
But suddenly when I realised to Check the HTTP address instead of HTTPS, I finally got the page loading properly.
The last problem I realised very late was that I connected multiple times on the wrong server instead of the new one that I created on csc pouta.</p>
    </section>
  </div>
</body>
</html>
