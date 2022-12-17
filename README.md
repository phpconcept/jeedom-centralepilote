# Plug-In Jeedom Centrale Fil-Pilote (centralepilote)

Ce plugin apporte une centrale de programmation pour envoyer les ordres "fil-pilote" (Confort, Eco, Hors-Gel, ...) aux radiateurs supportant cette fonction et �quip�s des contacteurs connect�s n�cessaires.
Le plugin ne pilote pas des contacteurs connect�s sp�cifiques, mais utilise une notion inspir�e du plugin virtuel pour utiliser tout type de contacteurs (Zigbee, WiFi, Z-wave, EnOcean, ...) d�j� g�r�s par Jeedom.


### Comprendre le fonctionnement d'un "Fil-Pilote"

En attendant une description dans cette page, vous pouvez aller voir : https://fr.wikipedia.org/wiki/Chauffage_%C3%A9lectrique#Fil_pilote

### Cr�er un Radiateur "Fil-Pilote"

Lors de la cr�ation d'un �quipement de type "Radiateur", il va falloir indiquer comment est r�alis� la fonction de fil-pilote pour ce radiateur. Autrement dit quels contacteurs sont utilis�s pour envoyer les ordres fil-pilote au radiateur.
Le plugin offre actuellement 4 possibilit�s. Les 3 premi�res sont � base de contacteurs simples on/off, la 4�me est � base d'objets virtuels, et permet (normalement) de couvrir tous les autres cas d'objets connect�s permettant d'envoyer une commande fil-pilote sans �tre des contacteurs simples.

Un sch�ma permet de bien expliciter comment est r�alis� la fonction de fil pilote, et ainsi de simplifier la chose.

- Constitution du fil-pilote par deux commutateurs

Cette m�thode permet de supporter les 4 modes : Confort, Eco, Hors-Gel et Off (d�lestage).
Il faut simplement configurer les noms des commutateurs utilis�s.

![Programmation](docs/images/config_radiateur_1.png)

- Constitution du fil-pilote par un seul commutateur - mode Confort/Off

Lorsqu'un seul commutateur on/off est utilis�, alors seuls deux modes sont support�s. Les modes support�s, d�pendent du sens dans lequel la diode de redressement a �t� branch�e.
Pour supporter les modes Confort/Off, il faut que la diode laisse passer dans le sens contacteur vers radiateur.

![Programmation](docs/images/config_radiateur_2.png)

- Constitution du fil-pilote par un seul commutateur - mode Confort/Hors-Gel

Ce cas est similaire au pr�c�dent, mais la diode est invers�e, ce qui fait que les modes support�s sont diff�rents.

![Programmation](docs/images/config_radiateur_3.png)

- Constitution du fil-pilote par commandes virtuelles

Dans cette configuration, plus complexe, vous allez indiquer pour chaque mode que vous voulez supporter, les commandes � faire sur des �quipements connect�s, pour r�aliser la commande fil-pilote.
Et vous allez aussi indiquer comment r�cup�rer l'�tat des �quipements connect�s pour calculer le mode actuel du radiateur (� noter que ce champ peut �tre vide, car certains �quipements ne savent pas retourner leur �tat).

Exemple pour le mode Confort :
![Programmation](docs/images/config_radiateur_4.png)

Explications : lorsque vous allez commander au radiateur de passer en mode "Confort", le plugin va automatiquement ex�cuter les commandes "Off" sur l'�quipement "ContacteurA" et l'�quipement "ContacteurB". Et de m�me lorsque vous allez demander � voir l'�tat du radiateur, le plugin va calculer celui-ci en lisant l'information "Etat" des deux contacteurs.

### Cr�er une Zone "Fil-Pilote"

Une "Zone" est simplement un regroupement de plusieurs radiateurs que vous voulez piloter en m�me temps. Cela peut par exemple �tre l'ensemble des radiateurs d'une m�me pi�ce. Ainsi si vous voulez passer la pi�ce en mode "Eco" vous pouvez le faire directement sans avoir � le faire pour chaque radiateur de la pi�ce.
Comme un radiateur, vous pouvez d�cider quelles commandes sont support�es ou non. Si vous choisissez un mode non support� par l'un des radiateur de la zone, alors celui-ci utilisera le mode alternatif que vous avez configur� ou celui par d�faut.

