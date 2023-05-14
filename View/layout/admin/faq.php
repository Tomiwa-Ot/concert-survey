<?php require_once __DIR__ . '/../header.php'; ?>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-sm-flex align-items-center justify-content-between mb-4">
        <h6 class="m-0 font-weight-bold text-primary"><?= $title ?></h6>
        <a href="/admin/faq/add" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-plus fa-sm text-white-50"></i> Add Faq</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Question</th>
                        <th>Answer</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($faq as $f): ?>
                        <tr>
                            <td><?= $f->getQuestion() ?></td>
                            <td><?= $f->getAnswer() ?></td>
                            <td>
                                <a href="/admin/faq/edit?id=<?= $f->getId() ?>" class="btn btn-primary btn-circle"><i class="fas fa-pen"></i></a>
                                <a href="/admin/faq/delete?id=<?= $f->getId() ?>" class="btn btn-danger btn-circle"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../footer.php'; ?>