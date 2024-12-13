# Nutri.vin

Plateforme open source et communautaire de QRCode pour la déclaration nutritionnelle de vos vins.

## Installation

Installation des dépendances :

```
sudo apt-get install php-zip php-mbstring librsvg2-bin
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

L'extension PHP `mbstring` est requis pour faire fonctionner la lib de qrcode.
Pour exporter de multiples fichiers l'extension `zip-archive` est requise.

### Optionnelles

Pour avoir un logo dans les qrcodes au format `eps` et `pdf`, il faut avoir [`rsvg-convert`](https://gitlab.gnome.org/GNOME/librsvg)

## License

Logiciel libre sous licence AGPL-3.0
