<?php

class DonationController extends AppController {
    public function beforeFilter()
    {
        parent::beforeFilter();

        $this->loadModel('Donation.Donation');
        $donation = $this->Donation->find('first');
        if (empty($donation)){
            $this->Donation->saveAll([
                'objectif' => 100,
                'total' => 0,
                'description' => $this->Lang->get('INDEX_DESCRIPTION'),
            ]);
        }
    }

    public function noConnectError() {
        $this->set('messageTitle', "Vous n'êtes pas connecté");
        $this->set('messageHTML', '<strong>Vous devez être connecté pour pouvoir faire un don !</strong>');
        $this->render('Errors/mineweb_custom_message');
    }

    public function index() {
        $this->set('title_for_layout', 'Donation');
        $this->loadModel('Donation.Donation');
        $donation = $this->Donation->find('first');
        $this->set(compact('donation'));
    }
    
    public function canceled() {
        $this->set('title_for_layout', 'Donation');
        $this->loadModel('Donation.Donation');
        $donation = $this->Donation->find('first');
        $this->set(compact('donation'));
    }
    
    public function return() {
        $this->set('title_for_layout', 'Donation');
        $this->loadModel('Donation.Donation');
        $donation = $this->Donation->find('first');
        $this->set(compact('donation'));
    }

    public function admin_index() {
        if($this->isConnected AND $this->User->isAdmin()){
            $this->loadModel('Donation.Donation');
            $donation = $this->Donation->find('first');
            $this->set(compact('donation'));

            $this->layout = 'admin';
        }else {
            $this->redirect('/');
        }
    }

    function admin_ajax_edit_goal() {
        if (!$this->Permissions->can('DONATION_ADMIN'))
            throw new ForbiddenException();
        if (!$this->request->is('post'))
            throw new BadRequestException();
        if (empty($this->request->data['objectif']))
            return $this->sendJSON(['statut' => false, 'msg' => $this->Lang->get('DONATION_EDIT_NULL')]);
            $obj = $this->request->data['objectif'];
        if ($this->request->data['objectif'] <= 0)
            return $this->sendJSON(['statut' => false, 'msg' => $this->Lang->get('DONATION_EDIT_ZERO')]);    
        
        $this->loadModel('Donation.Donation');
        $donation = $this->Donation->find('first');
        if (empty($donation))
            throw new ForbiddenException();

        $this->Donation->updateAll(
            array('objectif' => $obj),
            array('id' => $donation['Donation']['id'])
        );

        $this->sendJSON(['statut' => true, 'msg' => $this->Lang->get('DONATION_TAB_EDIT_SUCCESS', ['{NAME}' => $obj])]);
    }

    function admin_ajax_edit_email() {
        if (!$this->Permissions->can('DONATION_ADMIN'))
            throw new ForbiddenException();
        if (!$this->request->is('post'))
            throw new BadRequestException();
        if (empty($this->request->data['emailDon']))
            return $this->sendJSON(['statut' => false, 'msg' => $this->Lang->get('DONATION_EDIT_NULL')]);
        $mail = $this->request->data['emailDon'];
        
        $this->loadModel('Donation.Donation');
        $donation = $this->Donation->find('first');
        if (empty($donation))
            throw new NotFoundException();
        
        $this->Donation->read(null, $donation['Donation']['id']);
        $this->Donation->set(array('email' =>  $this->request->data['emailDon'],));
        $this->Donation->save();

        $this->sendJSON(['statut' => true, 'msg' => $this->Lang->get('DONATION_EMAIL_EDIT_SUCCESS', ['{EMAIL}' => $mail])]);
    }
    
    function admin_ajax_edit_description() {
        if (!$this->Permissions->can('DONATION_ADMIN'))
            throw new ForbiddenException();
        if (!$this->request->is('post'))
            throw new BadRequestException();
        if (empty($this->request->data['descriptionDon']))
            return $this->sendJSON(['statut' => false, 'msg' => $this->Lang->get('DONATION_EDIT_NULL')]);
        $description = $this->request->data['descriptionDon'];
        
        $this->loadModel('Donation.Donation');
        $donation = $this->Donation->find('first');
        if (empty($donation))
            throw new NotFoundException();

        $this->Donation->read(null, $donation['Donation']['id']);
        $this->Donation->set(array('description' =>  $this->request->data['descriptionDon'],));
        $this->Donation->save();

        $this->sendJSON(['statut' => true, 'msg' => $this->Lang->get('DONATION_DESCRIPTION_EDIT_SUCCESS')]);
    }

