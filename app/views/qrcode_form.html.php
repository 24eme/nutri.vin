<nav id="breadcrumb" class="small" aria-label="breadcrumb">
  <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo '/qrcode/'.$qrcode->user_id.'/list'; ?>">Liste de vos QR Codes</a></li>
      <li class="breadcrumb-item active" aria-current="page"><?php if ($qrcode->getId()): ?>Modification du QR Code<?php else: ?>Création d'un QR Code<?php endif; ?></li>
  </ol>
</nav>

<h2><?php if ($qrcode->getId()): ?>Modification du QR Code<?php else: ?>Création d'un QR Code<?php endif; ?></h2>

<div class="row">
  <div class="col-7">
      <form method="POST" id="form-edition" action="/qrcode/<?php echo $qrcode->user_id ?>/write" enctype="multipart/form-data" class="live-form">
      <?php if ($qrcode->getId()): ?>
          <input type="hidden" name="id" value="<?php echo $qrcode->getId(); ?>" />
      <?php endif; ?>

      <?php if (count($qrcode->getVisites())): ?>
      <p class="alert alert-warning">Ce QRCode a déjà été consulté par au moins une personne extérieure. Par soucis de transparence, la modification que vous pourriez réaliser sera consultable publiquement</p>
      <?php endif; ?>

      <h4 class="mt-4 mb-4"><i class="bi bi-person-fill"></i> Identité du commercialisant</h4>

      <div class="form-floating mb-3">
          <input type="text" class="form-control" id="domaine_nom" name="domaine_nom" placeholder="Mon domaine" value="<?php echo $qrcode->domaine_nom; ?>"/>
          <label for="domaine_nom">Nom du Domaine</label>
      </div>

      <h4 class="mt-4 mb-4"><i class="bi bi-droplet-fill"></i> Information relative au vin</h4>


      <div class="form-floating mb-3">
           <input type="text" class="form-control" id="cuvee_nom" name="cuvee_nom" placeholder="Ma cuvée" value="<?php echo $qrcode->cuvee_nom; ?>"/>
           <label for="cuvee_nom">Nom de la cuvée</label>
       </div>

       <div class="form-floating mb-3">
           <input list="appellations_liste" type="text" class="form-control" id="appellation" name="appellation" value="<?php echo $qrcode->appellation; ?>" placeholder="Appellation"/>
            <datalist id="appellations_liste">
            <?php
              if (isset($config['appellations'])):
                foreach ($config['appellations'] as $appellation):
            ?>
              <option value="<?php echo $appellation ?>"></option>
            <?php
                endforeach;
              endif;
            ?>
            </datalist>
            <label form="appellation">Appellation</label>
       </div>

       <div class="d-flex justify-content-between">

       <div class="form-floating col-sm-5">
           <input type="text" class="form-control" id="millesime" name="millesime" value="<?php echo $qrcode->millesime; ?>" placeholder="Millésime"/>
           <label form="millesime">Millésime</label>
       </div>

       <div class="form-floating col-sm-6">
           <input list="couleurs_liste" type="text" class="form-control" id="couleur" name="couleur" value="<?php echo $qrcode->couleur; ?>" placeholder="Rouge, Blanc, Rosé, ...."/>
            <datalist id="couleurs_liste">
            <?php
              if (isset($config['couleurs'])):
                foreach ($config['couleurs'] as $couleur):
            ?>
              <option value="<?php echo $couleur ?>"></option>
            <?php
                endforeach;
              endif;
            ?>
            </datalist>
           <label form="couleur">Couleur</label>
       </div>

       </div>

        <h4 class="mt-4 mb-4"><i class="bi bi-info-circle"></i> Informations complémentaires</h4>
        <div class="d-flex justify-content-between">
            <div class="col-sm-3">
                <div class="input-group mb-3">
                  <div class="form-floating">
                      <input type="text" class="form-control text-end input-float" id="alcool_degre" name="alcool_degre" value="<?php echo $qrcode->alcool_degre; ?>" placeholder="Volume d'alcool">
                      <label form="alcool_degre">Volume d'alcool</label>
                  </div>
                  <span class="input-group-text">%</span>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="input-group mb-3">
                  <div class="form-floating">
                      <input type="text" class="form-control text-end input-float" id="centilisation" name="centilisation" value="<?php echo $qrcode->centilisation; ?>" placeholder="Contenance">
                      <label form="centilisation">Contenance</label>
                  </div>
                  <span class="input-group-text">cl</span>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="input-group mb-3">
                  <div class="form-floating">
                      <input type="text" class="form-control" id="lot" name="lot" value="<?php echo $qrcode->lot; ?>" placeholder="Numéro de lot">
                      <label form="lot">Numéro de lot</label>
                  </div>
                </div>
            </div>
        </div>

        <h4 class="mt-4 mb-4"><i class="bi bi-card-list"></i> Liste des ingrédients</h4>

        <ul class="nav nav-tabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="ingredients_tableau_tab" data-bs-toggle="tab" data-bs-target="#ingredients_tableau" type="button" role="tab" aria-controls="ingredients_tableau" aria-selected="true">Liste</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="ingredients_texte_tab" data-bs-toggle="tab" data-bs-target="#ingredients_texte" type="button" role="tab" aria-controls="ingredients_texte" aria-selected="false">Texte</button>
          </li>
        </ul>

        <div class="tab-content py-4">
          <div class="tab-pane show active container m-0 p-0" id="ingredients_tableau" role="tabpanel" aria-labelledby="ingredients_tableau" tabindex="0" data-liveform-ignore>
            <p id="message_ingredients_vide" class="d-none">Aucun ingredient n'a été saisi</p>
            <table id="table_ingredients" class="table table-sm table-striped">
                  <thead>
                    <tr>
                      <th class="" scope="col"></th>
                      <th class="text-center" scope="col">Additif</th>
                      <th class="text-center" scope="col">Bio</th>
                      <th class="text-center" scope="col">Allergène</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
            </table>
            <template id="ingredient_row">
                <tr draggable="true">
                    <td class="ingredient_libelle" scope="row"><div class="input-group"><span class="input-group-text" style="cursor: grab;"><i class="bi bi-grip-vertical"></i></span><input form="form_add_ingredients" type="text" class="form-control input_ingredient" list="ingredients_list"></div></td>
                    <td class="ingredient_additif text-center align-middle">
                        <input form="form_add_ingredients" class="form-check-input checkbox_additif" type="checkbox" value="" label="case à cocher pour déclarer un additif">
                        <div class="input-group d-none">
                            <div class="input-group-text">
                                <input form="form_add_ingredients" class="form-check-input mt-0 checkbox_additif" type="checkbox" value="" label="case à cocher pour déclarer un additif">
                            </div>
                            <input form="form_add_ingredients" type="text" class="form-control input_additif" list="categories_additif_list" placeholder="Catégorie">
                        </div>
                    </td>
                    <td class="ingredient_ab text-center align-middle">
                        <input form="form_add_ingredients" class="form-check-input" type="checkbox" value="" aria-label="case à cocher pour déclarer un ingrédient bio">
                    </td>
                    <td class="ingredient_allergene text-center align-middle">
                        <input form="form_add_ingredients" class="form-check-input" type="checkbox" value="" aria-label="case à cocher pour déclarer un ingrédient allergène">
                    </td>
                </tr>
            </template>
            <div class="input-group">
                <div class="col-sm-12">
                    <div class="input-group">
                      <div class="form-floating">
                          <input list="ingredients_list" form="form_add_ingredients" id="text_add_ingredient" type="text" class="form-control" placeholder="Ingrédient(s)" aria-label="Ingrédient(s)" aria-describedby="btn_add_ingredient">
                          <label form="lot">Ingrédient(s)</label>
                      </div>
                      <button form="form_add_ingredients" class="btn btn-outline-primary" type="submit" id="btn_add_ingredient"><i class="bi bi-plus-circle"></i> Ajouter</button>
                    </div>
                </div>
                <datalist id="ingredients_list">
                    <?php foreach($qrcode::getFullListeIngredients() as $ingredient => $extra): ?>
                    <option value="<?php echo $ingredient ?>"<?php
                        foreach($extra as $k => $v) {
                            echo ' data-'.$k.'="'.$v.'"';
                        } ?>></option>
                    <?php endforeach; ?>
                </datalist>
                <datalist id="categories_additif_list">
                    <?php foreach($qrcode::getListeCategoriesAdditif() as $categorie): ?>
                    <option value="<?php echo $categorie ?>"></option>
                    <?php endforeach; ?>
                </datalist>
            </div>
            <div class="form-text">
              Il est possible d'ajouter plusieurs ingrédients d'un coup en les séparant par une ","
            </div>
          </div>
          <div class="tab-pane m-0 p-0" id="ingredients_texte" role="tabpanel" aria-labelledby="ingredients_texte" tabindex="0">
              <textarea class="form-control" rows="5" name="ingredients" id="ingredients"><?php echo $qrcode->ingredients ?></textarea>
          </div>
        </div>

        <h4 class="mt-4 mb-4"><i class="bi bi-clipboard-data"></i> Informations nutritionelles</h4>

        <ul id="nutritionnelle_tabs" class="nav nav-tabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link<?php echo $qrcode->nutritionnel_energie_kj ? '' : ' active' ?>" id="nutritionnelle_simplifie_tab" data-bs-toggle="tab" data-bs-target="#nutritionnelle_simplifie" type="button" role="tab" aria-controls="nutritionnelle_simplifie" aria-selected="true">Simplifié</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link<?php echo $qrcode->nutritionnel_energie_kj ? ' active' : '' ?>" id="nutritionnelle_complet_tab" data-bs-toggle="tab" data-bs-target="#nutritionnelle_complet" type="button" role="tab" aria-controls="nutritionnelle_complet" aria-selected="false">Complet</button>
            </li>
        </ul>

        <div class="tab-content mb-3 pt-4">
            <div class="tab-pane show<?php echo $qrcode->nutritionnel_energie_kj ? '' : ' show active' ?> m-0 p-0" id="nutritionnelle_simplifie" role="tabpanel" aria-labelledby="nutritionnelle_simplifie" tabindex="0">
            <table class="table table-sm table-striped">
              <tbody>
                <tr>
                  <td class="align-middle">Type de vin</td>
                    <td>
                      <div class="col-9 offset-3">
                      <div class="input-group">
                          <select name="vin_type" id="vin_type" class="form-select" form="form_convertir_nutritionnelle" required>
                               <option value="tranquille">Vin Tranquille ou Pétillant (de sec à moelleux)</option>
                               <option value="liqueur">Vin de Liqueur</option>
                               <option value="mousseux">Vin Mousseux (de brut à demi-sec)</option>
                          </select>
                      </div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="align-middle">Teneur en sucre</td>
                    <td>
                      <div class="col-6 offset-6">
                      <div class="input-group">
                        <input type="text" class="form-control text-sm-end" id="teneur_sucre" name="teneur_sucre" form="form_convertir_nutritionnelle" required />
                        <span class="input-group-text" id="basic-addon-cal" style="width:50px">g/L</span>
                      </div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="align-middle">Volume d'alcool (TAV)</td>
                    <td>
                      <div class="col-6 offset-6">
                      <div class="input-group">
                        <input type="text" class="form-control text-sm-end" id="nutri_simple_tav" name="nutri_simple_tav" form="form_convertir_nutritionnelle" required />
                        <span class="input-group-text" id="basic-addon-cal" style="width:50px">%</span>
                      </div>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            <div class="text-end">
            <button type="submit" form="form_convertir_nutritionnelle" class="btn btn-outline-primary"><i class="bi bi-calculator"></i> Convertir</button>
            </div>
            </div>

            <div class="tab-pane m-0 p-0<?php echo $qrcode->nutritionnel_energie_kj ? ' show active' : '' ?>" id="nutritionnelle_complet" role="tabpanel" aria-labelledby="nutritionnelle_complet">
                <table class="table table-sm table-striped">
                <tbody>
                  <tr>
                    <td class="align-middle">Énergie (kJ)</td>
                      <td>
                        <div class="col-6 offset-6">
                        <div class="input-group">
                          <input type="text" class="form-control text-sm-end" id="nutritionnel_energie_kj" name="nutritionnel_energie_kj" value="<?php echo $qrcode->nutritionnel_energie_kj; ?>"/>
                          <span class="input-group-text" id="basic-addon-cal" style="width:50px">kJ</span>
                        </div>
                      </div>
                      </td>
                  </tr>
                  <tr>
                    <td class="align-middle">Énergie (kcal)</td>
                      <td>
                        <div class="col-6 offset-6">
                        <div class="input-group">
                          <input type="text" class="form-control text-sm-end" id="nutritionnel_energie_kcal" name="nutritionnel_energie_kcal" value="<?php echo $qrcode->nutritionnel_energie_kcal; ?>"/>
                          <span class="input-group-text" id="basic-addon-cal" style="width:50px">kcal</span>
                        </div>
                      </div>
                      </td>
                  </tr>


                  <tr>
                    <td class="align-middle">Graisses</td>
                      <td class="text-sm-start">
                        <div class="col-6 offset-6">
                          <div class="input-group">
                            <input type="text" class="form-control text-sm-end" id="nutritionnel_graisses" name="nutritionnel_graisses" value="<?php echo $qrcode->nutritionnel_graisses; ?>"/>
                            <span class="input-group-text" id="basic-addon-graisses">g</span>
                          </div>
                        </div>
                    </td>
                  </tr>


                  <tr>
                    <td class="ps-5 align-middle">- dont acides gras saturés</td>
                    <td class="text-sm-start">
                      <div class="col-6 offset-6">
                        <div class="input-group">
                          <input type="text" class="form-control text-sm-end" id="nutritionnel_acides_gras" name="nutritionnel_acides_gras" value="<?php echo $qrcode->nutritionnel_acides_gras; ?>"/>
                          <span class="input-group-text" id="basic-addon-gras">g</span>
                        </div>
                      </div>
                    </td>
                  </tr>


                  <tr>
                    <td class="align-middle">Glucides</td>
                    <td class="text-sm-start">
                      <div class="col-6 offset-6">
                        <div class="input-group">
                          <input type="text" class="form-control text-sm-end" id="nutritionnel_glucides" name="nutritionnel_glucides" value="<?php echo $qrcode->nutritionnel_glucides; ?>"/>
                          <span class="input-group-text" id="basic-addon-glucides">g</span>
                        </div>
                      </div>
                    </td>
                  </tr>


                  <tr>
                    <td class="ps-5 align-middle">- dont sucres</td>
                    <td class="text-sm-start">
                      <div class="col-6 offset-6">
                        <div class="input-group">
                          <input type="text" class="form-control text-sm-end" id="nutritionnel_sucres" name="nutritionnel_sucres" value="<?php echo $qrcode->nutritionnel_sucres; ?>"/>
                          <span class="input-group-text" id="basic-addon-sucres">g</span>
                        </div>
                      </div>
                    </td>
                  </tr>

                  <tr>
                      <td class="align-middle">Fibres alimentaires</td>
                      <td class="text-sm-start">
                          <div class="col-6 offset-6">
                              <div class="input-group">
                                  <input type="text" class="form-control text-sm-end" id="nutritionnel_fibres" name="nutritionnel_fibres" value="<?php echo $qrcode->nutritionnel_fibres; ?>"/>
                                  <span class="input-group-text" id="basic-addon-fibres">g</span>
                              </div>
                          </div>
                      </td>
                  </tr>

                  <tr>
                    <td class="align-middle">Protéines</td>
                    <td class="text-sm-start">
                      <div class="col-6 offset-6">
                        <div class="input-group">
                          <input type="text" class="form-control text-sm-end" id="nutritionnel_proteines" name="nutritionnel_proteines" value="<?php echo $qrcode->nutritionnel_proteines; ?>"/>
                          <span class="input-group-text" id="basic-addon-proteines">g</span>
                        </div>
                      </div>
                    </td>
                  </tr>


                  <tr>
                    <td class="align-middle">Sel</td>
                    <td class="text-sm-start">
                      <div class="col-6 offset-6">
                        <div class="input-group">
                          <input type="text" class="form-control text-sm-end" id="nutritionnel_sel" name="nutritionnel_sel" value="<?php echo $qrcode->nutritionnel_sel; ?>"/>
                          <span class="input-group-text" id="basic-addon-sel">g</span>
                        </div>
                      </div>
                    </td>
                  </tr>

                  <tr>
                    <td class="align-middle">Sodium</td>
                    <td class="text-sm-start">
                      <div class="col-6 offset-6">
                        <div class="input-group">
                          <input type="text" class="form-control text-sm-end" id="nutritionnel_sodium" name="nutritionnel_sodium" value="<?php echo $qrcode->nutritionnel_sodium; ?>"/>
                          <span class="input-group-text" id="basic-addon-sodium">g</span>
                        </div>
                      </div>
                    </td>
                  </tr>

                </tbody>
                </table>
            </div>
        </div>

        <h4 class="mt-4 mb-4" id="photos"><i class="bi bi-image"></i> Photos</h4>

        <div class="mb-3 imgs-list">
            <div class="row">
                <div class="text-center col-sm-4 img_selector">
                    Bouteille
                    <img id="img_image_bouteille" src="<?php echo $qrcode->image_bouteille ?>" class="mb-2 mx-auto img-preview img-thumbnail"/>
                    <span class="img-add btn btn-link btn-sm">
                        <?php if (strpos($qrcode->image_bouteille ?? '', 'data:') === false): ?>Ajouter<?php else: ?>Modifier<?php endif; ?>
                    </span>
                    <span class="img-reset btn btn-link btn-sm d-none">
                        Réinitialiser
                    </span>
                    <span style="<?php if (strpos($qrcode->image_bouteille ?? '', 'data:') === false || isset($create)) { echo 'display: none;'; }?>">
                        <a class="btn btn-link btn-sm" href="/qrcode/<?php echo $qrcode->user_id ?>/edit/<?php echo $qrcode->getId(); ?>/img/0/delete">Supprimer</a>
                    </span>
                    <input type="file" accept="image/png, image/jpeg" class="d-none form-control" id="image_bouteille" name="image_bouteille" data-imageorigin="img_image_bouteille" defaultvalue="<?php echo $qrcode->image_bouteille; ?>"/>
                </div>
                <div class="text-center col-sm-4 img_selector">
                    Étiquette<br/>
                    <img id="img_image_etiquette" src="<?php echo $qrcode->image_etiquette ?>" class="mb-2 mx-auto img-preview img-thumbnail" style="opacity:<?php if (strpos($qrcode->image_etiquette ?? '', 'data:') === false): ?>0.55<?php else: ?>1<?php endif; ?>"/>
                    <span class="img-add btn btn-link btn-sm">
                        <?php if (strpos($qrcode->image_etiquette ?? '', 'data:') === false): ?>Ajouter<?php else: ?>Modifier<?php endif; ?>
                    </span>
                    <span class="img-reset btn btn-link btn-sm d-none">
                        Réinitialiser
                    </span>
                    <span style="<?php if (strpos($qrcode->image_etiquette ?? '', 'data:') === false || isset($create)) { echo 'display: none;'; }?>">
                        <a class="btn btn-link btn-sm" href="/qrcode/<?php echo $qrcode->user_id ?>/edit/<?php echo $qrcode->getId(); ?>/img/1/delete">Supprimer</a>
                    </span>
                    <input type="file" accept="image/png, image/jpeg" class="d-none form-control" id="image_etiquette" name="image_etiquette" data-imageorigin="img_image_etiquette" defaultvalue="<?php echo $qrcode->image_etiquette; ?>"/>
                </div>
                <div class="text-center col-sm-4 img_selector">
                    Contre-étiquette<br/>
                    <img id="img_image_contreetiquette" src="<?php echo $qrcode->image_contreetiquette ?>" class="mb-2 mx-auto img-preview img-thumbnail" style="opacity:<?php if (strpos($qrcode->image_etiquette ?? '', 'data:') === false): ?>0.55<?php else: ?>1<?php endif; ?>"/>
                    <span class="img-add btn btn-link btn-sm">
                        <?php if (strpos($qrcode->image_contreetiquette ?? '', 'data:') === false): ?>Ajouter<?php else: ?>Modifier<?php endif; ?>
                    </span>
                    <span class="img-reset btn btn-link btn-sm d-none">
                        Réinitialiser
                    </span>
                    <span style="<?php if (strpos($qrcode->image_contreetiquette ?? '', 'data:') === false || isset($create)) { echo 'display: none;'; }?>">
                        <a class="btn btn-link btn-sm" href="/qrcode/<?php echo $qrcode->user_id ?>/edit/<?php echo $qrcode->getId(); ?>/img/2/delete">Supprimer</a>
                    </span>
                    <input type="file" accept="image/png, image/jpeg" class="d-none form-control" id="image_contreetiquette" name="image_contreetiquette" data-imageorigin="img_image_contreetiquette" defaultvalue="<?php echo $qrcode->image_contreetiquette; ?>"/>
                </div>
            </div>
        </div>

        <h4 class="mt-4 mb-4"><i class="bi bi-tag"></i> Labels complémentaires</h4>
        <?php $labels = $qrcode->getLabels(); ?>
        <div class="mb-3">
          <?php foreach ($qrcode::$LABELS as $label): ?>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="label<?php echo $label ?>" value="<?php echo $label ?>" name="labels[]"<?php if(in_array($label, $labels)): ?> checked<?php endif; ?> />
              <label class="form-check-label" for="label<?php echo $label ?>"><?php echo $label ?></label>
            </div>
          <?php endforeach; ?>
        </div>

        <h4 class="mt-4 mb-4"><i class="bi bi-info-lg"></i> Autres informations destinées aux consommateurs</h4>

        <div class="mb-3">
            <textarea class="form-control" name="autres_infos" rows="3"><?php echo $qrcode->autres_infos; ?></textarea>
            <div class="form-text">
              Les informations indiquée ici ne doivent être ni commerciales, ni marketing.
            </div>
        </div>

        <h4 class="mt-4 mb-4"><i class="bi bi-building-check"></i> Responsabilité juridique</h4>

        <p>Vous êtes le seul responsable des informations nutritionelles affichées sur cette fiche. En la validant, vous garantissez qu'elle ne contienne ni information commerciales ni information marketing. Pour des raisons légales, la fiche doit contenir les informations permettant aux visiteurs et aux institutions en charge de la concurrence et de la répression des fraudes de vous contacter :</p>

        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="responsable_siret" name="responsable_siret" placeholder="SIRET du responsable" value="<?php echo $qrcode->responsable_siret ;?>" required="required"/>
            <label for="responsable_siret">SIRET du responsable</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="responsable_nom" name="responsable_nom" placeholder="Nom du responsable" value="<?php echo $qrcode->responsable_nom; ?>" required="required"/>
            <label for="responsable_nom">Dénomination sociale et forme juridique du responsable</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="responsable_adresse" name="responsable_adresse" placeholder="L'adresse du responsable" value="<?php echo $qrcode->responsable_adresse ;?>"/>
            <label for="responsable_adresse">Adresse du responsable</label>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="responsable_valid" required="required" data-liveform-ignore/>
            <label for="responsable_valid" class="form-check-label">J'ai vérifié que les informations de cette fiche sont conformes à mon vin et qu'aucune information commerciale ou marketing ne sera publiée.</label>
        </div>

        <?php if ($qrcode->exists('authorization_key')): ?>
            <input type="hidden" name="authorization_key" value="<?php echo $qrcode->authorization_key; ?>"/>
        <?php endif; ?>

      </form>
      <form id="form_add_ingredients"></form>
      <form id="form_convertir_nutritionnelle"></form>
  </div>
  <div class="col-1 text-center">
    <div class="vr h-100" style="color: var(--bs-border-color); opacity:1"></div>
  </div>
  <div class="col-4 mx-auto">
    <?php
      $iframe=false;
      $notpublicview = true;
    ?>
    <?php include('_phone.html.php') ?>
    </div>
