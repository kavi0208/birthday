# 75th Birthday Invitation Card

A modern, elegant invitation card with server-side RSVP tracking using Apache2 and PHP. Features WhatsApp-optimized link preview for elegant sharing.

## Files Included

1. **invitation.html** - The main invitation card with Open Graph meta tags
2. **rsvp.html** - The RSVP confirmation page
3. **save_rsvp.php** - PHP script for saving RSVP data
4. **preview-generator.html** - Tool to help you create the WhatsApp preview image
5. **preview.jpg** - Preview image for WhatsApp/social media sharing (you need to create this using preview-generator.html)

## Requirements

- Apache2 web server
- PHP 7.0 or higher

## Quick Start

### Running Locally with Apache2

1. **Install Apache2 and PHP** (if not already installed)

   On Ubuntu/Debian:
   ```bash
   sudo apt update
   sudo apt install apache2 php libapache2-mod-php
   ```

2. **Copy files to web directory**
   ```bash
   sudo cp -r /tmp/bday/* /var/www/html/birthday/
   sudo chown -R www-data:www-data /var/www/html/birthday/
   sudo chmod 755 /var/www/html/birthday/
   ```

3. **Create rsvps directory with write permissions**
   ```bash
   sudo mkdir -p /var/www/html/birthday/rsvps
   sudo chown www-data:www-data /var/www/html/birthday/rsvps
   sudo chmod 755 /var/www/html/birthday/rsvps
   ```

4. **Open in browser**
   - Go to: http://localhost/birthday/invitation.html
   - All RSVPs will be saved to the `rsvps/` folder

## WhatsApp Sharing Setup

The invitation is optimized for sharing on WhatsApp with a beautiful preview card. When someone receives your link, they'll see an elegant preview image with the event details.

### Setting Up the Preview Image

1. **Create the preview image** (Recommended size: 1200x630 pixels)

   **Option A: Use the Preview Generator (Easiest)**
   - Open `preview-generator.html` in your browser
   - The card shown is already perfectly sized (1200x630px)
   - Take a screenshot of just the black/gold card
   - Save as `preview.jpg`

   **Option B: Screenshot the Full Invitation**
   - Open `invitation.html` in your browser
   - Take a screenshot of the invitation
   - Crop to 1200x630 pixels
   - Save as `preview.jpg`

   **Option C: Design Your Own**
   - Use Canva, Photoshop, or any image editor
   - Create a 1200x630 pixel image
   - Include: "75th Birthday", "Mr Neerunjun Daboo", "27 Dec 2025", champagne glasses
   - Use black and gold colors to match the theme
   - Save as `preview.jpg`

2. **Upload the preview image**
   ```bash
   cp preview.jpg /var/www/html/birthday/preview.jpg
   ```

3. **Update the meta tags** in `invitation.html`

   Replace `https://yourwebsite.com` with your actual website URL:
   ```html
   <meta property="og:url" content="https://yourdomain.com/birthday/invitation.html">
   <meta property="og:image" content="https://yourdomain.com/birthday/preview.jpg">
   ```

4. **Test the preview**
   - Use Facebook's Sharing Debugger: https://developers.facebook.com/tools/debug/
   - Enter your invitation URL
   - Click "Scrape Again" to see the preview
   - This is how it will look on WhatsApp

### How to Share on WhatsApp

Once everything is set up:

1. Copy your invitation URL: `https://yourdomain.com/birthday/invitation.html`
2. Share it on WhatsApp
3. WhatsApp will automatically show the preview card with your image
4. Recipients click on the preview to open the full invitation
5. They can then RSVP directly

### Deploy Online

To make the invitation accessible online, upload to any web hosting with Apache2 and PHP:

**Hosting options:**
- **Shared hosting** (most common hosting providers support Apache/PHP)
- **VPS** (DigitalOcean, Linode, Vultr)
- **cPanel hosting** (very common and easy to use)

**Steps:**
1. Upload all files (invitation.html, rsvp.html, save_rsvp.php) via FTP/SFTP
2. Ensure the web server has write permissions to the directory (for creating `rsvps/` folder)
3. Access your site URL
4. All RSVP responses will be saved as text files in the `rsvps/` directory

## Customization

### Edit Invitation Details

Open `invitation.html` and replace the placeholders:

```html
<span>[Guest of Honor's Name]</span>  → Replace with the birthday person's name
<span>[Date]</span>                   → Replace with event date
<span>[Time]</span>                   → Replace with event time
<span>[Venue Address]</span>          → Replace with venue location
```

### Customize Colors

The invitation uses a purple gradient theme. To change colors, modify the CSS variables in `invitation.html`:

```css
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
```

Replace `#667eea` and `#764ba2` with your preferred colors.

### RSVP Button

The invitation includes a clickable button that directs guests to the RSVP page. The button is styled to match the elegant gold theme of the invitation.

## RSVP Data Collection

The RSVP form automatically saves each response to a separate text file on the server using PHP.

### How It Works:

1. When someone submits the RSVP form, the data is sent to `save_rsvp.php`
2. The PHP script creates a text file with all the person's details
3. Files are saved in the `rsvps/` directory with the format: `RSVP_PersonName_timestamp.txt`
4. A summary log is also maintained in `rsvps/rsvp_log.txt` for quick overview
5. Each file contains:
   - Person's name
   - Phone number
   - Attending status (YES/NO)
   - Number of guests
   - Any message they provided
   - Submission timestamp
   - Event details

### Accessing RSVPs:

- **Local server**: Check the `rsvps/` folder in `/var/www/html/birthday/rsvps/`
- **Deployed server**: Access via FTP/SFTP or your hosting control panel (cPanel, etc.)
- **Quick summary**: View `rsvps/rsvp_log.txt` for a one-line summary of each RSVP

### Security Note:

For production, consider:
- Adding `.htaccess` to protect the `rsvps/` directory from direct web access
- Implementing rate limiting to prevent spam submissions
- Adding CAPTCHA for additional security

## Printing the Invitation

To print the invitation card:
1. Open `invitation.html` in your browser
2. Press Ctrl+P (or Cmd+P on Mac)
3. Adjust print settings for best results
4. The printed version will include all event details and the RSVP button text

## Mobile Optimization

Both pages are fully responsive and work perfectly on:
- Desktop browsers
- Tablets
- Mobile phones

## Browser Compatibility

Works on all modern browsers:
- Chrome, Firefox, Safari, Edge
- iOS Safari, Chrome Mobile, Samsung Internet

## Support

If you have any issues:
- Make sure Apache2 and PHP are installed correctly
- Check Apache error logs: `sudo tail -f /var/log/apache2/error.log`
- Ensure the `rsvps/` directory has write permissions (755 or 775)
- Verify PHP is working: create a test file with `<?php phpinfo(); ?>` and access it

## License

Free to use and customize for personal events.
