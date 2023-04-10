# Aonmart
An good management project.

<br>

## Setup Guid
- ```git clone https://gitlab.com/web-developer11/aonmart.git```
- ```cd aonmart```
- ```composer update```
- ```cp .env.example .env```
- ```php artisan migrate --seed```

`Note: Don't forget configure database, before run migration and seed...`

<br/>
-> You want to ignore demo data seeding?

-> From ```database/seeders/DatabaseSeeder.php``` files. You can temporarily comment/remove ```$this->call(TestDataSeeder::class);``` and then again,

- ```php artisan migrate:fresh --seed```

### Happy Ending ... Thank You.
# aonmart
# aonmart
