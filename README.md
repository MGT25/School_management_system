# 🏫 School Management System

A comprehensive **PHP-based School Management System** designed to streamline academic administration through dedicated dashboards for **Admins**, **Teachers**, and **Students**. Built with **PHP 7.4**, **MySQL**, **Bootstrap 5**, and **custom CSS**, this system provides robust management of student enrollment, grading, and course assignments — all secured through login, signup, and logout functionalities.

🔗 GitHub Repository: [MGT25/school-management-system](https://github.com/MGT25/school-management-system)

---

## 📋 Table of Contents

- [Features](#features)
- [System Requirements](#system-requirements)
- [Technology Stack](#technology-stack)
- [Screenshots](#screenshots)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage Guide](#usage-guide)
- [Security Considerations](#security-considerations)
- [Contributing](#contributing)
- [License](#license)
- [Contact](#contact)

---

## ✅ Features

### 🧑‍💼 Admin Dashboard
- Secure login/logout
- Add, view, and manage students
- Add, view, and manage teachers
- Create and manage courses
- Enroll students and teachers into courses

### 👨‍🏫 Teacher Dashboard
- Secure login/logout
- View assigned courses
- Assign grades to enrolled students

### 🎓 Student Dashboard
- Signup/login/logout
- View enrolled courses
- View assigned grades

### 🔐 Authentication
- Secure **login/logout** for all users
- **Signup** available for student accounts
- Session-based access control and user redirection

### 🎨 UI & Custom Styling
- Built with **Bootstrap 5** for responsiveness
- Enhanced with **custom CSS** for personalized design and layout
- Fully responsive dashboards for desktop and mobile

---

## 🖥️ System Requirements

| Component        | Requirement     |
|------------------|------------------|
| PHP              | 7.4.x            |
| Database         | MySQL 5.7+       |
| Web Server       | Apache/Nginx     |
| Browser          | Chrome, Firefox, Edge |
| PHP Extensions   | PDO, mbstring, OpenSSL, curl |

---

## 🛠️ Technology Stack

- **Language:** PHP 7.4
- **Database:** MySQL
- **Frontend:** Bootstrap 5 + Custom CSS
- **Authentication:** PHP sessions, password hashing
- **Database Access:** PDO (no raw SQL, secure queries)

---

## 🖼️ Screenshots

> *(Save screenshots in the `/assets/` folder and link them here)*

### Admin Dashboard
![Admin Dashboard](assets/admin-dashboard.png)

### Student Signup Page
![Student Signup](assets/student-signup.png)

### Teacher Grade Entry
![Grade Entry](assets/teacher-grade.png)

---

## 🧰 Prerequisites

- PHP 7.4 installed and running
- MySQL Server 5.7 or higher
- Apache or Nginx Web Server
- Composer (optional)
- Git installed

---

## ⚙️ Installation

1. **Clone the Repository**
   ```bash
   git clone https://github.com/MGT25/school-management-system.git
   cd school-management-system
