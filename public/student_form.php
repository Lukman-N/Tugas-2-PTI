<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/app.php';
require_auth();

$rawId = array_key_exists('id', $_POST) ? post_string('id') : ($_GET['id'] ?? null);
$id = $rawId === null ? null : filter_var($rawId, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$editing = $rawId !== null;
$values = ['nim' => '', 'nama' => '', 'program_studi' => '', 'email' => '', 'telepon' => ''];
$errors = [];
$databaseReady = true;

if ($editing && $id === false) {
    http_response_code(400);
    $errors[] = t('invalid_id');
    $editing = false;
    $id = null;
}

if (is_post()) {
    if (!csrf_is_valid($_POST['_csrf'] ?? null)) {
        $errors[] = t('csrf_error');
    }
    foreach ($values as $field => $_) {
        $values[$field] = trim(post_string($field));
    }

    if (!preg_match('/^[A-Za-z0-9._-]{5,20}$/D', $values['nim'])) {
        $errors[] = t('invalid_nim');
    }
    if ($values['nama'] === '' || strlen($values['nama']) > 100) {
        $errors[] = t('invalid_name');
    }
    if ($values['program_studi'] === '' || strlen($values['program_studi']) > 100) {
        $errors[] = t('invalid_program');
    }
    if (!filter_var($values['email'], FILTER_VALIDATE_EMAIL) || strlen($values['email']) > 150) {
        $errors[] = t('invalid_email');
    }
    if ($values['telepon'] !== '' && !preg_match('/^[0-9+()\-\s]{7,20}$/D', $values['telepon'])) {
        $errors[] = t('invalid_phone');
    }

    if ($errors === []) {
        try {
            $pdo = db();
            if ($editing && is_int($id)) {
                $statement = $pdo->prepare(
                    'UPDATE students SET nim=:nim, nama=:nama, program_studi=:program_studi, email=:email, telepon=:telepon WHERE id=:id'
                );
                $statement->execute($values + ['id' => $id]);
                if ($statement->rowCount() === 0) {
                    $exists = $pdo->prepare('SELECT id FROM students WHERE id=:id');
                    $exists->execute(['id' => $id]);
                    if (!$exists->fetch()) {
                        $errors[] = t('not_found');
                    }
                }
            } else {
                $statement = $pdo->prepare(
                    'INSERT INTO students (nim, nama, program_studi, email, telepon) VALUES (:nim, :nama, :program_studi, :email, :telepon)'
                );
                $statement->execute($values);
            }
            if ($errors === []) {
                set_flash('success', t('saved'));
                redirect('index.php');
            }
        } catch (PDOException $exception) {
            if ($exception->getCode() === '23000') {
                $errors[] = t('duplicate_nim');
            } else {
                $databaseReady = false;
                $errors[] = t('db_error');
            }
        }
    }
} elseif ($editing && is_int($id)) {
    try {
        $statement = db()->prepare('SELECT nim, nama, program_studi, email, telepon FROM students WHERE id=:id');
        $statement->execute(['id' => $id]);
        $record = $statement->fetch();
        if (!$record) {
            http_response_code(404);
            $errors[] = t('not_found');
        } else {
            $values = $record;
        }
    } catch (PDOException $exception) {
        $databaseReady = false;
        $errors[] = t('db_error');
    }
}

$pageTitle = t($editing ? 'form_edit_title' : 'form_new_title');
require __DIR__ . '/../includes/header.php';
?>
<section class="page-heading compact-heading">
  <div><p class="eyebrow"><a href="index.php"><?= h(t('dashboard')) ?></a> / <?= h($pageTitle) ?></p><h1><?= h($pageTitle) ?></h1><p class="muted"><?= h(t('form_hint')) ?></p></div>
</section>
<?php foreach ($errors as $error): ?><div class="alert alert-error" role="alert"><?= h($error) ?></div><?php endforeach; ?>
<?php if (!$databaseReady): ?><div class="alert alert-info"><?= h(t('db_error')) ?></div><?php endif; ?>
<section class="panel form-panel">
  <form action="student_form.php<?= $editing && is_int($id) ? '?id=' . (int) $id : '' ?>" method="post" class="student-form">
    <input type="hidden" name="_csrf" value="<?= h(csrf_token()) ?>">
    <?php if ($editing && is_int($id)): ?><input type="hidden" name="id" value="<?= (int) $id ?>"><?php endif; ?>
    <div class="form-grid">
      <label><?= h(t('nim')) ?><span class="field-note"><?= h(t('required')) ?></span><input type="text" name="nim" value="<?= h($values['nim']) ?>" required maxlength="20" pattern="[A-Za-z0-9._-]{5,20}" autocomplete="off"></label>
      <label><?= h(t('name')) ?><span class="field-note"><?= h(t('required')) ?></span><input type="text" name="nama" value="<?= h($values['nama']) ?>" required maxlength="100" autocomplete="name"></label>
      <label><?= h(t('program')) ?><span class="field-note"><?= h(t('required')) ?></span><input type="text" name="program_studi" value="<?= h($values['program_studi']) ?>" required maxlength="100"></label>
      <label><?= h(t('email')) ?><span class="field-note"><?= h(t('required')) ?></span><input type="email" name="email" value="<?= h($values['email']) ?>" required maxlength="150" autocomplete="email"></label>
      <label><?= h(t('phone')) ?><span class="field-note"><?= h(t('optional')) ?></span><input type="tel" name="telepon" value="<?= h($values['telepon']) ?>" maxlength="20" autocomplete="tel"></label>
    </div>
    <div class="form-actions"><a class="button button-quiet" href="index.php"><?= h(t('cancel')) ?></a><button class="button button-primary" type="submit"><?= h(t('save')) ?></button></div>
  </form>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
