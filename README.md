ElasticSearch Playground
========================

# Instalation

Build VM using:

```
$ cd vagrant
$ vagrant up
```

Add to your hosts' ``/etc/hosts`` file

```
10.0.0.200  elastic.dev
```

Install app inside VM

```
$ cd /var/www/elastic
$ composer install
$ app/console doctrine:schema:create
$ app/console doctrine:fixtures:load
```

The last command may be very time consuming process. It loads 100000 objects into database.

Then go to the ``http://elastic.dev/app_dev.php/search`` and play with the search fields
