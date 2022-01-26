# ChronopostWS

Ce module PHP propose une intégration au WebServices de Chronopost. L'objectif est de pouvoir générer des étiquettes de transport et de les gérer.  
Techniquement le module reçoit un tableau correspondants aux étiquettes à créer. Ce tableau est enrichi sous forme d'un objet en fonction des requêtes exéécutées.

## Fonctionnement choisi
Tous les appels sont considérés multi colis et multi format d'étiquettes. Afin d'avoir un fonctionnement uniforme, toutes les étiquettes sont réservées et récupérées dans un second temps. Par construction du web-service, toutes les étiquettes sont envoyées dans un seul document. Il n'est donc pas possible d'attacher, dans ce cas là, un unique document au numéro de suivi.

Les services Chronopost ciblés :
````
ShippingServices
    - shippingMultiParcelV4
    - getReservedSkybillWithTypeAndMode
TrackingService
    - searchPOD
    - cancelSkybill
    - trackSkybillV2
````

## Fonctions supportées

Seule la partie Shipping est implémentée et fonctionnelle pour le moment.

- Réservation d'étiquettes de transport
    - 1 destinataire, 1 expéditeur, N colis
    - N destinataires, N expéditeurs, N colis
	- N destinataires, 1 expéditeur, N colis (sauf en mode A/R)
    - Mode aller / retour
    - Etiquettes multi formats (PDF, ZPL, ...)
    - Validation du format de chaque donnée en fonction de la documentation Chronopost
    - Contrôle général de cohérence (Readiness For Launch)
    - Enrichissement de l'objet shipment avec les réponses du web-service
	- Enregistrement sur disque des étiquettes
- Mode debug complet (dump des objets, dump des requêtes et réponses)
- Peut générer des exceptions PHP si l'application fonctionne de cette manière.



## Non supporté ou à optimiser (à ce jour)
- Shipment
    - Classe de détermination du nom du pays normalisé et de contrôle de la cohérence du code postal en fonction du code pays
    - Trouver un moyen plus élégant de faire des étiquettes aller / retour
- Tracking
    - A terminer
        - Annulation d'une lettre de transport
        - Récupération des preuves de livraison
        - Suivi d'un colis
- Optimisation des classes et des objets
- Ecrire une vraie documentation

## Usage

### Pour tester
Utiliser `composer update` pour déclarer le `namespace` et utilisez directement les scripts du dossier `./tests`.  
Le dossier `./tests/logs` contient 3 exemples d'appels réussis en mode "Debug". Les fichiers `.json` sont les dumps des objets, les fichiers `.xml` sont les appels et réponses au web-service. Les autres fichiers sont les étiquettes.

### Pour intégrer
Tout repose sur composer.

Tous les objets sont appelables manuellement. Néanmoins, il est bien plus facile de charger un tableau de données préparé ailleurs. La lecture de la documentation Chronopost est impérative pour comprendre ce qu'il faut fournir (la liste est longue) et la signification de certains paramètres. La fonction `RFLCheck()` de chaque object s'assure de la cohérence apparente avant appel du web-service. Notez qu'il existe des paramètres dits optionnels dans la documentation mais bloquants lors des appels si absents.

La convention est que le tableau associatif donné en entrée utilise le nom exact des propriétés des objets attendu par le webService. Chaque élément passera par un _"setter"_ qui contrôlera sa forme aussi précisément que possible. Les expressions régulières sont massivement utilisées (voir la classe `wsregex.php`).

Le dossier `/src/wsdata` contient l'ensemble des classes manipulant les objets unitaires à transettre au webservice.  
Le dossier `/src/utils` contient des classes annexes  
Le dossier `/src/exceptions` contient les classes de gestion des exception  
Les 3 classes principales sont:
- `/src/chronopost.php` qui gère les appels et les réponses au/du web-service
- `/src/shipment.php` qui gère l'objet d'appel au service `ShipphingService`
- `/src/tracking.php` qui gère les objets d'appel au service `TrackingService`

### Note aux développeurs
Le web-service est chatouilleux sur les données envoyées. `RFLCheck()` tient compte de ce qui a été vu lors du développement. Néanmoins, d'autres particularités peuvent exister. L'erreur `29` est caractéristique d'un problème de données ou de la forme de l'appel SOAP. Les exemples fournis ont été testés fonctionnels. Inspirez vous en.

## Contribuer
La contribution est la bienvenue sous forme de PR documentée unitaire ou de remontée de bug documentée également. Les demandes fonctionnelles ne seront traitées que si nous en avons le temps et si cela a un sens pour nous. Préférez les PRs.