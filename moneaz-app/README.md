# TP 1  - INFO910 Introduction DevOps


### Sujet
1 application avec au moins 2 conteneurs pour tourner :
- peu importe le langage ou le type d’application
- 1 des images doit être construite (dockerfile)
- 1 fichier compose.yml ou docker-compose.yml


### Présentation de l'application
Moneaz App est une application qui a été développée dans le cadre scolaire en L3 et que j'ai réutilisé dans le cadre de ce TP
afin de conteneuriser l'application.     
L'application permet de gérer vos budgets.    

Architecture de l'application & langages utilisés :
- Une architecture Client / Serveur
- Coté client : JS (ECMANext), jQuery, Ajax, Angular
- Coté serveur : PHP, Laravel
- Coté Base de données : SQL (ou No-SQL)

## Partie Docker
### Informations et pré-requis

Les ports 80, 3360 et 4200 doivent être disponibles sur votre machine.

### Exécuter l'application avec docker

* le front est exécuté sur le port 4200
* l'api est exécuté sur le port 80
* Mysql est exécuté sur le port 3360


Lancer cette commande
```
docker-compose up
```
Cela va :
- lancer un conteneur MySQL
- initialiser une base de données
- créé un conteneur qui installe les dépendances du back-end (vendors)
- créé un conteneur qui installe les dépendances du front-end (node_modules)
- créé un conteneur qui sert le back-end
- créé un conteneur qui sert le front-end


```composer update``` et ```laravel migrations``` seront automatiquement lancés lors du ```docker-compose up```