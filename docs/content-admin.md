# Content Admin

Maracuja CMS separe les pages codees des contenus vivants. Le client ne modifie pas la structure des pages, les CTA strategiques ou les gabarits. Il gere seulement les modules prevus pour son offre.

## Modules vivants

- `Notices`: annonce courte affichee sur la home si elle est publiee et dans sa periode de visibilite.
- `News`: actualites publiees entre une date de debut et une date de fin optionnelle.
- `Gallery`: images, textes courts, alt, credit, ordre et publication.

## Annonce courte

Le module `Annonce courte` sert aux messages ponctuels: horaires d ete, fermeture exceptionnelle, information temporaire.

Champs client:

- titre court optionnel;
- message court limite;
- lien optionnel;
- debut et fin de publication;
- statut publie.

Si aucune annonce active n existe, la section ne s affiche pas.

## Actualites

Les actualites ont une fenetre de publication:

- `published_at`: debut de publication;
- `expires_at`: fin de publication;
- `is_published`: statut manuel.

Une actualite expiree est masquee sur la home, la liste publique et la page detail.

La duree proposee par defaut est configuree avec:

```env
MARACUJA_NEWS_DEFAULT_DURATION_DAYS=30
```

## Regle produit

Un contenu devient administrable quand il correspond a une responsabilite client claire. La mise en page, les titres structurants, les CTA et les variantes de composants restent dans le theme ou les templates Blade.
