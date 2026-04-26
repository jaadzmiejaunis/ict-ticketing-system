# GayaCare Support System

GayaCare Support is a centralized ICT support ticketing system designed for institutional hardware, software, and network management.

## 🛠 Prerequisites

Before installing, ensure you have the following tools installed:
* **[Laravel Herd](https://herd.laravel.com/)** (Recommended for PHP/Web environment)
* **[XAMPP](https://www.apachefriends.org/)** (For MySQL database management)
* **[Node.js & npm](https://nodejs.org/)** (For frontend assets)
* **[Git](https://git-scm.com/)**
* **[VS Code](https://code.visualstudio.com/)** (Or your preferred IDE)

---

## 🚀 Installation & Setup

1.  **Clone the Repository**
    Open your terminal or VS Code and navigate to your Herd sites directory (usually `C:/Users/YOUR_USER/Herd`):
    ```bash
    git clone https://github.com/jaadzmiejaunis/ict-ticketing-system.git
    ```

2.  **Install Dependencies**
    Open the project folder in VS Code. In the terminal, run:
    ```bash
    composer run setup
    ```
    *This command will install PHP packages, create your `.env` file, and generate your application key.*

3.  **Environment Configuration**
    Open the `.env` file in your root directory and update the following values:

    * **App Name:**
        `APP_NAME="GayaCare Support"`
    * **Google reCAPTCHA API:**
        ```env
        RECAPTCHA_SITE_KEY=6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI
        RECAPTCHA_SECRET_KEY=6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe
        ```
    * **Brevo (Email) API:**
        ```env
        BREVO_API_KEY=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
        MAIL_FROM_ADDRESS="youremailaddress@example.com"
        MAIL_FROM_NAME="GayaCare Support System"
        ```

4.  **Create Admin Account**
    Since public registration is disabled, create your initial admin account via Tinker:
    ```bash
    php artisan tinker
    ```
    Inside the Tinker shell, paste the following:
    ```php
    App\Models\User::create([
        'name' => 'Admin',
        'email' => 'admin@example.com',
        'password' => Hash::make('password123'),
        'role' => 'admin',
        'is_active' => true,
    ]);
    ```
    Press `Ctrl + C` to exit.

---

## 💻 Running the Application

### Option A: Local Development (via Herd)
1. Open the **Laravel Herd** dashboard.
2. Go to **Sites** and locate `ict-ticketing-system.test`.
3. Click the URL or visit `http://ict-ticketing-system.test` in your browser.

### Option B: Local Network Access (Multiple Devices)
To access the system from other devices on the same Wi-Fi:

1.  **Find your IP Address:** Open CMD and type `ipconfig`. Look for your `IPv4 Address` (e.g., `192.168.0.10`).
2.  **Update `.env`:** Set `APP_URL=http://192.168.0.10:8000`.
3.  **Compile Assets:**
    ```bash
    npm run dev
    ```
4.  **Serve the Application:** In a new terminal tab, run:
    ```bash
    php artisan serve --host=192.168.0.10 --port=8000
    ```
5.  **Access:** Open any browser and enter `http://192.168.0.10:8000`.

---
> **Note:** Ensure your database is running in XAMPP (MySQL) before starting the application.

Now you are able to use the system.
