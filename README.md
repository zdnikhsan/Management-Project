# ManageProjek 🚀

**ManageProjek** adalah platform manajemen proyek modern yang dirancang untuk membantu tim mengelola tugas, berkolaborasi, dan melacak progress proyek dengan lebih efisien dan transparan.

## ✨ Fitur Utama

-   **Manajemen Proyek & Tugas**: Buat proyek baru dan kelola tugas di dalamnya dengan status yang dinamis.
-   **Role-Based Access Control (RBAC)**: Pembagian peran yang jelas antara **Admin**, **Leader**, dan **Staff** menggunakan [Spatie Permission](https://spatie.be/docs/laravel-permission).
-   **Alur Kerja Review**: Fitur persetujuan tugas (Approve/Reject) oleh Leader untuk memastikan kualitas kerja.
-   **Kolaborasi Real-time**: Fitur komentar pada setiap tugas untuk memudahkan komunikasi antar anggota tim.
-   **Dashboard Interaktif**: Statistik proyek dan tugas yang memberikan gambaran cepat tentang status pekerjaan.
-   **Landing Page Premium**: Tampilan awal yang modern dan profesional untuk meningkatkan user experience.

## 🛠️ Tech Stack

-   **Core**: [Laravel 11/12](https://laravel.com) (PHP 8.3+)
-   **Authentication**: [Laravel Breeze](https://laravel.com/docs/breeze)
-   **Authorization**: [Spatie Laravel-Permission](https://spatie.be/docs/laravel-permission)
-   **Database**: MySQL
-   **Frontend**: TailwindCSS & Vanilla CSS
-   **Build Tool**: [Vite](https://vitejs.dev)

## 🚀 Cara Instalasi

Ikuti langkah-langkah berikut untuk menjalankan project ini di komputer lokal Anda:

1.  **Clone Repository**
    ```bash
    git clone https://github.com/username/manageProjek.git
    cd manageProjek
    ```

2.  **Instal Dependensi PHP**
    ```bash
    composer install
    ```

3.  **Instal Dependensi Frontend**
    ```bash
    npm install
    ```

4.  **Konfigurasi Environment**
    Salin file `.env.example` menjadi `.env` dan sesuaikan pengaturan database Anda:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

5.  **Jalankan Migrasi & Seeding**
    Proses ini akan membuat tabel database dan mengisi data awal (roles & users):
    ```bash
    php artisan migrate --seed
    ```

6.  **Jalankan Aplikasi**
    Buka dua terminal dan jalankan perintah berikut:
    
    Terminal 1 (Server PHP):
    ```bash
    php artisan serve
    ```
    
    Terminal 2 (Assets Compiler):
    ```bash
    npm run dev
    ```

Aplikasi sekarang dapat diakses melalui `http://127.0.0.1:8000`.

---

Dibuat dengan ❤️ untuk produktivitas tim.
