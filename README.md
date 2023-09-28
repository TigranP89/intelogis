# API example in PHP

### Configure the application

Install the project dependencies and start the PHP server:

```
composer install
cd public
php -S 127.0.0.1:8000
```
### Working with the api

For work with the api you should use Postman.
User can work with two routes POST - 127.0.0.1:8000/delivery/fast and POST - 127.0.0.1:8000/delivery/slow routes.

POST [127.0.0.1:8000/delivery/fast](127.0.0.1:8000/delivery/fast) - Use "fast delivery" method.Send from Postman admin 'weight', 'targetKladr' and 'email'

Example:

```
{
    "weight": 0.5,
    "sourceKladr": "Address 1",
    "targetKladr": "Address 2"
}
```


POST [127.0.0.1:8000/delivery/slow](127.0.0.1:8000/delivery/slow) - Use "slow delivery" method.Send from Postman admin 'weight', 'targetKladr' and 'email'

Example:

```
{
    "weight": 0.5,
    "sourceKladr": "Address 1",
    "targetKladr": "Address 2"
}
```

## OR you can use

### Swagger

Open [http://127.0.0.1:8000/web/index.html](http://127.0.0.1:8000/web/index.html) to see all API calls.

If you want to regenerate the swagger.json file in your browser go to the 127.0.0.1:8000/api route.