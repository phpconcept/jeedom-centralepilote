# Plug-In Jeedom Centrale Fil-Pilote (centralepilote)

Ce plugin apporte une centrale de programmation pour envoyer les ordres "fil-pilote" (Confort, Eco, Hors-Gel, ...) aux radiateurs supportant cette fonction et équipés des contacteurs connectés nécessaires.
Le plugin ne pilote pas des contacteurs connectés spécifiques, mais utilise une notion inspirée du plugin virtuel pour utiliser tout type de contacteurs (Zigbee, WiFi, Z-wave, EnOcean, ...) déjà gérés par Jeedom.


### Comprendre le fonctionnement d'un "Fil-Pilote"

En attendant une description dans cette page, vous pouvez aller voir : https://fr.wikipedia.org/wiki/Chauffage_%C3%A9lectrique#Fil_pilote

### Créer un Radiateur "Fil-Pilote"

Lors de la création d'un équipement de type "Radiateur", il va falloir indiquer comment est réalisé la fonction de fil-pilote pour ce radiateur. Autrement dit quels contacteurs sont utilisés pour envoyer les ordres fil-pilote au radiateur.
Le plugin offre actuellement 4 possibilités. Les 3 premières sont à bases de contacteurs simples on/off, la 4ème est à base d'objets virtuels, et permet (normalement) de couvrir tous les autres cas d'objets connectés permettant d'envoyer une commande fil-pilote sans être des contacteurs simples.

Un schéma permet de bien expliciter comment est réaliser la fonction de fil pilote, et ainsi de simplifier la chose.

- Constitution du fil-pilote par deux commutateurs

Cette méthode permet de supporter les 4 modes : Confort, Eco, Hors-Gel et Off (délestage).
Il faut simplement configurer les noms des commutateurs utilisés.

![Programmation](docs/images/config_radiateur_1.png)

- Constitution du fil-pilote par un seul commutateur - mode Confort/Off

Lorsqu'un seul commutateur on/off est utilisé, alors seuls deux modes sont supportés. Les modes supportés, dépendent du sens dans lequel la diode de redressement a été branchée.
Pour supporter les modes Confort/Off, il faut que la diode laisse passer dans le sens contacteur vers radiateur.

![Programmation](docs/images/config_radiateur_2.png)

- Constitution du fil-pilote par un seul commutateur - mode Confort/Hors-Gel

Ce cas est similaire au précédent, mais la diode est inversée, ce qui fait que le mode supporté est différent.

![Programmation](docs/images/config_radiateur_3.png)

- Constitution du fil-pilote par commandes virtuelles

Dans cette configuration, plus complexe, vous allez indiquer pour chaque mode que vous voulez supporter, les commandes à faire sur des équipements connectés, pour réaliser la commande fil-pilote.
Et vous allez aussi indiquer comment récupérer l'état des équipements connectés pour calculer le mode actuel du radiateur (à noter que ce champ peut être vide, car certains équipements ne savent pas retourner leur état).

Exemple pour le mode Confort :
![Programmation](docs/images/config_radiateur_4.png)

Explications : lorsque vous allez commander au radiateur de passer en mode "Confort", le plugin va automatiquement exécuter les commandes "Off" sur l'équipement "ContacteurA" et l'équipement "ContacteurB". Et de même lorsque vous allez demander à voir l'état du radiateur, le plugin va calculer celui-ci en lisant l'information "Etat" des deux contacteurs.

### Créer une Zone "Fil-Pilote"

Une "Zone" est simplement un regroupement de plusieurs radiateurs que vous voulez piloter en même temps. Cela peut par exemple être l'ensemble des radiateurs d'une même pièce. Ainsi si vous voulez passer la pièce en mode "Eco" vous pouvez le faire directement sans avoir à le faire pour chaque radiateur de la pièce.
Comme un radiateur vous pouvez décider quels commandes sont supportées ou non. Si vous choisissez un mode non supporté par l'un des radiateur de la zone, alors celui-ci utilisera le mode alternatif que vous avez configuré ou celui par défaut.

Une fois la zone configurée, c'est au niveau des radiateurs que vous allez indiquer s'ils sont dans une zone ou non. Si le radiateur est dans une zone, il ne peut alors plus être commandé directement. 

### Configurations des programmations horaires

Les configurations de plages horaires sont globales au plugin. Elles se configurent depuis la panneau de configuration du plugin, en utilisant le bouton "Programmations" :
![Programmation](docs/images/config_programmation_1.png)

Le plugin arrive avec une programmation par défaut qu'il n'est pas possible de supprimer et que l'on ne doit pas modifier (il pourra être automatiquement réinitialisé ultérieurement).

Il est ensuite possible de créer des programmation personnalisées.

La configuration des modes de chauffage en fonction des heures se fait simplement en cliquant sur les icônes des modes. A chaque click le mode suivant est proposé. Il est aussi possible de choisir le mode par les boutons se trouvant en dessous, puis de clicker sur les plages horaires.

N'oublez pas de sauvegarder avant de quitter.

Configuration des plages horaires :

![Programmation](docs/images/programmation_1.png)

### Utilisation des plages horaires

La configuration d'un radiateur ou d'une zone pour qu'elle utilise une plage horaire ne se fait pas dans la configuration de l'équipement, mais en utilisant des commandes. Cela permet à une personne néofite de facilement passer un radiateur d'un mode manuel à un mode auto. Cela permet aussi à d'autres équipements ou scénarii de changer le mode de pilotage des radiateurs.

Une fois le radiateur (ou la zone) mis en mode "Auto", la selection du programme se fait par le bouton "Select". Chaque pression sur le bouton fait passer au programme suivant. Le programme 0 est le programme par défaut.

Radiateur en mode manuel :

![Programmation](docs/images/radiateur_show_1.png)

Radiateur en mode programmé :

![Programmation](docs/images/radiateur_show_2.png)



---
## Aspects Techniques

### Change Logs

Release v0.1 (beta) :
- Première version



### Problèmes connus

- Les commmandes "Confort -1" et "Confort -2" ne sont pas encore complètement gérées.
- Lorsqu'un contacteur est changé directement, sans passer par l'équipement "radiateur fil-pilote", l'état de ce dernier n'est pas automatiquement mis à jour.
- Lors de création d'un radiateur en choisissant les 3 configurations à base de contacteurs, les modes affichés dans la page de configuration ne sont pas instantanément mis à jour sur la page. Ils le sont apès la sauvegarde du radiateur.


### Aspirations & Idées & Evolutions

Une petite liste d'idées ou de fonctions que l'on pourrait rajouter dans Centrale Fil-Pilote :

- Détection automatique de fenêtre ouverte
- Améliorer la précision horaire : ajouter une programmation au 1/4 d'heure
- Créer des widgets plus "jolis" et intuitifs.


