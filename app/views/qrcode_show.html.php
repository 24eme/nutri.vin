<?php use app\config\Config; ?>
<?php if (!empty($publicview) && $qrcode->date_version != $lastVersion): ?>
    <div class="p-1 mt-2 bg-warning-subtle text-warning-emphasis small">
        Vous consultez la version du QRCode en date du <?php echo date('d/m/Y H:i', strtotime($qrcode->date_version)) ?>.<br />
        Pour consulter la dernière version à jour, veuillez suivre ce lien : <a href="<?php echo $urlbase."/".$qrcode->getId() ?>"><?php echo $urlbase."/".$qrcode->getId() ?></a>
    </div>
<?php endif; ?>

<div class="p-3 py-4 bg-white text-center liveform_anchor">

    <div id="carrousel" class="bg-white border rounded rounded-bottom-0 shadow-sm d-flex justify-content-center">
        <img id="slide_image_bouteille" class="mt-3 bg-white border-bottom" style="height: 200px;"
            data-liveform-name="image_bouteille" data-liveform-template="{{%s}}"
            src="<?php echo $qrcode->image_bouteille ?>" >
        <img id="slide_image_etiquette" class="mt-3 bg-white border-bottom" style="display: none; height: 200px;"
            data-liveform-name="image_etiquette" data-liveform-template="{{%s}}"
            src="<?php echo $qrcode->image_etiquette ?>" >
        <img id="slide_image_contreetiquette" class="mt-3 bg-white border-bottom" style="display: none; height: 200px;"
            data-liveform-name="image_contreetiquette" data-liveform-template="{{%s}}"
            src="<?php echo $qrcode->image_contreetiquette ?>" >
        <button class="position-absolute top-50 start-0 translate-middle-y text-secondary btn btn-lg px-1 fs-2" id="precedent" href="" onClick="changeSlide(-1); return false;"><i class="bi bi-chevron-compact-left"></i></button>
        <a class="position-absolute top-50 end-0 translate-middle-y text-secondary btn btn-lg px-1 fs-2" id="suivant" onClick="changeSlide(1)"><i class="bi bi-chevron-compact-right"></i></a>
    </div>

    <div class="bg-light-subtle border border-top-0 rounded rounded-top-0 pt-3 shadow-sm">
        <?php if (empty($publicview) || (!empty($publicview) && $qrcode->domaine_nom)): ?>
        <p data-liveform-name="domaine_nom" data-liveform-template='{{%s}}' class="fs-4">
          <?php echo $qrcode->domaine_nom ?>
        </p>
        <?php endif; ?>
        <?php if (empty($publicview) || (!empty($publicview) && ($qrcode->cuvee_nom || $qrcode->denomination || $qrcode->couleur))): ?>
        <p class="fs-3">
            <?php if (empty($publicview) || $qrcode->cuvee_nom): ?>
            <span data-liveform-name="cuvee_nom" data-liveform-template='{{%s}}'>
                <?php echo $qrcode->cuvee_nom ?>
            </span>
            <br/>
            <?php endif; ?>
            <?php if (empty($publicview) || $qrcode->denomination): ?>
            <small class="fw-light text-muted" data-liveform-name="denomination" data-liveform-template='{{%s}}'>
                <?php echo $qrcode->denomination ?>
            </small>
            <?php endif; ?>
            <?php if (empty($publicview) || $qrcode->denomination): ?>
            <small class="fw-light text-muted" data-liveform-name="couleur" data-liveform-template='{{%s}}'>
                <?php echo $qrcode->couleur ?>
            </small>
            <?php endif; ?>
        </p>
        <?php endif; ?>
        <?php if (empty($publicview) || (!empty($publicview) && $qrcode->millesime)): ?>
        <p class="fs-4" data-liveform-name="millesime" data-liveform-template='{{%s}}'>
            <?php echo $qrcode->millesime ?>
        </p>
        <?php endif; ?>
    </div>
</div>

