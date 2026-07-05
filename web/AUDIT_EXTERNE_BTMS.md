# Audit externe BTMS - Gestion intelligente du trafic routier

Date: 21/06/2026  
Projet audite: `C:\xampp\htdocs\gestion_trafic`  
Reference metier: memoire Wattu Yoon Wi / systeme intelligent de surveillance et de gestion des infractions routieres urbaines base sur IoT et IA.

## Verdict executif

L application couvre correctement les objectifs fonctionnels principaux du memoire pour une demonstration web: authentification, tableau de bord, carte interactive, suivi trafic, infractions, urgences, notifications, rapports, API IoT et service IA YOLOv8 prepare.

Niveau actuel estime: 78/100 pour un prototype academique avance.  
Niveau production estime: 52/100 sans durcissement securite, journalisation, tests, schema SQL versionne et integration materielle reelle.

## Couverture des objectifs du memoire

| Objectif du memoire | Etat | Commentaire |
|---|---:|---|
| Connexion securisee reservee aux autorites | Couvert | Authentification active et migration md5 vers `password_hash`. |
| Visualisation du trafic sur carte interactive | Couvert | Page `trafic.php` avec Leaflet/OpenStreetMap et points de surveillance. |
| Consultation camera / intersection | Partiel | Panneau camera et metadonnees presents, mais pas encore de vrai flux video IP/RTSP. |
| Detection et suivi des infractions | Couvert | Table infractions, plaque, type, lieu, date, statut, amende calculee. |
| Alertes / notifications | Couvert | Notifications generees par l API IoT selon trafic, infraction, urgence. |
| Historique des donnees | Couvert | Listes et rapports existent, mais filtres/date/export restent a renforcer. |
| Priorite vehicules urgence | Couvert cote web | Gestion urgence et API prioritaire; integration RFID materielle encore a tester. |
| Ajustement des feux selon densite | Partiel | Decision `Vert prolonge`/`Automatique` cote logiciel; pas encore commande physique Arduino documentee dans l app. |
| IA YOLOv8 | Partiel | Service FastAPI `/detect` pret, modele `best.pt` present, mais pipeline camera -> detection -> API web reste a automatiser. |
| Gestion des roles | Couvert | Admin, PCR, direction/directeur avec filtrage menu et refus d URL directe. |

## Forces observees

- Le projet est coherent avec le memoire: trafic, infractions, urgences, statistiques, notifications et rapports sont separes en modules.
- La page trafic evolue dans le bon sens pour une application de supervision: carte, points de controle, details camera, KPIs et historique.
- L API `api/api_trafic.php` peut recevoir des donnees IoT/IA et creer automatiquement les evenements metier.
- Le controle d acces par role est centralise dans `includes/auth.php`, ce qui reduit le risque de pages oubliees.
- Le service IA FastAPI est isole dans `ai_model/main.py`, bonne base pour brancher Raspberry Pi / YOLOv8.

## Risques critiques et recommandations

### P0 - Securite API IoT non authentifiee

`api/api_trafic.php` accepte des insertions depuis `$_REQUEST` ou JSON sans cle API, signature, jeton appareil ni limitation d origine. N importe qui sur le reseau local pourrait creer de fausses infractions ou urgences.

Recommandation: ajouter une cle `BTMS_DEVICE_TOKEN` cote appareils, verifier un header `X-BTMS-Token`, refuser sinon en HTTP 401, et journaliser les tentatives.

### P0 - Requetes SQL non preparees

Plusieurs fichiers utilisent `mysqli_real_escape_string` et concat SQL. C est acceptable en prototype, mais fragile. Fichiers concernes: `login.php`, `api/api_trafic.php`, `parametres.php`, `ajouter_urgence.php`, `ajouter_utilisateur.php`.

Recommandation: passer aux requetes preparees `mysqli_prepare` ou PDO, surtout pour login, API, parametres et creation utilisateur.

