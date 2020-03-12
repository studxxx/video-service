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

## Create vue app

```shell script
sudo npm i -g @vue/cli
vue create app
```

[CHANGELOG]: CHANGELOG.md
[version-badge]: https://img.shields.io/badge/version-0.0.0-blue.svg