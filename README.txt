ITDP GOVERNANCE PLATFORM — PHP + MySQL
======================================

This upgraded package includes:
- Secure ITDP / Ashram Shala / Wastigruh login
- Registration with administrator approval
- Role and institution based access control
- Admin dashboard with action center
- User approval / block / unblock
- Institution-wise records with search, filters and status workflow
- Work/task assignment, progress updates and task history
- Official officer orders / circular instructions
- Daily operational reports with admin verification/rejection
- Alerts and notices
- Secure file/document library with upload limits
- Official format/template library with downloads
- Profile and password change
- Activity/audit log
- CSRF protection on state-changing forms
- Prepared MySQLi statements and password_hash/password_verify
- Responsive modern UI

LOCAL SETUP (XAMPP)
1. Extract this folder to:
   C:\xampp\htdocs\ITDP-PHP-SQL\

2. Start Apache and MySQL from XAMPP.

3. Open phpMyAdmin and import:
   database.sql

4. Database defaults:
   Host: 127.0.0.1
   User: root
   Password: empty
   Database: itdp

   For another MySQL account, set environment variables:
   ITDP_DB_HOST, ITDP_DB_USER, ITDP_DB_PASS, ITDP_DB_NAME
   or edit config/db.php.

5. Open:
   http://localhost/ITDP-PHP-SQL/

DEMO ACCOUNTS
ITDP Admin:
  admin@itdp.com
  Admin#123

Ashram Shala:
  ashram@itdp.com
  Admin#123
  Institution: ASHRAM001

Wastigruh:
  wasti@itdp.com
  Admin#123
  Institution: WASTI001

IMPORTANT PRODUCTION STEPS
- Change demo passwords immediately.
- Use HTTPS.
- Use a dedicated MySQL user with limited privileges.
- Configure upload limits in php.ini.
- Back up the itdp database and uploads directory.
- Keep the uploads .htaccess file enabled.

MODULES
Dashboard
Users & Approvals
Records
Work & Tasks
Orders & Circulars
Daily Reports
Alerts
Files
Official Formats
Profile & Security
Activity Log

The package was syntax-checked with PHP CLI before delivery.