</div>

<div style="margin-bottom: calc(-.5 * var(--bs-gutter-x))" class="row mt-5 border-top py-3 bg-light">
    <div class="col-6">
        <a href="/qrcode/<?php echo $qrcode->user_id ?>/list" class="btn btn-light"><i class="bi bi-chevron-compact-left"></i> Retour à la liste</a>
    </div>
    <div class="col-6 text-end">
        <button type="submit" form="form-edition" class="btn btn-primary"><i class="bi bi-check2-circle"></i> Valider</button>
    </div>
</div>

<script>


const photoEtiquette = document.querySelector('#img_image_etiquette');
const photoContreEtiquette = document.querySelector('#img_image_contreetiquette');

const observer = new MutationObserver((mutations) => {
    mutations.forEach((mutation) => {
        if(mutation.attributeName.includes('src')){
            observer.disconnect();
            if (mutation.target.src.includes('data:')) {
                mutation.target.style.opacity = "1";
            } else {
                mutation.target.style.opacity = "0.55";
            }
            observer.observe(photoEtiquette, config);
            observer.observe(photoContreEtiquette, config);
        }
    });
});
const config = {
        childList: true,
        characterData: true,
        subtree: true,
        attributes: true
};

observer.observe(photoEtiquette, config);
observer.observe(photoContreEtiquette, config);

