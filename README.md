<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Intervention Image Package Requirements

 The `intervention/image` package, used for image processing including watermarking, requires the following PHP extensions:\n\n - **Mbstring PHP Extension**\n - **Image Processing PHP Extension**: You need at least one of GD, Imagick, or libvips installed. GD is commonly included with PHP, while Imagick is recommended for better performance. \n - **Exif PHP Extension**: Highly recommended for correct image orientation handling.\n\n Please ensure these extensions are enabled in your PHP installation if you encounter issues with image processing.\n\n ## Windows (XAMPP) Installation Notes for Imagick\n\n For users on Windows using XAMPP, the Imagick extension needs to be installed by downloading a pre-compiled DLL that matches your specific PHP version, architecture (x86/x64), and thread safety (TS/NTS). These DLLs can be found on the PECL website.\n\n The DLLs for version 3.8.0, which is compatible with various PHP versions including 7.2-8.4, can be downloaded from: [https://pecl.php.net/package/imagick/3.8.0/windows](https://pecl.php.net/package/imagick/3.8.0/windows)\n\n Make sure to select the download link that precisely matches your PHP environment details obtained from `phpinfo()`.\n

## CI/CD Deployment on Shared Hosting (cPanel)

This project is deployed on a shared hosting (cPanel) environment using a CI/CD pipeline that leverages Git (via cPanel's Git Version Control) and a post–receive hook (which runs a deploy script) to automate deployment. Below is a step–by–step guide on how we set up and link our cPanel Git repo (and then trigger deployment) so that pushing from your local machine (or GitHub) updates your live site.

---

### Step 1: Set Up Git Version Control in cPanel

1. **Log in to cPanel.**  
2. In the cPanel dashboard, search for "Git Version Control" (or "Git™ Version Control").  
3. Click "Create" (or "Create Repository").  
4. **Repository Path:**  
   - (Example: `/home/novuiroh/repos/novus.git`)  
   - (Choose a path outside your web root (e.g., not inside `public_html`) for security.)  
5. **Repository Name:**  
   - (Example: "novus")  
6. **Clone Repository:**  
   - (You can initialize an empty repo or clone from an existing remote (e.g., GitHub).)  
   - (If you initialize empty, you'll push from your local machine later.)  
7. Click "Create" (or "Create Repository") to finish.

---

### Step 2: Link Your cPanel Git Repo to GitHub (Optional)

- **If you want to pull from GitHub (or push to GitHub and then pull on cPanel):**  
  - In cPanel's Git Version Control, click "Clone" (or "Clone Repository") and paste your GitHub HTTPS URL (e.g., `https://github.com/yourusername/novus.git`).  
  - (Note: Using HTTPS is recommended for shared hosting; SSH requires extra SSH key setup.)  
- **If you're pushing directly to cPanel (automated deployment):**  
  - You can skip this step (or use a bare repo on cPanel).

---

### Step 3: Clone (or Pull) Your Repo to Your Deployment Directory

- **Clone (if you're starting fresh):**  
  - In cPanel's Terminal (or via SSH), run:  
    ```bash
    cd ~/public_html
    git clone /home/novuiroh/repos/novus.git novus
    ```
- **Pull (if you're updating an existing clone):**  
  - In cPanel's Terminal (or via SSH), run:  
    ```bash
    cd ~/public_html/novus
    git pull origin master
    ```
  (Adjust branch name if you're not on "master.")

---

### Step 4: Set Up a Post–Receive Hook (for Automated Deployment)

- In your cPanel Git repo (e.g., `/home/novuiroh/repos/novus.git/.git/hooks/`), create (or edit) a file named `post–receive` with the following content:
  ```bash
  #!/bin/bash
  sh /home/novuiroh/novus/deploy.sh
  ```
- **Make the hook executable:**  
  (In cPanel's Terminal or via SSH, run:)
  ```bash
  chmod +x /home/novuiroh/repos/novus.git/.git/hooks/post–receive
  ```
- **Also, ensure that your deploy script (`deploy.sh`) is executable:**  
  ```bash
  chmod +x /home/novuiroh/novus/deploy.sh
  ```

---

### Step 5: (Optional) Build Assets Locally and Commit (and Push) Built Files

- **On your local machine (in your project's root folder), run:**  
  ```bash
  npm install
  npm run build
  ```
- **Remove (or comment out) the built asset folders (e.g., "public/js", "public/css") from your `.gitignore`** so that they're committed.  
- **Commit (and push) your changes (including the built files) to GitHub (or your cPanel remote):**  
  ```bash
  git add –all
  git commit –m "Build assets (built locally)"
  git push origin master
  ```
- **If you're pushing to your cPanel remote (for automated deployment), run:**  
  ```bash
  git push production master
  ```
  (where "production" is your cPanel remote.)

---

### Step 6: Trigger Deployment (via Post–Receive Hook)

- **If you're pushing to your cPanel Git remote (or bare repo), your post–receive hook (which calls `deploy.sh`) will run automatically.**  
- **If you're pushing to GitHub (and then pulling on cPanel), you'll need to manually pull (or set up a webhook) to trigger your deploy script.**

---

### Additional Resources

- For a video walk–through (and further details) on setting up Git (and linking to GitHub) in cPanel, see:  
  [YouTube: Setting Up Git Version Control in cPanel (CI/CD for Shared Hosting)](https://www.youtube.com/watch?v=CUltSMH2EVU)

---

