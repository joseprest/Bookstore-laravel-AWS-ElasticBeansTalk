## SetUp Mac OS

1. Install Virtual Box
  * https://www.virtualbox.org/wiki/Downloads.
2. Install Vagrant
  * https://www.vagrantup.com/downloads.
3. Add Homestead to Vagrant
  * vagrant box add laravel/Homestead.
4. Clone homestead from folklore repository
  * git clone https://github.com/Folkloreatelier/homestead.git.
5. Initialize homestead
  * In homestead folder : bash init.sh.
6. In .homestead folder, modify Homestead.yaml with correct information
7. In homestead folder, vagrant reload --provision (everytime Homestead.yaml is modified)
8. In backend folder, modify .env file with correct information
9. Install nvm
  * https://github.com/creationix/nvm/blob/master/README.markdown.
  * check if nvm was properly installed.
10. In backend folder, composer install (line command)
11. line command : npm install -g gulp (setting gulp)
12. npm install in backend folder
13. run gulp command, if missing items, install them manually
  * npm install "missing_file_name".
14. In homestead file, line command : vagrant up
15. In homestead file, line command : vagrant ssh
16. In virtual machine, in backend file : composer install

# ESLint

JavaScript files are linted with ESLint and the AirBNB's eslint config.

See here for installation : https://github.com/airbnb/javascript/tree/master/packages/eslint-config-airbnb


# Déploiement

## Déployer sur stage
L'adresse du stage est http://stage.manivelle.io

### Déployer le backend
1. Vérifier qu'on est dans la branche `develop` de `backend`
2. `composer update`
3. `npm update`
4. `gulp build`
5. Commiter les changements
6. `envoy run deploy`
7. S'il n'y a pas de messages d'erreurs, la page est disponible http://stage.manivelle.io

### Déployer le worker
1. Vérifier qu'on est dans la branche `develop`
2. Puller les changements
3. `composer update` pour mettre à jour notre dossier vendor
4. `envoy run deploy`


## Déployer sur prod
L'adresse du prod est http://clients.manivelle.io. Quand on est bien certain que tout est beau sur stage, on peut déployer sur prod.

### Déployer le backend
1. Vérifier qu'on est dans la branche `develop` de `backend`
2. `./release.sh` (ce script fait toutes les étapes plus haut, composer, npm, build, etc...)
3. Vérifier qu'on est dans la branche `master` de `backend`
6. `envoy run deploy`
7. S'il n'y a pas de messages d'erreurs, la page est disponible http://clients.manivelle.io

### Déployer le worker
1. Vérifier qu'on est dans la branche `master`
2. Puller les changements
3. `composer update` pour mettre à jour notre dossier vendor
4. `envoy run deploy`
