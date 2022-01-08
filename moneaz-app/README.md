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

Les images Docker ainsi que les pods K8s fonctionnent.
Pour tester, vous pouvez essayer de vous créer un compte et de vous connecter par la suite.
L'application datant d'il y a 3 ans, je suis pas sure que les autres fonctionnalités soient toujours opérationnelles.
Je rencontre en revanche des problèmes de CORS avec Kubernetes

## Partie "Dev"
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

ATTENTION : Certaines dépendances sont longues à télécharger et dans certains cas, cela peut timeout (si vous avez une baisse de connexion ou autre). 
Dans ce cas, il faut simplement relancer le `docker-compose up`


## Partie "Ops"

Pour tester cette partie, attention à bien libérer les ports 4200, 80 et 3360. Stoppez donc les conteneurs Docker précédents.    
Veillez aussi à être toujours à la racine du projet `moneaz-app`

Tout d'abord, il faut lancer minikube. 
Etant donné que la compilation d'Angular est assez lourde, il faut alloué plus de mémoire de de CPU à minikube
```
minikube start --memory 6196 --cpus 4
```

Il faut ensuite donner à minikube les variables d'environnement afin de pouvoir les données les images Docker à créer
```
eval $(minikube -p minikube docker-env)
```

Nous avons besoin de build nos images Docker
```
docker-compose build --build-arg front_env="k8s"
```

A la racine du projet `moneaz-app`, il faut monter notre dossier local "api" dans le noeud de minikube et lancer les pods :
```
minikube mount ./api:/mnt/api
```
puis dans un second terminal
```
kubectl apply -f k8s
```

A ce stade, il faut log le pod front afin de bien attendre la fin de la compilation.

Vous pouvez recupérer le nom du pod front avec cette commande `kubectl get pods` ou stocker le nom du pod dans une variable pour un usage future :
```
POD_FRONT=$(kubectl get pod -l app=appmoneaz,role=front -o custom-columns=:metadata.name --no-headers)
```
```
kubectl logs $POD_FRONT
```

Avec Minikube, il faut donner une IP externe pour les pods :
```
minikube tunnel
```

Ensuite, copier le nom du pod pour le back. Vous pouvez lister les pods avec cette commande :
```
kubectl get pods
```
Ou stockez le dans une variable local pour l'utiliser ensuite :
```
POD_BACK=$(kubectl get pod -l app=appmoneaz,role=back -o custom-columns=:metadata.name --no-headers)
```

Ensuite, il faut installer les dépendances : cette étape peut être assez longue
```
kubectl exec $POD_BACK -- "composer" "i"
```
Puis lancer les migrations dans ce pod avec cette commande :
```
kubectl exec $POD_BACK -- "php" "artisan" "migrate" "--force"
```