const liveform = (function () {
    const _template = document.querySelector("[data-liveform-container]")
    const classe   = 'form.live-form'

    if (_template === null) {
        console.error('Pas de template')
        return false
    }

    function update(el) {
        const toUpdate = _template.querySelector("[data-liveform-name='"+el.name+"']")
        if (el.closest('[data-liveform-ignore]')) {
            return false;
        }

        if (toUpdate === null) {
            console.error("Pas d'élément liveform avec l'attribut : "+el.name)
            return false;
        }

        if (el.type === 'file') {
            const file = el.files[0]
            if (!file.type) {
                status.textContent = 'Error: The File.type property does not appear to be supported on this browser.';
                return;
            }
            if (!file.type.match('image.*')) {
                status.textContent = 'Error: The selected file does not appear to be an image.'
                return;
            }
            const reader = new FileReader();
            reader.addEventListener('load', event => {
                toUpdate.src = event.target.result;
                document.getElementById(el.dataset.imageorigin).src = event.target.result;
            });
            reader.readAsDataURL(file);
        } else {
            let ingredientsListe = el.value.replace(/_(.*?)_/g, "<strong>$1</strong>");
            ingredientsListe = ingredientsListe.replace(/ ?([^,]* : [^;]* ; )/g, " <em>$1</em> ");
            toUpdate.innerHTML = toUpdate.dataset.liveformTemplate.replace('{{%s}}', ingredientsListe)
        }
        const blockAnchor = toUpdate.closest('.liveform_anchor')
        _template.scrollTop = blockAnchor.offsetTop - ((_template.offsetHeight - blockAnchor.offsetHeight) / 2)
    }

    return {
        classe: classe,
        update: update
    }
})();

