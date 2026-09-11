# 📚 BookNest — AI-Powered Library Management System

> **Read • Learn • Grow**

BookNest is a full-stack **AI-powered Library Management System** built with Laravel, PHP, MySQL, Blade, HTML, CSS, and JavaScript.

The system combines traditional library management with **Role-Based Access Control (RBAC)**, **personalized book recommendations**, and a **Generative AI Library Assistant** that helps users discover, understand, and compare books.

---

## ✨ Overview

BookNest is designed to provide a modern and intelligent digital library experience.

Users can:

* Browse and search books
* Filter books by different criteria
* Explore categories
* View detailed book information
* Manage their personal profile
* Receive personalized recommendations
* Ask the AI assistant about books
* Discover books using natural language
* Compare different books

Administrators have additional capabilities for managing the entire library system, users, books, categories, and statistics.

---

## 🚀 Features

### 🔐 Authentication

* User registration
* Secure login
* Logout
* Password hashing
* Form validation
* Session-based authentication
* Protected routes

### 👥 Role-Based Access Control

BookNest contains two roles:

| Role    | Access                                                           |
| ------- | ---------------------------------------------------------------- |
| `user`  | Library browsing, search, recommendations, profile, AI assistant |
| `admin` | Full system management + user management + statistics + admin AI |

Admin privileges are enforced **server-side** and cannot be obtained by manipulating frontend requests.

Public registration always creates a normal `user` account.

---

## 📖 Library Management

### Books

Administrators can:

* Add books
* Edit books
* Delete books
* View books
* Manage book information
* Manage available copies
* Assign categories
* Manage book covers

Users can:

* Browse books
* Search books
* Filter books
* View book details
* Explore categories
* Receive recommendations

Book information includes:

* Title
* Author
* Description
* Category
* ISBN
* Publication date
* Available copies
* Cover image
* Additional metadata

---

## 🗂️ Categories

The system supports category management for organizing books.

Example categories include:

* Programming
* Artificial Intelligence
* Database
* Web Development
* Cyber Security
* Networking
* Business
* Science
* Literature

Administrators can create, update, and delete categories.

Users can browse and filter books by category.

---

## 🔎 Search & Filtering

BookNest provides database-driven search across:

* Book title
* Author
* Description
* ISBN
* Category

Users can also combine multiple filters.

For example:

```text
Search: Laravel
Category: Web Development
Availability: Available
```

Results are retrieved directly from the database rather than loading the entire library into JavaScript.

Laravel pagination is used to keep the system efficient with large numbers of books.

---

# 🤖 AI Library Assistant

BookNest includes a Generative AI assistant integrated through a dedicated Laravel service layer.

Users can ask questions such as:

```text
Recommend programming books for me.
```

```text
Tell me about Clean Code.
```

```text
What books are good for beginners learning PHP?
```

```text
Compare Clean Code and Laravel for Beginners.
```

Administrators can also request authorized library information such as:

```text
How many books are available?
```

```text
How many registered users are there?
```

```text
Which category contains the most books?
```

---

## 🔒 AI Security Architecture

AI is **never responsible for authorization**.

The application follows this flow:

```text
Authentication
      ↓
Authorization
      ↓
Current User Role
      ↓
Allowed Operation
      ↓
Backend Database Query
      ↓
Safe Context Preparation
      ↓
AI Service
      ↓
AI Response
      ↓
User
```

The AI service does **not** receive:

* Database credentials
* Unrestricted SQL access
* Unrestricted ORM access
* Authorization privileges
* Protected information from unauthorized users

For example, if a normal user asks for administrator-only statistics, the request is rejected by the backend before protected information is sent to the AI.

---

# 🧠 Personalized Recommendations

BookNest includes a personalized recommendation engine based on the user's actual profile.

The profile can contain:

* Interests
* Favorite topics
* Preferred categories
* Skills
* Educational interests
* Professional interests
* Learning goals

The recommendation system compares user preferences with book information such as:

* Title
* Description
* Category
* Author
* Metadata

### Recommendation Flow

```text
User Profile
     +
Book Information
     ↓
Text / Keyword Representation
     ↓
Similarity Calculation
     ↓
Normalized Relevance Score
     ↓
Percentage
     ↓
Sorted Recommendations
```

The system is designed to produce:

* Deterministic scores
* Meaningful relevance
* Stable recommendations
* Highest-match-first sorting

The recommendation percentages are **calculated dynamically** and are not hardcoded.

---

# 🖼️ Real Book Covers & Visual Assets

BookNest uses real book-cover assets rather than generated or placeholder images.

The project includes a large collection of real covers organized into categories such as:

* Arabic Books
* English Books
* Programming Books

The project also includes dedicated branding and visual assets:

