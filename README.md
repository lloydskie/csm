
# Client Satisfaction Management System (CSM)

## Overview
This is a web-based Client Satisfaction Management System for collecting, analyzing, and exporting client feedback. It features QR code survey links, admin authentication, passcode protection, response analytics, and Excel export.

## Features
- Admin login and signup (with passcode protection)
- Generate unique QR code survey links for clients
- Restrict survey access to only valid links
- Lockout and retry protection for admin passcode
- Visual dashboard with charts, individual and tabular response views
- Export all responses to Excel (.xlsx)

## Requirements
- XAMPP (Apache, MySQL, PHP >= 8.0)
- Composer (for PHP dependencies)

## Installation & Setup
1. **Clone or copy the project folder to your XAMPP `htdocs` directory:**
	 - Example: `C:\xampp\htdocs\csm`

2. **Create the database:**
	 - Start MySQL from XAMPP Control Panel.
	 - Open phpMyAdmin and create a new database (e.g., `csm_db`).
	 - Import the schema from `database/schema.sql`.

3. **Configure database connection:**
	 - Edit `database/config.php` and set your MySQL username, password, and database name if needed.

4. **Install Composer dependencies:**
	 - Open a terminal in the project root (`csm`).
	 - Run: `composer install`
	 - If you add new features, run: `composer require phpoffice/phpspreadsheet`

5. **Start Apache and MySQL in XAMPP.**

6. **Access the app:**
	 - Go to `http://localhost/csm/public/index.html` in your browser.

## Usage
- **Admin Signup:**
	- Click "Sign up instead" and enter the admin passcode (default: `123456`).
	- Create a new admin account.
- **Admin Login:**
	- Log in to access the dashboard.
- **Generate Survey QR Code:**
	- Click "Generate New QR Code Link" in the dashboard.
	- Share the generated link/QR code with clients.
- **Survey Submission:**
	- Clients use the provided link to submit feedback.
	- Used links cannot be reused.
- **View Results:**
	- Dashboard shows charts, individual responses, and a spreadsheet view.
	- Export responses to Excel for analysis.

## Security Notes
- Admin signup is protected by a passcode and lockout after failed attempts.
- Survey links are single-use and cannot be reused.

## Troubleshooting
- If you see errors about missing dependencies, run `composer install` in the project root.
- Make sure XAMPP services (Apache, MySQL) are running.
- Check `database/config.php` for correct DB credentials.

## License
MIT
