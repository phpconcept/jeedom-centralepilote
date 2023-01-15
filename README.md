# Plug-In Jeedom Centrale Fil-Pilote (centralepilote)

Ce plugin apporte une centrale de programmation pour envoyer les ordres "fil-pilote" (Confort, Eco, Hors-Gel, ...) aux radiateurs supportant cette fonction et �quip�s des contacteurs connect�s n�cessaires.
Le plugin ne pilote pas des contacteurs connect�s sp�cifiques, mais utilise une notion inspir�e du plugin virtuel pour utiliser tout type de contacteurs (Zigbee, WiFi, Z-wave, EnOcean, ...) d�j� g�r�s par Jeedom.


### Comprendre le fonctionnement d'un "Fil-Pilote"

En attendant une description dans cette page, vous pouvez aller voir : https://fr.wikipedia.org/wiki/Chauffage_%C3%A9lectrique#Fil_pilote

### Cr�er un Radiateur "Fil-Pilote"

Lors de la cr�ation d'un �quipement de type "Radiateur", il va falloir indiquer comment est r�alis� la fonction de fil-pilote pour ce radiateur. Autrement dit quels contacteurs sont utilis�s pour envoyer les ordres fil-pilote au radiateur.
Le plugin offre actuellement 4 possibilit�s. Les 3 premi�res sont � base de contacteurs simples on/off, la 4�me est � base d'objets virtuels, et permet (normalement) de couvrir tous les autres cas d'objets connect�s permettant d'envoyer une commande fil-pilote sans �tre des contacteurs simples.

Un sch�ma permet de bien expliciter comment est r�alis� la fonction de fil pilote, et ainsi simplifier la chose.

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

Explications de l'exemple : lorsque vous allez commander au radiateur de passer en mode "Confort", le plugin va automatiquement ex�cuter les commandes "Off" sur l'�quipement "ContacteurA" et l'�quipement "ContacteurB". Et de m�me lorsque vous allez demander � voir l'�tat du radiateur, le plugin va calculer celui-ci en lisant l'information "Etat" des deux contacteurs.

### Cr�er une Zone "Fil-Pilote"

Une "Zone" est simplement un regroupement de plusieurs radiateurs que vous voulez piloter en m�me temps. Cela peut par exemple �tre l'ensemble des radiateurs d'une m�me pi�ce. Ainsi si vous voulez passer la pi�ce en mode "Eco" vous pouvez le faire directement sans avoir � le faire pour chaque radiateur de la pi�ce.
Comme un radiateur, vous pouvez d�cider quelles commandes sont support�es ou non. Si vous choisissez un mode non support� par l'un des radiateur de la zone, alors celui-ci utilisera le mode alternatif que vous avez configur� ou celui par d�faut.

Une fois la zone cr��e, c'est au niveau des radiateurs que vous allez indiquer s'ils sont dans une zone ou non. Si le radiateur est dans une zone, il ne peut alors plus �tre command� directement. 

### Affichage d'un radiateur ou d'une zone

Par d�faut le plugin utilise des widget customiz�s, mais une option globale du plugin permet de repasser en plugin standard.

#### Description g�n�rale du Widget Custom

![Widget custom](docs/images/radiateur_show_3.png) ![Widget custom](docs/images/radiateur_show_4.png)

La premi�re ligne contient les boutons de commandes des modes, avec comme dernier bouton 'auto'. Le bouton du mode actif est en vert.

Le bouton 'auto' permet de mettre le radiateur en mode de programmation automatique. Lorsque le radiateur est en mode 'auto' le bouton est en bleu. Pour sortir du mode 'auto' il faut simplement choisir l'un des autres modes manuels (Confort, Eco, ...).

La seconde partie contient :
- dans une premi�re colonne les boutons de configurations sp�cifiques :
  - ![selection de la programmation](docs/images/bouton_trigger.png) : Configuration du d�clenchement programm�. Il est en bleu si un mode d�clenchement a �t� programm�.
  - ![selection de la programmation](docs/images/bouton_fenetre.png) : Configuration du mode "fen�tre ouvert", qui permet de mettre le radiateur en mode "bypass" le temps de l'ouverture, puis de revenir � son mode d'origine.
  - ![selection de la programmation](docs/images/bouton_programme.png) : Configuration du programme utilis� lorsque le radiateur est en mode 'auto'
- dans la colonne du milieu, un pictogramme illustant le mode actif, ainsi qu'un rappel du programme courant si le radiateur est en mode 'auto'
- dans le colonne de droite des informations compl�mentaires, comme le mode actuel du radiateur, la temp�rature cible (si elle est connue), la temp�rature mesur�e (si une mesure de temp�rature a �t� associ�e au radiateur).
 
#### S�lection du programme automatique

En utilisant le bouton en bas � gauche ![selection de la programmation](docs/images/bouton_programme.png), une fen�tre additionnelle aparait et permet de configurer le programme associ� au radiateur.

![Widget custom](docs/images/radiateur_show_select_prog.png) ![Widget custom](docs/images/radiateur_show_select_prog_2.png)

