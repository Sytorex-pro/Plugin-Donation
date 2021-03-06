<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= $Lang->get('DONATION_ADMIN_SETTINGS') ?></h3>
                </div>
                <div class="box-body">
                    <form action="<?= $this->Html->url(array('controller' => 'Donation', 'action' => 'admin_ajax_edit_tab')) ?>" method="post" data-ajax="true">
                        <div class="ajax-msg"></div>
                        <div class="form-group">
                            <input type="hidden" class="form-control" name="idDonation" value="<?= $donations[0]['Donation']['id'] ?>">
                            <input type="number" id="number_objectif<?= $donations[0]['Donation']['id'] ?>" class="form-control" value="<?= $donations[0]['Donation']['objectif'] ?>" onchange="numberController('number_objectif<?= $donations[0]['Donation']['id']?>', 'put-objectif<?= $donations[0]['Donation']['id']?>')">
                            <input type="hidden" id="put-objectif<?= $donations[0]['Donation']['id'] ?>" class="form-control" name="objectif">
                        </div>
                        <button type="submit" class="btn btn-primary"><?= $Lang->get('DONATION_EDIT_SETTINGS') ?></button>
                    </form>
                </div>
            </div>
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= $Lang->get('DONATION_ADMIN_SETTINGS_EMAIL') ?></h3>
                </div>
                <div class="box-body">
                    <form action="<?= $this->Html->url(array('controller' => 'Donation', 'action' => 'admin_ajax_edit_email')) ?>" method="post" data-ajax="true">
                        <div class="ajax-msg"></div>
                        <div class="form-group">
                            <input type="hidden" class="form-control" name="idDon" value="<?= $donations[0]['Donation']['id'] ?>">
                            <input type="email" name="emailDon" class="form-control" value="<?= $donations[0]['Donation']['email'] ?>">
                        </div>
                        <button type="submit" class="btn btn-primary"><?= $Lang->get('DONATION_EDIT_SETTINGS') ?></button>
                    </form>
                </div>
            </div>
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= $Lang->get('DONATION_ADMIN_SETTINGS_DESCRIPTION') ?></h3>
                </div>
                <div class="box-body">
                    <form action="<?= $this->Html->url(array('controller' => 'Donation', 'action' => 'admin_ajax_edit_description')) ?>" method="post" data-ajax="true">
                        <div class="ajax-msg"></div>
                        <div class="form-group">
                            <input type="hidden" class="form-control" name="idDon" value="<?= $donations[0]['Donation']['id'] ?>">
                            <textarea id="editor input" class="form-control white white-input" name="descriptionDon"
                                value="<?= (!empty($donations[0]['Donation']['description'])) ? nl2br($donations[0]['Donation']['description']) : $Lang->get('INDEX_DESCRIPTION') ?>"
                                cols="30" rows="10">
                            </textarea>
                        </div>
                        <button type="submit" class="btn btn-primary"><?= $Lang->get('DONATION_EDIT_SETTINGS') ?></button>
                    </form>
                </div>
            </div>
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= $Lang->get('DONATION_ADMIN_RENITIALIZE') ?></h3>
                </div>
                <div class="box-body">
                    <form action="<?= $this->Html->url(array('controller' => 'Donation', 'action' => 'admin_ajax_reset')) ?>" method="post" data-ajax="true">
                        <input type="hidden" class="form-control" name="idDon" value="<?= $donations[0]['Donation']['id'] ?>">
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