# rumorsmatrix/mud

 - [github: rumorsmatrix/mud](https://github.com/rumorsmatrix/mud)

## Install / configure

 - Make `public/` visible to the web
 - Provide your own `server/config/db.php' in the format:
    ```
     return [
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'database'  => 'database_name',
        'username'  => 'user',
        'password'  => 'password',
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => '',
     ];
     ```   
 - *todo*: Include database schema here
 - *todo*: client config


## Required / dependencies

 - PHP, a database supported by Illuminate
 - Composer
 - vakata/websocket
 - illuinate/database
 - mnapoli/front-yaml
 - mrmrs/colors
 - Bootstrap
 

## Changelog


### Week commencing 2019-01-28
 - added `Description::getHTML` and corresponding `content/descriptions/` directory, plus `examine` action to `Parser::handle()`
 - moved `Parser::sendLocation` to `Player->lookAtLocation`
 - refactored `Location` and `Player` to receive a `Server` instance once instead of passing it per-method


## To do

 - (maybe) refactor `Parser` to receive a `Server` instance once instead of passing it per-method
 - `Player` to become an extension of a more generic `entity` with a view to NPCs

