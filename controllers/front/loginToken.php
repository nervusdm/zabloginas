<?php
class ZabloginasLoginTokenModuleFrontController extends ModuleFrontController
{
    public $ssl = true;
    public $display_column_left = false;
    public $display_column_right = false;

    /**
     * Traite le token de connexion et connecte l'utilisateur
     * Vérifie la validité du token (existence, expiration, usage unique)
     */
    public function init()
    {
        parent::init();
        $token = Tools::getValue('zab_token');
        if (!$token) {
            Tools::redirect('index.php');
        }

        // Récupération du token en base de données
        $row = Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'zablogin_tokens` WHERE `token` = \''.pSQL($token).'\' ');

        if (!$row) {
            Tools::redirect('index.php');
        }

        // Vérification de l'usage unique et de l'expiration
        if ((int)$row['used'] === 1 || strtotime($row['expire_at']) < time()) {
            Tools::redirect('index.php');
        }
        
        // Vérification de l'existence du client
        $id_customer = (int)$row['id_customer'];
        $customer = new Customer($id_customer);
        if (!Validate::isLoadedObject($customer)) {
            Tools::redirect('index.php');
        }

        // Marquer le token comme utilisé
        Db::getInstance()->update('zablogin_tokens', ['used' => 1], '`id_token`='.(int)$row['id_token']);

        // Connexion du client et redirection vers son compte
        if (Validate::isLoadedObject($customer) && $customer->id) {
			$this->context->updateCustomer($customer);

            Tools::redirect('index.php?controller=my-account');
        }
		else {	// Client invalide ou supprimé
            Tools::redirect('index.php');
		}

        Tools::redirect('index.php');
    }
}
