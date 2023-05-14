<?php require_once __DIR__ . '/header.php'; ?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
</div>

<?php if (count($faq) > 0): ?>
    <?php foreach ($faq as $f): ?>
      <div class="card shadow mb-4">
          <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary"><?= $f->getQuestion() ?></h6>
          </div>
          <div class="card-body">
              <p><?= $f->getAnswer() ?></p>
          </div>
      </div>
    <?php endforeach ?>
<?php else: ?>
  <div class="text-center">
      <p class="lead text-gray-800 mb-5">No Faqs</p>
      <a href="/">&larr; Check back later</a>
  </div>
<?php endif ?>

<?php require_once __DIR__ . '/footer.php'; ?>