document.addEventListener('DOMContentLoaded', function () {
    const ne_kcal = document.querySelector('#nutritionnel_energie_kcal')
    const ne_j    = document.querySelector('#nutritionnel_energie_kj')
    const conversion = 4.184

    const rebuildCarousel = function () {
        (document.querySelectorAll('.imgs-list img') || []).forEach(function (i) {
            const imgCarousel = document.querySelector('#'+i.id.replace('img_', 'slide_'))
            imgCarousel.src = i.src
        })
    }

    document.addEventListener('change', function (e) {
        if (e.target.id.includes('nutritionnel_energie')) {
            const updated  = e.target
            const toUpdate = updated.id.includes('kcal') ? ne_j : ne_kcal;

            toUpdate.value = updated.id.includes('kcal') ? updated.value * conversion : updated.value / conversion;
            toUpdate.value = parseInt(toUpdate.value)
            liveform.update(toUpdate)
            e.stopPropagation()
        }

        if(e.target.classList.contains('checkbox_additif')) {
            const row = e.target.closest('tr');
            row.querySelectorAll('.checkbox_additif').forEach(function(item) { item.checked = e.target.checked;})
            row.querySelector('.ingredient_additif .input-group').classList.toggle("d-none", !e.target.checked);
            row.querySelector('.ingredient_additif > input[type=checkbox]').classList.toggle("d-none", e.target.checked);

            if(!e.target.checked) {
                row.querySelector('.ingredient_additif .input_additif').value = null
            } else {
                row.querySelector('.ingredient_additif .input_additif').focus();
            }

        }

        if(e.target.classList.contains('input_ingredient')) {
            const additif = autoDetectAdditif(e.target.value);
            if(additif) {
                if(!e.target.closest('tr').querySelector('.checkbox_additif').checked) {
                    e.target.closest('tr').querySelector('.checkbox_additif').click()
                }
                e.target.closest('tr').querySelector('.input_additif').value = additif
            }
        }

        if (e.target.closest('#table_ingredients')) {
            ingredientsTableToText();
        }

        if (e.target.id == 'ingredients') {
            ingredientsTextToTable();
            ingredientsTableToText();
        }

        if (e.target.type === 'file') {
            const container = e.target.closest('.img_selector')
            container.querySelector('.img-add').classList.add('d-none')
            container.querySelector('.img-reset').classList.remove('d-none')
        }

        if (e.target.closest(liveform.classe)) {
            liveform.update(e.target)
        }
    })

    document.querySelector('.imgs-list .row').addEventListener('click', function (e) {
        const el = e.target
        const container = el.closest('.img_selector')
        const img = container.querySelector('img')
        const input = document.querySelector("input[type=file]#"+img.id.replace('img_', ''))

        if (el.classList.contains('img-add') || el.classList.contains('img-preview')) {
            input.click()
        }

        if (el.classList.contains('img-reset')) {
            img.src = input.attributes['defaultvalue'].value
            input.value = ""
            container.querySelector('.img-add').classList.remove('d-none')
            container.querySelector('.img-reset').classList.add('d-none')
            rebuildCarousel()
        }
    });

    (document.querySelectorAll('.input-float') || []).forEach(function (el) {
        el.addEventListener('change', function() {
            let valeur = this.value;
            valeur = valeur.replace(/,/g, '.');
            valeur = parseFloat(valeur).toFixed(2);
            this.value = valeur;
        })
    });

    (function () {
        const table = document.querySelector('#table_ingredients')
        const tbody = table.querySelector('tbody')
        const thead = table.querySelector('thead')

        let elDragged

        table.addEventListener('dragstart', function (e) {
            console.log('dragstart')

            elDragged = e.target.tag === 'TR' ? e.target : e.target.closest('tr');
            elDragged.classList.add('dragging')
            e.dataTransfer.effectAllowed = 'move'
        });

        table.addEventListener('dragover', function (e) {
            console.log('drag');
            e.preventDefault();

            const row = e.target.tag === 'TR' ? e.target : e.target.closest('tr');
            row.style = 'border-bottom: dashed 1px black'
        })

        table.addEventListener('dragleave', function (e) {
            console.log('drag');
            e.preventDefault();

            const row = e.target.tag === 'TR' ? e.target : e.target.closest('tr');
            row.style = 'border-bottom: solid 0 inherit'
        })

        tbody.addEventListener('drop', function (e) {
            e.preventDefault();
            console.log('drop');

            const hoveredRow = e.target.tag === 'TR' ? e.target : e.target.closest('tr');
                  hoveredRow.style = 'border-bottom: solid 0 inherit'
            const nextSibling = hoveredRow.nextSibling
            tbody.insertBefore(elDragged, nextSibling)
        })

        thead.addEventListener('drop', function (e) {
            // pour mettre en premier élément
            e.preventDefault();
            console.log('drop');

            const hoveredRow = e.target.tag === 'TR' ? e.target : e.target.closest('tr');
                  hoveredRow.style = 'border-bottom: solid 0 inherit'
            tbody.insertBefore(elDragged, tbody.querySelector('tr'))
        })

        table.addEventListener('dragend', function (e) {
            console.log('dragend')

            elDragged.classList.remove('dragging')
            elDragged = null
            ingredientsTableToText()
        });
    })();

<?php if (isset($create)): ?>
    function dataURLtoBlob(dataurl) {
        let arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
            bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
        while(n--){
            u8arr[n] = bstr.charCodeAt(n);
        }
        return new Blob([u8arr], {type:mime});
    };

    (document.querySelectorAll('.imgs-list img') || []).forEach(function (i) {
        if (i.src.startsWith('data:') === false) { return false; }

        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(new File([dataURLtoBlob(i.src)], i.id+'.png', {type: 'image/png'}))

        const input = document.querySelector("input[type=file]#"+i.id.replace('img_', ''))
        input.files = dataTransfer.files
    })
<?php endif ?>

})