```text
branding/
├── navbar-logo.png
└── sidebar-logo.png

hero/
└── hero-image.png

reference/
└── homepage-reference.png

books/
├── Arabic Books/
├── English Books/
└── programming books/
```

Book covers are mapped to their corresponding database records.

No random stock or placeholder covers are used when a real supplied cover is available.

---

# 🎨 UI / UX

BookNest follows a warm, premium, editorial visual style inspired by the provided design reference.

### Color Palette

| Purpose                    | Color     |
| -------------------------- | --------- |
| Navbar / Sidebar           | `#192D43` |
| Sidebar Active             | `#2E4056` |
| Burgundy Accent            | `#7F2C3D` |
| AI / Recommendations Green | `#1D5243` |
| Search                     | `#485A6E` |
| Main Text                  | `#192D43` |
| Secondary Text             | `#334A55` |
| Muted Text                 | `#B0B4B2` |
| Main Background            | `#F7F1E9` |
| Cards                      | `#FDF8F2` |
| Borders                    | `#E7E0D7` |

### Typography

* **Playfair Display** — Main headings
* **Cormorant Garamond** — Editorial alternative
* **Inter** — Body text
* **Caveat** — Decorative handwritten elements

The interface is designed to feel:

* Elegant
* Academic
* Warm
* Modern
* Editorial
* Book-oriented

---

# 🖥️ Main Interface

The homepage contains:

### Navigation Bar

Includes:

* BookNest branding
* Main navigation
* Search
* Notifications
* User profile

### Sidebar

User navigation includes:

* Home
* Books
* Categories
* Search
* Recommendations
* My Profile
* AI Chatbot

Admin navigation additionally includes:

* Dashboard
* Users
* Books
* Categories
* Statistics
* AI Chatbot

### Hero Section

Features:

* Welcome message
* Main BookNest heading
* Library description
* Browse Books action
* Explore Categories action
* Hero image

### Features Section

Includes:

* Wide Selection
* Personalized
* Secure & Reliable
* AI Powered
* Book Lovers Community

### Featured Books

Displays real books from the database.

### AI Library Assistant

Provides natural-language interaction with the backend AI service.

### Personalized Recommendations

Displays books ranked according to the user's interests.

### Footer

Includes:

* BookNest branding
* Navigation links
* Social links
* Copyright information

---

# 📱 Responsive Design

BookNest is designed for:

* Desktop
* Tablet
* Mobile

Responsive behavior includes:

* Collapsible sidebar/navigation
* Responsive hero layout
* Responsive book cards
* Stacked mobile sections
* Mobile-friendly AI assistant
* Responsive recommendation cards
* Adaptive footer

The primary desktop visual target is:

```text
1536 × 1024
```

---

# 🛠️ Technology Stack

## Backend

* PHP
* Laravel
* Eloquent ORM
* Laravel Middleware
* Laravel Policies / Authorization
* Laravel Validation
* Laravel Services

## Database

* MySQL

## Frontend

* Blade
* HTML5
* CSS3
* JavaScript

## AI

* Generative AI API
* Dedicated Laravel AI Service
* Environment-based configuration

## Development Tools

* Composer
* npm
* Git
* GitHub

---

# 📂 Project Structure

A simplified project structure:

```text
BookNest/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   └── Requests/
│   │
│   ├── Models/
│   ├── Policies/
│   └── Services/
│
├── bootstrap/
│
├── config/
│
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
│
├── public/
│   └── ...
│
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│
├── routes/
│   ├── web.php
│   └── ...
│
├── storage/
│
├── tests/
│
├── artisan
├── composer.json
├── package.json
├── README.md
└── .env.example
```

---

# ⚙️ Requirements

Before installing BookNest, make sure your environment includes:

* PHP
* Composer
* MySQL
* Node.js
* npm

Recommended environment:

* Laravel-compatible PHP version
* MySQL 8+
* Node.js LTS
* Composer 2+

---

# 📥 Installation

Clone the repository:

```bash
git clone YOUR_REPOSITORY_URL
```

Navigate into the project:

```bash
cd BookNest
```

Install PHP dependencies:

```bash
composer install
```

Install frontend dependencies:

```bash
npm install
```

Create the environment file:

```bash
cp .env.example .env
```

On Windows, if `cp` is unavailable, copy `.env.example` manually and rename it to:

```text
.env
```

Generate the application key:

```bash
php artisan key:generate
```

---

# 🗄️ Database Configuration

Create a MySQL database, for example:

```text
booknest
```

