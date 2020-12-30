<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title"><?= $Lang->get('DONATION_ADMIN_SETTINGS') ?></h3>
                </div>
                <div class="card-body">
                    <form action="<?= $this->Html->url(array('controller' => 'Donation', 'action' => 'admin_ajax_edit_goal')) ?>" method="post" data-ajax="true">
                        <div class="ajax-msg"></div>
                        <div class="form-group">
                            <input type="number" id="number_objectif<?= $donation['Donation']['id'] ?>" class="form-control" value="<?= $donation['Donation']['objectif'] ?>" onchange="numberController('number_objectif<?= $donation['Donation']['id']?>', 'put-objectif<?= $donation['Donation']['id']?>')">
                            <input type="hidden" id="put-objectif<?= $donation['Donation']['id'] ?>" class="form-control" name="objectif">
                        </div>
                        <button type="submit" class="btn btn-primary"><?= $Lang->get('DONATION_EDIT_SETTINGS') ?></button>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title"><?= $Lang->get('DONATION_ADMIN_SETTINGS_EMAIL') ?></h3>
                </div>
                <div class="card-body">
                    <form action="<?= $this->Html->url(array('controller' => 'Donation', 'action' => 'admin_ajax_edit_email')) ?>" method="post" data-ajax="true">
                        <div class="ajax-msg"></div>
                        <div class="form-group">
                            <input type="email" name="emailDon" class="form-control" value="<?= $donation['Donation']['email'] ?>">
                        </div>
                        <button type="submit" class="btn btn-primary"><?= $Lang->get('DONATION_EDIT_SETTINGS') ?></button>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title"><?= $Lang->get('DONATION_ADMIN_SETTINGS_DESCRIPTION') ?></h3>
                </div>
                <div class="card-body">
                    <form action="<?= $this->Html->url(array('controller' => 'Donation', 'action' => 'admin_ajax_edit_description')) ?>" method="post" data-ajax="true">
                        <div class="ajax-msg"></div>
                        <div class="form-group">
                            <textarea id="editor input" class="form-control white white-input" name="descriptionDon" cols="30" rows="10"><?= (!empty($donation['Donation']['description'])) ? nl2br($donation['Donation']['description']) : $Lang->get('INDEX_DESCRIPTION') ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary"><?= $Lang->get('DONATION_EDIT_SETTINGS') ?></button>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title"><?= $Lang->get('DONATION_ADMIN_RENITIALIZE') ?></h3>
                </div>
                <div class="card-body">
                    <form action="<?= $this->Html->url(array('controller' => 'Donation', 'action' => 'admin_ajax_reset')) ?>" method="post" data-ajax="true">
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Voulez-vous vraiment rénitialiser ?');"> <?= $Lang->get('DONATION_RENITIALIZE') ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    function numberController(inputId, outputId) {
        var get = document.getElementById(inputId).value;
        document.getElementById(outputId).value = get;
    }
</script>
