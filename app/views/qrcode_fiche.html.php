<h1>Fiche nutritionnelle d'accompagnement</h1>

    <h2><?php echo $qrcode->domaine_nom; ?></h2>

    <?php echo '(' . $qrcode->responsable_nom . ' - ' . $qrcode->responsable_siret . ')'; ?></span>

    <h3><span><?php echo $qrcode->cuvee_nom; ?></span></h3>

<span><?php echo $qrcode->denomination; ?></span>
<br>
<span><?php echo $qrcode->couleur; ?></span>
<br>
<span><?php echo $qrcode->millesime; ?></span>

    <h3>Informations complémentaires</h3>
    <table>
        <tbody>
                <tr><td>Volume d'alcool</td><td><?php echo $qrcode->alcool_degre; ?>% vol</td></tr>
                <tr>
                    <td>Contenance</td><td><?php echo $qrcode->centilisation; ?> cl</td>
                </tr>
                <tr>
                    <td>N° de lot</td><td><?php echo $qrcode->lot; ?></td>
                </tr>
        </tbody>
    </table>

    <h3>Ingrédients</h3>
            <?php echo $qrcode->ingredients = preg_replace(['/_(.*?)_/','/ ?([^,]* : [^;]* ; )/'], ['<strong>$1</strong>', ' <em>$1</em> '], $qrcode->ingredients); ?>
<br><small>Les ingrédients allergènes sont indiqués en <strong>gras</strong>. Les ingrédients issus de l'agriculture biologique sont indiqués avec une <em>*</em></small>
    <h3>Informations nutritionnelles <small>pour 100 mL</small></h3>
    <table>
        <tbody>
            <tr>
                <td>Énergie</td>
                <td>
                    <span><?php echo $qrcode->nutritionnel_energie_kj ?: 0;?> kJ, </span>
                    <span><?php echo $qrcode->nutritionnel_energie_kcal ?: 0;?> kCal</span>
                </td>
            </tr>
            <tr>
                <td>Matières grasses<br><small>dont acides gras saturés</small></td>
                <td>
                    <span><?php echo $qrcode->nutritionnel_graisses?: 0.00; ?> g</span>
                    <br>
                    <small>
                        <span><?php echo $qrcode->nutritionnel_acides_gras ?: 0.00; ?> g</span>
                    </small>
                </td>
            </tr>
            <tr>
                <td>Glucides<br><small>dont sucres</small></td>
                <td>
                    <span><?php echo $qrcode->nutritionnel_glucides?: 0.00; ?> g</span>
                    <br>
                    <small>
                        <span><?php echo $qrcode->nutritionnel_sucres?: 0.00; ?> g</span>
                    </small>
                </td>
            </tr>
            <tr>
                <td>Fibres alimentaires</td>
                <td>
                    <span><?php echo $qrcode->nutritionnel_fibres?: 0.00; ?> g</span>
                </td>
            </tr>
            <tr>
                <td>Protéines</td>
                <td>
                    <span><?php echo $qrcode->nutritionnel_proteines?: 0.00; ?> g</span>
                </td>
            </tr>
            <tr>
                <td>Sel</td>
                <td>
                    <span><?php echo $qrcode->nutritionnel_sel?: 0.00; ?> g</span>
                </td>
            </tr>
            <tr>
                <td>Sodium</td>
                <td>
                    <span><?php echo $qrcode->nutritionnel_sodium?: 0.00; ?> g</span>
                </td>
            </tr>
        </tbody>
    </table>

<?php if (!empty($qrcode->getLabels())): ?>
    <h3>Labels</h3>
    <?php foreach ($qrcode::$LABELS as $label): ?>
        <?php if (in_array($label, $qrcode->getLabels())): ?>
            <?php echo $label . ' '; ?>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (!empty($qrcode->autres_infos)): ?>
<h3>Autres informations</h3>
    <p><?php echo $qrcode->autres_infos; ?></p>
<?php endif;?>