Then update `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=booknest
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations:

```bash
php artisan migrate
```

Seed the database:

```bash
php artisan db:seed
```

Or migrate and seed together:

```bash
php artisan migrate:fresh --seed
```

> `migrate:fresh --seed` deletes existing database tables, so use it only when resetting a development database.

---

# 🖼️ Storage Setup

Create the Laravel storage link:

```bash
php artisan storage:link
```

This allows publicly accessible uploaded book covers and other required assets to be served correctly.

---

# 🤖 AI Configuration

AI credentials must never be hardcoded.

Add the following variables to `.env`:

```env
AI_API_KEY=
AI_MODEL=
AI_BASE_URL=
```

Example:

```env
AI_API_KEY=your_api_key_here
AI_MODEL=your_model_name
AI_BASE_URL=your_provider_endpoint
```

Never commit the real `.env` file.

The repository should contain:

```text
.env.example
```

instead of real secrets.

---

# ▶️ Running the Application

Start the Laravel development server:

```bash
php artisan serve
```

The application will normally be available at:

```text
http://127.0.0.1:8000
```

For frontend development, use:

```bash
npm run dev
```

For a production frontend build:

```bash
npm run build
```

---

# 🧪 Testing

Run Laravel tests with:

```bash
php artisan test
```

Useful commands:

```bash
php artisan route:list
```

```bash
php artisan optimize:clear
```

For development database reset:

```bash
php artisan migrate:fresh --seed
```

---

# 🔐 Security

BookNest follows several security principles:

* CSRF protection
* Secure password hashing
* Authentication middleware
* Server-side authorization
* Role validation
* Mass-assignment protection
* Input validation
* Secure file uploads
* Blade escaping
* Protected admin routes
* Protected AI operations
* Safe database queries
* Environment-based secrets

Frontend role values are never trusted.

AI is never trusted with authorization decisions.

---

# 👤 Roles & Permissions

## User

Normal registered users can:

* Browse books
* Search
* Filter
* View book details
* Browse categories
* Edit their profile
* Receive personalized recommendations
* Use permitted AI features

## Admin

Administrators can:

* Access the admin dashboard
* Manage users
* Manage books
* Manage categories
* View library statistics
* Use administrator AI capabilities
* Perform authorized library management operations

---

# 📊 Admin Statistics

The admin dashboard uses real database queries to calculate statistics such as:

* Total users
* Total books
* Total categories
* Available books
* Books per category
* Low-availability books

Statistics are never hardcoded.

---

# 🧩 Architecture Principles

BookNest follows a clean Laravel architecture.

### Controllers

Handle HTTP requests and responses.

### Models

Represent database entities and relationships.

### Services

Contain reusable business logic such as:

* AI processing
* Recommendation calculations

### Middleware / Policies

Handle authentication and authorization.

### Form Requests

Handle structured validation where appropriate.

### Blade Components

Provide reusable UI elements.

This keeps the application maintainable and prevents large controllers or duplicated logic.

---

# 🔄 Core Application Flow

A typical authenticated request follows:

```text
Browser
   ↓
Laravel Route
   ↓
Authentication
   ↓
Authorization
   ↓
Controller
   ↓
Service
   ↓
Model / Database
   ↓
Blade View
   ↓
Browser
```

For AI requests:

```text
Browser
   ↓
Laravel Route
   ↓
Authentication
   ↓
Authorization
   ↓
AI Intent
   ↓
Allowed Database Query
   ↓
Safe Context
   ↓
AI Service
   ↓
AI Provider
   ↓
Response
   ↓
Blade / JavaScript
   ↓
User
```

---

# 🎯 Project Goals

BookNest was designed to demonstrate practical knowledge in:

* Full-stack web development
* Laravel architecture
* PHP
* MySQL
* Database design
* Authentication
* Authorization
* RBAC
* RESTful application design
* Blade templating
* Responsive UI development
* AI integration
* Recommendation systems
* Secure application architecture
* Software testing
* Git/GitHub workflow

---

# 👩‍💻 Project Type

**Academic / Graduation Project**

BookNest demonstrates how a traditional library management system can be extended with modern AI capabilities while maintaining:

* Security
* Maintainability
* Database integrity
* Role separation
* Personalized experiences

---

# 📌 Future Improvements

Potential future enhancements include:

* Online book borrowing and return tracking
* Reading history
* Book ratings and reviews
* Favorites / wishlist
* Advanced semantic embeddings
* AI-powered reading plans
* Email notifications
* Book availability notifications
* Advanced analytics
* Librarian reservation workflows
* More sophisticated recommendation models

---

# 📄 License

This project is developed for educational and academic purposes.

Book cover images and third-party assets remain subject to their respective copyright and licensing terms.

---

---

<div align="center">

### ✨ Developed with passion by

**Salma Waleed**

<img src="assets/signature.png" width="180" alt="Salma Waleed Signature">

**BookNest — Read • Learn • Grow**

</div>
