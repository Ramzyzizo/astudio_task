# astudio_task

## Project Setup

Follow these steps to set up the project on your local machine.

### 1️⃣ Clone the Repository
```sh
git clone repo
cd astudio_task
```

### 2️⃣ Copy & Configure Environment File
```sh
cp .env.example .env
```
Then, update the `.env` file with your database credentials and environment settings.

### 3️⃣ Install Dependencies
```sh
composer install
```

### 4️⃣ Generate Application Key
```sh
php artisan key:generate
```

### 5️⃣ Run Migrations & Seed Database
```sh
php artisan migrate --seed
```

### 6️⃣ Set Up Laravel Passport
```sh
php artisan passport:install
php artisan passport:keys
php artisan passport:client --personal
```

### 7️⃣ Clear Cache & Start Server
```sh
php artisan config:clear
php artisan cache:clear
php artisan serve
```


### ✅ Your project is now ready! 🚀

