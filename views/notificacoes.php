<?php

require_login();

$usuario_id=current_user()['id'];

/* Marcar todas como lidas */
if(is_post()){

    check_csrf();

    db()->prepare(
        "UPDATE notificacoes
         SET lida=1
         WHERE usuario_id=?"
    )->execute([
        $usuario_id
    ]);

    flash('success','Notificações marcadas como lidas.');

    redirect_to(url('notificacoes'));
}

/* Buscar notificações */
$s=db()->prepare(
    "SELECT *
     FROM notificacoes
     WHERE usuario_id=?
     ORDER BY id DESC"
);

$s->execute([
    $usuario_id
]);

$items=$s->fetchAll();

$title='Notificações - '.APP_NAME;

require __DIR__.'/../includes/header.php';
?>

<h1>Notificações</h1>

<?php if($items): ?>

    <form method="post">

        <input
            type="hidden"
            name="csrf"
            value="<?=e(csrf())?>"
        >

        <button class="btn small">
            Marcar todas como lidas
        </button>

    </form>

    <?php foreach($items as $n): ?>

        <div class="card">

            <div>

                <?php if(!$n['lida']): ?>

                    <span class="badge">
                        Nova
                    </span>

                <?php endif; ?>

            </div>

            <h2>
                <?=e($n['titulo'])?>
            </h2>

            <p>
                <?=e($n['mensagem'])?>
            </p>

            <?php if(
                !empty($n['demanda_id']) &&
                current_user()['tipo']==='ong'
            ): ?>

                <p>
                    <a
                        class="btn small"
                        href="<?=url('interessados',['id'=>$n['demanda_id']])?>"
                    >
                        Ver interessado e confirmar
                    </a>
                </p>

            <?php endif; ?>

            <small>
                <?=e(date(
                    'd/m/Y H:i',
                    strtotime($n['data_criacao'])
                ))?>
            </small>

        </div>

    <?php endforeach; ?>

<?php else: ?>

    <div class="card">

        <h2>Nenhuma notificação</h2>

        <p>
            Você não possui notificações no momento.
        </p>

    </div>

<?php endif; ?>

<?php require __DIR__.'/../includes/footer.php'; ?>