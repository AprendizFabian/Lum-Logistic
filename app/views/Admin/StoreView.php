
<div class="container">
    <h1><?= $title ?></h1>

    <?php if (!empty($tiendasPaginadas)): ?>
        <ul>
            <?php foreach ($tiendasPaginadas as $tienda): ?>
                <li>
                    <?= htmlspecialchars($tienda['store_name'] ?? 'Sin nombre') ?>
                    (<?= htmlspecialchars($tienda['store_email'] ?? 'Sin dirección') ?>)
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
      <?php var_dump( $tiendasPaginadas); ?>
        <p>No hay tiendas registradas.</p>
    <?php endif; ?>
</div>
