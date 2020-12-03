# AIM Software

**Make sure you already install**

- docker
- docker-compose

**Cloning project**
```$xslt
git clone https://github.com/ARBCalls/lead_engine.git lead_engine --branch=development
```

**Run Docker Services**

cd to laradock folder
```$xslt
docker-compose up -d --build nginx mysql
```

**Log into the workspace**
cd to laradock folder and ssh into workspace bash
```$xslt
docker-compose exec --user=laradock workspace bash
```

**Environment setup**

- go to project root (/var/www) and Copy env.development to .env and change the setting according to your local development machine
- go to laradock folder inside the project root folder (/var/www/laradock) and Copy env.development to .env


**Application Installation**
```$xslt
composer install
php artisan key:generate
npm install
php artisan migrate
php artisan passport:install
npm run dev
```

**Create the first admin login**
```$xslt
php artisan init:createAdmin
```

**Login to the application**
```$xslt
Login with the credential below:
email: admin@aimleadengine.com
password: admin
```

