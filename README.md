# 🤖 AI-Powered Real-Time Communication System

<p align="center">
  <img src="https://laravel.com/img/logomark.min.svg" width="60" />
  <img src="https://vuejs.org/images/logo.png" width="60" />
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel">
  <img src="https://img.shields.io/badge/Vue.js-3.x-4FC08D?style=for-the-badge&logo=vue.js">
  <img src="https://img.shields.io/badge/WebSockets-Realtime-2E86C1?style=for-the-badge">
  <img src="https://img.shields.io/badge/AI-Integrated-8E44AD?style=for-the-badge">
</p>

---

## 🚀 Overview

A **scalable real-time communication system** built with **Laravel 11** and **Vue 3**, designed to deliver instant messaging with **AI-powered conversational capabilities**.

This project goes beyond a traditional chat application by implementing an **event-driven architecture**, real-time WebSocket communication, and intelligent AI chat automation.

---

## ✨ Key Features

### 🤖 AI-Powered Messaging
- AI chatbot integration for automated responses
- Smart conversation handling via external AI APIs
- Extensible AI service layer for future enhancements

---

### 💬 Real-Time Communication
- Instant messaging using WebSockets (Pusher / Laravel Echo)
- Live typing indicators
- Message delivery & read status
- Group chat functionality

---

### 👥 User & Account System
- Secure authentication system
- Profile customization (name, email, bio, avatar)
- Social links integration (LinkedIn, Facebook, Instagram)
- Two-Factor Authentication (2FA)

---

### 🎨 Frontend Experience
- Built with Vue 3 Composition API
- Reactive and dynamic UI updates
- Real-time state synchronization
- Dark / Light mode support

---

## 🏗️ System Architecture

This project follows a **modular event-driven architecture**:

- Event Broadcasting for real-time communication
- Decoupled AI service integration layer
- Separation of frontend (Vue) and backend (Laravel)
- Scalable message handling using queues (if enabled)

---

## 🧠 Technical Highlights

- Real-time event broadcasting system
- WebSocket-based messaging infrastructure
- Clean MVC backend structure (Laravel)
- Component-based frontend architecture (Vue)
- API-driven AI integration layer

---

## 🛠️ Tech Stack

- **Backend:** Laravel 11 (PHP 8.2+)
- **Frontend:** Vue 3, Vite, Bootstrap 5
- **Realtime:** Pusher / Laravel Echo
- **Database:** MySQL
- **AI Integration:** External AI API (e.g. OpenAI)

---

## 📂 Project Structure

app/Http/Controllers → Backend logic (chat, users, AI)
resources/js/components → Vue components (UI & realtime)
routes/web.php → Application routing
app/Events → Broadcasting events
app/Services → AI & business logic layer


---

## ⚙️ Installation

### 1. Clone the repository
```bash
git clone https://github.com/MohmmedJouda/Messenger-App.git
cd Messenger-App
```
### 2. Install dependencies
```bash
composer install
npm install
```
### 3. Environment setup
```bash
cp .env.example .env
php artisan key:generate
```
### 4. Run migrations
```bash
php artisan migrate
```
##💡 Future Improvements
* AI memory for long conversations
* Message encryption (end-to-end)
* Redis for scaling real-time system
* Push notifications (mobile support)
* Microservices AI separation layer

### 📌 Project Vision

This system is designed as a scalable foundation for modern communication platforms, combining:

Real-time engineering + AI intelligence + clean system design

### 👨‍💻 Author
Mohammed Jouda

* GitHub: MohmmedJouda
* Focus: Backend Engineering | Laravel | System Design

⭐ If you like this project

Give it a star ⭐ and feel free to explore the codebase.
