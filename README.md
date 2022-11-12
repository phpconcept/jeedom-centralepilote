# Plug-In Jeedom Centrale Fil-Pilote (centralepilote)

Ce plugin apporte une centrale de programmation pour envoyer les ordres "fil-pilote" (Confort, Eco, Hors-Gel, ...) aux radiateurs supportant cette fonction et �quip�s des contacteurs connect�s n�cessaires.
Le plugin ne pilote pas des contacteurs connect�s sp�cifiques, mais utilise une notion inspir�e du plugin virtuel pour utiliser tout type de contacteurs (Zigbee, WiFi, Z-wave, EnOcean, ...) d�j� g�r�s par Jeedom.


### Comprendre le fonctionnement d'un "Fil-Pilote"

En attendant une description dans cette page, vous pouvez aller voir : https://fr.wikipedia.org/wiki/Chauffage_%C3%A9lectrique#Fil_pilote

### Cr�er un Radiateur "Fil-Pilote"

Lors de la cr�ation d'un �quipement de type "Radiateur", il va falloir indiquer comment est r�alis� la fonction de fil-pilote pour ce radiateur. Autrement dit quels contacteurs sont utilis�s pour envoyer les ordres fil-pilote au radiateur.
Le plugin offre actuellement 4 possibilit�s. Les 3 premi�res sont � bases de contacteurs simples on/off, la 4�me est � base d'objets virtuels, et permet (normalement) de couvrir tous les autres cas d'objets connect�s permettant d'envoyer une commande fil-pilote sans �tre des contacteurs simples.

Un sch�ma permet de bien expliciter comment est r�aliser la fonction de fil pilote, et ainsi de simplifier la chose.

- Constitution du fil-pilote par deux commutateurs

Cette m�thode permet de supporter les 4 modes : Confort, Eco, Hors-Gel et Off (d�lestage).
Il faut simplement configurer les noms des commutateurs utilis�s.

![Programmation](docs/images/config_radiateur_1.png)

- Constitution du fil-pilote par un seul commutateur - mode Confort/Off

Lorsqu'un seul commutateur on/off est utilis�, alors seuls deux modes sont support�s. Les modes support�s, d�pendent du sens dans lequel la diode de redressement a �t� branch�e.
Pour supporter les modes Confort/Off, il faut que la diode laisse passer dans le sens contacteur vers radiateur.

![Programmation](docs/images/config_radiateur_2.png)

- Constitution du fil-pilote par un seul commutateur - mode Confort/Hors-Gel

Ce cas est similaire au pr�c�dent, mais la diode est invers�e, ce qui fait que le mode support� est diff�rent.

![Programmation](docs/images/config_radiateur_3.png)

- Constitution du fil-pilote par commandes virtuelles

Dans cette configuration, plus complexe, vous allez indiquer pour chaque mode que vous voulez supporter, les commandes � faire sur des �quipements connect�s, pour r�aliser la commande fil-pilote.
Et vous allez aussi indiquer comment r�cup�rer l'�tat des �quipements connect�s pour calculer le mode actuel du radiateur (� noter que ce champ peut �tre vide, car certains �quipements ne savent pas retourner leur �tat).

Exemple pour le mode Confort :
![Programmation](docs/images/config_radiateur_4.png)

Explications : lorsque vous allez commander au radiateur de passer en mode "Confort", le plugin va automatiquement ex�cuter les commandes "Off" sur l'�quipement "ContacteurA" et l'�quipement "ContacteurB". Et de m�me lorsque vous allez demander � voir l'�tat du radiateur, le plugin va calculer celui-ci en lisant l'information "Etat" des deux contacteurs.

### Cr�er une Zone "Fil-Pilote"

Une "Zone" est simplement un regroupement de plusieurs radiateurs que vous voulez piloter en m�me temps. Cela peut par exemple �tre l'ensemble des radiateurs d'une m�me pi�ce. Ainsi si vous voulez passer la pi�ce en mode "Eco" vous pouvez le faire directement sans avoir � le faire pour chaque radiateur de la pi�ce.
Comme un radiateur vous pouvez d�cider quels commandes sont support�es ou non. Si vous choisissez un mode non support� par l'un des radiateur de la zone, alors celui-ci utilisera le mode alternatif que vous avez configur� ou celui par d�faut.

Une fois la zone configur�e, c'est au niveau des radiateurs que vous allez indiquer s'ils sont dans une zone ou non. Si le radiateur est dans une zone, il ne peut alors plus �tre command� directement. 

### Configurations des programmations horaires

Les configurations de plages horaires sont globales au plugin. Elles se configurent depuis la panneau de configuration du plugin, en utilisant le bouton "Programmations" :
![Programmation](docs/images/config_programmation_1.png)

Le plugin arrive avec une programmation par d�faut qu'il n'est pas possible de supprimer et que l'on ne doit pas modifier (il pourra �tre automatiquement r�initialis� ult�rieurement).

Il est ensuite possible de cr�er des programmation personnalis�es.

La configuration des modes de chauffage en fonction des heures se fait simplement en cliquant sur les ic�nes des modes. A chaque click le mode suivant est propos�. Il est aussi possible de choisir le mode par les boutons se trouvant en dessous, puis de clicker sur les plages horaires.

N'oublez pas de sauvegarder avant de quitter.

Configuration des plages horaires :

![Programmation](docs/images/programmation_1.png)

### Utilisation des plages horaires

La configuration d'un radiateur ou d'une zone pour qu'elle utilise une plage horaire ne se fait pas dans la configuration de l'�quipement, mais en utilisant des commandes. Cela permet � une personne n�ofite de facilement passer un radiateur d'un mode manuel � un mode auto. Cela permet aussi � d'autres �quipements ou sc�narii de changer le mode de pilotage des radiateurs.

Une fois le radiateur (ou la zone) mis en mode "Auto", la selection du programme se fait par le bouton "Select". Chaque pression sur le bouton fait passer au programme suivant. Le programme 0 est le programme par d�faut.

Radiateur en mode manuel :

![Programmation](docs/images/radiateur_show_1.png)

Radiateur en mode programm� :

![Programmation](docs/images/radiateur_show_2.png)



---
## Aspects Techniques

### Change Logs

Release v0.1 (beta) :
- Premi�re version



### Probl�mes connus

- Les commmandes "Confort -1" et "Confort -2" ne sont pas encore compl�tement g�r�es.
- Lorsqu'un contacteur est chang� directement, sans passer par l'�quipement "radiateur fil-pilote", l'�tat de ce dernier n'est pas automatiquement mis � jour.
- Lors de cr�ation d'un radiateur en choisissant les 3 configurations � base de contacteurs, les modes affich�s dans la page de configuration ne sont pas instantan�ment mis � jour sur la page. Ils le sont ap�s la sauvegarde du radiateur.


### Aspirations & Id�es & Evolutions

Une petite liste d'id�es ou de fonctions que l'on pourrait rajouter dans Centrale Fil-Pilote :

- D�tection automatique de fen�tre ouverte
- Am�liorer la pr�cision horaire : ajouter une programmation au 1/4 d'heure
- Cr�er des widgets plus "jolis" et intuitifs.


