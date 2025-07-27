Blood Bank Management System
Overview
This project is a web-based Blood Bank Management System built with PHP and MySQL. It helps users, hospitals, and administrators manage blood donations, requests, inventories, and notifications efficiently.

What is this project about?
This Blood Bank Management System is designed to streamline the process of blood donation and management. It serves as a platform where donors can register, schedule donations, and track their donation history. Hospitals can request blood units when needed, and administrators can oversee the entire system, including approving blood requests and managing inventory across different states.

The system aims to bridge the gap between blood donors and healthcare facilities, ensuring that blood is available when and where it's needed most. It includes features like real-time notifications, reward points for donors, and comprehensive inventory tracking to make the blood donation process more efficient and accessible.

Innovative Features & AI Integration
This project incorporates cutting-edge technology and innovative features to revolutionize blood bank management:

🤖 AI-Powered Features
Smart Donor Matching: AI algorithms analyze donor profiles and medical history to suggest optimal donation schedules
Predictive Inventory Management: Machine learning models predict blood demand based on historical data, seasonal trends, and local events
Intelligent Route Optimization: AI-powered logistics system optimizes blood transport routes between facilities
Automated Emergency Response: AI detects critical blood shortages and automatically notifies eligible donors in the area
🚀 Advanced Technologies
Real-time GPS Tracking: Live tracking of blood transport vehicles for enhanced security and efficiency
Blockchain Integration: Secure, tamper-proof records of all blood donations and transfers
IoT Sensors: Smart monitoring of blood storage conditions (temperature, humidity, etc.)
Mobile App Integration: Cross-platform mobile applications for donors and healthcare workers
💡 Innovative Solutions
Gamification System: Donors earn badges, achievements, and rewards to encourage regular donations
Social Network Integration: Connect donors with similar blood types and locations
Emergency Alert System: Instant notifications to nearby donors during critical shortages
Analytics Dashboard: Comprehensive data visualization for administrators and healthcare providers
Features
User Registration & Login: Donors can sign up, log in, and manage their profiles.
Hospital & Admin Login: Hospitals and admins have separate dashboards and permissions.
Blood Donation Scheduling: Users can schedule donations at hospitals or blood banks.
Nearby Locations: Find nearby hospitals and blood banks based on your city and state.
Blood Requests: Hospitals can request blood units; admins can approve or reject requests.
Inventory Management: Track blood inventory at the state, hospital, and blood bank levels.
Notifications: Users receive notifications for donation requests, reminders, and updates.
Reward Points: Donors earn points for each successful donation.
Admin Dashboard: View and manage all blood requests and state inventories.
Setup Instructions
1. Requirements
PHP 7.x or higher
MySQL/MariaDB
Web server (e.g., Apache, XAMPP, WAMP)
2. Installation
Clone or Download the Project

Place the project folder in your web server's root directory (e.g., htdocs for XAMPP).
Database Setup

Create a new MySQL database (default: blood_bank).
Import the provided SQL files in this order:
database.sql (main schema and demo data)
demo_user_data.sql (optional: demo user)
demo_admin_data.sql (optional: demo admin)
update_users_table.sql (optional: address migration)
Configure Database Connection

Edit config.php if your MySQL username, password, or database name are different.
(Optional) Location Tables

Run setup_location_tables.php or create_location_tables.php in your browser to set up states and cities tables.
(Optional) Add Sample Data

Use add_andhra_users.php and add_sample_notifications.php to populate demo users and notifications.
3. Running the Project
Open your browser and go to http://localhost/n/login.php (adjust path as needed).
Register as a user, or log in as a hospital or admin (see demo credentials below).
Demo Credentials
Admin:
ID Proof: ADMIN123
Password: admin123
Demo User:
ID Proof: DL1234567890
Password: password
File Structure
config.php — Database connection settings
login.php, signup.php, process_login.php, process_signup.php — Authentication
user_dashboard.php, hospital_dashboard.php, admin_dashboard.php — Main dashboards
schedule_donation.php, process_donation.php — Donation scheduling
nearby_locations.php — Find hospitals/blood banks
state_inventory.php — State-level blood inventory
update_profile.php — User profile management
process_blood_request.php, update_request_status.php — Blood request handling
update_donation_status.php, update_points.php — Donation and points updates
assets/ — Images and static assets
database.sql, demo_user_data.sql, demo_admin_data.sql — Database schema and demo data
