# Contempo Luthiers

Site Laravel + Filament de Contempo Luthiers, construit sur le socle technique Maracuja CMS.

## Local

URL locale :

```txt
http://contempoluthiers.local
```

Commandes utiles :

```bash
composer install
npm install
npm run build
php artisan migrate --seed
php artisan test
```

Admin :

```txt
/admin
```

## Environnements

Le fichier `.env` est propre à chaque environnement et n'est pas versionné.

- local : `.env`
- serveur : `.env` sur l'hébergement
- préparation serveur locale : `.env.production`, non versionné

Le fichier `.env.example` sert uniquement de modèle sans secret.

## Production

En production, vérifier notamment :

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://contempoluthiers.fr
MARACUJA_INDEXABLE=true
```

La base distante, le SMTP et les mots de passe restent dans le `.env` du serveur.

## Déploiement

Les fichiers FTP/FileZilla ne doivent pas être versionnés. Ils sont ignorés par Git via `ftp.*.xml`.

Avant livraison ou upload :

```bash
npm run build
php artisan test
php artisan maracuja:doctor --production
```

## Notes

`Maracuja CMS` reste le nom du socle technique, comme WordPress serait le nom du moteur. Le nom public du site est `Contempo Luthiers`.
