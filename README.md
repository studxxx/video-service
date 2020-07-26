# [RabbitMQ](https://bitbucket.org/studxxx/rabbitmq)

[![version][version-badge]][CHANGELOG]

## Resolve  http cors

### var 1 proxy

```
http://video-service.loc
http://video-service.loc/api/user -> ... -> http://api.video-service.loc/user
```

```
server {
    listen 80;
    index index.html;
    root /var/www/frontend/public;

    location / {
        try_files $uri /index.html;
    }

    location /api {
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-Proto https;
        proxy_set_header X-Forwarded-For $remote_addr;
        proxy_set_header X-Forwarded-Host $remote_addr;
        proxy_set_header X-Nginx-Proxe true;
        proxy_pass http://api-nginx;
        proxy_ssl_session_reuse off;
        proxy_redirect off;
    }
}
```

### var 2 header

```
http://video-service.loc
http://api.video-service.loc/user
```

```
server {
    listen 80;
    index index.php index.html;
    root /var/www/api/public;

    add_header 'Access-Control-Allow-Origin' '*' always;
    add_header 'Access-Control-Allow-Credentials' 'true' always;
    add_header 'Access-Control-Allow-Methods' 'GET,POST,PUT,DELETE,HEAD,OPTIONS' always;
    add_header 'Access-Control-Allow-Headers' 'Origin,Content-Type,Accept,Authorization' always;

    location / {
        if ($request_method = 'OPTIONS') {
            add_header 'Access-Control-Allow-Origin' '*' always;
            add_header 'Access-Control-Allow-Credentials' 'true' always;
            add_header 'Access-Control-Allow-Methods' 'GET,POST,PUT,DELETE,HEAD,OPTIONS' always;
            add_header 'Access-Control-Allow-Headers' 'Origin,Content-Type,Accept,Authorization' always;
            add_header 'Content-Type' 'text/plain charset=UTF-8';
            add_header 'Content-Length' 0;
            return 204;
        }
        try_files $uri /index.php?$args;
    }

    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass api-php-fpm:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }
}
```

## Create vue app

```shell script
sudo npm i -g @vue/cli
vue create app
```

[CHANGELOG]: CHANGELOG.md
[version-badge]: https://img.shields.io/badge/version-0.0.7-blue.svg