Une fois la zone cr��e, c'est au niveau des radiateurs que vous allez indiquer s'ils sont dans une zone ou non. Si le radiateur est dans une zone, il ne peut alors plus �tre command� directement. 

### Configurations des programmations horaires

Les configurations de plages horaires sont globales au plugin. Elles se configurent depuis la panneau de configuration du plugin, en utilisant le bouton "Programmations" :
![Programmation](docs/images/config_programmation_1.png)

Le plugin arrive avec une programmation par d�faut qu'il n'est pas possible de supprimer et que l'on ne doit pas modifier (il pourra �tre automatiquement r�initialis� ult�rieurement).

Il est ensuite possible de cr�er des programmations personnalis�es.

La configuration des modes de chauffage en fonction des heures se fait simplement en cliquant sur les ic�nes des modes. A chaque click le mode suivant est propos�. Il est aussi possible de choisir le mode par les boutons se trouvant en dessous, puis de clicker sur les plages horaires.

N'oublez pas de sauvegarder avant de quitter.

Configuration des plages horaires :

![Programmation](docs/images/programmation_1.png)

### Utilisation des plages horaires

La configuration d'un radiateur ou d'une zone pour qu'elle utilise une plage horaire ne se fait pas dans la configuration de l'�quipement, mais en utilisant des commandes. Cela permet � une personne n�ofite de facilement passer un radiateur d'un mode manuel � un mode auto. Cela permet aussi � d'autres �quipements ou sc�narii de changer le mode de pilotage des radiateurs.

Une fois le radiateur (ou la zone) mis en mode "Auto", la selection du programme se fait par le menu d�roulant "Programme Select". Le fonctionnement actuel du core jeedom, fait que la liste commence par un choix "Aucun" (qui n'a aucun sens :-) ). Choisisez le bon programme et celui-ci s'affichera dans "Programme".

Radiateur en mode manuel (Confort, Eco, etc... ) :

![Programmation](docs/images/radiateur_show_1.png)

Radiateur en mode programm� :

![Programmation](docs/images/radiateur_show_2.png)

### Objet "Centrale"

Cet objet unique permet des actions globales sur les radiateurs. C'est lui aussi qui m�morise les diff�rentes programmations (m�me si celles-ci sont accessibles par un bouton d�di�).

La centrale permet en particulier de r�aliser une fonction de d�lestage (ou bypass - contournement), en imposant un mode � tous les radiateurs d'un coup et en les figeant dans ce mode jusqu'au retour � la normal.
Le d�lestage se fait par des commandes 'delestage', 'horsgel' et 'eco'. Pour sortir de ce mode, une commande 'normal' est � utiliser.

Le premier cas d'usage est celui du d�lestage � proprement parler. Il permet de couper tous les radiateurs et donc de r�aliser un d�lestage sur les chauffages. Il peut �tre utilis� par les sc�narii et de ce fait �tre coupl� � EcoWatt.
Le second cas d'usage est celui du d�part en cong�s (ou pour une longue absence), le mode 'horsgel' permet de mettre toute la maison dans ce mode.
Le troisi�me cas d'usage est celui d'un d�part moins long de la maison (journ�e ?) et permet de mettre celle-ci d'un coup en mode 'eco'.

Centrale en mode "normal" :
![Centrale](docs/images/centrale_show_1.png)

Centrale en mode "D�lestage":
![Centrale](docs/images/centrale_show_2.png)


---
## Aspects Techniques

### Change Logs

Release v0.4 (beta) :

- Nouveaut�s :
  - Ajout d'un widget "custom" pour les radiateurs et les zones.
  - Possibilit� de choisir le widget custom ou le widget syst�me par configuration globale
  - Ajout d'une notion de d�clenchement (trigger) par radiateur/zone.
  
- Bug corrections :
  - Lors de la cr�ation initiale, l'�tat de l'�quipement "centrale" n'est pas correctement affich�.
  - Ajout du contr�le que au minimum 2 modes sont s�lectionn�s
  - Lors du changement de la programmation sur un horaire en cours, la mise � jour est maintenant prise en compte imm�diatement et au prochain 'clock-tick'

Release v0.3 (beta) :
- Ajout de la fonction de d�lestage (bypass) au niveau de l'objet "Centrale". Cela permet d'envoyer un ordre centralis� obligatoire � tous les radiateurs (Off, Hors-Gel ou Eco). Les radiateurs resteront dans ce mode jusqu'� l'odre de retour � la normal. Ils reprendront alors le mode de pilotage dans lequel ils �taient.

Release v0.2 (beta) :
- Migration automatique depuis la v0.1 vers la v0.2 pas compl�tement transparente. En particulier : perte des programmations, la commande 'pilotage' peut afficher la valeur 'manuel', mais cela sera updater � la prochaine transition. L'ordre des commandes peut aussi �tre perturb�e.
- Modification du concept de pilotage manuel/auto qui �tait s�par� des modes de chaleur. Un seul concept de "pilotage" reste pouvant prendre comme valeurs les modes de chauffage 'confort', 'confort_1', 'confort_2', 'eco', 'horsgel', 'off' et 'auto'. Cela all�ge l'utilisation et le code sous-jacent. 
- Cons�quence : Suppression des commandes "prog_select" et "manuel". Renommage de la commande 'mode' en commande 'etat'.
- S�lection des programmes du mode auto par un 'select'. Ajout des commandes "programme_select" (action) et "programme_id" (info) qui viennent compl�ter la commande "programme" (info) qui contient le nom du programme selectionn�.
- Ajout d'une information sur la puissance des radiateurs (pour usage futur de mesures ou d'analyses).
- Changement du stockage des programmations : ils �taient dans la configuration du plugin (ce qui �tait un probl�me car perte lors de la desactivation), ils sont maintenant dans la configuration de l'objet (unique) 'Centrale'.

