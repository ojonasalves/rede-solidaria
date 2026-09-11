<?php

require_once __DIR__.'/../config/config.php';

require_admin();

/* Alterar status de usuário */

if(is_post()){

    check_csrf();

    $usuario_id=(int)($_POST['usuario_id']??0);
    $novo_status=$_POST['status']??'';

    if(!in_array($novo_status,['ativo','inativo'],true)){

        flash(
            'error',
            'Status inválido.'
        );

        redirect_to('index.php');
    }

    /* Impedir que o administrador altere o próprio status */

    if($usuario_id===(int)current_user()['id']){

        flash(
            'error',
            'Você não pode alterar o status da sua própria conta.'
        );

        redirect_to('index.php');
    }

    $s=db()->prepare(
        "SELECT id
         FROM usuarios
         WHERE id=?"
    );

    $s->execute([
        $usuario_id
    ]);

    if(!$s->fetch()){

        flash(
            'error',
            'Usuário não encontrado.'
        );

        redirect_to('index.php');
    }

    db()->prepare(
        "UPDATE usuarios
         SET status=?
         WHERE id=?"
    )->execute([
        $novo_status,
        $usuario_id
    ]);

    flash(
        'success',
        'Status do usuário atualizado com sucesso.'
    );

    redirect_to('index.php');
}


/* Estatísticas */

$stats=[];

$stats['usuarios']=
    db()->query(
        "SELECT COUNT(*) c
         FROM usuarios"
    )->fetch()['c'];

$stats['ongs']=
    db()->query(
        "SELECT COUNT(*) c
         FROM usuarios
         WHERE tipo='ong'"
    )->fetch()['c'];

$stats['solidarios']=
    db()->query(
        "SELECT COUNT(*) c
         FROM usuarios
         WHERE tipo='solidario'"
    )->fetch()['c'];

$stats['demandas']=
    db()->query(
        "SELECT COUNT(*) c
         FROM demandas"
    )->fetch()['c'];


/* Lista de usuários */

$s=db()->query(
    "SELECT
        id,
        nome,
        email,
        tipo,
        status,
        data_cadastro
     FROM usuarios
     ORDER BY id DESC"
);

$usuarios=$s->fetchAll();


/* Lista de demandas */

$s=db()->query(
    "SELECT
        d.id,
        d.titulo,
        d.categoria,
        d.cidade,
        d.status,
        d.data_criacao,
        o.nome_fantasia
     FROM demandas d
     JOIN ongs o ON o.id=d.ong_id
     ORDER BY d.id DESC"
);

$demandas=$s->fetchAll();


$title='Administração - '.APP_NAME;

require __DIR__.'/../includes/header.php';

?>

<h1>Administração</h1>

<p>
    Gerencie os usuários e acompanhe os principais
    indicadores da plataforma.
</p>

<!-- Estatísticas -->

<div class="grid3">


<div class="card">

    <b>👥 Usuários</b>

    <h2>
        <?=e((string)$stats['usuarios'])?>
    </h2>

</div>


<div class="card">

    <b>🏢 ONGs</b>

    <h2>
        <?=e((string)$stats['ongs'])?>
    </h2>

</div>


<div class="card">

    <b>🙋 Solidários</b>

    <h2>
        <?=e((string)$stats['solidarios'])?>
    </h2>

</div>


<div class="card">

    <b>📋 Demandas</b>

    <h2>
        <?=e((string)$stats['demandas'])?>
    </h2>

</div>


</div>

<!-- Usuários -->

<div class="card">


<h2>Usuários cadastrados</h2>

