# INFOS

__Adresse IP :__ 172.17.4.5

__Nom de domaine :__ coquatrix-dfsgr1.local

__Login SSH :__ root

__Mot de passe SSH :__ 6kJP59tr

__Lien aaPanel :__ https://172.17.4.5:15489/13c1829c

__Identifiant aaPanel :__ e1jmrelo

__Mot de passe aaPanel :__ 85be118c

__Nom de la BDD :__ habit_tracker

__Identifiant BDD :__ habit_tracker

__Mot de passe de la BDD :__ 6985463b65daf


# PROCÉDURE DE DÉPLOIEMENT

## Etape 1 : Se connecter en SSH

__Noter :__ Adresse IP, nom de domaine, identifiants SSH

__Terminal :__ ```Windows``` ssh root@<span style="color:red">[adresse_ip]</span>


## Etape 2 : Récupérer le code source de l’application existante

__Terminal :__ ```VSCode``` git clone <span style="color:red">[adresse_github]</span>


## Etape 3 : Installer les dépendances

__Terminal :__ ```VSCode``` composer install --optimize-autoloader

__Terminal :__ ```VSCode``` composer require --dev phpstan/phpstan

__VSCode :__ Créer un fichier "phpstan.neon" à la racine :

```yaml
parameters:
    level: 5
    paths:
        - public
        - src
        - templates
```

**Pour lancer l'analyse PhpStan :**
- __Terminal :__ `VSCode` vendor/bin/phpstan analyse


## Etape 4 : Vérifier que le projet fonctionne en local

__Terminal :__ ```VSCode``` php bin/serve


## Etape 5 : Installer GitCliff

__Terminal :__ ```VSCode``` scoop install git-cliff

__Terminal :__ ```VSCode``` git-cliff --init

__Terminal :__ ```VSCode``` git add .

__Terminal :__ ```VSCode``` git commit -m "doc:Installation de cliff"

__Terminal :__ ```VSCode``` git-cliff --bump -o ./CHANGELOG.md

_Voir la version générée dans CHANGELOG.mg_

__Terminal :__ ```VSCode``` git add .

__Terminal :__ ```VSCode``` git commit -m "version <span style="color:red">[tag]</span>"

__Terminal :__ ```VSCode``` git tag <span style="color:red">[tag]</span>

__Terminal :__ ```VSCode``` git push origin main


## Etape 6 : Installer aaPanel

__Terminal :__ ```Windows SSH``` URL=https://www.aapanel.com/script/install_7.0_en.sh && if [ -f /usr/bin/curl ];then curl -ksSO "$URL" ;else wget --no-check-certificate -O install_7.0_en.sh "$URL";fi;bash install_7.0_en.sh aapanel