Release v0.1 (beta) :
- Premi�re version



### Probl�mes connus

- Les commmandes "Confort -1" et "Confort -2" ne sont pas encore compl�tement g�r�es.
- Lorsqu'un contacteur est chang� directement, sans passer par l'�quipement "radiateur fil-pilote", l'�tat de ce dernier n'est pas automatiquement mis � jour.
- Lors de cr�ation d'un radiateur en choisissant les 3 configurations � base de contacteurs, les modes affich�s dans la page de configuration ne sont pas instantan�ment mis � jour sur la page. Ils le sont ap�s la sauvegarde du radiateur.
- Les commandes avec des options (select) ne sont pas encore bien g�r�es pour les retours d'�tat des contacteurs.
- Il ne faut surtout pas d�truire ou d�sactiver l'objet "Centrale". Le PlugIn essaie de l'emp�cher, mais tout n'est pas encore contr�l�.
- Il faut laisser au moins deux modes par radiateur (pour l'instant le plugIn ne v�rifie pas ...)
- L'affichage de la temp�rature actuelle dans le widget n'est pas mis � jour automatiquement mais uniquement lors d'un changement d'�tat
- La fonction de d�clenchement n'est pas possible actuellement avec le widget standard.

### Aspirations & Id�es & Evolutions

Une petite liste d'id�es ou de fonctions que l'on pourrait rajouter dans Centrale Fil-Pilote :

- D�tection automatique de fen�tre ouverte
- Am�liorer la pr�cision horaire : ajouter une programmation au 1/4 d'heure
- Cr�er des widgets plus "jolis" et intuitifs.
- Ajouter une fonction de d�clenchement (passage � un mode � heure fixe) avec �ventuellement un retour � un mode donn� au bout d'un certain temps.
- Ajouter le cas d'un radiateur ne supportant pas le fil-pilote (mode on/off = confort/off).
- Ajouter un nom court aux programmations
- Bouton "Boost" : cas d'usage lancer le mode "Confort" dans une salle de bain, juste pendant 1h ou 2h et revenir � l'�tat normal automatiquement.
- Utiliser les informations (optionnelles) de temp�rature de la pi�ce et de la puissance du radiateur pour proposer des analyses ou des audits.


