# Ecoservice

## [Lire la documentation !](docs/0Sommaire.md)

### Sur votre navigateur :

- [ecoservice.com](http://ecoservice.com)
- [localhost:8080](http://localhost:8080)

## Environement

- Projet sur `/data/ecs/www`

- Synchro des fichiers automatique sur phpStorm ou `vagrant rsync-auto` : [Installation](docs/1Installation.md#sources)

- VSCode est déconseillé

- Dupliquez `config.yaml.dist` &rarr; `cp config.yaml.dist vm_config.yaml`

## Le flux de travail

- Je créer une branche à partir de develop identifiée par le numéro de mon ticket
- Je code ma feature
- Je met à jour ma branche pour éviter les conflits : [Mettre a jour sa branche](docs/3GitFlow.md#majbranche)
- Je créer la PR [Faire une PR](docs/3GitFlow.md#environments)
- Si je travaille encore dessus je met en premier dans le titre de ma PR `[ci skip]`
- J'envoie le lien de ma PR sur `#conversation`
- Je me fait review par les autres membres de l'équipe et la CI avant de merge

## Règles d'or
- Le code est la meilleure documentation.

- Vos variables/constantes doivent être nommées en sorte que quelqu'un ne connaissant rien en informatique puisse comprendre votre code`

## Conventions

- Suivre l'architecture :

```
- Core (code utilisé dans tout les espaces comme les mails/ connexion...)
- FrontOffice : Interface utilisateur et récupération des donnée avec API
- Admin : Pages d'administration et de gestion des contenus
```

## Tests / debug

Pour se connecter sur le back office [/admin](ecoservice.coom/admin) avec les ids
`admin-ecs@yopmail.com` / `Azerty69`

Pour les utilisateurs front office: `test-ecs@yopmail.com` `Azerty69`

##### Complétez la liste [Liste des utilisateurs de tests](docs/4Tests.md#comptes-utilisateurs)
