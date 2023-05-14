<?php require_once __DIR__ . '/../header.php'; ?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
</div>

<!--Section: Contact v.2-->
<section class="mb-4">

    <div class="row">

        <!--Grid column-->
        <div class="col-md-9 mb-md-0 mb-5">
            <form id="contact-form" name="contact-form" action="/admin/faq/add" method="post">

                <!--Grid row-->
                <div class="row">

                    <!--Grid column-->
                    <div class="col-md-12 form-group">
                        <input type="text" id="name" name="question" class="form-control" placeholder="Question">
                    </div>
                    <!--Grid column-->

                </div>
                <!--Grid row-->

                <!--Grid row-->
                <div class="row">

                    <!--Grid column-->
                    <div class="col-md-12 form-group">
                        <textarea type="text" id="message" name="answer" rows="7" class="form-control md-textarea" placeholder="Answer"></textarea>
                    </div>
                </div>
                <!--Grid row-->

                <div><?= $token ?></div>

                <div class="row">
                    <div class="col-md-12 form-group">
                        <input type="submit" class="btn btn-primary btn-user btn-block">
                    </div>
                </div>
            </form>

            <div class="status"></div>
        </div>
        <!--Grid column-->

    </div>

</section>
<!--Section: Contact v.2-->

<?php require_once __DIR__ . '/../footer.php'; ?>