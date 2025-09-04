#!/bin/bash

# Wait for database to be ready
echo "Waiting for database connection..."
sleep 10

# Test database connection
echo "Testing database connection..."
DB_CONNECTED=false

# Try to connect to database
if timeout 10 php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'Database connection successful'; } catch (Exception \$e) { echo 'Database connection failed: ' . \$e->getMessage(); exit(1); }" 2>/dev/null; then
    echo "Database connection successful"
    DB_CONNECTED=true
else
    echo "Database connection failed, continuing without database operations"
    DB_CONNECTED=false
fi

# Clear Laravel caches (without database dependency)
echo "Clearing Laravel caches..."
php artisan config:clear --no-interaction || echo "Config clear failed"
php artisan route:clear --no-interaction || echo "Route clear failed"
php artisan view:clear --no-interaction || echo "View clear failed"

# Generate app key if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    echo "Generating new APP_KEY..."
    php artisan key:generate
fi

# Run migrations only if database is connected
if [ "$DB_CONNECTED" = true ]; then
    echo "Running database migrations..."
    php artisan migrate --force --no-interaction || echo "Migrations failed"
else
    echo "Skipping migrations - database not connected"
fi

# Start Apache
echo "Starting Apache..."
apache2-foreground
