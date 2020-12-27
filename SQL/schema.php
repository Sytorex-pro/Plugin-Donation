<?php
class DonationAppSchema extends CakeSchema {

    public $file = 'schema.php';

    public function before($event = array()) {
        return true;
    }

    public function after($event = array()) {}

    public $donation__donations = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'unsigned' => false, 'key' => 'primary'],
        'objectif' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'unsigned' => false],
        'total' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'unsigned' => false],
        'email' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 255, 'unsigned' => false],
        'description' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 255, 'unsigned' => false]
    ];

    public $donation__paypal_histories = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'unsigned' => false, 'key' => 'primary'],
        'payment_id' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 255, 'unsigned' => false],
        'user_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'unsigned' => false],
        'offer_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'unsigned' => false],
        'payment_amount' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 255, 'unsigned' => false],
        'item' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 255, 'unsigned' => false],
        'created' => ['type' => 'timestamp', 'null' => false, 'default' => 'CURRENT_TIMESTAMP'],
    ];
}
