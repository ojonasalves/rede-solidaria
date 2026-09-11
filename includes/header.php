<?php
$flash=flash_get();

/* Contar notificações não lidas */
$notificacoes_nao_lidas=0;

if(current_user()){

    $s=db()->prepare(
        "SELECT COUNT(*) AS total
         FROM notificacoes
         WHERE usuario_id=? AND lida=0"
    );

    $s->execute([
        current_user()['id']
    ]);

    $notificacoes_nao_lidas=(int)$s->fetch()['total'];
}
?>

<!doctype html>
<html lang="pt-BR">

<head>

<meta charset="utf-8">

<meta name="viewport" content="width=device-width,initial-scale=1">

<title><?=e($title??APP_NAME)?></title>

<link rel="stylesheet" href="/assets/css/style.css">

</head>

<body>

<header>

<div class="container nav">

<a class="brand" href="<?=url()?>" aria-label="Rede Solidária - Início">
    <img
        src="/assets/img/logo.png"
        alt="Rede Solidária - Conectando pessoas e causas"
        width="240"
        style="width:240px !important; height:auto !important; max-width:240px !important; display:block;"
    >
</a>

<nav>

<a href="<?=url()?>">
    Início
</a>

<a href="<?=url('demandas')?>">
    Demandas
</a>

<?php if(current_user()): ?>

<a href="<?=url('notificacoes')?>">
    🔔 Notificações
    <?php if($notificacoes_nao_lidas>0): ?>
        (<?=$notificacoes_nao_lidas?>)
    <?php endif; ?>
</a>

<a href="<?=url('painel')?>">
    Painel
</a>

<?php if(current_user()['tipo']==='admin'): ?>

<a href="/admin/index.php">
    Admin
</a>

<?php endif; ?>

<a href="<?=url('logout')?>">
    Sair
</a>

<?php else: ?>

<a href="<?=url('login')?>">
    Entrar
</a>

<a class="btn small" href="<?=url('cadastro')?>">
    Cadastrar
</a>

<?php endif; ?>

</nav>

</div>

</header>

<main class="container">

<?php if($flash): ?>

<div class="alert <?=$flash[0]?>">
    <?=e($flash[1])?>
</div>

<?php endif; ?>