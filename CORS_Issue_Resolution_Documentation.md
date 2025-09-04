# Laravel + Vue + Inertia CORS Issue Resolution Documentation

## Problem Summary

**Issue**: Blank screen with CORS (Cross-Origin Resource Sharing) errors in Laravel + Vue + Inertia application running on Laravel Valet with HTTPS.

**Environment**: 
- Laravel 10.x
- Vue 3.x
- Inertia.js
- Laravel Valet (HTTPS enabled)
- Vite as build tool

---

## Root Cause Analysis

### 1. Mixed Content Issue
The primary problem was **mixed content blocking**:
- **HTTPS Site**: `https://novus.test` (Laravel Valet with SSL)
- **HTTP Assets**: `http://127.0.0.1:5173` (Vite development server)
- **Browser Policy**: Modern browsers block HTTP resources on HTTPS pages

### 2. CORS Policy Violation
Secondary issue was **CORS policy violation**:
- **Origin**: `https://novus.test`
- **Target**: `http://127.0.0.1:5173`
- **Error**: "No 'Access-Control-Allow-Origin' header is present"

### 3. Asset Loading Strategy
Laravel was detecting the Vite development server and serving development assets instead of production assets:
```html
<!-- Problematic Development Assets -->
<script type="module" src="http://127.0.0.1:5173/@vite/client"></script>
<script type="module" src="http://127.0.0.1:5173/resources/js/app.js"></script>
```

---

## Error Messages Observed

### Browser Console Errors
```
1. Access to script at 'http://127.0.0.1:5173/@vite/client' from origin 'https://novus.test' 
   has been blocked by CORS policy: No 'Access-Control-Allow-Origin' header is present.

2. GET http://127.0.0.1:5173/@vite/client net::ERR_FAILED 200 (OK)

3. Access to script at 'http://127.0.0.1:5173/resources/js/app.js' from origin 'https://novus.test' 
   has been blocked by CORS policy: No 'Access-Control-Allow-Origin' header is present.

4. GET http://127.0.0.1:5173/resources/js/app.js net::ERR_FAILED 200 (OK)
```

### Laravel Log Errors
```
Please provide a valid cache path.
InvalidArgumentException: Please provide a valid cache path.
```

---

## Solution Implementation

### Step 1: Stop Vite Development Server
```bash
pkill -f vite
```

**Why**: Prevents Laravel from detecting development server and serving development assets.

### Step 2: Fix Storage Permissions
```bash
chmod -R 775 storage/ bootstrap/cache/
```

**Why**: Resolves "Please provide a valid cache path" error.

### Step 3: Clear Laravel Caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

**Why**: Removes cached references to development server URLs.

### Step 4: Update Vite Configuration
```javascript
// vite.config.js
export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/css/ck-editor.css',
                'resources/css/app.css'
            ],
            refresh: true,
            detectTls: false, // Disable TLS detection
        }),
        // ... other plugins
    ],
    server: {
        host: '127.0.0.1',
        port: 5173,
    },
    // ... rest of config
});
```

**Why**: `detectTls: false` prevents Laravel from automatically detecting and using development server.

### Step 5: Build Production Assets
```bash
npm run build
```

**Why**: Creates optimized production assets in `/public/build/assets/` directory.

### Step 6: Verify Asset Serving
```bash
# Check if built assets are being served
curl -s https://novus.test | grep -E "(script|link)" | grep build

# Verify assets are accessible
curl -s -o /dev/null -w "%{http_code}" https://novus.test/build/assets/app-3fe0e14b.js
```

**Expected Output**: 200 status code and built asset URLs.

---

## Before vs After

### Before (Problematic)
```html
<!-- Development Assets (Causing CORS Errors) -->
<script type="module" src="http://127.0.0.1:5173/@vite/client"></script>
<script type="module" src="http://127.0.0.1:5173/resources/js/app.js"></script>
<link rel="stylesheet" href="http://127.0.0.1:5173/resources/css/app.css" />
```

### After (Working)
```html
<!-- Production Assets (No CORS Issues) -->
<link rel="preload" as="style" href="/build/assets/app-e619ff32.css" />
<link rel="preload" as="style" href="/build/assets/ck-editor-8e2de217.css" />
<link rel="modulepreload" href="/build/assets/app-3fe0e14b.js" />
<link rel="stylesheet" href="/build/assets/app-e619ff32.css" />
<script type="module" src="/build/assets/app-3fe0e14b.js"></script>
```

---

## Development Workflow

### For Production (Recommended)
```bash
# Build assets
npm run build

# Access site
https://novus.test
```

### For Development (Optional)
```bash
# Start Vite development server
npm run dev

# Access site via HTTP (not HTTPS)
http://novus.test
```

**Note**: Use HTTP for development to avoid mixed content issues.

---

## Prevention Measures

### 1. Environment-Specific Configuration
```javascript
// vite.config.js
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js'],
            refresh: true,
            detectTls: process.env.NODE_ENV === 'development' ? true : false,
        }),
    ],
});
```

### 2. Automated Build Process
```json
// package.json
{
    "scripts": {
        "dev": "vite",
        "build": "vite build",
        "preview": "vite preview"
    }
}
```

### 3. Deployment Checklist
- [ ] Run `npm run build`
- [ ] Clear Laravel caches
- [ ] Verify built assets are accessible
- [ ] Test HTTPS functionality

---

## Troubleshooting Guide

### If CORS Errors Persist

1. **Check for Running Vite Processes**
   ```bash
   ps aux | grep vite | grep -v grep
   ```

2. **Verify Port Usage**
   ```bash
   lsof -i :5173
   ```

3. **Check Asset URLs**
   ```bash
   curl -s https://novus.test | grep -E "(vite|127.0.0.1)"
   ```

4. **Clear Browser Cache**
   - Hard refresh (Ctrl+Shift+R)
   - Clear browser cache completely

### If Built Assets Not Loading

1. **Check File Permissions**
   ```bash
   ls -la public/build/assets/
   ```

2. **Verify Asset Existence**
   ```bash
   curl -I https://novus.test/build/assets/app-3fe0e14b.js
   ```

3. **Rebuild Assets**
   ```bash
   rm -rf public/build/
   npm run build
   ```

---

## Key Takeaways

1. **Mixed Content**: HTTPS sites cannot load HTTP resources
2. **CORS Policy**: Cross-origin requests require proper headers
3. **Asset Strategy**: Use production assets for HTTPS sites
4. **Cache Management**: Clear caches when switching asset sources
5. **Development vs Production**: Different configurations for different environments

---

## Best Practices

1. **Always build assets for production**
2. **Use HTTPS consistently in production**
3. **Clear caches after configuration changes**
4. **Monitor browser console for CORS errors**
5. **Test both development and production environments**

---

## Conclusion

The CORS issue was resolved by:
1. Stopping the Vite development server
2. Building production assets
3. Configuring Laravel to use built assets
4. Clearing all caches
5. Ensuring proper file permissions

This approach ensures the application works correctly with HTTPS while maintaining development capabilities when needed.

---

*Documentation created for Laravel + Vue + Inertia CORS Issue Resolution*
*Date: August 31, 2025*
