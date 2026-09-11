<?php
require_role('solidario');

$s=db()->prepare(
    "SELECT p.*,d.titulo,d.cidade,o.nome_fantasia
     FROM participacoes p
     JOIN demandas d ON d.id=p.demanda_id
     JOIN ongs o ON o.id=d.ong_id
     WHERE p.solidario_id=?
     ORDER BY p.id DESC"
);

$s->execute([current_user()['id']]);
$items=$s->fetchAll();

$title='Participações - '.APP_NAME;
require __DIR__.'/../includes/header.php';
?>

<h1>Minhas participações</h1>

<div class="grid3">

<?php foreach($items as $p): ?>

<?php
$status_nome=[
    'interessado'=>'Interessado',
    'confirmado'=>'Confirmado',
    'concluido'=>'Concluído'
];

$status=$status_nome[$p['status']] ?? ucfirst($p['status']);
?>

<div class="card">

    <h3><?=e($p['titulo'])?></h3>

    <p>
        <?=e($p['nome_fantasia'])?> · <?=e($p['cidade'])?>
    </p>

    <p>
        <b>Status:</b>
        <span class="badge"><?=e($status)?></span>
    </p>

</div>

<?php endforeach; ?>

</div>

<?php if(!$items): ?>

<div class="card">
    Nenhuma participação ainda.
</div>

<?php endif; ?>

<?php require __DIR__.'/../includes/footer.php'; ?>
