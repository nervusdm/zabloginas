# 🔐 ZabLoginAs - Module PrestaShop


Un module PrestaShop sécurisé qui permet aux administrateurs de se connecter en tant que client via un système de tokens temporaires. Faites en ce que vous voulez ;)

## ✨ Fonctionnalités

- 🛡️ **Connexion sécurisée** : Utilisation de tokens cryptographiques à usage unique
- ⏱️ **Expiration automatique** : Les tokens expirent automatiquement après 60 secondes
- 📝 **Traçabilité** : Enregistrement de l'employé ayant généré le token
- 🎯 **Interface simple** : Bouton directement intégré dans la fiche client admin
- 🔒 **Sécurité renforcée** : Vérifications multiples des permissions et validité

## 📋 Prérequis

- PrestaShop 1.7 ou supérieur
- PHP 7.4 ou supérieur (adapté à votre PS)
- Extension PHP `random_bytes` (généralement incluse)

## 🚀 Installation

1. Téléchargez le module ou clonez ce dépôt
2. Envoyez le dossier compressé (zip) dans prestashop / module / installer
3. Le module n'a pas de configuration particulière. Vous pouvez gérer les droits de vos employés via l'interface usuelle de Prestashop

## 📖 Utilisation

1. Rendez-vous dans **Clients > Clients** dans votre back-office
2. Ouvrez la fiche d'un client
3. Dans la section "Se connecter comme ce client" (en bas de page), cliquez sur le bouton **Se connecter sur ce client**
4. Vous serez automatiquement connecté en tant que ce client sur le front-office

## 🏗️ Architecture technique

### Structure des fichiers

```
zabloginas/
├── zabloginas.php                          # Classe principale du module
├── controllers/
│   ├── admin/
│   │   └── AdminZabLoginAsController.php   # Contrôleur admin (génération token)
│   └── front/
│       └── loginToken.php                  # Contrôleur front (traitement token)
└── README.md
```

### Base de données

Le module crée une table `zablogin_tokens` avec la structure suivante :

| Champ         | Type           | Description                           |
|---------------|----------------|---------------------------------------|
| `id_token`    | INT UNSIGNED   | Identifiant unique du token          |
| `id_customer` | INT UNSIGNED   | ID du client concerné                 |
| `token`       | VARCHAR(128)   | Token sécurisé généré                 |
| `id_employee` | INT UNSIGNED   | ID de l'employé ayant généré le token |
| `expire_at`   | DATETIME       | Date d'expiration du token            |
| `used`        | TINYINT(1)     | Statut d'utilisation (0/1)            |

## 🔒 Sécurité

- **Tokens cryptographiques** : Génération via `random_bytes(32)` (64 caractères hexadécimaux)
- **Usage unique** : Chaque token ne peut être utilisé qu'une seule fois
- **Expiration rapide** : Les tokens expirent après 60 secondes
- **Vérifications multiples** : Contrôle des permissions admin, validité du client, etc.
- **Traçabilité** : Enregistrement de l'employé ayant initié la connexion

## 🛠️ Développement

### Hooks utilisés

- `displayAdminCustomers` : Affiche le bouton de connexion dans la fiche client

### Méthodes principales

- `install()` : Installation du module et création de la table
- `hookDisplayAdminCustomers()` : Affichage du bouton dans l'admin
- `AdminZabLoginAsController::initContent()` : Génération du token sécurisé
- `ZabloginasLoginTokenModuleFrontController::init()` : Traitement et validation du token

## 🤝 Contribution

Les contributions sont les bienvenues ! N'hésitez pas à :

1. 🍴 Fork le projet
2. 🌟 Créer une branche pour votre fonctionnalité (`git checkout -b feature/AmazingFeature`)
3. 💾 Commiter vos changements (`git commit -m 'Add some AmazingFeature'`)
4. 📤 Pusher vers la branche (`git push origin feature/AmazingFeature`)
5. 🔀 Ouvrir une Pull Request

## 📝 Changelog

### Version 1.1.0
- Amélioration de la sécurité avec tokens à usage unique
- Ajout de la traçabilité des connexions
- Interface utilisateur améliorée

## 📄 Licence

Ce projet est sous licence MIT -

## 👨‍💻 Auteur


## ⚠️ Avertissement

Ce module permet aux administrateurs de se connecter en tant que clients. Assurez-vous que seuls les administrateurs de confiance ont accès à cette fonctionnalité et utilisez-la uniquement à des fins de support client ou de débogage.

---

⭐ Si ce module vous a été utile, n'hésitez pas à lui donner une étoile sur GitHub !