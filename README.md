# 💬 Enterprise Real-Time Messenger Ecosystem

<p align="center">
  <img src="https://laravel.com/img/logomark.min.svg" alt="Laravel Logo" width="60">
  <img src="https://vuejs.org/images/logo.png" alt="Vue Logo" width="60">
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel" alt="Laravel 11">
  <img src="https://img.shields.io/badge/Vue.js-3.x-4FC08D?style=for-the-badge&logo=vue.js" alt="Vue 3">
  <img src="https://img.shields.io/badge/Pusher-Real--Time-300D4F?style=for-the-badge&logo=pusher" alt="Pusher">
  <img src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php" alt="PHP 8.2">
  <img src="https://img.shields.io/badge/Vite-Ready-646CFF?style=for-the-badge&logo=vite" alt="Vite">
</p>

---

## 📌 نظرة عامة (Project Overview)
نظام محادثة متطور (Real-Time Messenger) مصمم بمعمارية هندسية تضمن الأداء العالي (High Performance) والقابلية للتوسع (Scalability). المشروع ليس مجرد تطبيق شات، بل هو بيئة متكاملة تدمج بين قوة **Laravel 11** في التعامل مع البيانات، وسرعة **Vue 3** في الواجهات، وتقنية **Websockets** للتواصل اللحظي.

## 🏗 المعمارية البرمجية (Technical Architecture)
تم بناء المشروع مع التركيز على مبادئ **Clean Code** و **SOLID Principles**:
- **Service Layer Pattern:** لفصل منطق الأعمال عن الـ Controllers.
- **Real-Time Events:** استخدام `ShouldBroadcast` مع **Pusher** لتحديث الواجهات فورياً.
- **Reactive UI:** استخدام **Vue.js 3** مع **Composition API** لإدارة حالة التطبيق.
- **Database Optimization:** استخدام الـ Indexes و Eager Loading (`with()`) لتقليل استعلامات قاعدة البيانات.

## 🚀 المميزات التقنية (Core Features)
- **Instant Messaging:** نظام دردشة فوري ثنائي الاتجاه.
- **Advanced Profile System:** رفع وتغيير الصور مع معاينة فورية (Instant Preview) باستخدام `FileReader API`.
- **Hybrid Notification System:** تنبيهات لحظية (Broadcast) مع أرشفة في قاعدة البيانات (Database Store).
- **Security & Session Control:** نظام مراقبة الأجهزة المتصلة مع إمكانية إنهاء الجلسات (Logout other devices) لرفع مستوى الأمان.
- **System Health Dashboard:** أدوات لمراقبة حجم التخزين، الكاش، وتنظيف السجلات (Logs) برمجياً.
- **Custom CMS:** لوحة تحكم إدارية مبنية بواسطة **Filament v3**.

## 📸 لقطات الشاشة (Screenshots)

<p align="center">
  <img src="screenshots/chat-main.png" alt="Chat Interface" width="400">
  <img src="screenshots/profile-settings.png" alt="Profile Management" width="400">
</p>
<p align="center">
  <img src="screenshots/session-management.png" alt="Security Dashboard" width="800">
</p>

## 🛠 المتطلبات التقنية (Prerequisites)
- **PHP** >= 8.2
- **Composer**
- **Node.js & NPM**
- **MySQL** 8.0+
- **Pusher Account** (لإعدادات الـ Real-time)

## ⚙️ خطوات التثبيت (Installation Steps)

1. **نسخ المشروع:**
   ```bash
   git clone [https://github.com/your-username/messenger-app.git](https://github.com/your-username/messenger-app.git)
   cd messenger-app