#### Cr�ation, suppression et visualisation des d�clenchements unitaires programm�s

En utilisant le bouton en haut � gauche ![configuration trigger](docs/images/bouton_trigger.png), vous allez pouvoir cr�er des d�clenchements unitaires programm�s. Lorsqu'un d�clencheur existe, le bouton devient bleu.

![Configuration trigger](docs/images/radiateur_show_trigger_1.png) ![Configuration trigger](docs/images/radiateur_show_trigger_2.png)

Un click sur le bouton bleu, permet de visualiser les programmations pr�vues, de les supprimer, ou d'en ajouter de nouvelles

![Configuration trigger](docs/images/radiateur_show_trigger_3.png)

Notez que les d�clenchements ne sont pas persistant (ou r�current), ils sont automatiquement supprim�s une fois la date pass�e.

#### Mode fen�tre ouverte

Ce mode n'est pas encore disponible, bien que le bouton soit pr�sent ...

#### Affichage en mode "D�lestage"

Le mode "d�lestage" est une fonction globale, configurable au niveau de l'objet "Centrale Fil-Pilote", et qui met l'ensemble de toute l'installation dans un mode forc� de type "Off", "Eco" ou Hors-Gel".

![Delestage](docs/images/radiateur_show_delestage.png)

Lorsque l'on sort du mode "d�lestage", les radiateurs reprennent leur mode pr�c�dent. Si des d�clenchements �taient programm�s, les d�clenchements programm�s sont tous jou�s instantanement afin que le radiateur se retrouve dans l'�tat attendu � l'instant donn�.

#### Affichage d'une zone

Un widget de zone se comporte de fa�on similaire � un radiateur.

![Affichage zone](docs/images/zone_show_1.png)

Les radiateurs associ�s � une zone, ne sont plus pilotables individuellement, ils affichent simplement leur �tat. 

![Affichage zone](docs/images/zone_show_2.png)
 
Il est possible de leur retirer leur visibilit�, dans la configuration standard de l'objet, pour ne pas surcharger le panel d'une pi�ce.

Il est fortement recommand� de mettre dans une zone des radiateurs de m�me "capacit�" (ayant les m�mes modes). Cependant si un radiateur n'a pas le bon mode qu'impose la zone, il utilisera son mode alternatif, tel que configur� dans les propri�t�s de l'objet.

Exemple d'un radiateur ne supportant pas le mode "Eco" et pour lequel le mode "Hors-Gel" a �t� configur� comme mode alternatif :

![Affichage zone](docs/images/zone_show_3.png)

#### Widget Standard

Le widget standard est bien plus basique, mais il contient toutes les informations n�cessaires. 

Notez cependant qu'il ne permet pas de configurer le mode de d�clenchement unitaire.

Radiateur en mode manuel (Confort, Eco, etc... ) :

![Programmation](docs/images/radiateur_show_1.png)

Radiateur en mode programm� :

![Programmation](docs/images/radiateur_show_2.png)

Notez que l'affichage des informations de programmation est en deux parties : 
- L'information "Programme" qui indique quel est le programme actif.
- Le menu de s�lection "Programme Select" qui permet de changer le programme actif. Il pr�sente par d�faut le texte "Aucun" qui ne peut pas �tre modifi� (limitation du widget standard).

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

La configuration d'un radiateur ou d'une zone pour qu'elle utilise une plage de programmation horaire ne se fait pas dans la configuration de l'�quipement, mais en utilisant des commandes. Cela permet � une personne n�ofite de facilement passer un radiateur d'un mode manuel � un mode auto. Cela permet aussi � d'autres �quipements ou sc�narii de changer le mode de pilotage des radiateurs.

Une fois le radiateur (ou la zone) mis en mode "Auto", la selection du programme se fait depuis le widget du radiateur. Voir ci-dessus les diff�rentes possibilit�s offertes par le widget.


### Objet "Centrale Fil-Pilote"

Cet objet unique permet des actions globales sur les radiateurs. C'est lui aussi qui m�morise les diff�rentes programmations (m�me si celles-ci sont accessibles par un bouton d�di�).

La centrale permet en particulier de r�aliser une fonction de d�lestage (ou bypass - contournement), en imposant un mode � tous les radiateurs d'un coup et en les figeant dans ce mode jusqu'au retour � la normal.
Le d�lestage se fait par des commandes 'delestage', 'horsgel' et 'eco'. Pour sortir de ce mode, une commande 'normal' est � utiliser.

Cas d'usages :
- Le premier cas d'usage est celui du d�lestage � proprement parler. Il permet de couper tous les radiateurs et donc de r�aliser un d�lestage sur les chauffages. Il peut �tre utilis� par les sc�narii et de ce fait �tre coupl� � EcoWatt.
- Le second cas d'usage est celui du d�part en cong�s (ou pour une longue absence), le mode 'horsgel' permet de mettre toute la maison dans ce mode.
- Le troisi�me cas d'usage est celui d'un d�part moins long de la maison (journ�e ?) et permet de mettre celle-ci d'un coup en mode 'eco'.

Widget de l'objet "Central Fil-Pilote" :