[Site web de aaPanel](https://www.aapanel.com/new/download.html?_gl=1*mvrlwt*_up*MQ..*_ga*MTE5NzE3MTQwNi4xNzQ3MzgwNDA5*_ga_PKXWQERS47*czE3NDczODA0MDckbzEkZzAkdDE3NDczODA0MDckajAkbDAkaDA.)

__Terminal :__ ```Windows SSH``` _Do you want to install aaPanel to the /www directory now?_ : Répondre "y"

_Laisser l’installation se faire ..._

__Noter :__ username, password, aaPanel Internal Address

__Navigateur :__ Ouvrir le lien "aaPanel Internal Address"

__Navigateur aaPannel :__ Forcer l’ouverture de la page non sécurisée

__Navigateur aaPannel :__ Se connecter avec les identifiants donnés

__Navigateur aaPannel :__ Sélectionner LNMP (One-click)

_Laisser l’installation se faire ..._

__Navigateur aaPannel :__ Se rendre dans l’onglet "Website" à gauche

__Navigateur aaPannel :__ Cliquer sur le bouton "Add site"

__Navigateur aaPannel :__ Noter le nom de domaine dans "Domaine name"

__Navigateur aaPannel :__ Sélectionner "Create" sur le champ "FTP"

__Navigateur aaPannel :__ Sélectionner "MySQL" sur le champ "Database"

__Navigateur aaPannel :__ Modifier le nom de la base de données

__Navigateur aaPannel :__ Cliquer sur le bouton "Confirm"

__Noter :__ Identifiants FTP et Database

__Navigateur :__  Se rendre sur le site avec le nom de domaine et vérifier qu'il affiche _"Congratulations, the site is created successfully!"_


## Etape 7 : Premier déploiement

__Terminal :__ ```Windows SSH``` cd /

__Terminal :__ ```Windows SSH``` ls /www/wwwroot

_Vérifier qu’il y a bien un fichier avec le nom de domaine_

__Terminal :__ ```Windows SSH``` mkdir /var/depot_git

__Terminal :__ ```Windows SSH``` cd /var/depot_git

__Terminal :__ ```Windows SSH``` git init --bare

__Terminal :__ ```VSCode``` git remote add vps root@<span style="color:red">[adresse_ip]</span>:/var/depot_git

__Terminal :__ ```VSCode``` git push -u vps <span style="color:red">[tag]</span>

__Terminal :__ ```Windows SSH``` cd /

__Terminal :__ ```Windows SSH``` touch deploy.sh

__Terminal :__ ```Windows SSH``` nano deploy.sh

__Terminal :__ ```Windows SSH (dans deploy.sh)``` git --work-tree=/www/wwwroot/<span style="color:red">[nom_de_domaine]</span> --git-dir=/var/depot_git checkout -f $1

__Terminal :__ ```Windows SSH``` _Quitter le fichier (ctrl+X, O, Entrer)_

__Terminal :__ ```Windows SSH``` bash /deploy.sh <span style="color:red">[tag]</span>


## Etape 8 : Configurer aaPanel

__Navigateur aaPannel :__ Sélectionner le site en cliquant sur le nom de domaine

__Navigateur aaPannel :__ Se rendre dans l’onglet "Site directory"

__Navigateur aaPannel :__ Sélectionner "/public" dans le champ "Running directory" et cliquer sur le bouton "Save"

__Navigateur aaPannel :__ Se rendre dans l’onglet "URL rewrite"

__Navigateur aaPannel :__ Sélectionner "mvc"

__Navigateur aaPannel :__ Cliquer sur le bouton "Save"

__Navigateur aaPannel :__ Se rendre dans l’onglet "SSL"

__Navigateur aaPannel :__ Sélectionner "Let’s Encrypt"

__Navigateur aaPannel :__ Cocher le nom de domaine

__Navigateur aaPannel :__ Cliquer sur le bouton "Apply"

_Impossible lors de l'épreuve car le nom de domaine est local : Invalid identifiers requested :: Cannot issue for "coquatrix-dfsgr1.local": Domain name does not end with a valid public suffix (TLD)_

__Navigateur aaPannel :__ Se rendre dans l’onglet "Composer"

__Navigateur aaPannel :__ Cliquer sur le bouton "Apply"

__Navigateur aaPannel :__ Fermer la modale


## Etape 9 : Créer la base de données

__VSCode :__ Modifier les variables d'environements

```yaml
DB_HOST="localhost"
DB_PORT="3306"
DB_DATABASE=[database_user]
DB_USERNAME=[database_user]
DB_PASSWORD=[database_password]
```

__Navigateur aaPannel :__ Se rendre dans l'onglet "Files" à gauche

__Navigateur aaPannel :__ Sélectionner le nom de domaine

__Navigateur aaPannel :__ Ajouter les variables d'environements

__Navigateur aaPannel :__ Fermer et enregistrer le fichier

__Terminal :__ ```Windows SSH``` cd /www/wwwroot/<span style="color:red">[nom_de_domaine]</span>

__Terminal :__ ```Windows SSH``` php bin/create-database

__Terminal :__ ```Windows SSH``` php bin/load-demo-data

__Navigateur :__  Se rendre sur le site avec le nom de domaine et vérifier qu'il affiche le site


## Etape 10 : Commit

__Terminal :__ ```VSCode``` git add .

_Liste des mots clés dans cliff.toml_

__Terminal :__ ```VSCode``` git commit -m "<span style="color:red">[mot_clé]</span>:<span style="color:red">[commentaire]</span>"

__Terminal :__ ```VSCode``` git push origin main


## Etape 11 : Commit et déploiement

__Terminal :__ ```VSCode``` git add .

_Liste des mots clés dans cliff.toml_

__Terminal :__ ```VSCode``` git commit -m "<span style="color:red">[mot_clé]</span>:<span style="color:red">[commentaire]</span>"

**Si changement de version majeure :**
- __Terminal :__ ```VSCode``` git-cliff --bump major -o ./CHANGELOG.md

**Sinon :**
- __Terminal :__ ```VSCode``` git-cliff --bump -o ./CHANGELOG.md


_Voir la version générée dans CHANGELOG.mg_

__Terminal :__ ```VSCode``` git add .

__Terminal :__ ```VSCode``` git commit -m "version <span style="color:red">[tag]</span>"

__Terminal :__ ```VSCode``` git tag <span style="color:red">[tag]</span>

__Terminal :__ ```VSCode``` git push origin main

__Terminal :__ ```VSCode``` git push vps <span style="color:red">[tag]</span>

__Terminal :__ ```Windows SSH``` bash /deploy.sh <span style="color:red">[tag]</span>


