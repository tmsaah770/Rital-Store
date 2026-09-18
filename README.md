# 🛒 Rital Store — Commercial E-Commerce Platform

[![React](https://img.shields.io/badge/React-20232A?style=for-the-badge&logo=react&logoColor=61DAFB)](https://react.dev/)
[![Vite](https://img.shields.io/badge/Vite-646CFF?style=for-the-badge&logo=vite&logoColor=white)](https://vitejs.dev/)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com/)
[![Status](https://img.shields.io/badge/Status-Pilot%20%2F%20Staging-orange?style=for-the-badge)]()

> A modern, responsive e-commerce web application developed for a commercial client to manage product catalogs, real-time customer ordering, and seamless shopping cart workflows. Currently deployed in a client-pilot staging environment.

---

## 🔗 Live Demo & Links
- **Staging / Pilot Demo:** [Visit Rital Store](https://rital1store-001-site1.ftempurl.com)
- **Developer Portfolio:** [Mahmoud Abotaleb](https://mahmoud-abotaleb-portfolio.vercel.app)

---

## ✨ Key Features

### 🛍️ Customer Experience
- **Interactive Product Catalog:** Fast, responsive product listings with category filtering and instant search.
- **Persistent Shopping Cart:** Client-side state persistence to preserve cart items across page reloads and browser sessions.
- **Mobile-First Responsive Design:** Optimized layouts providing a native-app feel on smartphones, tablets, and desktops.
- **Streamlined Checkout Flow:** Intuitive step-by-step order placement designed to maximize conversion rates.

### ⚙️ Technical Highlights
- Built with **React** and **Vite** for lightning-fast bundling, sub-second HMR, and optimized production builds.
- Styled using **Tailwind CSS** for clean, modular, and utility-driven component styling.
- Modular architecture with clean separation between UI components, custom hooks, and state management.

---

## 🛠️ Tech Stack

| Domain | Technology |
| :--- | :--- |
| **Frontend Framework** | React.js (Component-driven architecture) |
| **Build Tool** | Vite |
| **Styling & UI** | Tailwind CSS, Lucide React / Icons |
| **State Management** | React Context API / Custom Hooks |
| **Deployment** | Production Staging Server |

---

## 📂 Project Structure

```text
src/
├── assets/          # Static assets, brand logos, and icons
├── components/      # Reusable UI components (Navbar, Footer, Cart, ProductCard)
├── hooks/           # Custom hooks for state management and local storage
├── pages/           # Application views (Home, Products, Cart, Checkout)
├── utils/           # Helper functions and constants
├── App.jsx          # Main application entry and routing
└── main.jsx         # DOM rendering and global configurations