<?php if($usuarios): ?>

    <div style="overflow-x:auto;">

        <table style="width:100%;border-collapse:collapse;">

            <thead>

                <tr>

                    <th style="text-align:left;padding:10px;border-bottom:1px solid #ddd;">
                        ID
                    </th>

                    <th style="text-align:left;padding:10px;border-bottom:1px solid #ddd;">
                        Nome
                    </th>

                    <th style="text-align:left;padding:10px;border-bottom:1px solid #ddd;">
                        E-mail
                    </th>

                    <th style="text-align:left;padding:10px;border-bottom:1px solid #ddd;">
                        Tipo
                    </th>

                    <th style="text-align:left;padding:10px;border-bottom:1px solid #ddd;">
                        Status
                    </th>

                    <th style="text-align:left;padding:10px;border-bottom:1px solid #ddd;">
                        Cadastro
                    </th>

                    <th style="text-align:left;padding:10px;border-bottom:1px solid #ddd;">
                        Ação
                    </th>

                </tr>

            </thead>

            <tbody>

            <?php foreach($usuarios as $usuario): ?>

                <tr>

                    <td style="padding:10px;border-bottom:1px solid #eee;">
                        <?=e((string)$usuario['id'])?>
                    </td>

                    <td style="padding:10px;border-bottom:1px solid #eee;">
                        <?=e($usuario['nome'])?>
                    </td>

                    <td style="padding:10px;border-bottom:1px solid #eee;">
                        <?=e($usuario['email'])?>
                    </td>

                    <td style="padding:10px;border-bottom:1px solid #eee;">

                        <?php if($usuario['tipo']==='ong'): ?>

                            <span class="badge">
                                ONG
                            </span>

                        <?php elseif($usuario['tipo']==='solidario'): ?>

                            <span class="badge">
                                Solidário
                            </span>

                        <?php else: ?>

                            <span class="badge">
                                Administrador
                            </span>

                        <?php endif; ?>

                    </td>

                    <td style="padding:10px;border-bottom:1px solid #eee;">

                        <?php if($usuario['status']==='ativo'): ?>

                            <span class="badge">
                                Ativo
                            </span>

                        <?php else: ?>

                            <span class="badge">
                                Inativo
                            </span>

                        <?php endif; ?>

                    </td>

                    <td style="padding:10px;border-bottom:1px solid #eee;">

                        <?=e(
                            date(
                                'd/m/Y H:i',
                                strtotime($usuario['data_cadastro'])
                            )
                        )?>

                    </td>

                    <td style="padding:10px;border-bottom:1px solid #eee;">

                        <?php if(
                            (int)$usuario['id'] !==
                            (int)current_user()['id']
                        ): ?>

                            <form method="post">

                                <input
                                    type="hidden"
                                    name="csrf"
                                    value="<?=e(csrf())?>"
                                >

                                <input
                                    type="hidden"
                                    name="usuario_id"
                                    value="<?=e((string)$usuario['id'])?>"
                                >

                                <?php if($usuario['status']==='ativo'): ?>

                                    <input
                                        type="hidden"
                                        name="status"
                                        value="inativo"
                                    >

                                    <button
                                        class="btn small"
                                        type="submit"
                                    >
                                        Inativar
                                    </button>

                                <?php else: ?>

                                    <input
                                        type="hidden"
                                        name="status"
                                        value="ativo"
                                    >

                                    <button
                                        class="btn small"
                                        type="submit"
                                    >
                                        Ativar
                                    </button>

                                <?php endif; ?>

                            </form>

                        <?php else: ?>

                            <span class="badge">
                                Sua conta
                            </span>

                        <?php endif; ?>

                    </td>

                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

    </div>

<?php else: ?>

    <p>
        Nenhum usuário cadastrado.
    </p>

<?php endif; ?>


</div>

<!-- Demandas -->

<div class="card">


<h2>Demandas cadastradas</h2>

<?php if($demandas): ?>

    <div style="overflow-x:auto;">

        <table style="width:100%;border-collapse:collapse;">

            <thead>

                <tr>

                    <th style="text-align:left;padding:10px;border-bottom:1px solid #ddd;">
                        ID
                    </th>

                    <th style="text-align:left;padding:10px;border-bottom:1px solid #ddd;">
                        Título
                    </th>

                    <th style="text-align:left;padding:10px;border-bottom:1px solid #ddd;">
                        ONG
                    </th>

                    <th style="text-align:left;padding:10px;border-bottom:1px solid #ddd;">
                        Categoria
                    </th>

                    <th style="text-align:left;padding:10px;border-bottom:1px solid #ddd;">
                        Cidade
                    </th>

                    <th style="text-align:left;padding:10px;border-bottom:1px solid #ddd;">
                        Status
                    </th>

                    <th style="text-align:left;padding:10px;border-bottom:1px solid #ddd;">
                        Criada em
                    </th>

                </tr>

            </thead>

            <tbody>

            <?php foreach($demandas as $demanda): ?>

                <tr>

                    <td style="padding:10px;border-bottom:1px solid #eee;">
                        <?=e((string)$demanda['id'])?>
                    </td>

                    <td style="padding:10px;border-bottom:1px solid #eee;">
                        <?=e($demanda['titulo'])?>
                    </td>

                    <td style="padding:10px;border-bottom:1px solid #eee;">
                        <?=e($demanda['nome_fantasia'])?>
                    </td>

                    <td style="padding:10px;border-bottom:1px solid #eee;">

                        <span class="badge">
                            <?=e($demanda['categoria'])?>
                        </span>

                    </td>

                    <td style="padding:10px;border-bottom:1px solid #eee;">
                        <?=e($demanda['cidade'])?>
                    </td>

                    <td style="padding:10px;border-bottom:1px solid #eee;">

                        <?php if($demanda['status']==='aberta'): ?>

                            <span class="badge">
                                Aberta
                            </span>

                        <?php else: ?>

                            <span class="badge">
                                Encerrada
                            </span>

                        <?php endif; ?>

                    </td>

                    <td style="padding:10px;border-bottom:1px solid #eee;">

                        <?=e(
                            date(
                                'd/m/Y H:i',
                                strtotime($demanda['data_criacao'])
                            )
                        )?>

                    </td>

                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

    </div>

<?php else: ?>

    <p>
        Nenhuma demanda cadastrada.
    </p>

<?php endif; ?>


</div>

<?php require __DIR__.'/../includes/footer.php'; ?>
