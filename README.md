#### Projekta uzstādīšanas instrukcija

- Tā kā datubāzei jāatrodas docker konteinerī, uz datora nepieciešams uzinstalēt docker un docker-compose 
  - Kad tas ir izdarīts - startējam konteineri ar komandu
    - `docker-compose up -d`
- Tā kā sistēma izmanto PHP 7.4 un Laravel 7 - uz datora jjāuzinstalē PHP 7.4 un Composer
- Nepieciešams uzinstalēt rojekta biblotēkas, izpildot komandu 
  - `composer install`
- Ja nav, jāuzinstalē NodeJs (vismaz 12. versija)
- Projekta frontend tiek uzstādīts izpildot sekojošas komandas 
  - `npm install && nrpm run development`
- Projekta .env failā nepieciešams norādīt piekļuves parametrus datubāzei
- Jāizpilda datubāzes migrācijas, kas izveidos visas projektam nepieciešamās tabulas
  - `php artisan migrate`
- Palaist projektu lokāli
  - `php artisan serv `
- (Pēc noklusējuma) Projekts pieejams pārlūkā: http://localhost:8000/ 
- Lai veiktu protokolu augšupielādi, nepieciešams reģistrēties
- Var sākt protokolu augšupielādi un sekot līdzi izmaiņām statistikas rādītājos
