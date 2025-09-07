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
php artisan key:generate
php artisan migrate
php artisan db:seed
-- This will populate the DB with test tickers and users

```
## 🚀 Features (Screenshots of project)
#### login Page
<img width="200" height="2000" alt="image" src="https://github.com/user-attachments/assets/e30517b8-64a8-4733-a348-e9538cfc4493" />

#### Register Page
<img width="200" height="200" alt="image" src="https://github.com/user-attachments/assets/131f60ce-6592-4e36-a095-1263f22c229e" />

#### Already Registered / Login Page
<img width="200" height="200" alt="image" src="https://github.com/user-attachments/assets/0fac2d91-86ad-4fa6-8d06-d08c960f643f" />

#### Forgot Password Page
<img width="200" height="200" alt="image" src="https://github.com/user-attachments/assets/22755ed1-20b0-4cf0-9f3a-320f6bf509a0" />

#### Profile Page (With all the security features as well as session clearing)
<img width="200" height="300" alt="image" src="https://github.com/user-attachments/assets/267fde62-3d8e-4faf-b7e4-a3d192373794" />

#### Ticket Dashboard
<img width="200" height="200" alt="image" src="https://github.com/user-attachments/assets/a1e78290-ee0c-4ccc-a09e-f6a248330f11" /><br>
<img width="200" height="200" alt="image" src="https://github.com/user-attachments/assets/dc5f84bb-7160-4f56-8752-93d29cc3b882" />

#### Create Ticket Page
<img width="200" height="200" alt="image" src="https://github.com/user-attachments/assets/ea3fd3ce-e478-4747-8264-086f06fd87ac" /><br>

#### Create Ticket Error Handling Page
<img width="200" height="200" alt="image" src="https://github.com/user-attachments/assets/897791eb-40ae-4218-9503-bde86774711f" />
#### Create Ticket Success Page
<img width="200" height="200" alt="image" src="https://github.com/user-attachments/assets/e298c9e5-0ae1-41ad-aaa2-9b1a974069e0" />


#### Update Ticket Page
<img width="200" height="200" alt="image" src="https://github.com/user-attachments/assets/d44e2b1e-84da-4dc3-8013-d86161c9c39b" />

#### Create Ticket Error Handling / Sucess Page
<img width="200" height="200" alt="image" src="https://github.com/user-attachments/assets/308d8410-625f-4224-b628-a49b9fb290b1" />


#### Search Ticket Page

👨‍💻 Author
Vikash Gokool<br>
Msunduzi, South Africa<br>
📧 vikashgokool@yahoo.com<br>
🔗 <a href="https://github.com/VikashGokool86">GitHub Profile</a>



 




