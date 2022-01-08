#!/bin/sh

minikube start --memory 6196 --cpus 4
eval $(minikube -p minikube docker-env)
docker-compose build --build-arg front_env="k8s"
minikube mount ./api:/mnt/api &
sleep 2
kubectl apply -f k8s/
minikube tunnel &
POD=$(kubectl get pod -l app=appmoneaz,role=back -o custom-columns=:metadata.name --no-headers)
kubectl exec $POD -- "composer" "i"
kubectl exec $POD -- "php" "artisan" "migrate" "--force"
