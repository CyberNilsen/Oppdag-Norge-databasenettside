# 🔧 Oppdag-Norge

**Oppdag-Norge** is a website developed to explore and learn more about **web development**, **database integration**, and **email automation** – with a primary focus on **PHP**, **MySQL**, and **SendGrid** integration. This project combines practical web development with hands-on learning in backend technologies.

> ⚠️ **Important:** This project is intended for **educational purposes only**. Ensure you have proper authorization before running any scripts on your own server.

---

## 🧠 What is Oppdag-Norge?

**Oppdag-Norge** is a personal project created to learn more about **PHP**, **MySQL**, and **SendGrid** for email management. The website includes functionalities like user registration, password hashing, and email notifications.

The core features of the project include:
- 🔑 User Registration with secure password handling
- 📧 SendGrid email integration for email notifications
- 🌐 MySQL Database for storing user data

---

## 🖥️ Screenshot

![image](https://github.com/user-attachments/assets/1f09dc6a-66a8-4faf-9c22-139449258a9b)



---

## 📦 Setup Instructions

To run **Oppdag-Norge** locally, follow these steps:

1. **Download** or clone the repository:
    - Clone the repo:  
      `git clone https://github.com/cybernilsen/Oppdag-Norge.git`
  
2. **Create the `.env` file** in the root directory and include the following configuration:

    ```plaintext
    SENDGRID_API_KEY=your_sendgrid_api_key
    SMTP_HOST=smtp.sendgrid.net
    SMTP_PORT=587
    SMTP_FROM_EMAIL=your_sendgrid_email@example.com
    SMTP_FROM_NAME=Oppdag-Norge

    DB_SERVER=localhost
    DB_USERNAME=root
    DB_PASSWORD=
    DB_NAME=oppdagnorge
    ```

3. **Set up the MySQL Database**:

   - Create a database called `oppdagnorge`.
   - Create a table for user data with the following structure:

    ```sql
    CREATE TABLE users (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(100) NOT NULL,
        password VARCHAR(255) NOT NULL,
        two_fa_enabled TINYINT(1) DEFAULT 1,
        two_fa_code VARCHAR(100) DEFAULT NULL,
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        name VARCHAR(255) NOT NULL,
        two_fa_method VARCHAR(50) DEFAULT 'email',
        two_fa_expiry BIGINT(20) DEFAULT NULL
    );
    ```

4. **Move the project folder** to your XAMPP's `htdocs` directory:

   - Path: `C:/xampp/htdocs/Oppdag-Norge/`

5. **Start the XAMPP servers**:

   - Open XAMPP and start **Apache** and **MySQL**.

6. **Access the website**:

   Navigate to:

   ```plaintext
   http://localhost/Oppdag-Norge/index.php
