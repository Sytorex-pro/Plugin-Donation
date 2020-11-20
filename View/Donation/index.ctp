<?= $this->Html->css('Donation.donation-style.css?'.rand(1, 1000000))?>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<?php
    $objectif = $donations[0]['Donation']['objectif'];
    $total = $donations[0]['Donation']['total'];
    $current = (round($total / $objectif * 100, 1));
?>

<style type="text/css">
        .w3-xlarge .w3-center{
            width:<?= ($total <= $objectif) ? $current : 100 ?>%;
        }
</style>

<div class="container-donation white">
    <div class="firstbox-donation">
        <h1 class="fad-title text-center">Faire un don</h1>
        <br />
        <div class="index-description">
            <p><?= (!empty($donations[0]['Donation']['description'])) ? nl2br($donations[0]['Donation']['description']) : $Lang->get('INDEX_DESCRIPTION') ?></p>
        </div>
        <br />
        <?php if(!empty($donations[0]['Donation']['email']) || !empty($donations[0]['Donation']['id']) || !empty($total) || !empty($objectif)) { ?>
            <blockquote class="bq-donation">
                <?= $Lang->get('OBJECTIF_DONATION') ?><?= $objectif ?>€<br>
                <?= $Lang->get('TOTAL_DONATION') ?><?= $total ?>€<br>
                <?= $Lang->get('POURCENTAGE_DONATION') ?><?= $current ?>%<br>
            </blockquote>
            <br />
                <div class="w3-light-grey w3-xlarge">
                    <div class="w3-container
                        <?php        if($current == 0)  { ?>w3-light-grey 
                        <?php } else if($current <= 20) { ?>w3-red 
                        <?php } else if($current <= 40) { ?>w3-orange 
                        <?php } else if($current <= 60) { ?>w3-yellow 
                        <?php } else if($current <= 80) { ?>w3-green 
                        <?php } else if($current < 100) { ?>w3-aqua 
                        <?php } else if($current >= 100) { ?>w3-blue 
                        <?php } ?>w3-center"><?= $current ?>%
                    </div>
                </div>
            <br />
            <div class="donation-login">
                <?php if($isConnected): ?>
                    <input type="number" id="input-amount-paypal" min="1" name="continuer-visible" step="1" class="form-control continue-box" placeholder="<?= $Lang->get('VALIDE_EUR') ?>" onchange="numberController('input-amount-paypal', 'output-amount-paypal', 'output-value-amount-paypal')">
                    <br />
                    <button type="button" id="button-paypal" class="fud-yes text-center btn" data-toggle="modal" data-target="#confirmation"><?= $Lang->get('MAKE_DONATION') ?></button>
                    <br />
                <?php else: ?>
                    <a type="button" href="#login" class="fud-no text-center"><?= $Lang->get('MAKE_DONATION_NO_CONNECTED') ?></a>
                    <br />
                <?php endif; ?>
            </div>

            <!-- -- Modal de confimation du paiement de la donnation  -- -->
            <div class="modal fade" id="confirmation" tabindex="-1" role="dialog" aria-labelledby="Confirmation-of-donation" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="Confirmation-of-donation">Confirmation du paiement</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <p>Cliquer sur le bouton pour payer et valider votre don</p>
                            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" class="form-horizontal">
                                <input name="currency_code" type="hidden" value="EUR" />
                                <input name="shipping" type="hidden" value="0.00" />
                                <input name="tax" type="hidden" value="0.00" />
                                <input name="return" type="hidden" value="<?= $this->Html->url(array('controller' => 'donnation', 'action' => 'index', 'return'), true) ?>" />
                                <input name="cancel_return" type="hidden" value="<?= $this->Html->url(array('controller' => 'donnation', 'action' => 'index', 'error'), true) ?>" />
                                <input name="notify_url" type="hidden" value="<?= $this->Html->url(array('controller' => 'donnation', 'action' => 'ipn'), true) ?>" />
                                <input name="cmd" type="hidden" value="_xclick" />
                                <input name="business" id="mail_paypal" type="hidden" value="<?= $donations[0]['Donation']['email'] ?>" />
                                <input name="item_name" type="hidden" id="output-value-item_name-paypal" />
                                <input name="no_note" type="hidden" value="1" />
                                <input name="lc" type="hidden" value="FR" />
                                <input name="custom" type="hidden" value="<?= $user['id'] ?>" />
                                <input name="bn" type="hidden" value="PP-BuyNowBF" />
                                <input type="hidden" name="cbt" value="<?= $Lang->get('SHOP__PAYPAL_RETURN_MSG', array('{WEBSITE_NAME}' => $website_name)) ?>" />
                                <input type="hidden" name="charset" value="UTF-8" />
                                <input type="hidden" name="amount" id="output-value-amount-paypal" />
                                <button type="submit" name="submit" value="paypal" class="btn btn-primary btn-block" data-toggle="modal" data-target="#step2" data-dismiss="modal">Payer <span id="output-amount-paypal"></span>€ avec Paypal <i class="fab fa-paypal" aria-hidden="true"></i></button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Fermé</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <blockquote class="bq-donation">
                <?= $Lang->get('OBJECTIF_DONATION') ?>NaN€<br>
                <?= $Lang->get('TOTAL_DONATION') ?>NaN€<br>
                <?= $Lang->get('POURCENTAGE_DONATION') ?>NaN%<br>
            </blockquote>
            <br />
            <div class="w3-light-grey w3-xlarge">
                <div class="w3-container w3-white w3-center">NaN%</div>
            </div>
            <br />
            <div class="donation-login">
                <button type="button" class="fud-no text-center" disabled><?= $Lang->get('INDEX_DONATION_INVALID') ?></button>
                <br />
            </div>
        <?php } ?>
    </div>
</div>

<script>
    function numberController(inputId, outputInner, outputValue) {
        var get = document.getElementById(inputId).value;
        document.getElementById(outputInner).innerHTML = get;
        document.getElementById(outputValue).value = get;
        document.getElementById("output-value-item_name-paypal").value = "Don de <?= $user['pseudo']?> (" + get + " €)"
    }

    var x = setInterval(function() {
        if (document.getElementById('input-amount-paypal').value <= 0) {
            document.getElementById("button-paypal").disabled = true;
        } else {
            document.getElementById("button-paypal").disabled = false;
        }
    }, 100);
</script>
<br />