function autoDetectAllergene(ingredient) {
    if(ingredient.match(/^_[^_]*_\*?$/)) {

        return ingredient
    }

    const dataOption = document.querySelector(`#ingredients_list option[value="${ingredient}"`)

    if(dataOption && dataOption.dataset && dataOption.dataset.allergene) {
        ingredient = '_'+ingredient+'_'
    }

    return ingredient
}

function autoDetectAdditif(ingredient) {
    const dataOption = document.querySelector(`#ingredients_list option[value="${ingredient}"`)

    if(dataOption && dataOption.dataset && dataOption.dataset.additif) {
        return dataOption.dataset.additif
    }

    return null
}

function ingredientsTextToTable() {
    let ingredientsText = document.getElementById('ingredients').value
    const ingredientsTableBody = document.querySelector('table#table_ingredients tbody')

    ingredientsTableBody.innerHTML = "";
    document.querySelector('#table_ingredients').classList.toggle('d-none', !ingredientsText);
    document.querySelector('#message_ingredients_vide').classList.toggle('d-none', ingredientsText);

    if(!ingredientsText) {
        return;
    }

    ingredientsText = ingredientsText.replace(/;/g, ',;');

    let ingredients = ingredientsText.split(/[ ]*,[ ]*(?![^()]*\))/);
    let additif = null

    for(let ingredient of ingredients) {
        if(ingredient.match(/^;/)) {
            additif = null
            ingredient = ingredient.replace(/^;[ ]*/, '')
        }
        if(ingredient.match(/\:/)) {
            additif = ingredient.split(/[ ]*:[ ]*/)[0]
            ingredient = ingredient.split(/[ ]*:[ ]*/)[1]
        }
        ingredient = autoDetectAllergene(ingredient)
        if(!additif) {
            additif = autoDetectAdditif(ingredient)
        }
        const templateClone = document.querySelector("#ingredient_row").content.cloneNode(true);
        if(additif) {
            templateClone.querySelectorAll('.checkbox_additif').forEach(function(item) { item.checked = true;})
            templateClone.querySelector('td.ingredient_additif .input-group').classList.remove('d-none');
            templateClone.querySelector('td.ingredient_additif > input[type=checkbox]').classList.add('d-none');
            templateClone.querySelector('.input_additif').value = additif
        }
        templateClone.querySelector('td.ingredient_libelle input.input_ingredient').value = ingredient.replace(/[_\*]/g, '');
        if(ingredient.match(/\*$/)) {
            templateClone.querySelector('td.ingredient_ab input').checked = true
        }
        if(ingredient.match(/^_[^_]*_\*?$/)) {
            templateClone.querySelector('td.ingredient_allergene input').checked = true
        }
        ingredientsTableBody.appendChild(templateClone);
    }
}

