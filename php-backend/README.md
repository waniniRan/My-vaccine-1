# PHP Backend for My-vaccine

## Setup

1. **Create the MySQL database:**
   - Create a database named `immunity` (or change the name in `db.php`).
   - Use the provided SQL files to create tables for all models.

2. **Configure database connection:**
   - Edit `php-backend/db.php` and set your MySQL username and password.

3. **API Usage:**
   - Each model (users, facilities, guardians, etc.) has its own folder with CRUD PHP scripts.
   - Use HTTP POST/GET requests to interact with these endpoints.

## Endpoints Example
- `users/create.php` — Create a new user
- `users/login.php` — User login
- `facilities/create.php` — Create a new facility
- ...and so on for other models

## Security Note
This is a minimal, educational PHP backend. For production, add authentication, input validation, and security best practices. 