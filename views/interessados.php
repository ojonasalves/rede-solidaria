<?php
require_role('ong');

$id=(int)($_GET['id']??0);

$s=db()->prepare(
    "SELECT d.id,d.titulo,d.cidade,o.nome_fantasia
     FROM demandas d
     JOIN ongs o ON o.id=d.ong_id
     WHERE d.id=? AND o.usuario_id=?"
);

$s->execute([
    $id,
    current_user()['id']
]);

$d=$s->fetch();

if(!$d){
    http_response_code(404);
    exit('Demanda não encontrada.');
}

/* Alterar status do interessado */
if(is_post()){

    check_csrf();

    $participacao_id=(int)($_POST['participacao_id']??0);
    $status=$_POST['status']??'';

    $status_validos=['interessado','confirmado','concluido'];

    if(!in_array($status,$status_validos,true)){
        flash('error','Status inválido.');
        redirect_to(url('interessados',['id'=>$id]));
    }

    /*
     * Buscar a participação e confirmar
     * que pertence à demanda da ONG logada.
     */
    $p=db()->prepare(
        "SELECT
            p.id,
            p.status,
            p.solidario_id,
            u.nome
         FROM participacoes p
         JOIN usuarios u ON u.id=p.solidario_id
         JOIN demandas d ON d.id=p.demanda_id
         JOIN ongs o ON o.id=d.ong_id
         WHERE p.id=?
           AND p.demanda_id=?
           AND o.usuario_id=?"
    );

    $p->execute([
        $participacao_id,
        $id,
        current_user()['id']
    ]);

    $participacao=$p->fetch();

    if(!$participacao){
        flash('error','Participação não encontrada.');
        redirect_to(url('interessados',['id'=>$id]));
    }

    $status_anterior=$participacao['status'];

    /*
     * Atualizar status
     */
    $u=db()->prepare(
        "UPDATE participacoes p
         JOIN demandas d ON d.id=p.demanda_id
         JOIN ongs o ON o.id=d.ong_id
         SET p.status=?
         WHERE p.id=?
           AND p.demanda_id=?
           AND o.usuario_id=?"
    );

    $u->execute([
        $status,
        $participacao_id,
        $id,
        current_user()['id']
    ]);

    /*
     * Criar notificação somente quando
     * o status realmente tiver mudado.
     */
    if($status_anterior!==$status){

        $nomes_status=[
            'interessado'=>'Interessado',
            'confirmado'=>'Confirmado',
            'concluido'=>'Concluído'
        ];

        $titulo_notificacao='Atualização da sua participação';

        $mensagem_notificacao=
            'A ONG "'.$d['nome_fantasia'].
            '" alterou sua participação na demanda "'.
            $d['titulo'].
            '" para "'.$nomes_status[$status].'".';

        db()->prepare(
            "INSERT INTO notificacoes
             (usuario_id,titulo,mensagem)
             VALUES(?,?,?)"
        )->execute([
            $participacao['solidario_id'],
            $titulo_notificacao,
            $mensagem_notificacao
        ]);
    }

    flash('success','Status atualizado com sucesso.');

    redirect_to(url('interessados',['id'=>$id]));
}

/* Buscar interessados */
$s=db()->prepare(
    "SELECT
        p.id,
        p.status,
        p.data_cadastro,
        u.nome,
        u.email,
        s.telefone,
        s.cidade
     FROM participacoes p
     JOIN usuarios u ON u.id=p.solidario_id
     LEFT JOIN solidarios s ON s.usuario_id=u.id
     WHERE p.demanda_id=?
     ORDER BY p.id DESC"
);

$s->execute([$id]);

$items=$s->fetchAll();

$title='Interessados - '.APP_NAME;
require __DIR__.'/../includes/header.php';
?>

<h1>Interessados na demanda</h1>

<div class="card">

    <h2><?=e($d['titulo'])?></h2>

    <p>
        <b>ONG:</b> <?=e($d['nome_fantasia'])?>
    </p>

    <p>
        <b>Cidade:</b> <?=e($d['cidade'])?>
    </p>

</div>

<?php if($items): ?>

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

    <h2><?=e($p['nome'])?></h2>

    <p>
        <b>E-mail:</b><br>
        <?=e($p['email'])?>
    </p>

    <p>
        <b>Telefone:</b><br>
        <?=e($p['telefone'] ?: 'Não informado')?>
    </p>

    <p>
        <b>Cidade:</b><br>
        <?=e($p['cidade'] ?: 'Não informada')?>
    </p>

    <p>
        <b>Status atual:</b>
        <span class="badge">
            <?=e($status)?>
        </span>
    </p>

    <form method="post">

        <input
            type="hidden"
            name="csrf"
            value="<?=e(csrf())?>"
        >

        <input
            type="hidden"
            name="participacao_id"
            value="<?=e((string)$p['id'])?>"
        >

        <label>
            Alterar status

            <select name="status">

                <option
                    value="interessado"
                    <?=$p['status']==='interessado'?'selected':''?>
                >
                    Interessado
                </option>

                <option
                    value="confirmado"
                    <?=$p['status']==='confirmado'?'selected':''?>
                >
                    Confirmado
                </option>

                <option
                    value="concluido"
                    <?=$p['status']==='concluido'?'selected':''?>
                >
                    Concluído
                </option>

            </select>

        </label>

        <button class="btn small">
            Atualizar status
        </button>

    </form>

</div>

<?php endforeach; ?>

</div>

<?php else: ?>

<div class="card">

    <h2>Nenhum interessado ainda.</h2>

    <p>
        Quando um Solidário clicar em "Quero ajudar",
        ele aparecerá nesta lista.
    </p>

</div>

<?php endif; ?>

<p>

    <a class="btn outline" href="<?=url('demandas')?>">
        Voltar para minhas demandas
    </a>

</p>

<?php require __DIR__.'/../includes/footer.php'; ?>