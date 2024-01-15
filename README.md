# SIGMA
Special Interest Group Management Assistant

Event Management System für die EAST Convention

## Setup
- PHP8.1 minimum
### System requirements
- PHP mysql extension
- Linux or WSL2 with linux (recommended)

### Local installation
1. Clone git repository
2. Copy file `.env.example` to `.env`
3. Add your local database credentials
4. Run command `composer install` and `npm run build` (or `npm run dev` to listen for any changes)
5. Generate app key with this command `php artisan key:generate`
6. (Optional) Test connection with `php artisan db`, when successful, exit with typing `exit`
7. Create tables in database `php artisan migrate`
8. (Optional) Fill with testing data `php artisan db:seed` or if you want to refresh the whole database `php artisan migrate:fresh --seed`
9. run local webserver with `php artisan serve` [`--port=80`]
