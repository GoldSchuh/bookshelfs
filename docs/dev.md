Quick play:
```bash
docker run --rm -p 8080:80 -v ./:/var/www/html/apps-extra/bookshelfs ghcr.io/juliusknorr/nextcloud-dev-php82:latest
```

(running nextcloud-docker-dev image with bookshelfs app mounted)
docker exec -it -u www-data master-nextcloud-1 bash -c "cd apps-extra/bookshelfs && ./vendor/phpunit/phpunit/phpunit -c tests/phpunit.xml"


'npm run watch'



