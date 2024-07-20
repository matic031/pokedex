# Pokedex (Fullstack pokemon viewer app)
Installation and setup,
follow these steps to clone the repository, install dependencies, and run the application:

## 1. Clone the Repository
`git clone https://gitlab.com/maticmajeric/pokedex.git && cd pokedex` 
## 2. Copy environment variables
`cp .env.example .env`
## 3. Install Sail, Dependencies, Run Migrations
```
composer require laravel/sail --dev && \
php artisan sail:install && \
./vendor/bin/sail up -d && \
./vendor/bin/sail composer install && \
./vendor/bin/sail artisan key:generate && \
./vendor/bin/sail artisan migrate && \
./vendor/bin/sail artisan storage:link
```

## 4. Select MySQL as the database

## 5. Install NPM Packages
`./vendor/bin/sail npm install`

## 6. Run the application
`./vendor/bin/sail npm run dev`

## 7. Access the Application
Open your browser and visit [http://localhost](http://localhost)