![Centrale](docs/images/centrale_show_1.png)

Il s'agit d'un widgeet standard, qui devrait �voluer dans le temps vers un widget custom apportant plus d'information sur l'�tat global de l'installation.

Notez que la sortie d'un mode "D�lestage" (mode Off), vers un mode "Normal" peut-�tre assez violent pour votre installation �lectrique et potentiellement faire sauter celle-ci.
En effet si tous les radiateurs se rallument en m�me temps ils peuvent solliciter votre compteur au del� des KVA qu'il peut supporter.
Une am�lioration, peut-�tre avec un mode progressif est � l'�tude.


---
## Aspects Techniques

### Change Logs

Release v1.0 :
- Premi�re version class�e "stable". 

Release v0.8 (beta) :
- Nouveaut�s :
  - Suppression de la configuration globale du plugIn "Affichage des modes", qui n'�tait pas r�ellement impl�ment�.
  - Lors du refresh d'un radiateur, celui-ci va v�rifier l'�tat des commutateurs associ�s, si l'�tat n'est pas le m�me il va �tre forc�.
  - Un refresh des radiateurs est fait toutes les 5 minutes par une tache cron.
  - Mise en place d'un m�canisme de d�tection d'un arr�t non propre du plugin (genre coupure de courant), et capacit� � d�tecter cet arr�t au red�marrage. C'est important pour les coupures de courant, qui peuvent avoir remis � z�ro les �tats de commutateurs associ�s aux radiateurs.
  
- Bug corrections :
  - Code optimisation : changement du mode de stockage des programmes dans les configurations jeedom de la centrale.

Release v0.7 (beta) :
- Bug corrections :
  - Lorsqu'une temp�rature est associ�e au radiateur, la valeur est maintenant mise � jour dynamiquement sur le widet du radiateur.

Release v0.6 (beta) :
- Nouveaut�s :
  - Extension � 24h des cr�neaux pour la cr�ation des d�clencheurs.

- Bug corrections :
  - Sur app mobile, le mode d�clenchement ne prenait pas les bons horaires pour les cr�neaux en 15,30 ou 45 minutes.
  - Sur app mobile, lors de la cr�ation d'un d�clenchement, tous les modes �taient affich�s et non pas seulement les modes support�s par le radiateur.
  - Sur app mobile, lors de la cr�ation d'un d�clenchement, affichage par d�faut de "Choisir ..." au lieu d'une zone vide.

Release v0.5 (beta) :
- Bug corrections :
  - Aucun affichage de widget custom sur mobile

Release v0.4 (beta) :
- Nouveaut�s :
  - Ajout d'un widget "custom" pour les radiateurs et les zones.
  - Possibilit� de choisir le widget custom ou le widget syst�me par configuration globale
  - Ajout d'une notion de d�clenchement (trigger) par radiateur/zone.
  - Ajout de la gestion des modes "confort -1" et "confort -2" qui n'�taient pas correctement pris en compte.
  
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



### Questions Fr�quentes (FAQ) et Probl�mes connus

- Lorsqu'un contacteur est chang� directement, sans passer par l'�quipement "radiateur fil-pilote", l'�tat de ce dernier n'est pas automatiquement mis � jour.
- Les commandes avec des options (select) ne sont pas encore bien g�r�es pour les retours d'�tat des contacteurs.
- Il ne faut surtout pas d�truire ou d�sactiver l'objet "Centrale". Le PlugIn essaie de l'emp�cher, mais tout n'est pas encore contr�l�.
- La fonction de d�clenchement n'est pas possible avec le widget standard.
- Quand le mode bypass est d�clench�, il ne s�applique que sur les radiateurs/zones actives. Si un radiateur/zone devient actif apr�s le d�clenchement du bypass, le bypass sera ignor�.
- Lorsqu'une page ou un widget est redimensionn� par l'utilisateur, les widget en mode custom (mode par d�faut) ne se rafraichissent pas bien lors de la sortie du mode redimensionnement. Recharger simplement la page pour r�soudre le probl�me.
- Dans le widget custom (mode par defaut) le bouton "fen�tre ouverte" ne fait rien pour l'instant.
- Lorsqu'un radiateur fait parti d'une zone et que l'on modifie la configuration du mode alternatif correspondant � l'�tat actuel de la zone, le mode alternatif n'est pas tout de suite pris en compte. Il faut soit le frocer manuellement (en refor�ant le mode de la zone), soit attendre le tick d'horloge en mode 'auto'.


### Aspirations & Id�es & Evolutions

Une petite liste d'id�es ou de fonctions que l'on pourrait rajouter dans Centrale Fil-Pilote :

- D�tection automatique de fen�tre ouverte
- Am�liorer la pr�cision horaire : ajouter une programmation au 1/4 d'heure
- Ajouter le cas d'un radiateur ne supportant pas le fil-pilote (mode on/off = confort/off).
- Ajouter un nom court aux programmations
- Utiliser les informations (optionnelles) de temp�rature de la pi�ce et de la puissance du radiateur pour proposer des analyses ou des audits.


