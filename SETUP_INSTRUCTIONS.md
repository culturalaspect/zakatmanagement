# Setup Instructions

## Database Setup

1. **Create the MySQL Database:**
   ```sql
   CREATE DATABASE zakat_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

2. **Verify .env Configuration:**
   The `.env` file has been created with the following database settings:
   - Database: `zakat_db`
   - Host: `127.0.0.1`
   - Port: `3306`
   - Username: `root`
   - Password: (empty)

   If your MySQL root user has a password, update the `DB_PASSWORD` in `.env` file.

## Running Migrations

After creating the database, run the migrations:

```bash
cd laravel_project
php artisan migrate
```

This will create all the required tables:
- users
- districts
- zakat_council_members
- schemes
- scheme_categories
- fund_allocations
- district_quotas
- scheme_distributions
- local_zakat_committees
- lzc_members
- phases
- beneficiaries
- beneficiary_representatives
- notifications
- password_reset_tokens
- sessions

## Seeding Initial Data (Optional)

To seed the database with initial data:

```bash
php artisan db:seed
```

## Starting the Development Server

```bash
php artisan serve
```

The application will be available at: `http://localhost:8000`

## Default Login Credentials

After seeding, you can use these credentials:
- **Super Admin**: admin@zakat.gov.pk / password
- **Administrator HQ**: adminhq@zakat.gov.pk / password

## Important Notes

1. Make sure MySQL is running on your localhost
2. Ensure the database `zakat_db` exists before running migrations
3. If you encounter any issues, check the `.env` file configuration
4. The application key has been generated automatically

