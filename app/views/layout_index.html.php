<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Nutri.Vin - Plateforme open source de QR Code nutritionnel pour le vin</title>
        <meta name="description" content="Plateforme open source et communautaire de QR Code pour la déclaration nutritionnelle de vins" />
        <link rel="icon" type="image/png" sizes="16x16" rel="noopener" target="_blank" href="/images/favicons/favicon-16x16.png">
        <link rel="icon" type="image/png" sizes="32x32" rel="noopener" target="_blank" href="/images/favicons/favicon-32x32.png">
        <link rel="apple-touch-icon" sizes="180x180" rel="noopener" target="_blank" href="/images/favicons/apple-touch-icon.png">
        <link rel="manifest" href="/images/favicons/site.webmanifest">
        <link href="/css/bootstrap.min.css" rel="stylesheet" />
        <link href="/css/bootstrap-icons.min.css" rel="stylesheet" />
        <!-- Custom styles for this template -->
        <link href="/css/common.css" rel="stylesheet" />
        <?php include($THEME.'css.php'); ?>
    </head>
    <body>
        <div class="bg-white shadow">
        <div class="container" style="max-width: 1050px;">
            <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3">
                <div class="col-md-2 mb-2 mb-md-0">
                    <a href="/" class="d-inline-flex link-body-emphasis text-decoration-none">
                        <img src="/images/logo.svg" height="80" />
                    </a>
                </div>

                <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="#" class="nav-link px-2 link-secondary">Accueil</a></li>
                    <li><a href="#" class="nav-link px-2">Les avantages</a></li>
                    <li><a href="#" class="nav-link px-2">Les fonctionnalités</a></li>
                    <li><a href="#" class="nav-link px-2">Conformité UE</a></li>
                    <li><a href="#" class="nav-link px-2">Les instances</a></li>
                    <li><a href="#" class="nav-link px-2">À propos</a></li>
                </ul>

                <div class="col-md-2 text-end">
                    <div class="dropdown">
                      <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Créer un QR Code sur Nutri.Vin
                      </button>
                      <ul class="dropdown-menu">
                        <li><a href="/qrcode" class="dropdown-item" href="#">Créer un QR Code sur Nutri.Vin</a></li>
                        <li><a href="" class="dropdown-item" href="#">Créer un QR Code sur Qr-So.fr</a></li>
                      </ul>
                    </div>
                </div>
            </header>
        </div>
        </div>
        <div class="bg-light">
        <div class="container" style="max-width: 1050px;">
            <div class="row">
                <div class="col"></div>
                <div class="col-8 justify-content-center text-center mt-5">
                    <h1>QR Code et Vins</h1>
                    <p class="lead mb-4">Une plateforme open source et communautaire de génération de QR Code pour la déclaration nutritionnelle de bouteilles vins.</p>
                </div>
                <div class="col"></div>
            </div>
            <div class="text-center mt-2">
                <img src="/images/capture_browser.png" class="img-fluid">
            </div>
        </div>
        </div>
        <div class="bg-white">
            <div class="container pt-4 pb-5" style="max-width: 1050px;">
                <h2>Les avantages</h2>

                <div class="row mt-4">
                    <div class="col-6">
                        <div class="card shadow-sm">
                            <div class="card-header">
                                <i class="bi bi-window-split"></i> Ergonomie
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Aide à la saisie</h5>
                                <p class="card-text">La détéction des allergènes et addifits lors de la saisie des ingrédients, calcul simplifié des valeurs nutrionnelles. Tous au long de la saisie le résultat est visible en temps réél.<br /><br /><br /></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card shadow-sm">
                            <div class="card-header">
                                <i class="bi bi-patch-check"></i> Conforme à la législation
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Respect du réglement européens</h5>
                                <p class="card-text">Le logiciel est adapté et conçu pour respecter la mise en oeuvre prévu par la commission européenes dans le règlement (UE) <a href="https://eur-lex.europa.eu/legal-content/FR/TXT/PDF/?uri=OJ:C_202301190">n° 1308/2013</a>.<br /><br /><br /></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 mt-4">
                        <div class="card shadow-sm">
                            <div class="card-header">
                                <i class="bi bi-opencollective"></i> Open source et communautaire
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">L'open source au service de la durabilité</h5>
                                <p class="card-text">Le projet peut être utilisé sur nutri.vin directement, sur une instance mis à disposition par une interprofession ou librement installé sur son propre serveur avec votre nom de domaine, pour avoir la maitrîse du QR Code dans le temps.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 mt-4">
                        <div class="card shadow-sm">
                            <div class="card-header">
                                <i class="bi bi-clock-history"></i> Pérénité
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Pérénité</h5>
                                <p class="card-text">Le projet peut être utilisé sur nutri.vin directement, sur une instance mis à disposition par une interprofession ou librement installé sur son propre serveur avec votre nom de domaine, pour avoir la maitrîse du QR Code dans le temps.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-light">
            <div class="container pt-4 pb-5" style="max-width: 1050px;">
                <h2>Les fonctionnalités</h2>
                <p>Découvrir le fonctionnement de la plateforme graçe à cette vidéo :</p>
                <div class="row">
                    <div class="col">
                        <video controls class="img-fluid border rounded">
                            <source src="/nutrivin.mp4" type="video/mp4" />
                            <source src="/nutrivin.webm" type="video/webm" />
                            <p>
                                Votre navigateur ne prend pas en charge les vidéos HTML5. Voici
                                <a href="/nutrivin.mp4">un lien pour télécharger la vidéo</a>.
                            </p>
                        </video>
                    </div>
                    <div class="col">
                        <table class="table table-striped table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Fonctionnalités</th>
                                    <th class="text-center col-1">Nutri.Vin</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Création de QRCodes illimités</td>
                                    <td class="text-success text-center"><i class="bi bi-check-circle-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Images de la bouteille</td>
                                    <td class="text-success text-center"><i class="bi bi-check-circle-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Détéction des allergènes et additifs</td>
                                    <td class="text-success text-center"><i class="bi bi-check-circle-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Calcul simplifiés des valeurs nutritionnelles</td>
                                    <td class="text-success text-center"><i class="bi bi-check-circle-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Rendu en temps réél</td>
                                    <td class="text-success text-center"><i class="bi bi-check-circle-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Duplication d'un QR Code</td>
                                    <td class="text-success text-center"><i class="bi bi-check-circle-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Personnalisation du QR Code (logo et texte)</td>
                                    <td class="text-success text-center"><i class="bi bi-check-circle-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Téléchargement du QR Code pour l'impression (PDF, EPS)</td>
                                    <td class="text-success text-center"><i class="bi bi-check-circle-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Téléchargement multiples des QR Codes</td>
                                    <td class="text-success text-center"><i class="bi bi-check-circle-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Respect du réglement européen n° 1308/2013</td>
                                    <td class="text-success text-center"><i class="bi bi-check-circle-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Versionnage des modifications dans le temps</td>
                                    <td class="text-success text-center"><i class="bi bi-check-circle-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Compteur anonyme des vues</td>
                                    <td class="text-success text-center"><i class="bi bi-check-circle-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Installer le logiciel soi-même</td>
                                    <td class="text-success text-center"><i class="bi bi-check-circle-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Le connecter avec son nom de domaine</td>
                                    <td class="text-success text-center"><i class="bi bi-check-circle-fill"></i></td>
                                </tr>

                                <tr>
                                    <td>Installer le logiciel soi-même</td>
                                    <td class="text-success text-center"><i class="bi bi-check-circle-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Interopérabilité des données entre les instances</td>
                                    <td class="text-success text-center"><i class="bi bi-check-circle-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Connexion avec viticonnect</td>
                                    <td class="text-success text-center"><i class="bi bi-check-circle-fill"></i></td>
                                </tr>
                                <tr>
                                    <td>Récupération des informations de votre exploitation</td>
                                    <td class="text-success text-center"><i class="bi bi-check-circle-fill"></i></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white">
            <div class="container pt-4 pb-5" style="max-width: 1050px;">
                <h2 class="mb-4">Conforme aux legislations</h2>
                <div class="card w-50">
                    <div class="card-header">
                        Conformité au règlement (UE) n° 1308/2013
                    </div>
                    <ul class="list-group list-group-flush">
                    <li class="list-group-item"><i class="bi bi-patch-check text-success"></i> Pas d'informations commerciales</li>
                    <li class="list-group-item"><i class="bi bi-patch-check text-success"></i> Les utilisateurs ne sont pas tracés</li>
                </div>
            </div>
        </div>
        <div class="bg-light">
            <div class="container pt-4 pb-5" style="max-width: 1050px;">
                <h2>Les instances existantes</h2>

                <p>Le QRCode généré va utiliser un nom de domaine, ce choix de nom de domaine est un choix important, pour que le QR Code dure dans le temps.<br /><br />

                Avec cette plateforme vous pouvez choisir le gestionnaire de nom de domaine qui vous paraît le plus fiable et le plus cohérent par rapport à votre vin, ou bien même d'utiliser votre propre nom de domaine.<br /><br />

                Voici les organisations qui ont actuellement installé cette plateforme et que vous pouvez utiliser : </p>

                <table class="table table-striped table-bordered mt-4">
                    <thead>
                        <tr>
                            <th>Instance</th>
                            <th>Gestionnaire</th>
                            <th>Description</th>
                            <th>Coût</th>
                            <th class="text-center">Nom de domaine</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Nutri.Vin</td>
                            <td><a href="https://24eme.fr">Société 24ème</a></td>
                            <td>Accessible à tous les organismes compatible avec viticonnect.</td>
                            <td>Gratuit</td>
                            <td><a href="https://nutri.vin/">nutri.vin</a></td>
                        </tr>
                        <tr>
                            <td>Qr-So.fr</td>
                            <td><a href="https://www.vignobles-sudouest.fr">L'interprofession des Vins du Sud Ouest</a></td>
                            <td>L'accès est limité aux adhérents à l'interprofession.</td>
                            <td>Compris dans la cotisation pour les AOC géré par l'interprofession et payant pour les autres AOC.</td>
                            <td><a href="https://qr-so.fr/">qr-so.fr</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div>
            <div class="container pt-4 pb-5" style="max-width: 1050px;">
                <h2>À propos</h2>
                <div class="row">
                    <div class="col-2"><img height="160" src="https://www.24eme.fr/img/24eme.svg" />  </div>
                    <div class="col">
                        <p class="mt-4">Ce logiciel a été créé par le 24ème, une société coopérative spécialisée depuis 2010 dans le développement de logiciels libres pour des communautés de métiers et principalement dans le domaine viticole.</p>
                        <a href="" class="btn btn-link float-end">En savoir plus sur le 24ème</a>
                    </div>

                </div>
            </div>
        </div>
        <div class="container" style="max-width: 1050px;">
            <footer class="text-center text-muted pt-2">
                Logiciel libre sous licence AGPL-3.0 : <a href="https://github.com/24eme/nutri.vin">voir le code source</a>
            </footer>
        </div>
        <script src="/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
