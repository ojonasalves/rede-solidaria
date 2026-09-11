<?php
require_role('ong');

$id=(int)($_GET['id']??0);

$s=db()->prepare(
    "SELECT d.*
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
    exit('Demanda não encontrada.');
}

if(is_post()){

    check_csrf();

    $titulo=trim($_POST['titulo']??'');
    $descricao=trim($_POST['descricao']??'');
    $categoria=trim($_POST['categoria']??'');
    $cidade=trim($_POST['cidade']??'');
    $prazo=$_POST['prazo']??null;
    $status=$_POST['status']??'';

    if(
        $titulo==='' ||
        $descricao==='' ||
        $categoria==='' ||
        $cidade==='' ||
        !in_array($status,['aberta','encerrada'],true)
    ){

        flash('error','Preencha corretamente todos os campos.');
        redirect_to(url('editar-demanda',['id'=>$id]));
    }

    db()->prepare(
        "UPDATE demandas
         SET titulo=?,descricao=?,categoria=?,cidade=?,prazo=?,status=?
         WHERE id=?"
    )->execute([
        $titulo,
        $descricao,
        $categoria,
        $cidade,
        $prazo?:null,
        $status,
        $id
    ]);

    flash('success','Demanda atualizada com sucesso.');

    redirect_to(url('demandas'));
}

$title='Editar demanda - '.APP_NAME;

require __DIR__.'/../includes/header.php';
?>

<div class="form">

    <h1>Editar demanda</h1>

    <form method="post">

        <input
            type="hidden"
            name="csrf"
            value="<?=e(csrf())?>"
        >

        <label>
            Título
            <input
                name="titulo"
                value="<?=e($d['titulo'])?>"
                required
            >
        </label>

        <label>
            Descrição
            <textarea
                name="descricao"
                rows="7"
                required
            ><?=e($d['descricao'])?></textarea>
        </label>

        <label>
            Categoria
            <input
                name="categoria"
                value="<?=e($d['categoria'])?>"
                required
            >
        </label>

        <label>
            Cidade
            <input
                name="cidade"
                value="<?=e($d['cidade'])?>"
                required
            >
        </label>

        <label>
            Prazo
            <input
                type="date"
                name="prazo"
                value="<?=e($d['prazo'])?>"
            >
        </label>

        <label>
            Status

            <select name="status">

                <option
                    value="aberta"
                    <?=$d['status']==='aberta'?'selected':''?>
                >
                    Aberta
                </option>

                <option
                    value="encerrada"
                    <?=$d['status']==='encerrada'?'selected':''?>
                >
                    Encerrada
                </option>

            </select>
        </label>

        <button class="btn">
            Salvar alterações
        </button>

    </form>

</div>

<?php require __DIR__.'/../includes/footer.php'; ?>
