# Wheat Distribution API Integration Setup

This document explains how to configure the integration with the Wheat Distribution Application API for fetching verified member details.

## Step 1: Create API Client in Wheat Distribution Application

First, you need to create an external API client in the Wheat Distribution Application.

### Option A: Using Seeder (Recommended)

1. Navigate to the wheat_distribution application directory
2. Run the seeder to create the API client:

```bash
cd /path/to/wheat_distribution
php artisan db:seed --class=ZakatApplicationApiClientSeeder
```

This will create an API client with:
- **Username**: `zakat_application`
- **Password**: A randomly generated password (displayed in the console)

**Important**: Copy the generated password from the console output!

### Option B: Using Artisan Command

Alternatively, you can use the artisan command:

```bash
php artisan create:external-api-client
```

Follow the prompts to create the client.

### Option C: Using Tinker

```bash
php artisan tinker
```

```php
$client = App\Models\ExternalApiClient::create([
    'name' => 'Zakat Disbursement Application',
    'username' => 'zakat_application',
    'password' => 'your_secure_password_here',
    'is_active' => true,
    'description' => 'External API client for Zakat Disbursement Application',
]);
```

## Step 2: Configure Zakat Application

Add the following environment variables to your `.env` file in the zakat application:

```env
# Wheat Distribution API Configuration
WHEAT_API_BASE_URL=http://localhost:8001/api
WHEAT_API_USERNAME=zakat_application
WHEAT_API_PASSWORD=your_generated_password_here
WHEAT_API_TOKEN=  # Optional: If you have a pre-generated token, you can set it here
WHEAT_API_TIMEOUT=30
```

**Note**: Replace `your_generated_password_here` with the password generated in Step 1.

## How It Works

1. **API Authentication**: The system will automatically authenticate with the Wheat Distribution API using the provided credentials to obtain a Bearer token.

2. **Member Lookup**: When a user enters a CNIC and clicks "Fetch Details", the system:
   - Validates the CNIC format
   - Calls the Wheat Distribution API to lookup the member
   - If a verified member is found, shows a prompt asking if you want to pre-fill the form
   - If member is not found or not verified, allows manual entry

3. **Pre-filling Form**: If you choose to pre-fill, the following fields will be automatically populated:
   - Full Name
   - Father/Husband Name
   - Mobile Number
   - Date of Birth
   - Gender

## API Endpoint

The integration uses the following endpoint:
- **URL**: `{WHEAT_API_BASE_URL}/external/zakat/member/lookup`
- **Method**: POST
- **Authentication**: Bearer Token
- **Request Body**: `{ "cnic": "12345-1234567-9" }`

## Getting API Credentials

To get API credentials for the Wheat Distribution Application:

1. Access the Wheat Distribution Application admin panel
2. Create an External API Client (if not already created)
3. Use the username and password to configure this application

## Production Setup

When deploying to production, update the `WHEAT_API_BASE_URL` to point to your production Wheat Distribution API URL.

