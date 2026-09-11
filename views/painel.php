<?php

require_login();

$u=current_user();

$stats=[];

/*
 * Estatísticas da ONG
 */
if($u['tipo']==='ong'){

    $s=db()->prepare(
        "SELECT id
         FROM ongs
         WHERE usuario_id=?"
    );

    $s->execute([
        $u['id']
    ]);

    $ong=$s->fetch();

    if(!$ong){
        exit('Perfil da ONG não encontrado.');
    }

    $ong_id=$ong['id'];

    /* Total de demandas */
    $s=db()->prepare(
        "SELECT COUNT(*) AS total
         FROM demandas
         WHERE ong_id=?"
    );

    $s->execute([$ong_id]);

    $stats['demandas']=(int)$s->fetch()['total'];

    /* Demandas abertas */
    $s=db()->prepare(
        "SELECT COUNT(*) AS total
         FROM demandas
         WHERE ong_id=? AND status='aberta'"
    );

    $s->execute([$ong_id]);

    $stats['abertas']=(int)$s->fetch()['total'];

    /* Demandas encerradas */
    $s=db()->prepare(
        "SELECT COUNT(*) AS total
         FROM demandas
         WHERE ong_id=? AND status='encerrada'"
    );

    $s->execute([$ong_id]);

    $stats['encerradas']=(int)$s->fetch()['total'];

    /* Interessados */
    $s=db()->prepare(
        "SELECT COUNT(*) AS total
         FROM participacoes p
         JOIN demandas d ON d.id=p.demanda_id
         WHERE d.ong_id=?"
    );

    $s->execute([$ong_id]);

    $stats['interessados']=(int)$s->fetch()['total'];

    /* Confirmados */
    $s=db()->prepare(
        "SELECT COUNT(*) AS total
         FROM participacoes p
         JOIN demandas d ON d.id=p.demanda_id
         WHERE d.ong_id=? AND p.status='confirmado'"
    );

    $s->execute([$ong_id]);

    $stats['confirmados']=(int)$s->fetch()['total'];

    /* Concluídos */
    $s=db()->prepare(
        "SELECT COUNT(*) AS total
         FROM participacoes p
         JOIN demandas d ON d.id=p.demanda_id
         WHERE d.ong_id=? AND p.status='concluido'"
    );

    $s->execute([$ong_id]);

    $stats['concluidos']=(int)$s->fetch()['total'];

}

/*
 * Estatísticas do Solidário
 */
elseif($u['tipo']==='solidario'){

    /* Total de participações */
    $s=db()->prepare(
        "SELECT COUNT(*) AS total
         FROM participacoes
         WHERE solidario_id=?"
    );

    $s->execute([
        $u['id']
    ]);

    $stats['participacoes']=(int)$s->fetch()['total'];

    /* Interessados */
    $s=db()->prepare(
        "SELECT COUNT(*) AS total
         FROM participacoes
         WHERE solidario_id=? AND status='interessado'"
    );

    $s->execute([
        $u['id']
    ]);

    $stats['interessados']=(int)$s->fetch()['total'];

    /* Confirmados */
    $s=db()->prepare(
        "SELECT COUNT(*) AS total
         FROM participacoes
         WHERE solidario_id=? AND status='confirmado'"
    );

    $s->execute([
        $u['id']
    ]);

    $stats['confirmados']=(int)$s->fetch()['total'];

    /* Concluídos */
    $s=db()->prepare(
        "SELECT COUNT(*) AS total
         FROM participacoes
         WHERE solidario_id=? AND status='concluido'"
    );

    $s->execute([
        $u['id']
    ]);

    $stats['concluidos']=(int)$s->fetch()['total'];

}

$title='Painel - '.APP_NAME;

require __DIR__.'/../includes/header.php';

?>

<h1>Olá, <?=e($u['nome'])?>!</h1>

<p>
    Bem-vindo ao seu painel do <b><?=e(APP_NAME)?></b>.
</p>

<?php if($u['tipo']==='ong'): ?>

<h2>Resumo da ONG</h2>

<div class="grid3">

    <div class="card">
        <b>📋 Minhas demandas</b>
        <h2><?=$stats['demandas']?></h2>
    </div>

    <div class="card">
        <b>🟢 Demandas abertas</b>
        <h2><?=$stats['abertas']?></h2>
    </div>

    <div class="card">
        <b>🔴 Demandas encerradas</b>
        <h2><?=$stats['encerradas']?></h2>
    </div>

    <div class="card">
        <b>🙋 Interessados</b>
        <h2><?=$stats['interessados']?></h2>
    </div>

    <div class="card">
        <b>🤝 Confirmados</b>
        <h2><?=$stats['confirmados']?></h2>
    </div>

    <div class="card">
        <b>✅ Concluídos</b>
        <h2><?=$stats['concluidos']?></h2>
    </div>

</div>

<div class="card">

    <h2>Área da ONG</h2>

    <p>
        Publique novas demandas e acompanhe as pessoas
        interessadas em ajudar.
    </p>

    <a class="btn" href="<?=url('nova-demanda')?>">
        Publicar demanda
    </a>

    <a class="btn outline" href="<?=url('demandas')?>">
        Minhas demandas
    </a>

</div>

<?php elseif($u['tipo']==='solidario'): ?>

<h2>Resumo das suas participações</h2>

<div class="grid3">

    <div class="card">
        <b>🙋 Participações</b>
        <h2><?=$stats['participacoes']?></h2>
    </div>

    <div class="card">
        <b>🟡 Interessados</b>
        <h2><?=$stats['interessados']?></h2>
    </div>

    <div class="card">
        <b>🤝 Confirmados</b>
        <h2><?=$stats['confirmados']?></h2>
    </div>

    <div class="card">
        <b>✅ Concluídos</b>
        <h2><?=$stats['concluidos']?></h2>
    </div>

</div>

<div class="card">

    <h2>Área do Solidário</h2>

    <p>
        Encontre demandas sociais e acompanhe suas
        participações.
    </p>

    <a class="btn" href="<?=url('demandas')?>">
        Encontrar demandas
    </a>

    <a class="btn outline" href="<?=url('participacoes')?>">
        Minhas participações
    </a>

</div>

<?php endif; ?>

<div class="card">

    <h2>Minha conta</h2>

    <p>
        <b>Tipo:</b>
        <?=e(strtoupper($u['tipo']))?>
    </p>

    <p>
        <b>E-mail:</b>
        <?=e($u['email'])?>
    </p>

    <a href="<?=url('perfil')?>">
        Editar meu perfil
    </a>

</div>

<?php require __DIR__.'/../includes/footer.php'; ?>