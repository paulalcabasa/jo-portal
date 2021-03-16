
## HOW TO INSTALL THE BACKEND

1. composer install
2. rename .env.example to .env
3. Run php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
4. Run php artisan jwt:secret
5. Run php artisan key:generate
6. Always run php artisan optimize whenever there are changes in route or env file

## HOW TO INSTALL THE FRONTEND

1. cd frontend
2. Rename .env.development.local.sample to .env.development.local
3. Rename .env.production.local.sample to .env.production.local
4. npm install
