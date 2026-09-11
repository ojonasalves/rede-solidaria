<?php

require_role('ong');

$s=db()->prepare(
    "SELECT id
     FROM ongs
     WHERE usuario_id=?"
);

$s->execute([
    current_user()['id']
]);

$ong=$s->fetch();

if(!$ong){
    exit('Perfil da ONG não encontrado.');
}

/* Categorias disponíveis */
$categorias=[
    'Alimentação',
    'Roupas',
    'Educação',
    'Saúde',
    'Moradia',
    'Meio ambiente',
    'Animais',
    'Doações',
    'Voluntariado',
    'Outros'
];

if(is_post()){

    check_csrf();

    $t=trim($_POST['titulo']??'');
    $d=trim($_POST['descricao']??'');
    $c=trim($_POST['categoria']??'');
    $ci=trim($_POST['cidade']??'');
    $p=$_POST['prazo']??null;

    if(
        $t==='' ||
        $d==='' ||
        $ci==='' ||
        !in_array($c,$categorias,true)
    ){

        flash(
            'error',
            'Preencha corretamente todos os campos obrigatórios.'
        );

        redirect_to(url('nova-demanda'));
    }

    db()->prepare(
        "INSERT INTO demandas
        (ong_id,titulo,descricao,categoria,cidade,prazo,status)
        VALUES(?,?,?,?,?,?,'aberta')"
    )->execute([
        $ong['id'],
        $t,
        $d,
        $c,
        $ci,
        $p?:null
    ]);

    flash(
        'success',
        'Demanda publicada com sucesso.'
    );

    redirect_to(url('demandas'));
}

$title='Nova demanda - '.APP_NAME;

require __DIR__.'/../includes/header.php';

?>

<div class="form">

    <h1>Publicar demanda</h1>

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
                required
            >
        </label>

        <label>
            Descrição

            <textarea
                name="descricao"
                rows="7"
                required
            ></textarea>
        </label>

        <label>
            Categoria

            <select name="categoria" required>

                <option value="">
                    Selecione uma categoria
                </option>

                <?php foreach($categorias as $categoria): ?>

                    <option value="<?=e($categoria)?>">
                        <?=e($categoria)?>
                    </option>

                <?php endforeach; ?>

            </select>

        </label>

        <label>
            Cidade

            <input
                name="cidade"
                required
            >
        </label>

        <label>
            Prazo

            <input
                type="date"
                name="prazo"
            >
        </label>

        <button class="btn">
            Publicar demanda
        </button>

    </form>

</div>

<?php require __DIR__.'/../includes/footer.php'; ?>