function ingredientsTableToText() {
    let ingredientsText = '';
    let currentAdditif = '';
    document.querySelector('table#table_ingredients tbody').querySelectorAll('tr').forEach(function(item) {
        let ingredient = item.querySelector('input.input_ingredient').value
        if (!ingredient) {
            return;
        }
        let newAdditif = null;
        if(item.querySelector('input.checkbox_additif').checked) {
            newAdditif = item.querySelector('input.input_additif').value
        }
        if(currentAdditif && newAdditif != currentAdditif) {
            ingredientsText += ' ; '
            currentAdditif = null;
        } else if(ingredientsText) {
            ingredientsText += ', '
        }
        if(newAdditif == currentAdditif) {
            newAdditif = null;
        }
        if(newAdditif) {
            ingredientsText += newAdditif + " : ";
            currentAdditif = newAdditif
        }
        if(item.querySelector('td.ingredient_allergene input').checked) {
            ingredient = '_'+ingredient+'_'
        }
        ingredient = autoDetectAllergene(ingredient)
        if(item.querySelector('td.ingredient_ab input').checked) {
            ingredient += '*'
        }
        ingredientsText += ingredient;
    })
    document.getElementById('ingredients').value = ingredientsText;
    liveform.update(document.getElementById('ingredients'));
}


