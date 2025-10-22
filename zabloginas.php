<?php
if (!defined('_PS_VERSION_')) exit;

class Zabloginas extends Module
{
    /**
     * Constructeur du module - initialise les propriétés de base
     */
    public function __construct()
    {
        $this->name = 'zabloginas';
        $this->tab = 'administration';
        $this->version = '1.1.0';
        $this->author = 'David MASSON';
        parent::__construct();

        $this->displayName = 'Login as Customer (secure)';
        $this->description = 'Permet à un admin de se connecter comme un client, via un token sécurisé.';
    }

    /**
     * Installation du module - enregistre le hook et crée la table
     * @return bool Succès de l'installation
     */
    public function install()
    {
        return parent::install()
            && $this->registerHook('displayAdminCustomers')
            && $this->installDb();
    }

    /**
     * Crée la table pour stocker les tokens de connexion
     * @return bool Succès de la création de la table
     */
    private function installDb()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'zablogin_tokens` (
            `id_token` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `id_customer` INT UNSIGNED NOT NULL,
            `token` VARCHAR(128) NOT NULL,
            `id_employee` INT UNSIGNED NOT NULL,
            `expire_at` DATETIME NOT NULL,
            `used` TINYINT(1) NOT NULL DEFAULT 0,
            PRIMARY KEY (`id_token`),
            UNIQUE KEY (`token`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8mb4;';

        return Db::getInstance()->execute($sql);
    }

    /**
     * Hook affiché sur la page du client dans l'admin
     * Ajoute un bouton pour se connecter en tant que client
     * @param array $params Paramètres du hook (contient id_customer)
     * @return string HTML du bouton de connexion
     */
    public function hookDisplayAdminCustomers($params)
    {
        $id_customer = (int)$params['id_customer'];
        $url = $this->context->link->getAdminLink('AdminZabLoginAs', true, [], [
            'id_customer' => $id_customer,
        ]);

        return '<div class="col">
        <div class="card customer-zabloginas"><div class="card-header">
        <h3><i class="material-icons">person</i> Se connecter comme ce client</h3>
        </div><div class="card-body">
        <div class="alert alert-info">
        Vous pouvez vous connecter en tant que ce client en cliquant sur le bouton ci-dessous. Un token sécurisé sera généré et utilisé pour la connexion.
        </div>
        <a class="btn btn-default" target="_blank" href="'.$url.'">
                    <i class="icon-user"></i> Se connecter sur ce client
                </a>
        </div></div></div></div>
                ';
    }
}
