# SETUP

This document will serve as a setup guide for this appilcation.

## The Backend
The backend is built with PHP 8.1 without framework

Uses PSR4, and composer, so cd into the `itc/backend` directory then run

```
composer install
```


To run the application run the PHP built in server in the itc directory

```
 php -S localhost:8000 -t backend
```

Please ensure it's running o port `8000`, this port is hard coded in the frontend [assets\js\app.js]

Once the server is started, the API is accessible 

## The Frontend
The frontend is built with Javascript, CSS and HTML.

To run the frontend, first of all, start the backend.
Then open frontend/index.html from the web browser

The page was tested on the latest google chrome but will work for later.

## Other info
I could not write tests because I have limited time. I take tests seriously. Please forgive me.

I spent so time trying to get accurate regex for cleaning the data, not perfect at the moment, so needs improvement