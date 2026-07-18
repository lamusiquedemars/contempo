# Deploiement LWS - Contempo Luthiers

Objectif: installer Maracuja CMS sur un sous-domaine sans toucher au site public existant.

## Sous-domaine

Sous-domaine conseille:

```txt
cms.contempoluthiers.fr
```

Le document root du sous-domaine doit pointer vers le dossier `public` de Laravel:

```txt
/htdocs/cms.contempoluthiers.fr/public
```

Si LWS ne permet pas de pointer directement vers `public`, installer le projet hors du dossier public web et faire pointer le sous-domaine vers son sous-dossier `public`.

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
php artisan storage:link
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Si `composer` n'est pas disponible sur l'hebergement, uploader le dossier `vendor` prepare localement.

## Points email

Avant reprise:

- demander le deblocage de `atelier@contempoluthiers.fr` a LWS;
- signaler a LWS l'erreur Microsoft `S3150` sur l'IP `193.203.239.122`;
- exclure les hard bounces;
- exclure temporairement Hotmail, Outlook, Live et MSN si LWS n'a pas resolu la reputation Microsoft;
- verifier qu'aucun email ne contient `contempoluthiers.local`.
