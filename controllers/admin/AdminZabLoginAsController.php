<?php
class AdminZabLoginAsController extends ModuleAdminController
{
    /**
     * Constructeur du contrôleur admin
     */
    public function __construct()
    {
        parent::__construct();
        $this->bootstrap = true;
    }
    
    /**
     * Génère un token sécurisé et redirige vers la page de connexion front
     * Vérifie les permissions admin et la validité du client
     */
    public function initContent()
    {
        // Vérification des permissions admin
        if (!$this->context->employee->id || !$this->context->employee->isLoggedBack()) {
            die('Accès refusé.');
        }

        $id_customer = (int)Tools::getValue('id_customer');
        if ($id_customer <= 0) {
            die('Client invalide.');
        }

        $customer = new Customer($id_customer);
        if (!Validate::isLoadedObject($customer)) {
            die('Client introuvable.');
        }

        // Génération du token sécurisé (expire dans 60 secondes)
        $token = bin2hex(random_bytes(32));
        $expire = date('Y-m-d H:i:s', time() + 60);
        $id_employee = (int)$this->context->employee->id;

        // Sauvegarde du token en base
        Db::getInstance()->insert('zablogin_tokens', [
            'id_customer'  => (int)$customer->id,
            'token'        => pSQL($token),
            'id_employee'  => $id_employee,
            'expire_at'    => pSQL($expire),
            'used'         => 0
        ]);

        // Redirection vers le contrôleur front avec le token
        $url = $this->context->link->getModuleLink('zabloginas', 'loginToken', ['zab_token' => $token], true);
        Tools::redirect($url);
    }
}
