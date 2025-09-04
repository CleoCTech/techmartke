# Laravel+Vue+Inertia Application Deployment Guide

This guide provides step-by-step instructions for deploying the Novus Laravel+Vue+Inertia application to cPanel hosting.

## Prerequisites

- cPanel hosting account with PHP 8.1+ support
- SSH access (optional but recommended)
- Domain name pointing to your hosting
- Git repository access (for CI/CD method)

## Method 1: Manual Deployment (ZIP Upload)

### Step 1: Prepare Your Application Locally

1. **Build your Vue assets:**
   ```bash
   cd /Users/ceo/Code/novus
   npm run build
   ```

2. **Install production dependencies:**
   ```bash
   composer install --optimize-autoloader --no-dev
   npm ci --production
   ```

3. **Create deployment archive:**
   ```bash
   # Create the archive with all necessary files
   tar -czf novus-deployment.tar.gz \
     --exclude='.git' \
     --exclude='.env' \
     --exclude='.env.example' \
     --exclude='storage/logs/*' \
     --exclude='storage/framework/cache/*' \
     --exclude='storage/framework/sessions/*' \
     --exclude='storage/framework/views/*' \
     --exclude='tests' \
     --exclude='.vscode' \
     --exclude='.idea' \
     --exclude='node_modules/.cache' \
     .
   ```

### Step 2: Upload to cPanel

1. **Access cPanel File Manager:**
   - Log into your cPanel account
   - Click on "File Manager"

2. **Navigate to public_html:**
   - In the left pane, click on `public_html`

3. **Upload and extract:**
   - Click "Upload" and upload `novus-deployment.tar.gz`
   - Right-click on the uploaded file and select "Extract"
   - Extract to `public_html`

### Step 3: Restructure for cPanel

1. **Create Laravel folder outside public_html:**
   ```
   /home/yourusername/
   ├── laravel/         # Laravel application (without public folder)
   │   ├── app/
   │   ├── bootstrap/
   │   ├── config/
   │   ├── database/
   │   ├── resources/
   │   ├── routes/
   │   ├── storage/
   │   ├── vendor/
   │   ├── node_modules/
   │   └── ...
   └── public_html/     # Web root (contents of Laravel's public folder)
       ├── index.php
       ├── assets/
       ├── build/       # Vite build files
       ├── images/
       └── ...
   ```

2. **Move files:**
   - Select all files and folders EXCEPT the `public` folder
   - Move them to a new folder called `laravel` (outside public_html)
   - Move all contents from the `public` folder to `public_html`

### Step 4: Update Configuration

1. **Update public_html/index.php:**
   ```php
   <?php
   
   use Illuminate\Contracts\Http\Kernel;
   use Illuminate\Http\Request;
   
   define('LARAVEL_START', microtime(true));
   
   if (file_exists($maintenance = __DIR__.'/../laravel/storage/framework/maintenance.php')) {
       require $maintenance;
   }
   
   require __DIR__.'/../laravel/vendor/autoload.php';
   
   $app = require_once __DIR__.'/../laravel/bootstrap/app.php';
   
   $kernel = $app->make(Kernel::class);
   
   $response = $kernel->handle(
       $request = Request::capture()
   )->send();
   
   $kernel->terminate($request, $response);
   ```

2. **Create .env file in laravel folder:**
   ```env
   APP_NAME=Novus
   APP_ENV=production
   APP_KEY=base64:your-generated-key
   APP_DEBUG=false
   APP_URL=https://yourdomain.com
   
   LOG_CHANNEL=stack
   LOG_DEPRECATIONS_CHANNEL=null
   LOG_LEVEL=debug
   
   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password
   
   BROADCAST_DRIVER=log
   CACHE_DRIVER=file
   FILESYSTEM_DISK=local
   QUEUE_CONNECTION=sync
   SESSION_DRIVER=file
   SESSION_LIFETIME=120
   
   MEMCACHED_HOST=127.0.0.1
   
   REDIS_HOST=127.0.0.1
   REDIS_PASSWORD=null
   REDIS_PORT=6379
   
   MAIL_MAILER=smtp
   MAIL_HOST=mailpit
   MAIL_PORT=1025
   MAIL_USERNAME=null
   MAIL_PASSWORD=null
   MAIL_ENCRYPTION=null
   MAIL_FROM_ADDRESS="hello@example.com"
   MAIL_FROM_NAME="${APP_NAME}"
   
   AWS_ACCESS_KEY_ID=
   AWS_SECRET_ACCESS_KEY=
   AWS_DEFAULT_REGION=us-east-1
   AWS_BUCKET=
   AWS_USE_PATH_STYLE_ENDPOINT=false
   
   PUSHER_APP_ID=
   PUSHER_APP_KEY=
   PUSHER_APP_SECRET=
   PUSHER_HOST=
   PUSHER_PORT=443
   PUSHER_SCHEME=https
   PUSHER_APP_CLUSTER=mt1
   
   VITE_APP_NAME="${APP_NAME}"
   VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
   VITE_PUSHER_HOST="${PUSHER_HOST}"
   VITE_PUSHER_PORT="${PUSHER_PORT}"
   VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
   VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
   ```

