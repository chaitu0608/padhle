# Padhle - Somaiya Student & Admin Portal

## 📌 Overview
**Padhle** is a **student and admin portal** designed for Somaiya institutions, providing a seamless login system, dashboard, and access to academic resources. Built with modern web technologies, it ensures a smooth and efficient experience for students and administrators.

## 🚀 Features
- 🔐 **Secure Login & Authentication** for students and admins
- 🎓 **Student Dashboard** with access to assignments, attendance, and notices
- 📑 **Admin Panel** to manage student data, announcements, and resources
- 📊 **User-Friendly UI** powered by Tailwind CSS & Bootstrap
- 🖥️ **Responsive Design** for seamless use on all devices
- ⚡ **Fast & Scalable** backend with PHP & MySQL

## 🛠️ Tech Stack
- **Frontend:** HTML, CSS, JavaScript, Tailwind CSS, Bootstrap
- **Backend:** PHP
- **Database:** MySQL
- **Additional Libraries:** jQuery, AJAX (for smooth interactions)

## 🎯 Installation & Setup
1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/padhle.git
   cd padhle
   ```
2. Set up a local server using **XAMPP** or **MAMP**.
3. Import the database:
   - Open **phpMyAdmin**.
   - Create a new database named `padhle_db`.
   - Import `padhle_db.sql` (provided in the repo).
4. Configure database connection in `config.php`:
   ```php
   $servername = "localhost";
   $username = "root";
   $password = "";
   $dbname = "padhle_db";
   ```
5. Start the server and access the portal via:
   ```
   http://localhost/padhle/
   ```

## 📌 Usage
- **Students** can log in to check attendance, submit assignments, and view notices.
- **Admins** can manage student records, post announcements, and track academic data.

## 🔥 Future Enhancements
- 📱 **Mobile App Integration**
- 🛡️ **Two-Factor Authentication** for extra security
- 📢 **Live Notifications & Messaging System**
- 📊 **Analytics Dashboard** for student performance tracking

## 🤝 Contributing
Pull requests are welcome! If you'd like to improve Padhle, fork the repo and submit a PR. Let's build something awesome together! 🚀

## 📜 License
This project is **open-source** and free to use. Feel free to modify and enhance it for educational purposes.

---
💡 **Padhle - Your Gateway to an Efficient Academic Experience!**
