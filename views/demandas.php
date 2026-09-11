<?php

$title='Demandas - '.APP_NAME;

require __DIR__.'/../includes/header.php';

?>

<h1>
    <?php if(current_user() && current_user()['tipo']==='ong'): ?>
        Minhas demandas
    <?php else: ?>
        Demandas sociais
    <?php endif; ?>
</h1>

<div class="card">


<form method="get" action="index.php">

    <input type="hidden" name="page" value="demandas">

    <label>
        Buscar

        <input
            type="text"
            name="q"
            value="<?=e($_GET['q']??'')?>"
            placeholder="Digite uma palavra..."
        >
    </label>

    <label>
        Cidade

        <input
            type="text"
            name="cidade"
            value="<?=e($_GET['cidade']??'')?>"
            placeholder="Ex.: Santa Teresa"
        >
    </label>

    <label>
        Categoria

        <select name="categoria">

            <option value="">
                Todas as categorias
            </option>

            <option value="Alimentação" <?=($_GET['categoria']??'')==='Alimentação'?'selected':''?>>
                Alimentação
            </option>

            <option value="Roupas" <?=($_GET['categoria']??'')==='Roupas'?'selected':''?>>
                Roupas
            </option>

            <option value="Educação" <?=($_GET['categoria']??'')==='Educação'?'selected':''?>>
                Educação
            </option>

            <option value="Saúde" <?=($_GET['categoria']??'')==='Saúde'?'selected':''?>>
                Saúde
            </option>

            <option value="Moradia" <?=($_GET['categoria']??'')==='Moradia'?'selected':''?>>
                Moradia
            </option>

            <option value="Meio ambiente" <?=($_GET['categoria']??'')==='Meio ambiente'?'selected':''?>>
                Meio ambiente
            </option>

            <option value="Animais" <?=($_GET['categoria']??'')==='Animais'?'selected':''?>>
                Animais
            </option>

            <option value="Doações" <?=($_GET['categoria']??'')==='Doações'?'selected':''?>>
                Doações
            </option>

            <option value="Voluntariado" <?=($_GET['categoria']??'')==='Voluntariado'?'selected':''?>>
                Voluntariado
            </option>

            <option value="Outros" <?=($_GET['categoria']??'')==='Outros'?'selected':''?>>
                Outros
            </option>

        </select>

    </label>

    <button class="btn" type="submit">
        Buscar
    </button>

</form>


</div>

<?php

$q=trim($_GET['q']??'');
$cidade=trim($_GET['cidade']??'');
$categoria=trim($_GET['categoria']??'');

$params=[];

/*
 * Verificar se existe usuário logado
 */
$usuario=current_user();

/*
 * Montar consulta
 */
$sql="
    SELECT
        d.*,
        o.nome_fantasia
    FROM demandas d
    JOIN ongs o ON o.id=d.ong_id
    WHERE 1=1
";

/*
 * Se for ONG:
 * mostrar somente as demandas pertencentes à ONG logada.
 */
if($usuario && $usuario['tipo']==='ong'){

    $s=db()->prepare(
        "SELECT id
         FROM ongs
         WHERE usuario_id=?"
    );

    $s->execute([
        $usuario['id']
    ]);

    $ong=$s->fetch();

    if(!$ong){
        exit('Perfil da ONG não encontrado.');
    }

    $sql.="
        AND d.ong_id=?
    ";

    $params[]=$ong['id'];

}

/*
 * Se NÃO for ONG:
 * mostrar somente demandas abertas.
 */
else{

    $sql.="
        AND d.status='aberta'
    ";

}

/*
 * Filtro de busca
 */
if($q!==''){

    $sql.="
        AND (
            d.titulo LIKE ?
            OR d.descricao LIKE ?
            OR d.categoria LIKE ?
            OR d.cidade LIKE ?
        )
    ";

    $termo='%'.$q.'%';

    $params[]=$termo;
    $params[]=$termo;
    $params[]=$termo;
    $params[]=$termo;
}

/*
 * Filtro por cidade
 */
if($cidade!==''){

    $sql.="
        AND d.cidade LIKE ?
    ";

    $params[]='%'.$cidade.'%';

}

/*
 * Filtro por categoria
 */
if($categoria!==''){

    $sql.="
        AND d.categoria=?
    ";

    $params[]=$categoria;

}

$sql.=" ORDER BY d.id DESC";

$s=db()->prepare($sql);

$s->execute($params);

$items=$s->fetchAll();

?>

<?php if($items): ?>

<div class="grid3">

<?php foreach($items as $d): ?>

<div class="card">


<span class="badge">
    <?=e($d['categoria'])?>
</span>

<?php if($d['status']==='encerrada'): ?>

    <span class="badge">
        Demanda encerrada
    </span>

<?php endif; ?>

<h2>
    <?=e($d['titulo'])?>
</h2>

<p>
    <?=e($d['descricao'])?>
</p>

<p>
    <b>ONG:</b>
    <?=e($d['nome_fantasia'])?>
</p>

<p>
    <b>Cidade:</b>
    <?=e($d['cidade'])?>
</p>

<p>
    <b>Prazo:</b>
    <?=e($d['prazo'] ?: 'Não informado')?>
</p>

<?php if($usuario && $usuario['tipo']==='ong'): ?>

    <a
        class="btn small"
        href="<?=url('demanda',['id'=>$d['id']])?>"
    >
        Ver demanda
    </a>

    <a
        class="btn small outline"
        href="<?=url('editar-demanda',['id'=>$d['id']])?>"
    >
        Editar
    </a>

    <a
        class="btn small outline"
        href="<?=url('interessados',['id'=>$d['id']])?>"
    >
        Interessados
    </a>

<?php else: ?>

    <a
        class="btn small"
        href="<?=url('demanda',['id'=>$d['id']])?>"
    >
        Ver demanda
    </a>

<?php endif; ?>


</div>

<?php endforeach; ?>

</div>

<?php else: ?>

<div class="card">


<?php if($usuario && $usuario['tipo']==='ong'): ?>

    <h2>Nenhuma demanda cadastrada.</h2>

    <p>
        Você ainda não possui demandas cadastradas
        ou nenhuma demanda corresponde aos filtros informados.
    </p>

    <a class="btn" href="<?=url('nova-demanda')?>">
        Publicar nova demanda
    </a>

<?php else: ?>

    <h2>Nenhuma demanda encontrada.</h2>

    <p>
        Não encontramos demandas com os filtros informados.
    </p>

<?php endif; ?>


</div>

<?php endif; ?>

<?php require __DIR__.'/../includes/footer.php'; ?>
