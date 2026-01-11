# Zakat Beneficiaries Management System

A comprehensive Laravel-based system for managing Zakat fund disbursement among beneficiaries in Gilgit-Baltistan.

## Features

- **Zakat Council Members Management**: Dynamic members with tenure tracking
- **District Management**: Manage districts with population data
- **Scheme Management**: Dynamic schemes with percentages, age restrictions, and categories
- **Fund Allocation**: Track fund releases, installments, district quotas, and scheme distributions
- **Local Zakat Committees**: Manage LZCs with members and tenure tracking
- **Disbursement Phases**: Create and manage multiple phases for fund disbursement
- **Beneficiary Registration**: Register beneficiaries with comprehensive validation
- **Approval Workflow**: Administrator HQ approval/rejection system with remarks
- **Role-Based Access**: Super Admin, Administrator HQ, and District User roles
- **Reports & Dashboards**: District-wise and scheme-wise reporting

## System Requirements

- PHP >= 8.2
- MySQL/MariaDB
- Composer
- Node.js & NPM (for assets, if needed)

## Installation

1. **Navigate to the project directory:**
   ```bash
   cd laravel_project
   ```

2. **Install dependencies:**
   ```bash
   composer install
   ```

3. **Copy environment file:**
   ```bash
   copy .env.example .env
   ```
   (On Linux/Mac: `cp .env.example .env`)

4. **Generate application key:**
   ```bash
   php artisan key:generate
   ```

5. **Configure database in `.env`:**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=zakat_beneficiaries
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Run migrations:**
   ```bash
   php artisan migrate
   ```

7. **Seed initial data:**
   ```bash
   php artisan db:seed
   ```

8. **Start the development server:**
   ```bash
   php artisan serve
   ```

9. **Access the application:**
   - URL: http://localhost:8000
   - Super Admin: admin@zakat.gov.pk / password
   - Administrator HQ: adminhq@zakat.gov.pk / password

## Project Structure

```
laravel_project/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   └── LoginController.php
│   │   │   ├── AdminHQController.php
│   │   │   ├── BeneficiaryController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── DistrictController.php
│   │   │   ├── FundAllocationController.php
│   │   │   ├── LocalZakatCommitteeController.php
│   │   │   ├── LZCMemberController.php
│   │   │   ├── PhaseController.php
│   │   │   ├── ReportController.php
│   │   │   ├── SchemeController.php
│   │   │   └── ZakatCouncilMemberController.php
│   │   └── Middleware/
│   │       └── RoleMiddleware.php
│   └── Models/
│       ├── Beneficiary.php
│       ├── BeneficiaryRepresentative.php
│       ├── District.php
│       ├── DistrictQuota.php
│       ├── FundAllocation.php
│       ├── LocalZakatCommittee.php
│       ├── LZCMember.php
│       ├── Notification.php
│       ├── Phase.php
│       ├── Scheme.php
│       ├── SchemeCategory.php
│       ├── SchemeDistribution.php
│       ├── User.php
│       └── ZakatCouncilMember.php
├── database/
│   ├── migrations/
│   └── seeders/
│       └── DatabaseSeeder.php
├── public/
│   └── assets/ (Theme files from analytic-html)
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php
│       ├── auth/
│       │   └── login.blade.php
│       └── dashboard/
│           └── index.blade.php
└── routes/
    └── web.php
```

## Key Features Explained

### 1. Zakat Council Members
- Manage provincial zakat committee members
- Track tenure (start date, end date)
- Members can be changed dynamically

### 2. Fund Allocation
- Record fund releases from Ministry
- Support multiple installments
- Set district-wise quotas based on population
- Distribute funds among schemes by percentage

### 3. Local Zakat Committees (LZCs)
- Create LZCs for each district
- Manage committee members with tenure
- Track formation date and tenure end date

### 4. Disbursement Phases
- Create multiple phases for fund disbursement
- Set maximum beneficiaries and amount per phase
- Track phase status (draft, open, closed, approved)

### 5. Beneficiary Registration
- Comprehensive validation:
  - CNIC duplicate check (same phase, same scheme)
  - Age restrictions based on scheme
  - Committee member exclusion during tenure
  - Phase limits (beneficiaries count and amount)
- Support for representatives (for underage beneficiaries)
- District users can only register for their district

### 6. Approval Workflow
- District users submit beneficiaries
- Administrator HQ reviews and approves/rejects
- Remarks can be added at each stage
- Notifications sent to district users

### 7. Role-Based Access
- **Super Admin**: Full access to all features
- **Administrator HQ**: Can approve/reject beneficiaries, view all data
- **District User**: Can only register beneficiaries for their district

## Database Schema

The system includes the following main tables:
- `users` - System users with roles
- `districts` - Districts of Gilgit-Baltistan
- `zakat_council_members` - Provincial zakat council members
- `schemes` - Zakat schemes
- `scheme_categories` - Categories within schemes (e.g., Middle School, High School)
- `fund_allocations` - Fund releases and installments
- `district_quotas` - District-wise quotas for each allocation
- `scheme_distributions` - Scheme-wise distribution within district quotas
- `local_zakat_committees` - Local zakat committees
- `lzc_members` - Members of local zakat committees
- `phases` - Disbursement phases
- `beneficiaries` - Registered beneficiaries
- `beneficiary_representatives` - Representatives for underage beneficiaries
- `notifications` - System notifications

## Views to Complete

The following views need to be created (controllers are ready):
- `resources/views/zakat-council-members/` (index, create, edit, show)
- `resources/views/districts/` (index, create, edit, show)
- `resources/views/schemes/` (index, create, edit, show)
- `resources/views/fund-allocations/` (index, create, edit, show)
- `resources/views/local-zakat-committees/` (index, create, edit, show)
- `resources/views/lzc-members/` (index, create, edit, show)
- `resources/views/phases/` (index, create, edit, show)
- `resources/views/beneficiaries/` (index, create, edit, show)
- `resources/views/admin-hq/` (pending-approvals)
- `resources/views/reports/` (index, district-wise, scheme-wise)

You can use the theme files in `public/assets/` and the layout structure in `resources/views/layouts/app.blade.php` as reference.

## Theme Integration

The system uses the **Analytic HTML** theme located in `public/assets/`. All theme assets (CSS, JS, images) are available and integrated into the master layout.

## Important Notes

1. **CNIC Validation**: The system ensures a beneficiary cannot be registered twice in the same phase and scheme combination.

2. **Committee Member Exclusion**: Active LZC members cannot apply for schemes during their tenure.

3. **Age Restrictions**: Some schemes have minimum age requirements. Underage beneficiaries require a representative.

4. **Phase Limits**: Each phase has maximum beneficiaries and amount limits that cannot be exceeded.

5. **District Restrictions**: District users can only access and register beneficiaries for their assigned district.

## Development

To continue development:

1. Complete the remaining views using the theme structure
2. Add form validation and error handling
3. Implement data tables for listing pages
4. Add export functionality for reports
5. Implement JazzCash integration for payments
6. Add more comprehensive reporting features

## License

This project is proprietary software for Zakat & Ushur Department, Gilgit-Baltistan.
