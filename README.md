# 🛍️ Retal Store — Full-Stack E-Commerce & Admin Platform

[![Laravel 12](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com/)
[![PHP 8.2+](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![Tailwind CSS v4](https://img.shields.io/badge/Tailwind_CSS-v4.0-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com/)
[![Vite](https://img.shields.io/badge/Vite-Bundler-646CFF?style=for-the-badge&logo=vite&logoColor=white)](https://vitejs.dev/)
[![MySQL](https://img.shields.io/badge/MySQL-InnoDB-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)

> A comprehensive, commercial-grade Full-Stack E-Commerce platform built with **Laravel 12**, **Blade**, and **Tailwind CSS v4**. Engineered following strict **MVC architecture** principles, featuring a customer storefront with WhatsApp order routing and an administrative control panel with Role-Based Access Control (RBAC).

---

## 🔗 Live Preview & Quick Links

- **Staging / Client Pilot Demo:** [https://rital1store-001-site1.ftempurl.com](https://rital1store-001-site1.ftempurl.com)
- **Source Code Repository:** [github.com/tmsaah770/Rital-Store](https://github.com/tmsaah770/Rital-Store)
- **Developer Portfolio:** [mahmoud-abotaleb-portfolio.vercel.app](https://mahmoud-abotaleb-portfolio.vercel.app)

---

## 📑 Table of Contents
- [Architecture & Workflow](#-architecture--workflow)
- [Key Features](#-key-features)
- [Tech Stack](#-tech-stack)
- [System Routing Overview](#-system-routing-overview)
- [Project Directory Structure](#-project-directory-structure)
- [Security & Architecture Standards](#-security--architecture-standards)
- [Getting Started & Local Setup](#-getting-started--local-setup)
- [Author & Contact](#-author--contact)

---

## 🏗️ Architecture & Workflow

The platform follows the classic **Model-View-Controller (MVC)** monolithic architecture, providing tight integration between client requests, database transactions, and reactive server-rendered UI.

```text
[ Customer / Admin ]
        │
        ▼
   [ web.php Routes ]
        │
   [ Middleware Layer ] ──> (CSRF Protection, Session Auth, Role Check: Admin/Employee)
        │
        ▼
 [ Controller Layer ] ──> (Handles Business Logic & Form Request Validation)
        │
   ┌────┴────────────────────────┐
   ▼                             ▼
[ Eloquent Models ]        [ Blade Engine + Tailwind ]
   │ (Transactions)              │ (Compiled via Vite)
   ▼                             ▼
[ MySQL Database ]          [ HTML5 / Client UI ]
```

---

## ✨ Key Features

### 🛒 1. Customer Storefront
- **Responsive Modern UI:** Fully mobile-first design built with Tailwind CSS v4, delivering fast rendering and seamless user experience across all viewports.
- **Smart Product Catalog:** Dynamic categorization, detailed product views with multi-image presentation, and availability badges.
- **Interactive Shopping Cart:** Full cart lifecycle (add, update quantities, remove items) with automatic subtotal and total calculations.
- **Integrated Checkout Pipeline:** Fast order submission with billing/shipping address capture.
- **WhatsApp Order Integration:** Instant order dispatch and tracking workflow via direct WhatsApp messaging.
- **Customer Portal:** Secure customer account management, authentication, and past order tracking.

### 🛡️ 2. Administrative Control Panel
- **Role-Based Access Control (RBAC):** Middleware-enforced route protection isolating admin, employee, and customer privileges.
- **Real-time Analytics Dashboard:** At-a-glance KPI metrics tracking total revenue, order volume, inventory counts, and registered users.
- **Product & Category Management (CRUD):** Complete catalog controls including image upload handling, stock adjustments, and category tree assignment.
- **Order Lifecycle Management:** Status pipeline management (`Pending`, `Processing`, `Shipped`, `Completed`, `Cancelled`) with order inspection.
- **Staff Administration:** Dedicated controls for managing employee accounts, permissions, and customer data.

---

## 🛠️ Tech Stack

| Component | Technology | Description |
| :--- | :--- | :--- |
| **Backend Framework** | Laravel 12.x | Robust PHP MVC framework handling business logic and ORM |
| **Language Runtime** | PHP 8.2+ | Modern PHP leveraging typed properties and performance optimizations |
| **Template Engine** | Laravel Blade | High-performance server-side rendering with component inheritance |
| **Frontend Styling** | Tailwind CSS v4 | Utility-first, responsive CSS architecture |
| **Asset Bundler** | Vite | Ultra-fast asset compilation and HMR (Hot Module Replacement) |
| **Database** | MySQL (InnoDB) | Relational database utilizing Eloquent ORM, migrations, and seeders |
| **Authentication** | Session-Based Auth | State-managed security layer with custom role validation middleware |

---

## 🛣️ System Routing Overview

### Public & Storefront Routes
| Method | Endpoint | Description |
| :--- | :--- | :--- |
| `GET` | `/` | Storefront landing page and featured products |
| `GET` | `/product/{id}` | Product specification and image gallery |
| `GET` | `/cart` | Review active shopping cart items |
| `POST` | `/cart/add` | Append item to user cart session |
| `GET` | `/checkout` | Order details, shipping input, and payment summary |
| `POST` | `/order/submit` | Process and store newly placed customer orders |

### Authentication Routes
| Method | Endpoint | Description |
| :--- | :--- | :--- |
| `GET / POST` | `/login` | Authentication form & credential verification |
| `GET / POST` | `/register` | New customer registration pipeline |
| `POST` | `/logout` | Invalidate user session and flush auth tokens |

### Admin Protected Routes (`auth`, `role:admin|employee`)
| Method | Endpoint | Description |
| :--- | :--- | :--- |
| `GET` | `/admin/dashboard` | Main KPI metrics and analytics overview |
| `GET / POST / PUT / DELETE` | `/admin/products` | Complete Product inventory CRUD |
| `GET / POST / PUT / DELETE` | `/admin/categories` | Catalog category architecture management |
| `GET / PUT` | `/admin/orders` | Order lifecycle inspection and status updates |

---

## 📂 Project Directory Structure

```text
RETAL-STORE/
├── app/
│   ├── Http/
│   │   ├── Controllers/       # Admin, Storefront, Cart & Order Controllers
│   │   └── Middleware/        # Authentication & Role Authorization Guards
│   └── Models/                # Eloquent Data Models (User, Product, Category, Order)
├── database/
│   ├── migrations/            # Version-controlled schema definitions
│   └── seeders/               # Test datasets for demonstration
├── public/                    # Compiled assets, favicon, and entry scripts
├── resources/
│   ├── css/                   # Tailwind CSS stylesheets
│   ├── js/                    # Application client logic
│   └── views/                 # Blade templates (Storefront & Admin Views)
├── routes/
│   └── web.php                # Application route definitions
├── storage/                   # File uploads, caching, and framework logs
└── vite.config.js             # Asset bundler configuration
```

---

## 🔒 Security & Architecture Standards

- **SQL Injection Prevention:** 100% parameter binding and abstraction through Laravel's **Eloquent ORM** and Query Builder.
- **Cross-Site Scripting (XSS) Mitigation:** Strict auto-escaping applied across all dynamic outputs in **Blade** templates (`{{ $variable }}`).
- **CSRF Token Verification:** Automated CSRF token validation enforced on all state-altering POST/PUT/DELETE requests via Laravel middleware.
- **Storage Isolation:** Uploaded product assets stored within `storage/app/public` and exposed securely via symbolic link (`php artisan storage:link`), keeping root storage inaccessible from the web.
- **Credential Protection:** Zero hardcoded secrets; strict adherence to environment separation through `.env` and `.gitignore`.

---

## 🚀 Getting Started & Local Setup

### Prerequisites
- **PHP** >= 8.2 (`pdo_mysql`, `mbstring`, `openssl` extensions enabled)
- **Composer** (v2+)
- **Node.js** (v18+) & **npm**
- **MySQL Database Server**

### Step-by-Step Installation

1. **Clone the Repository:**
   ```bash
   git clone [https://github.com/tmsaah770/Rital-Store.git](https://github.com/tmsaah770/Rital-Store.git)
   cd Rital-Store
   ```

2. **Install Backend Dependencies:**
   ```bash
   composer install
   ```

3. **Install Frontend Dependencies:**
   ```bash
   npm install
   ```

4. **Configure Environment Variables:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Update your database credentials (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) in `.env`.*

5. **Symlink Storage Directory:**
   ```bash
   php artisan storage:link
   ```

6. **Run Migrations and Seeders:**
   ```bash
   php artisan migrate --seed
   ```

7. **Compile Frontend Assets:**
   ```bash
   npm run dev
   ```

8. **Start Local Development Server:**
   *(In a separate terminal window)*
   ```bash
   php artisan serve
   ```

The application will be accessible at: `http://127.0.0.1:8000`

---

## 👨‍💻 Author

**Mahmoud Abotaleb**  
*Full-Stack Web Developer (Laravel & Modern Web)*  
- **GitHub:** [@tmsaah770](https://github.com/tmsaah770)  
- **LinkedIn:** [mahmoud-abotaleb](https://linkedin.com/in/mahmoud-abotaleb-7924a3392)  
- **Portfolio:** [mahmoud-abotaleb-portfolio.vercel.app](https://mahmoud-abotaleb-portfolio.vercel.app)
