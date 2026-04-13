# 💬 Messenger App - Real-Time Communication Ecosystem

<p align="center">
  <img src="https://laravel.com/img/logomark.min.svg" alt="Laravel Logo" width="50">
  <img src="https://vuejs.org/images/logo.png" alt="Vue Logo" width="50">
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel" alt="Laravel 11">
  <img src="https://img.shields.io/badge/Vue.js-3.x-4FC08D?style=for-the-badge&logo=vue.js" alt="Vue 3">
  <img src="https://img.shields.io/badge/Pusher-Broadcasting-300D4F?style=for-the-badge&logo=pusher" alt="Pusher">
  <img src="https://img.shields.io/badge/Vite-Ready-646CFF?style=for-the-badge&logo=vite" alt="Vite">
</p>

---

## 📌 Project Overview
A comprehensive real-time messaging platform built with **Laravel 11** and **Vue 3**. This application provides a secure, modern, and highly interactive user experience. It features real-time communication, group management, and a robust user settings suite designed for privacy and customization.

## ✨ Core Features

### 🗨️ Communication
- **Real-Time Messaging:** Instant private and group messaging powered by **Pusher**.
- **Group Chats:** Create, manage, and interact within dynamic group conversations.
- **Instant Notifications:** Real-time event broadcasting for new messages.

### ⚙️ User Settings & Account Management
- **Profile Customization:** Full control over personal data (Name, Email, Phone, Bio).
- **Social Integration:** Link your profile to **LinkedIn**, **Facebook**, and **Instagram**.
- **Security Suite:** - Password management.
  - **Two-Factor Authentication (2FA)** for enhanced account security.
- **Theme Support:** Fully functional **Dark & Light Mode** toggle.

### 🛠 Technical Highlights
- **Reactive UI:** Built using **Vue 3 Composition API** for a seamless experience.
- **Instant Preview:** Real-time image preview system for profile photo updates.
- **Optimized Architecture:** Clean code standards following Laravel best practices.

## 🚀 Technology Stack
- **Backend:** Laravel 11 (PHP 8.2+)
- **Frontend:** Vue.js 3, Vite, Bootstrap 5
- **Real-time:** Pusher & Laravel Echo
- **Database:** MySQL

## 📂 Project Structure
- `resources/js/components/`: Modular Vue components for real-time interactions.
- `app/Http/Controllers/`: Backend logic for conversation, profile, and security.
- `routes/web.php`: Application routing and middleware management.

## 🛠 Setup Guide

1. **Clone the repository:**
   ```bash
   git clone [https://github.com/MohmmedJouda/Messenger-App.git](https://github.com/MohmmedJouda/Messenger-App.git)
   cd Messenger-App

## 🚀 Installation & Setup

### 1. Install Dependencies
```bash
composer install
npm install

# Create your .env file
cp .env.example .env

# Generate the app encryption key
php artisan key:generate

# Run the migrations to create the necessary tables
php artisan migrate

# Compile assets with Vite
npm run dev

# Start Laravel server
php artisan serve

