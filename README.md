# 🎫 Swiffy — Ticket Management System

Swiffy is a modern Laravel-based ticketing platform designed for internal support workflows. It empowers teams to create, assign, track, and resolve tickets with clarity and speed. Built with Livewire, Alpine.js, and TailwindCSS, Swiffy delivers a dynamic, responsive experience for both agents and stakeholders.

---

## 🚀 Features

- **Create, Update, Delete Tickets**  
  Full CRUD support with validation, asset uploads, and dynamic form syncing.

- **Stakeholder & Assignee Management**  
  Assign tickets to users and notify stakeholders via email. Uses pivot relationships for scalable many-to-many linking.

- **Asset Uploads & Previews**  
  Upload multiple files with live previews, removal logic, and update-safe syncing.

- **Dynamic Filtering & Search**  
  Filter tickets by number, assignee, stakeholder, and status. Includes reset logic and query persistence.

- **Dashboard with Charts**  
  Visualize ticket status, assignee performance, and recent activity using Chart.js and Livewire components.

- **Email Notifications**  
  Automatic emails on ticket creation, update, and deletion — sent to assignees and stakeholders.

---

## 🧱 Tech Stack

- **Laravel 12.26.4**
- **Jetstream** (For secure login and password encryption and 2 factor authentication feature)
- **Livewire**
- **Alpine.js**
- **Bootstrap**
- **Chart.js**
- **Blade Components**
- **MySQL**
- **SMTP / Mailgun / SendGrid** (Currently Set to Mail log i.e emails can be viewed in laravel.log file)

---

## 📦 Installation

```bash
git clone https://github.com/VikashGokool86/swiffyTicketBookingApp.git
cd swiffy
composer install
npm install && npm run build
cp .env.example .env
-- ** Add Database details and email details if you have a email server and want to recieve the email
<img width="30" height="30" alt="image" src="https://github.com/user-attachments/assets/4d9611dc-f7f0-4d06-b35d-91382c76ae5c" />
<img width="30" height="30" alt="image" src="https://github.com/user-attachments/assets/92663d46-b4d7-45eb-b250-d470219447a3" />

php artisan key:generate
php artisan migrate
php artisan db:seed
-- This will populate the DB with test tickers and users

```
## 🚀 Features (Screenshots of project)





👨‍💻 Author
Vikash Gokool
Msunduzi, South Africa
📧 vikashgokool@yahoo.com
🔗 GitHub Profile



 




