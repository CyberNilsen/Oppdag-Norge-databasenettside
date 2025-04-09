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

   - Firstly you have to go 3 times into the folder like this: Oppdag-Norge-databasenettside\Oppdag-Norge-databasenettside\Oppdag_Norge_databasenettside then add the third subfolder into htdocs.
   - Path: `C:/xampp/htdocs/Oppdag_Norge_databasenettside/`

5. **Start the XAMPP servers**:

   - Open XAMPP and start **Apache** and **MySQL**.

6. **Access the website**:

   Navigate to:

   ```plaintext
   http://Oppdag_Norge_databasenettside/index.php




# ⚙️ Composer Installation

If you need to install or update **Composer**, follow the steps below:

1. **Check if Composer is installed** by running the following command in your terminal:

    ```bash
    composer --version
    ```

2. If Composer is **not installed** or needs to be **updated**, follow these steps:

    - **Install Composer** (if not already installed):
      
      - For **Windows**, download and run the installer from [getcomposer.org](https://getcomposer.org/download/).
      
      - For **macOS** and **Linux**, run the following command in the terminal:

        ```bash
        curl -sS https://getcomposer.org/installer | php
        ```

    - **Update Composer** (if already installed):

      Run this command in your terminal:

      ```bash
      composer self-update
      ```

3. Once Composer is installed or updated, you can install the necessary dependencies for this project by running:

    ```bash
    composer install
    ```

---

## 🚀 How to Use

1. Clone or download the repository to your local machine.
2. Set up the `.env` file as described in the setup instructions.
3. Start XAMPP, configure your MySQL database, and run the website locally.
4. Register as a user and experience the email functionality with SendGrid.

---

## ⚙️ Technologies Used

- **PHP** (for backend logic)
- **MySQL** (for database storage)
- **SendGrid** (for email notifications)
- **XAMPP** (for local server environment)
- **GitHub Pages** (for hosting the project documentation)

---

## 📜 License

This project is licensed under the MIT License.

---

> Developed with curiosity and passion by Andreas (CyberNilsen)

