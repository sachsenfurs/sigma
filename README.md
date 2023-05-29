# SIGMA
Special Interest Group Management Assistant

Event Management System für die EAST Convention

## Setup
### System requirements
- PHP8.0 minimum
- PHP8 mysql extension ``
- Linux or WSL2 with linux (recommended)
- Laravel
    -  You can install it with `composer global require laravel/installer`

### Local installation
1. Clone git repository
2. Run command `composer install` in project root
3. Copy file `.env.example` to `.env`
4. Add your local database credentials
5. Generate app key with this command `php artisan key:generate`
6. Test connection with `php artisan db`, when successful, exit with typing `exit`
7. Migrate Database `php artisan migrate`
8. Open mysql console with `php artisan db`
9. Create new user in db with `INSERT INTO users (name, email, is_admin) VALUES ("NICNAME", "YOUREMAIL@sachsenfurs.de", true);` This is required because the app is connected to authentication is passed to an external provider 
10. php artisan serve