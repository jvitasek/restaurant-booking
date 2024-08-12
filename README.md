# :calendar: Restaurant Booking App
This is just a simple restaurant booking app.

### Features
- Laravel Breeze for authentication
- Filament Forms + Tables :heart:
- `spatie/permission` for admin/guest roles

### Setup
- clone the app
- run `composer install`
- run `npm i && npx vite build`
- copy `.env.example` to `.env` and set up your DB connection
- run `php artisan key:generate`
- run `php artisan migrate:fresh --seed`

### Testing
- `composer pest` to run all feature and unit tests
- `composer phpstan` to run static analysis
- `composer pint` to fix code style

### Wishlist
- [ ] dynamic opening hours
- [ ] e-mail booking confirmation with a signed link to cancel the booking
- [ ] Filament panel for easier management of clients/bookings
