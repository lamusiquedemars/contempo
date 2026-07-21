# Deploiement LWS - Contempo Luthiers

Objectif: installer Maracuja CMS sur un sous-domaine sans toucher au site public existant.

## Sous-domaine

Sous-domaine conseille:

```txt
cms.contempoluthiers.fr
```

Quand l'offre le permet, le document root du sous-domaine pointe vers le
dossier `public` de Laravel:

```txt
/htdocs/cms.contempoluthiers.fr/public
```

Sur l'offre LWS actuelle, la racine du sous-domaine est imposée. Le projet est
donc uploadé dans `/htdocs/cms.contempoluthiers.fr`. Le `.htaccess` et le
`index.php` versionnés à la racine adaptent le routage pour Laravel. Le
`.htaccess` bloque aussi l'accès web à `.env`, `vendor`, `config` et aux autres
fichiers internes. Ne pas remplacer ces fichiers par des copies manuelles non
suivies dans Git.

## Variables serveur

Sur le serveur, le fichier `.env` doit contenir notamment:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://cms.contempoluthiers.fr
MARACUJA_INDEXABLE=false

MAIL_MAILER=smtp
MAIL_SCHEME=smtps
MAIL_HOST=mail.contempoluthiers.fr
MAIL_PORT=465
MAIL_FROM_ADDRESS=atelier@contempoluthiers.fr
MAIL_FROM_NAME="${APP_NAME}"
```

`MARACUJA_INDEXABLE=false` evite que le sous-domaine CMS concurrence le site public existant.

## Avant upload

En local:

```bash
npm run build
php artisan test
php artisan maracuja:doctor --production
```

Ne pas uploader:

- `.git`
- `node_modules`
- `.env` local
- fichiers de cache locaux
- fichiers temporaires

## Apres upload

Sur le serveur, executer ou verifier:

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Le dossier `public/storage` doit exister et être accessible en écriture. Le
stockage public Maracuja est direct : ne pas exécuter `php artisan storage:link`.

Si `composer` n'est pas disponible sur l'hebergement, uploader le dossier `vendor` prepare localement.

## Points email

Avant reprise:

- demander le deblocage de `atelier@contempoluthiers.fr` a LWS;
- signaler a LWS l'erreur Microsoft `S3150` sur l'IP `193.203.239.122`;
- exclure les hard bounces;
- exclure temporairement Hotmail, Outlook, Live et MSN si LWS n'a pas resolu la reputation Microsoft;
- verifier qu'aucun email ne contient `contempoluthiers.local`.
