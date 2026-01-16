Apartment Rental Platform – Laravel API

A RESTful Apartment & Room Rental Platform built with Laravel 12, providing secure authentication, apartment management, image uploads, admin approval workflows, and OTP verification via WhatsApp.

⸻

Features

Authentication & Security
• User registration with WhatsApp OTP verification
• Login using Laravel Sanctum
• Forgot / Reset password using OTP
• Secured API routes with middleware & tokens

Apartments & Rooms
• Add apartments and rooms with multiple images
• Upload and manage apartment photos
• Display approved apartments only
• Advanced filtering by:
• City
• Governorate
• Price range
• Number of rooms
• Apartment type
• Furnished status

Admin Panel (API Based)
• View pending users
• Approve / reject users (with rejection reason)
• View pending apartments
• Approve / reject apartments

📸 Media Handling
• Multiple images per apartment
• Seeder with real apartment & room data
• Public storage handling using Laravel filesystem

Tech Stack
• Laravel 12
• PHP 8.2+
• MySQL
• Laravel Sanctum
• WhatsApp OTP (UltraMsg API)
• Postman
• Composer

Project Structure (Key Parts)
app/
├── Http/
│ ├── Controllers/
│ ├── Requests/
│ └── Middleware/
├── Models/
├── Services/ # OTP Service
└── Helpers/ # WhatsApp Helper

database/
├── migrations/
└── seeders/

storage/app/public/
├── apartments/
└── rooms/

Installation & Setup

Clone the repository

git clone <repository-url>
cd project

Install dependencies

composer install

Environment setup

cp .env.example .env
php artisan key:generate

Configure .env

DB_DATABASE=your_database
DB_USERNAME=root
DB_PASSWORD=

ULTRAMSG_TOKEN=your_token
ULTRAMSG_INSTANCE_ID=your_instance_id
ULTRAMSG_API_URL=https://api.ultramsg.com

Run migrations & seeders

php artisan migrate --seed
Create storage symlink
php artisan storage:link
Run the server
php artisan serve

Authentication
• Uses Laravel Sanctum
• Login returns a Bearer Token
• Include token in request headers:
Authorization: Bearer YOUR_TOKEN

API Examples:
Register
POST /api/register
Verify OTP
POST /api/verify-otp
Login
POST /api/login
Get Approved Apartments
GET /api/apartments
Create Apartment (Authenticated)
POST /api/apartments

Testing
• All APIs tested using Postman
• Supports environment variables in Postman

Notes
• Users can submit up to 5 apartments per 24 hours
• Exceeding the limit temporarily blocks the account
• Apartments are visible to the public only after admin approval

Author

Motaz Al-Masri
Backend Developer – Laravel
Faculty of Informatics Engineering
