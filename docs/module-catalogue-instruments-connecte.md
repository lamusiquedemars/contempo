# Catalogue d’instruments connecté

## Intention

Ce module affiche sur le site une sélection d’instruments gérés dans Cremona.
Il n’est pas un second back-office métier : la fiche, l’état, le prix et la
disponibilité sont décidés dans Cremona.

## Responsabilités

| Cremona | CMS |
| --- | --- |
| Créer l’instrument, ses attributs métier, ses médias et son état | Recevoir une copie publiée, sans écriture métier |
| Décider s’il est montrable et quelles données sont publiques | Rendre la liste, les filtres et la fiche publique |
| Vente, location, atelier, stock, historique | Thème, URL, SEO et composants visuels |

## Parcours

1. L’atelier crée ou met à jour l’instrument dans Cremona.
2. Il sélectionne les informations publiques puis déclenche « Publier sur le site ».
3. Le CMS actualise sa projection locale identifiée par l’identifiant Cremona.
4. Les pages publiques lisent uniquement cette projection.
5. Un retrait de publication ou un état non publiable retire l’instrument du catalogue public sans supprimer son historique Cremona.

## Écrans CMS à construire

- page `/instruments` : cartes, famille, disponibilité publique, prix ou « sur demande » ;
- page `/instruments/{slug}` : galerie, attributs publics, texte, appel à contact ;
- écran de contrôle interne en lecture seule : dernière synchronisation et diagnostic, sans formulaire de création métier.

## Réutilisation

Le socle de réception est générique. Le présent module est l’adaptateur
« catalogue luthier » ; un autre métier réutilise le socle, mais fournit ses
propres champs, filtres et vues.
