# Nutri.vin

Plateforme open source et communautaire de QRCode pour la déclaration nutritionnelle de vos vins.

<a href="https://hosted.weblate.org/engage/nutrivin/">
<img src="https://hosted.weblate.org/widget/nutrivin/interface/svg-badge.svg" alt="État de la traduction" />
</a>

## Installation

Installation des dépendances :

```
sudo apt-get install php-zip php-mbstring librsvg2-bin php-gd
```

Récupérer le code source :

```
git clone https://github.com/24eme/nutri.vin.git
```

Pour le lancer :

```
php -S localhost:8000 -t public
```

## Dépendances


### Requis

L'extension PHP `mbstring` est requise pour faire fonctionner la lib de qrcode.
L'extension PHP `GD` est requise pour exporter le QRCode en PNG.
Pour exporter de multiples fichiers l'extension `zip-archive` est requise.

### Optionnelles

Pour avoir un logo dans les qrcodes au format `eps` et `pdf`, il faut avoir [`rsvg-convert`](https://gitlab.gnome.org/GNOME/librsvg)

## Traduction

La traduction des ingrédients et des éléments nutritionnels peut se faire via l'interface de [Weblate](https://hosted.weblate.org/engage/nutrivin/), ou en éditant directement les fichiers `.po` du dossier `locale`

[![État de la traduction](https://hosted.weblate.org/widget/nutrivin/interface/multi-auto.svg)](https://hosted.weblate.org/engage/nutrivin/)

## Financement

Ce projet a été financé en majeur partie par [l'interprofession des Vins du Sud-Ouest](https://www.vignobles-sudouest.fr/) et aussi par le 24ème.

Il est développé et maintenu par le 24ème qui accepte les dons afin pour pouvoir continuer à le faire évoluer et assurer sa maintenance dans les meilleurs conditions : https://liberapay.com/NutriVin/

## License

Logiciel libre sous licence AGPL-3.0
