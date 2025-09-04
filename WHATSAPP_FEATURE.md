# WhatsApp Floating Button Feature

## Overview
A floating WhatsApp button that appears on all pages of the Novus Institute website, allowing visitors to easily chat with reception.

## Features

### ✨ **Visual Design**
- **Floating Position**: Fixed position at bottom-right corner
- **WhatsApp Branding**: Official WhatsApp green gradient colors
- **Smooth Animations**: Hover effects, pulse animation, scale transforms
- **Responsive**: Adapts to mobile and desktop screens
- **Tooltip**: Shows "Chat with us on WhatsApp" on hover

### 🔧 **Functionality**
- **Direct WhatsApp Link**: Opens WhatsApp with pre-filled message
- **Configurable**: Phone number and default message can be changed via config
- **Cross-Platform**: Works on all devices (mobile/desktop)
- **Analytics Ready**: Click tracking capability

## Configuration

### Environment Variables
Add these to your `.env` file:

```env
WHATSAPP_PHONE=254724301007
WHATSAPP_DEFAULT_MESSAGE="Hello! I would like to learn more about Novus Institute programs."
WHATSAPP_ENABLED=true
```

### Default Configuration
If environment variables are not set, the system uses these defaults:
- **Phone**: `254724301007` (Novus Institute number)
- **Message**: `"Hello! I would like to learn more about Novus Institute programs."`
- **Enabled**: `true`

## Implementation Details

### Files Modified/Created

1. **Component**: `resources/js/Components/WhatsAppButton.vue`
   - Main floating button component
   - Handles hover effects and animations
   - Fetches configuration from backend

2. **Layouts Updated**:
   - `resources/js/Guest/Layouts/Guest.vue` - Guest pages
   - `resources/js/Guest/Layouts/Blank.vue` - Auth pages  
   - `resources/js/System/Layouts/AppLayout.vue` - Admin pages

3. **Backend Configuration**:
   - `config/app.php` - Added WhatsApp configuration section
   - `app/Http/Controllers/Admin/GeneralController.php` - Added API endpoint
   - `routes/web.php` - Added public API route

4. **App Registration**:
   - `resources/js/app.js` - Registered component globally

### API Endpoint
- **URL**: `/api/whatsapp-config`
- **Method**: GET
- **Response**: JSON with phone, message, and enabled status

## Usage

### For Visitors
1. The WhatsApp button appears on every page
2. Click the floating green button
3. WhatsApp opens with pre-filled message
4. User can modify message and send

### For Administrators
1. Configure settings in `.env` file
2. Phone number should include country code (e.g., 254 for Kenya)
3. Message can be customized for different purposes

## Customization

### Styling
The component uses Tailwind CSS classes and custom CSS animations. Key styling features:

```css
/* WhatsApp brand colors */
background: linear-gradient(135deg, #25d366, #128c7e);

/* Hover effects */
transform: scale(1.1);
box-shadow: 0 6px 20px rgba(37, 211, 102, 0.6);

/* Pulse animation */
@keyframes pulse {
  0% { box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4); }
  50% { box-shadow: 0 4px 12px rgba(37, 211, 102, 0.8), 0 0 0 10px rgba(37, 211, 102, 0.1); }
  100% { box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4); }
}
```

### Positioning
- **Desktop**: Bottom-right, 20px from edges
- **Mobile**: Bottom-right, 15px from edges
- **Z-index**: 1000 (above other content)

## Browser Compatibility
- ✅ Chrome/Chromium
- ✅ Firefox
- ✅ Safari
- ✅ Edge
- ✅ Mobile browsers (iOS Safari, Chrome Mobile)

## Performance
- **Lightweight**: Minimal impact on page load
- **Lazy Loading**: Configuration fetched after component mount
- **Caching**: Configuration cached in component state
- **No Dependencies**: Uses only Vue.js and native browser APIs

## Security
- **External Link**: Opens in new tab with `rel="noopener noreferrer"`
- **No Data Collection**: Only tracks clicks for analytics (optional)
- **Configurable**: Can be disabled via environment variable

## Troubleshooting

### Button Not Appearing
1. Check if `WHATSAPP_ENABLED=true` in `.env`
2. Verify component is registered in `app.js`
3. Check browser console for errors

### Wrong Phone Number
1. Update `WHATSAPP_PHONE` in `.env`
2. Ensure country code is included
3. Clear browser cache

### Message Not Pre-filled
1. Check `WHATSAPP_DEFAULT_MESSAGE` in `.env`
2. Verify API endpoint `/api/whatsapp-config` is accessible
3. Check network tab for API errors

## Future Enhancements
- [ ] Multiple phone numbers support
- [ ] Department-specific messages
- [ ] Click analytics dashboard
- [ ] Custom branding options
- [ ] A/B testing for different messages