### P1 - Absence de protection CSRF

Les formulaires de creation utilisateur, urgence et parametres n ont pas de jeton CSRF. Un utilisateur connecte pourrait etre pousse a soumettre une action non voulue.

Recommandation: generer un token en session, l inclure dans chaque formulaire POST et le verifier avant toute modification.

### P1 - Roles metier a clarifier

La base accepte `admin`, `pcr`, `direction`. L utilisateur parle aussi de `directeur`. Le code normalise `directeur` vers `direction`, mais la base ne l accepte pas directement.

Recommandation: garder `direction` en base et afficher `Directeur / Direction` dans l interface, ou modifier l enum SQL si le memoire exige explicitement `directeur`.

### P1 - Integration IA encore demonstrative

Le service `/detect` existe, mais l application PHP ne consomme pas encore automatiquement ses resultats. Le flux ideal doit etre: camera -> FastAPI YOLOv8 -> detection plaque/vehicule/urgence -> insertion via API web -> notification.

Recommandation: ajouter un script passerelle Raspberry Pi ou un endpoint PHP/Python qui envoie les detections a `api/api_trafic.php` avec token appareil.

### P1 - Donnees cartographiques statiques

Les intersections sont codees en dur dans `trafic.php`. Pour un vrai centre de supervision, elles doivent venir d une table `intersections` ou `cameras`.

Recommandation: creer tables `intersections(id, nom, latitude, longitude, statut)` et `cameras(id, intersection_id, code, flux_url, statut)`.

### P2 - UX encore inegale entre pages

`dashboard.php` et `trafic.php` sont modernes, mais `notifications.php`, `parametres.php`, `statistiques.php`, `urgence.php` gardent des tables Bootstrap simples. Pour un rendu inspire Figma/centre de controle, il faut uniformiser.

Recommandation: meme shell app partout, en-tete operationnel, cartes KPI, filtres rapides, badges de statut, actions visibles selon role.

### P2 - Tests et auditabilite absents

Il n y a pas de suite de tests, pas de table de logs, pas d historique d actions utilisateurs.

Recommandation: ajouter `logs_actions` avec utilisateur, action, cible, date, IP; puis journaliser login, acces refuse, creation utilisateur, modification parametres, reception API.

## Recommandations UI inspirees des grands systemes de gestion routiere

- Carte centrale comme surface principale, avec couches: trafic dense, infractions, urgences, cameras hors ligne.
- Panneau lateral contextuel pour l intersection selectionnee: cameras, derniere detection, vitesse moyenne, voie prioritaire, feu actif.
- Bandeau d alertes temps reel trie par criticite: urgence, accident, infraction, congestion.
- Filtres rapides: aujourd hui, 7 jours, par lieu, par type infraction, paye/non paye.
- Mode direction: lecture analytique, cartes KPI, graphiques, rapports exportables.
- Mode PCR: action terrain, alertes, infractions, urgences, carte operationnelle.
- Mode admin: utilisateurs, parametres, appareils, cameras, seuils, roles.

## Plan de mise a niveau conseille

1. Securiser l API IoT par token appareil.
2. Ajouter CSRF sur tous les formulaires POST.
3. Remplacer les SQL concatenees par des requetes preparees.
4. Creer tables `intersections`, `cameras`, `logs_actions`, `appareils_iot`.
5. Connecter `ai_model/main.py` au flux reel camera et a `api/api_trafic.php`.
6. Uniformiser UI de toutes les pages au style command center.
7. Ajouter filtres, recherche, pagination et export CSV/PDF par periode.
8. Ajouter tests de roles: admin, pcr, direction.

## Conclusion

Le projet est fidele aux objectifs du memoire et solide pour une soutenance ou une demonstration avancee. Les prochaines priorites ne sont plus d ajouter beaucoup de nouvelles pages, mais de professionnaliser: securite, integration materielle reelle, donnees dynamiques, journalisation et finition UI coherente.
