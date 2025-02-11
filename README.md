# News Aggregator (Dockerized with SQLite)

This is a Laravel project running in a **Dockerized environment** with **SQLite** as the database.

## 🚀 Setup Instructions

### 1️⃣ Clone the Repository
```bash
git clone https://github.com/MariamWagdy/news-aggregator
cd news-aggregator
```

### 2️⃣ Create the `.env` File
Copy the example `.env` file and update it:
```bash
cp .env.example .env
```
Edit the `.env` file and ensure the database configuration is set to **use SQLite inside Docker**:
```env
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/database/database.sqlite
DB_FOREIGN_KEYS=true
```

### 3️⃣ Create the SQLite Database File
```bash
mkdir -p database
touch database/database.sqlite
chmod -R 777 database/database.sqlite storage bootstrap/cache
```

### 4️⃣ Build and Start Docker Containers
```bash
docker compose up -d --build
```

### 5️⃣ Run Migrations
```bash
docker compose exec app php artisan migrate
```

### 6️⃣ Access the Application
Visit [http://localhost:8000](http://localhost:8000) in your browser.

## 🛑 Stopping the Containers
To stop the containers, run:
```bash
docker compose down
```

## 🎯 Additional Commands
- Restart Docker containers:
  ```bash
  docker compose up -d --force-recreate
  ```
- Run Laravel commands inside the container:
  ```bash
  docker compose exec app php artisan tinker
  ```
- View Laravel logs:
  ```bash
  docker compose exec app tail -f storage/logs/laravel.log
  ```

## 🛠 Troubleshooting
- **Database file not found?** Ensure it's inside the container:
  ```bash
  docker compose exec app ls -l /var/www/database/
  ```
- **Laravel migrations fail?** Check database permissions:
  ```bash
  docker compose exec app chmod -R 777 /var/www/database/database.sqlite
  ```

## 📌 Important Notes
- **Do NOT commit `database.sqlite` or `.env`** to Git. They are environment-specific.
- If running on **Windows**, ensure your project path is mounted correctly.

🚀 Now your Laravel app is fully Dockerized with SQLite! 🎉

