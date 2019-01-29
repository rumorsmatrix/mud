# rumorsmatrix/mud

 - [github: rumorsmatrix/mud](https://github.com/rumorsmatrix/mud)


## Changelog

### Week commencing 2019-01-28
 - moved `Player->say()` from `Parser::handle()` and added corresponding JSON object
 - added `Description::getHTML` and corresponding `content/descriptions/` directory, plus `examine` action to `Parser::handle()`
 - moved `Parser::sendLocation` to `Player->lookAtLocation`
 - refactored `Location` and `Player` to receive a `Server` instance once instead of passing it per-method


## To do

 - landing / login / sign up page(s)
 - client needs to show/focus/hide the text input on connect/disconnect
 - client needs a connection status / connect / disconnect button
 - (maybe) refactor `Parser` to receive a `Server` instance once instead of passing it per-method
 - `Player` to become an extension of a more generic `entity` with a view to NPCs




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
 - [Composer](https://getcomposer.org/)
 - [vakata/websocket](https://github.com/vakata/websocket)
 - [illuminate/database](https://github.com/illuminate/database)
 - [mnapoli/front-yaml](https://github.com/mnapoli/front-yaml])
 - [mrmrs/colors](https://github.com/mrmrs/colors)
 - [Bootstrap](https://getbootstrap.com/)