# Deploiement LWS - Contempo Luthiers

Objectif : déployer Maracuja CMS sur le site public Contempo Luthiers.

## Cible de production

L’URL publique est `https://contempoluthiers.fr`.

Sur l'offre LWS actuelle, la racine réellement exposée et contenant `artisan`
est `~/htdocs`. Le projet est donc uploadé à cette racine. Le `.htaccess` et le
`index.php` versionnés à la racine adaptent le routage pour Laravel. Le
`.htaccess` bloque aussi l'accès web à `.env`, `vendor`, `config` et aux autres
fichiers internes. Ne pas remplacer ces fichiers par des copies manuelles non
suivies dans Git.

## Variables serveur

Sur le serveur, le fichier `.env` doit contenir notamment:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://contempoluthiers.fr
MARACUJA_INDEXABLE=true
MARACUJA_CREMONA_ISSUER=https://cremona.maracujadigital.fr
MARACUJA_CREMONA_ORGANIZATION_SLUG=contempo-luthiers

MAIL_MAILER=smtp
MAIL_SCHEME=smtps
MAIL_HOST=mail.contempoluthiers.fr
MAIL_PORT=465
MAIL_FROM_ADDRESS=atelier@contempoluthiers.fr
MAIL_FROM_NAME="${APP_NAME}"
```

`MARACUJA_INDEXABLE=false` evite que le sous-domaine CMS concurrence le site public existant.
Le lien « Gestion commerciale » ouvre directement l’organisation Contempo dans Cremona. Ces deux variables configurent sa destination indépendamment du bridge des formulaires.

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
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Dépendances Composer locales

Contempo utilise des packages locaux de `maracuja-site-core` pour Audience,
Brevo et Media. Ils sont résolus et copiés dans `vendor/` **en local** : la
racine `../maracuja-site-core` n'existe pas sur LWS.

Avant l'upload, exécuter localement les commandes Composer et transmettre les
dossiers préparés `vendor/maracuja-digital/site-core-audience`,
`vendor/maracuja-digital/site-core-brevo` et
`vendor/maracuja-digital/site-core-media`, ainsi que
`public/js/maracuja-site-core/`.

Ne pas lancer `composer install` ou `composer update` sur LWS pour une version
qui modifie ces packages : le serveur ne pourrait pas résoudre le dépôt local.

### Migrations de production

Toujours consulter `php artisan migrate:status` avant une livraison. Ne jamais
exécuter une migration globale si une migration plus ancienne est en attente et
ne fait pas partie de la livraison validée.

Pour la transition Audience/Brevo, exécuter uniquement :

```bash
php artisan migrate --force --path=database/migrations/2026_11_09_000000_align_audience_schema_with_site_core.php
```

Cette migration est additive : elle conserve les contacts, campagnes,
événements et réglages Brevo existants, y compris la clé API chiffrée.

Ne jamais exécuter `php artisan migrate --seed` ou `php artisan db:seed` sur la
base de production : le seeder contient uniquement des données de démarrage
locales/test et le compte démo est désactivé en environnement production.

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