document.querySelector('#form_add_ingredients').addEventListener('submit', function(e) {
    e.preventDefault();
    const input_ingredients = document.querySelector('#ingredients');
    const text_add_ingredient = document.querySelector('#text_add_ingredient');

    text_add_ingredient.focus();
    if(!text_add_ingredient.value) {
        return;
    }

    ingredient_to_add = text_add_ingredient.value;

    if(input_ingredients.value) {
        input_ingredients.value += '; '
    }
    input_ingredients.value += ingredient_to_add;
    text_add_ingredient.value = "";
    ingredientsTextToTable();
    ingredientsTableToText();
    liveform.update(document.getElementById('ingredients'));
});

convert_valeur_energetique_kj = {
    'tranquille': {
        'inf_4':  [24, 59, 122, 191, 260, 328, -1, -1, -1, -1],
        'inf_9':  [32, 67, 130, 199, 267, 336, -1, -1, -1, -1],
        'inf_12': [38, 74, 137, 205, 274, 343, -1, -1, -1, -1],
        'inf_18': [48, 82, 144, 213, 282, 350, -1, -1, -1, -1],
        'inf_45': [74,110, 173, 241, 310, 378, -1, -1, -1, -1]
    },
    'liqueur': {
        'inf_4':  [-1, -1, -1, -1, -1, -1, 385, 443, 500, -1],
        'inf_9':  [-1, -1, -1, -1, -1, -1, 393, 450, 508, -1],
        'inf_12': [-1, -1, -1, -1, -1, -1, 400, 457, 514, -1],
        'inf_18': [-1, -1, -1, -1, -1, -1, 408, 465, 522, -1],
        'inf_45': [-1, -1, -1, -1, -1, -1, 436, 493, 550, -1]
    },
    'mousseux': {
        'inf_3':  [23, 58, 121, 190, 259, 327, -1, -1, -1, -1],
        'inf_6':  [28, 64, 127, 195, 264, 332, -1, -1, -1, -1],
        'inf_12': [36, 71, 134, 203, 271, 340, -1, -1, -1, -1],
        'inf_17': [45, 81, 144, 212, 281, 349, -1, -1, -1, -1],
        'inf_32': [68, 98, 161, 229, 298, 367, -1, -1, -1, -1],
        'inf_50': [90,126, 189, 257, 326, 395, -1, -1, -1, -1]
    }
};