### Step 5: Setup Database

1. **Create MySQL database in cPanel:**
   - Go to "MySQL Databases" in cPanel
   - Create a new database
   - Create a database user
   - Add user to database with all privileges

2. **Run migrations:**
   ```bash
   cd ~/laravel
   php artisan migrate
   ```

### Step 6: Set Permissions

```bash
chmod -R 755 ~/laravel
chmod -R 775 ~/laravel/storage
chmod -R 775 ~/laravel/bootstrap/cache
chmod 644 ~/laravel/.env
```

### Step 7: Generate Application Key

```bash
cd ~/laravel
php artisan key:generate
```

### Step 8: Optimize for Production

```bash
cd ~/laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Method 2: CI/CD Deployment

### Step 1: Setup GitHub Actions

Create `.github/workflows/deploy.yml`:

```yaml
name: Deploy to cPanel

on:
  push:
    branches: [ main ]

jobs:
  deploy:
    runs-on: ubuntu-latest
    
    steps:
    - name: Checkout code
      uses: actions/checkout@v3
      
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.1'
        extensions: mbstring, xml, ctype, iconv, intl, pdo_mysql, dom, filter, gd, iconv, json, mbstring, pdo, session, tokenizer, xml, ctype, fileinfo, openssl
        
    - name: Setup Node.js
      uses: actions/setup-node@v3
      with:
        node-version: '18'
        cache: 'npm'
        
    - name: Install Composer dependencies
      run: composer install --optimize-autoloader --no-dev --no-interaction --prefer-dist
        
    - name: Install NPM dependencies
      run: npm ci
      
    - name: Build assets
      run: npm run build
      
    - name: Create deployment package
      run: |
        tar -czf deployment.tar.gz \
          --exclude='.git' \
          --exclude='.env' \
          --exclude='.env.example' \
          --exclude='storage/logs/*' \
          --exclude='storage/framework/cache/*' \
          --exclude='storage/framework/sessions/*' \
          --exclude='storage/framework/views/*' \
          --exclude='tests' \
          --exclude='.vscode' \
          --exclude='.idea' \
          --exclude='node_modules/.cache' \
          .
          
    - name: Deploy to cPanel
      uses: appleboy/scp-action@v0.1.4
      with:
        host: ${{ secrets.CPANEL_HOST }}
        username: ${{ secrets.CPANEL_USERNAME }}
        password: ${{ secrets.CPANEL_PASSWORD }}
        port: 22
        source: "deployment.tar.gz"
        target: "/home/${{ secrets.CPANEL_USERNAME }}"
        
    - name: Execute deployment script
      uses: appleboy/ssh-action@v0.1.5
      with:
        host: ${{ secrets.CPANEL_HOST }}
        username: ${{ secrets.CPANEL_USERNAME }}
        password: ${{ secrets.CPANEL_PASSWORD }}
        port: 22
        script: |
          cd /home/${{ secrets.CPANEL_USERNAME }}
          
          # Extract deployment package
          tar -xzf deployment.tar.gz
          
          # Backup existing installation
          if [ -d "laravel" ]; then
            mv laravel laravel_backup_$(date +%Y%m%d_%H%M%S)
          fi
          
          # Move Laravel files
          mkdir -p laravel
          mv app bootstrap config database resources routes storage vendor node_modules composer.json composer.lock package.json package-lock.json vite.config.js tailwind.config.js postcss.config.js .env.example laravel/
          
          # Move public contents to public_html
          cp -r public/* public_html/
          
          # Set permissions
          chmod -R 755 laravel
          chmod -R 775 laravel/storage
          chmod -R 775 laravel/bootstrap/cache
          
          # Run Laravel commands
          cd laravel
          php artisan config:cache
          php artisan route:cache
          php artisan view:cache
          
          # Clean up
          cd ..
          rm deployment.tar.gz
```

### Step 2: Setup GitHub Secrets

Add these secrets to your GitHub repository:

- `CPANEL_HOST`: Your cPanel server hostname
- `CPANEL_USERNAME`: Your cPanel username
- `CPANEL_PASSWORD`: Your cPanel password

### Step 3: Create Deployment Script

Create `deploy.sh` in your project root:

```bash
#!/bin/bash

# Deployment script for cPanel
set -e

echo "Starting deployment..."

# Variables
CPANEL_USER="your_cpanel_username"
CPANEL_HOST="your_cpanel_host"
REMOTE_PATH="/home/$CPANEL_USER"

# Build assets
echo "Building assets..."
npm run build

# Create deployment package
echo "Creating deployment package..."
tar -czf deployment.tar.gz \
  --exclude='.git' \
  --exclude='.env' \
  --exclude='.env.example' \
  --exclude='storage/logs/*' \
  --exclude='storage/framework/cache/*' \
  --exclude='storage/framework/sessions/*' \
  --exclude='storage/framework/views/*' \
  --exclude='tests' \
  --exclude='.vscode' \
  --exclude='.idea' \
  --exclude='node_modules/.cache' \
  .

# Upload to server
echo "Uploading to server..."
scp deployment.tar.gz $CPANEL_USER@$CPANEL_HOST:$REMOTE_PATH/

# Execute deployment on server
echo "Executing deployment on server..."
ssh $CPANEL_USER@$CPANEL_HOST << 'EOF'
  cd /home/$CPANEL_USER
  
  # Extract deployment package
  tar -xzf deployment.tar.gz
  
  # Backup existing installation
  if [ -d "laravel" ]; then
    mv laravel laravel_backup_$(date +%Y%m%d_%H%M%S)
  fi
  
  # Move Laravel files
  mkdir -p laravel
  mv app bootstrap config database resources routes storage vendor node_modules composer.json composer.lock package.json package-lock.json vite.config.js tailwind.config.js postcss.config.js .env.example laravel/
  
  # Move public contents to public_html
  cp -r public/* public_html/
  
  # Set permissions
  chmod -R 755 laravel
  chmod -R 775 laravel/storage
  chmod -R 775 laravel/bootstrap/cache
  
  # Run Laravel commands
  cd laravel
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  
  # Clean up
  cd ..
  rm deployment.tar.gz
EOF

echo "Deployment completed successfully!"
```

### Step 4: Manual Deployment Commands

For manual deployment using the script:

```bash
# Make script executable
chmod +x deploy.sh

# Run deployment
./deploy.sh
```

---

## Troubleshooting

### Common Issues

1. **500 Internal Server Error:**
   - Check file permissions
   - Verify .env file exists and is readable
   - Check Laravel logs: `~/laravel/storage/logs/laravel.log`

2. **Assets not loading:**
   - Ensure Vite build was completed
   - Check asset paths in your blade templates
   - Verify `public/build` folder exists

3. **Database connection issues:**
   - Verify database credentials in .env
   - Check if database exists and user has proper permissions

4. **Manifest not available:**
   - Ensure the `public` folder contents are in `public_html`
   - Verify `public_html/build/manifest.json` exists

### Performance Optimization

1. **Enable cPanel caching:**
   - Use cPanel's "LiteSpeed Cache" if available
   - Configure browser caching

2. **Database optimization:**
   - Enable query caching
   - Optimize database indexes

3. **Asset optimization:**
   - Enable gzip compression
   - Minify CSS and JavaScript

### Security Considerations

1. **File permissions:**
   - Keep Laravel files outside web root
   - Set proper permissions on sensitive directories

2. **Environment variables:**
   - Never commit .env files
   - Use strong database passwords

3. **SSL/TLS:**
   - Always use HTTPS in production
   - Install SSL certificate in cPanel

---

## Maintenance

### Regular Tasks

1. **Backup:**
   - Database backup: Use cPanel's backup feature
   - File backup: Download laravel folder regularly

2. **Updates:**
   - Keep Laravel and dependencies updated
   - Monitor security advisories

3. **Monitoring:**
   - Check error logs regularly
   - Monitor application performance

### Update Process

1. **For manual deployments:**
   - Follow the deployment steps again
   - Backup before updating

2. **For CI/CD deployments:**
   - Push changes to main branch
   - Monitor GitHub Actions for deployment status

---

## Support

For issues related to:
- **Laravel:** Check Laravel documentation and community forums
- **Vue/Inertia:** Check Inertia.js documentation
- **cPanel:** Contact your hosting provider
- **Deployment:** Check this guide and server logs
