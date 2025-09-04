#!/bin/bash

# Wait for database to be ready
echo "Waiting for database connection..."
sleep 10

# Test database connection
echo "Testing database connection..."
php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database connection successful';" || echo "Database connection failed, continuing anyway..."

# Clear Laravel caches (without database)
echo "Clearing Laravel caches..."
php artisan config:clear || echo "Config clear failed"
php artisan route:clear || echo "Route clear failed"
php artisan view:clear || echo "View clear failed"
php artisan cache:clear || echo "Cache clear failed"

# Generate app key if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    echo "Generating new APP_KEY..."
    php artisan key:generate
fi

# Run migrations
echo "Running database migrations..."
php artisan migrate --force

# Start Apache
echo "Starting Apache..."
apache2-foreground