document.querySelector('#form_convertir_nutritionnelle').addEventListener('submit', function(e) {
    e.preventDefault();
    nutri_update_complet();
    bootstrap.Tab.getOrCreateInstance(document.querySelector('#nutritionnelle_complet_tab')).show()
});

function nutri_update_complet() {
    sucre = document.querySelector('#teneur_sucre').value;
    cat_sucre = null;
    type =  document.querySelector('#vin_type').value.replace(',', '.');
    alcool =  document.querySelector('#nutri_simple_tav').value.replace(',', '.');
    if (!alcool || !type || !sucre) {
        return;
    }
    cat_alcool = 10;
    if ((type == 'tranquille') || (type == 'liqueur')) {
        if (sucre <= 4) {
            cat_sucre = 'inf_4';
        }else if (sucre <= 9) {
            cat_sucre = 'inf_9';
        }else if (sucre <= 12) {
            cat_sucre = 'inf_12';
        }else if (sucre <= 18) {
            cat_sucre = 'inf_18';
        }else if (sucre <= 45) {
            cat_sucre = 'inf_45';
        }
    } else if (type == 'mousseux') {
        if (sucre <= 0) {
        }else if (sucre <= 3) {
            cat_sucre = 'inf_3';
        }else if (sucre <= 6) {
            cat_sucre = 'inf_6';
        }else if (sucre <= 12) {
            cat_sucre = 'inf_12';
        }else if (sucre <= 17) {
            cat_sucre = 'inf_17';
        }else if (sucre <= 32) {
            cat_sucre = 'inf_32';
        }else if (sucre <= 50) {
            cat_sucre = 'inf_50';
        }
    }
    if (alcool <= 0) {
    }else if (alcool <= 0.5) {
        cat_alcool = 0;
    } else if (alcool <= 3) {
        cat_alcool = 1;
    } else if (alcool <= 6) {
        cat_alcool = 2;
    } else if (alcool <= 9) {
        cat_alcool = 3;
    } else if (alcool <= 12) {
        cat_alcool = 4;
    } else if (alcool <= 15) {
        cat_alcool = 5;
    } else if (alcool <= 17) {
        cat_alcool = 6;
    } else if (alcool <= 20) {
        cat_alcool = 7;
    } else if (alcool <= 22) {
        cat_alcool = 8;
    }

    if (convert_valeur_energetique_kj[type] && convert_valeur_energetique_kj[type][cat_sucre] && convert_valeur_energetique_kj[type][cat_sucre][cat_alcool] && convert_valeur_energetique_kj[type][cat_sucre][cat_alcool] > 0) {
        document.querySelector('#nutritionnel_energie_kj').value = convert_valeur_energetique_kj[type][cat_sucre][cat_alcool];
        document.querySelector('#nutritionnel_glucides').value = alcool / 10;
        document.querySelector('#nutritionnel_sucres').value = alcool / 10;
        document.querySelector('#nutritionnel_energie_kj').dispatchEvent(new Event('change', {bubbles: true}));
    }
    return false;
}

document.querySelector('#alcool_degre').addEventListener('change', function(e) {
        document.querySelector('#nutri_simple_tav').value = document.querySelector('#alcool_degre').value;
});

ingredientsTextToTable();

</script>
