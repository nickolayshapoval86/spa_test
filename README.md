composer install
npm install
cp .env.example .env
php artisan key:generate
* put database access credentials to .env *
npm run dev
php artisan serve