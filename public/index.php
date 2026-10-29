<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/app.php';
require_auth();

$rawSearch = $_GET['q'] ?? '';
$search = is_string($rawSearch) ? trim($rawSearch) : '';
$students = [];
$totalStudents = 0;
$programCount = 0;
$databaseReady = true;

try {
    $pdo = db();
    $totalStudents = (int) $pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();
    $programCount = (int) $pdo->query('SELECT COUNT(DISTINCT program_studi) FROM students')->fetchColumn();
    if ($search !== '') {
        $statement = $pdo->prepare(
            'SELECT id, nim, nama, program_studi, email, telepon FROM students
             WHERE nim LIKE :nim_query OR nama LIKE :name_query OR program_studi LIKE :program_query
             ORDER BY nama ASC'
        );
        $like = '%' . $search . '%';
        $statement->execute(['nim_query' => $like, 'name_query' => $like, 'program_query' => $like]);
        $students = $statement->fetchAll();
    } else {
        $students = $pdo->query(
            'SELECT id, nim, nama, program_studi, email, telepon FROM students ORDER BY nama ASC'
        )->fetchAll();
    }
} catch (PDOException $exception) {
    $databaseReady = false;
}

$pageTitle = t('dashboard');
require __DIR__ . '/../includes/header.php';
?>
<section class="page-heading">
  <div>
    <h1><?= h(t('dashboard')) ?></h1>
    <p class="muted"><?= h(t('page_subtitle')) ?></p>
  </div>
  <a class="button button-primary" href="student_form.php"><?= h(t('add_student')) ?></a>
</section>
<?php if (!$databaseReady): ?>
  <div class="alert alert-error" role="alert"><?= h(t('db_error')) ?></div>
<?php else: ?>
  <section class="summary-grid" aria-label="<?= h(t('dashboard')) ?>">
    <div class="summary-item"><span><?= h(t('total_students')) ?></span><strong><?= number_format($totalStudents, 0, ',', '.') ?></strong></div>
    <div class="summary-item"><span><?= h(t('study_programs')) ?></span><strong><?= number_format($programCount, 0, ',', '.') ?></strong></div>

  </section>
  <section class="panel">
    <div class="panel-heading">
      <div><h2><?= h(t('student_list')) ?></h2><p><?= h(t('list_hint')) ?></p></div>
      <span class="record-count"><?= number_format(count($students), 0, ',', '.') ?> <?= h(t('student_count')) ?></span>
    </div>
    <form class="search-form" action="index.php" method="get">
      <label class="search-box"><input type="search" name="q" value="<?= h($search) ?>" placeholder="<?= h(t('search_placeholder')) ?>" aria-label="<?= h(t('search_placeholder')) ?>"></label>
      <button class="button button-secondary" type="submit"><?= h(t('search')) ?></button>
      <?php if ($search !== ''): ?><a class="button button-quiet" href="index.php"><?= h(t('reset')) ?></a><?php endif; ?>
    </form>
    <div class="table-wrap">
      <table>
        <thead><tr><th><?= h(t('nim')) ?></th><th><?= h(t('name')) ?></th><th><?= h(t('program')) ?></th><th><?= h(t('email')) ?></th><th><?= h(t('phone')) ?></th><th><?= h(t('actions')) ?></th></tr></thead>
        <tbody>
        <?php if ($students === []): ?>
          <tr><td colspan="6" class="empty-cell"><strong><?= h($search === '' ? t('empty_title') : t('no_results')) ?></strong><span><?= h($search === '' ? t('empty_desc') : '') ?></span></td></tr>
        <?php else: foreach ($students as $student): ?>
          <tr>
            <td><span class="student-id"><?= h($student['nim']) ?></span></td>
            <td><strong class="student-name"><?= h($student['nama']) ?></strong></td>
            <td><span class="program-pill"><?= h($student['program_studi']) ?></span></td>
            <td><?= h($student['email']) ?></td>
            <td><?= h($student['telepon']) ?></td>
            <td class="row-actions">
              <a class="action-link" href="student_form.php?id=<?= (int) $student['id'] ?>" aria-label="<?= h(t('edit')) ?> <?= h($student['nama']) ?>"><?= h(t('edit')) ?></a>
              <form action="student_delete.php" method="post" class="inline-form" data-confirm="<?= h(t('confirm_delete')) ?>">
                <input type="hidden" name="_csrf" value="<?= h(csrf_token()) ?>">
                <input type="hidden" name="id" value="<?= (int) $student['id'] ?>">
                <button class="action-link action-delete" type="submit"><?= h(t('delete')) ?></button>
              </form>
            </td>
          </tr>
        <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </section>
<?php endif; ?>
<?php require __DIR__ . '/../includes/footer.php'; ?>