    function admin_ajax_reset() {
        if (!$this->Permissions->can('DONATION_ADMIN'))
            throw new ForbiddenException();
        if (!$this->request->is('post'))
            throw new BadRequestException();
        
        $this->loadModel('Donation.Donation');
        $donation = $this->Donation->find('first');
        if (empty($donation))
            throw new NotFoundException();

        $this->loadModel('Donation.Donation');
        $this->Donation->read(null, $donation['Donation']['id']);
        $this->Donation->set(array(
            'objectif' => 100,
            'total' => 0,
            'description' => $this->Lang->get('INDEX_DESCRIPTION'),
        ));
        $this->Donation->save();
        $this->sendJSON(['statut' => true, 'msg' => $this->Lang->get('DONATION_RESET_SUCCESS')]);
    }

    // Inspiré du plugin Shop
    public function ipn() { // cf. https://developer.paypal.com/docs/classic/ipn/gs_IPN/
        $this->autoRender = false;
        if ($this->request->is('post')) { //On vérifie l'état de la requête

            // On assigne les variables
            $item_name = $this->request->data['item_name'];
            $item_number = $this->request->data['item_number'];
            $payment_status = strtoupper($this->request->data['payment_status']);
            $payment_amount = $this->request->data['mc_gross'];
            $payment_currency = $this->request->data['mc_currency'];
            $txn_id = $this->request->data['txn_id'];
            $receiver_email = $this->request->data['receiver_email'];
            $payer_email = $this->request->data['payer_email'];
            $user_id = $this->request->data['custom'];

            // On vérifie que l'utilisateur contenu dans le champ custom existe bien
            $this->loadModel('User');
            if (!$this->User->exist($user_id)) {
                throw new InternalErrorException('PayPal : Unknown user');
            }

            // On prépare la requête de vérification
            $IPN = 'cmd=_notify-validate';
            foreach ($this->request->data as $key => $value) {
                $value = urlencode($value);
                $IPN .= "&$key=$value";
            }

            // On fais la requête
            $cURL = curl_init();
            curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($cURL, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($cURL, CURLOPT_URL, "https://www.paypal.com/cgi-bin/webscr");
            curl_setopt($cURL, CURLOPT_ENCODING, 'gzip');
            curl_setopt($cURL, CURLOPT_BINARYTRANSFER, true);
            curl_setopt($cURL, CURLOPT_POST, true); // POST back
            curl_setopt($cURL, CURLOPT_POSTFIELDS, $IPN); // the $IPN
            curl_setopt($cURL, CURLOPT_HEADER, false);
            curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cURL, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
            curl_setopt($cURL, CURLOPT_FORBID_REUSE, true);
            curl_setopt($cURL, CURLOPT_FRESH_CONNECT, true);
            curl_setopt($cURL, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($cURL, CURLOPT_TIMEOUT, 60);
            curl_setopt($cURL, CURLINFO_HEADER_OUT, true);
            curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
                'Connection: close',
                'Expect: ',
            ));
            $Response = curl_exec($cURL);
            $Status = (int)curl_getinfo($cURL, CURLINFO_HTTP_CODE);
            curl_close($cURL);

            // On traite la réponse :

            // On vérifie que il y ai pas eu d'erreur
            if (empty($Response) || $Status != 200 || !$Status) {
                throw new InternalErrorException('PayPal : Error with PayPal Response');
            }

            // On vérifie que la paiement est vérifié
            if (!preg_match('~^(VERIFIED)$~i', trim($Response))) {
                throw new InternalErrorException('PayPal : Paiement not verified');
            }

            // On effectue les autres vérifications
            if ($payment_status == "COMPLETED") { //Le paiment est complété

                if ($payment_currency == "EUR") { //Le paiement est bien en euros

                    // On vérifie que le paiement pas déjà en base de données
                    $this->loadModel('Donation.PaypalHistory');
                    $findPayment = $this->PaypalHistory->find('first', array('conditions' => array('payment_id' => $txn_id)));

                    if (empty($findPayment)) {

                        // On l'ajoute dans l'historique des paiements
                        $this->PaypalHistory->set(array(
                            'payment_id' => $txn_id,
                            'user_id' => $user_id,
                            'offer_id' => $this->request->data['idOffer'],
                            'payment_amount' => $payment_amount,
                            'item' => $clear_name,
                        ));
                        $this->PaypalHistory->save();

                        $id = 0;
                        $current = $this->Donation->find('first', array('conditions' => array('total' => $id)));
                        $total = $current + $payment_amount;
                        
                        // On update le total des paiements
                        $this->Donation->updateAll(
                            array('total' => $total),
                            array('id' => $id)
                        );
                        $this->Donation->save();

                        //Envoie de notification
                        $this->loadModel('Notification');
                        $this->Notification->setToUser('Merci pour votre donation ! ❤️', $this->User->getKey('pseudo'));

                    } else
                        throw new InternalErrorException('PayPal : Payment already credited');
                    return $this->response->statusCode(200);

                } else {
                    throw new InternalErrorException('PayPal : Bad currency');
                }

            } else {
                throw new InternalErrorException('PayPal : Paiement not completed');
            }

        } else {
            throw new InternalErrorException('PayPal : Not post');
        }
    }
}
