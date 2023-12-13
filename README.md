### deploy with Docker
```
git clone https://github.com/MechanicSCB/hicaliber_test <project-name>
```
```
cd <project-name>
```
```
composer update
```
```
cp .env.example .env
```
```
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
```
```
sail up -d
```
```
sail artisan key:generate
```
```
sail npm i
```
```
sail npm run build
```
- Warning: seeding 1 million rows can take a significant amount of time.
- To reduce the amount of test data, change the line 42
in database/seeders/HouseSeeder.php
```
sail artisan migrate:fresh --seed
```

- Visit `http://localhost`
