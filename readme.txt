To test this project:
1. Install php and add it to your environmenet variables (you can use wamp or xampp)
2. Run apache server from wamp or xampp
3. Open command prompt or terminal in proj folder.
4. Create database using following commands:
    php bin/console doctrine:database:create
    php bin/console doctrine:schema:update --force
5. Start symfony server with the following command:
    php bin/console server:start
6. Test the site by opening:
    localhost:8000