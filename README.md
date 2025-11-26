# Iconic Secure Upload Demo

Real backend demo:
- MySQL (production-like) via PDO
- ClamAV scanning (clamd or clamscan)
- Logs in storage/logs/scans.log
- Public UI: / (upload form)
- API: POST /api/upload

Setup:
1. Install MySQL, PHP (pdo_mysql) and ClamAV.
2. Edit config/app.ini with DB credentials or set a secure DB user.
3. Run: php src/Database/migrations.php
4. Run: php -S localhost:8080
5. Open http://localhost:8080