<div class="px-3 pt-3 bg-light-subtle border-top ">
    <?php if(empty($publicview) || (!empty($publicview) && ($qrcode->alcool_degre || $qrcode->centilisation || $qrcode->lot))): ?>
    <div class="card text-bg-light mt-2 mb-4 shadow-sm liveform_anchor">
        <div class="card-header text-center"><i class="bi bi-info-circle float-start"></i> <?php echo _("Informations complémentaires"); ?></div>
        <table class="table table-sm table-striped-columns mb-0">
            <tbody>
                <?php if (empty($publicview) || $qrcode->alcool_degre): ?>
                <tr>
                    <td class="text-start"><?php echo _("Volume d'alcool"); ?></td>
                    <td class="text-end"
                        data-liveform-name="alcool_degre" data-liveform-template='{{%s}} % vol'
                    ><?php echo $qrcode->alcool_degre ?>% vol</td>
                </tr>
                <?php endif; ?>
                <?php if (empty($publicview) || $qrcode->centilisation): ?>
                <tr>
                    <td class="text-start"><?php echo _("Contenance"); ?></td>
                    <td class="text-end"
                        data-liveform-name="centilisation" data-liveform-template='{{%s}} cl'>
                            <?php echo $qrcode->centilisation ?> cl</td>
                </tr>
                <?php endif; ?>
                <?php if (empty($publicview) || $qrcode->lot): ?>
                <tr>
                    <td class="text-start"><?php echo _("N° de lot"); ?></td>
                    <td class="text-end"
                        data-liveform-name="lot" data-liveform-template='{{%s}}'>
                            <?php echo $qrcode->lot ?></td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
    <?php if (empty($publicview) || (!empty($publicview) && $qrcode->ingredients)): ?>
    <div class="card text-bg-secondary mt-4 mb-3 shadow-sm liveform_anchor">
        <div class="card-header text-center"><i class="bi bi-card-list float-start"></i> <?php echo _("Ingrédients"); ?></div>
        <div class="card-body text-dark bg-white">
            <p data-liveform-name="ingredients" data-liveform-template='{{%s}}'
                class="card-text">
                <?php if ($qrcode->ingredients): ?>
                    <?php if ($current_language == 'fr_FR.utf8'): ?>
                        <?php echo $qrcode->ingredients ?>
                    <?php else: ?>
                        <?php echo $qrcode->getIngredientsTraduits($qrcode->ingredients); ?>
                    <?php endif; ?>
                <?php endif; ?>
            </p>
            <small>
                <?php echo sprintf(_("Ingrédients allergènes indiqués en %sgras%s."), "<strong>", "</strong>"); ?><br/>
                <?php echo sprintf(_("Ingrédients issus de l'agriculture biologique indiqué avec une %s*%s."), "<em>", "</em>"); ?>
            </small>
        </div>
    </div>
    <?php endif; ?>
    <?php if (
            empty($publicview) ||
            (!empty($publicview) && ($qrcode->nutritionnel_energie_kj || $qrcode->nutritionnel_energie_kj === 0)) ||
            (!empty($publicview) && ($qrcode->nutritionnel_energie_kcal || $qrcode->nutritionnel_energie_kcal === 0)) ||
            (!empty($publicview) && ($qrcode->nutritionnel_graisses || $qrcode->nutritionnel_graisses === 0)) ||
            (!empty($publicview) && ($qrcode->nutritionnel_glucides || $qrcode->nutritionnel_glucides === 0)) ||
            (!empty($publicview) && ($qrcode->nutritionnel_fibres || $qrcode->nutritionnel_fibres === 0)) ||
            (!empty($publicview) && ($qrcode->nutritionnel_proteines || $qrcode->nutritionnel_proteines === 0)) ||
            (!empty($publicview) && ($qrcode->nutritionnel_sel || $qrcode->nutritionnel_sel === 0)) ||
            (!empty($publicview) && ($qrcode->nutritionnel_sodium || $qrcode->nutritionnel_sodium === 0))
          ):
    ?>
    <div class="card text-bg-primary mt-4 mb-2 shadow-sm liveform_anchor">
        <div class="card-header text-center"><i class="bi bi-clipboard-data float-start"></i> Informations nutritionnelles</div>
        <table class="table table-sm table-striped-columns mb-0">
            <thead>
                <tr>
                    <th class="text-start col-6"><?php echo _("Type"); ?></th>
                    <th class="text-end col-6"><?php echo _("Pour 100 mL"); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-start"><?php echo _("Énergie"); ?></td>
                    <td class="text-end">
                        <span data-liveform-name="nutritionnel_energie_kj" data-liveform-template='{{%s}} kJ'>
                            <?php echo $qrcode->nutritionnel_energie_kj ?: 0 ?> <?php echo _("kJ"); ?>
                        </span>
                        <br>
                        <span data-liveform-name="nutritionnel_energie_kcal" data-liveform-template='{{%s}} kCal'>
                            <?php echo $qrcode->nutritionnel_energie_kcal ?: 0 ?> <?php echo _("kCal"); ?>
                        </span><br>
                    </td>
                </tr>
                <tr>
                    <td class="text-start"><?php echo _("Matières grasses"); ?><br><small class="ps-3"><?php echo _("dont acides gras saturés"); ?></small></td>
                    <td class="text-end">
                        <span data-liveform-name="nutritionnel_graisses" data-liveform-template='{{%s}} g'>
                            <?php echo $qrcode->nutritionnel_graisses ?: 0.00 ?> <?php echo _("g"); ?>
                        </span>
                        <br>
                        <small>
                        <span data-liveform-name="nutritionnel_acides_gras" data-liveform-template='{{%s}} g'>
                            <?php echo $qrcode->nutritionnel_acides_gras ?: 0.00 ?> <?php echo _("g"); ?>
                        </span>
                        </small>
                    </td>
                </tr>
                <tr>
                    <td class="text-start"><?php echo _("Glucides"); ?><br><small class="ps-3"><?php echo _("dont sucres"); ?></small></td>
                    <td class="text-end">
                        <span data-liveform-name="nutritionnel_glucides" data-liveform-template='{{%s}} g'>
                            <?php echo $qrcode->nutritionnel_glucides ?: 0.00 ?><?php echo _("g"); ?>
                        </span>
                        <br>
                        <small>
                        <span data-liveform-name="nutritionnel_sucres" data-liveform-template='{{%s}} g'>
                            <?php echo $qrcode->nutritionnel_sucres ?: 0.00 ?> <?php echo _("g"); ?>
                        </span>
                        </small>
                    </td>
                </tr>
                <tr>
                    <td class="text-start"><?php echo _("Fibres alimentaires"); ?></td>
                    <td class="text-end">
                        <span data-liveform-name="nutritionnel_fibres" data-liveform-template='{{%s}} g'>
                            <?php echo $qrcode->nutritionnel_fibres ?: 0.00 ?> <?php echo _("g"); ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="text-start"><?php echo _("Protéines"); ?></td>
                    <td class="text-end">
                        <span data-liveform-name="nutritionnel_proteines" data-liveform-template='{{%s}} g'>
                            <?php echo $qrcode->nutritionnel_proteines ?: 0.00 ?> <?php echo _("g"); ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="text-start"><?php echo _("Sel"); ?></td>
                    <td class="text-end">
                        <span data-liveform-name="nutritionnel_sel" data-liveform-template='{{%s}} g'>
                            <?php echo $qrcode->nutritionnel_sel ?: 0.00 ?> <?php echo _("g"); ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="text-start"><?php echo _("Sodium"); ?></td>
                    <td class="text-end">
                        <span data-liveform-name="nutritionnel_sodium" data-liveform-template='{{%s}} g'>
                            <?php echo $qrcode->nutritionnel_sodium ?: 0.00 ?> <?php echo _("g"); ?>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <div class="py-4 bg-white text-center liveform_anchor">
      <?php foreach ($qrcode::$LABELS as $label): ?>
        <img class="bg-white px-1" data-liveform-name="labels[]" data-liveform-template='{{<?php echo $label ?>}}' style="height: 30px;<?php if(!in_array($label, $qrcode->getLabels())): ?> display:none;<?php endif; ?>" title="Vin labellisé <?php echo $label ?>" src="/images/labels/<?php echo strtolower($label) ?>.png" >
      <?php endforeach; ?>
    </div>

    <?php if (!empty($qrcode->autres_infos)): ?>
    <div class="card text-bg-Light my-4 shadow-sm liveform_anchor">
        <div class="card-header text-center"><i class="bi bi-clipboard-data float-start"></i><?php echo _("Autres informations"); ?></div>
        <p class="pt-2 px-2"
           data-liveform-name="autres_infos" data-liveform-template='{{%s}}'
        >
            <?php echo $qrcode->autres_infos ?>
        </p>
    </div>
    <?php endif; ?>

    <div class="mt-2 pt-2 small text-secondary text-center border-top liveform_anchor">
            <strong><?php echo _("Mentions légales") ?> :</strong>

            <?php echo _("Cette fiche nutritionnelle est éditée sous la seule responsablité de") ?> :
                <span data-liveform-name="responsable_nom" data-liveform-template='{{%s}}' ><?php echo $qrcode->responsable_nom; ?></span>
                (<a target="_blank" href="https://annuaire-entreprises.data.gouv.fr/entreprise/<?php echo $qrcode->getResponsableSIREN(); ?>" class="link-secondary"><span data-liveform-name="responsable_siret" data-liveform-template='{{%s}}' ><?php echo $qrcode->getResponsableSIREN(); ?></span></a>),
                <span data-liveform-name="responsable_adresse" data-liveform-template='{{%s}}' ><?php echo $qrcode->responsable_adresse; ?></span>.

            <?php echo _("Elle est hébergée par") ?> :
                <?php echo Config::getInstance()->get('herbergeur_raison_sociale'); ?>
                (<a target="_blank" href="https://annuaire-entreprises.data.gouv.fr/entreprise/<?php echo Config::getInstance()->get('herbergeur_siren'); ?>" class="link-secondary"><?php echo Config::getInstance()->get('herbergeur_siren'); ?></a>),
                <?php echo Config::getInstance()->get('herbergeur_adresse'); ?>, <?php echo Config::getInstance()->get('herbergeur_contact'); ?>.

            <?php echo Config::getInstance()->get('herbergeur_raison_sociale'); ?>,
            <?php echo _("sa plateforme") ?> <?php echo preg_replace('/https?:../', '', $urlbase); ?>
            <?php echo _("et le projet libre") ?> <a target="_blank" href="https://github.com/24eme/nutri.vin/" class="link-secondary">NutriVin</a>
            <?php echo _("ne peuvent être tenus responsables des informations publiées. Un historique de chaque modification est conservée et consultable publiquement") ?>
        </p>
    </div>
    <?php if (!empty($publicview)): ?>
    <div class="small text-secondary text-center">
      <?php echo _("Créé le "); ?><?php echo date('d/m/Y H:i', strtotime($qrcode->date_creation)); ?>
      <?php if (!empty($publicview) && count($allVersions) > 1): ?>
      – <span class="dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false"><?php echo _("Modifié le "); ?><?php echo date('d/m/Y H:i', strtotime($lastVersion)); ?></span>
        <ul class="dropdown-menu">
          <?php foreach ($allVersions as $version): ?>
              <li><a class="dropdown-item<?php if($version == $qrcode->date_version): ?> active disabled<?php endif; ?>" href="<?php echo $urlbase."/".$qrcode->getId() ?>?version=<?php echo urlencode($version); ?>"><?php echo _("Voir la version du "); ?><?php echo date('d/m/Y H:i', strtotime($version)); ?></a></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
      <?php $nbVue = count($qrcode->getVisites()); ?>
      <span class="ps-3" title="<?php echo _("Seules l'heure de la première visite de la session et une évaluation géographique de la connexion sont conservées pour réaliser cette statistique. Conformément à la législation, aucune données à caractère personnel n'est conservé et aucun suivi des visiteurs de cette fiche n'est réalisé. La consultation de la page ne nécessite pas de cookie."); ?>">
          <i class="bi bi-eye"  style="cursor: pointer;"></i> <?php echo $nbVue; ?> <?php echo _("vue"); ?><?php if ($nbVue > 1): ?>s<?php endif; ?>
      </span>
    </div>
    <?php endif ?>

</div>

<script>
    let ingredientsListe = document.querySelector("[data-liveform-name=ingredients]")

    ingredientsListe.innerHTML = ingredientsListe.innerHTML.replace(/_(.*?)_/g, "<strong>$1</strong>");
    ingredientsListe.innerHTML = ingredientsListe.innerHTML.replace(/ ?([^,]* : [^;]* ; )/g, " <em>$1</em> ");
</script>

<script>
    const slide = ["image_bouteille", "image_etiquette", "image_contreetiquette"];

    let numero = 0;

    function changeSlide(sens) {
        orig = numero
        numero += sens;
        if (numero < 0) {
            numero = slide.length - 1;
        }
        if (numero > slide.length - 1) {
            numero = 0;
        }
        document.getElementById("slide_"+slide[orig]).style.display = 'none'
        document.getElementById("slide_"+slide[numero]).style.display = 'block'
    }

</script>
