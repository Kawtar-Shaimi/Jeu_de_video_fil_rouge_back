# Fil Rouge YouCode Année 1

#### Vu que nous sommes une ESN qui se spécialise dans la technologie PHP, nous avons opté pour la création d'un boilerplate permettant à nos futurs collaborateurs de développer des solutions digitales dans des domaines variés. En tant qu'apprenants, votre rôle est de comprendre cette architecture, respecter les conventions établies dans ce projet, et répondre aux besoins fonctionnels qui vous seront partagés.

## Objectif du Mini-Projet

Ce mini-projet vise principalement à évaluer les compétences en POO (programmation orientée objet) des apprenants.

### Étapes préliminaires

- Comprendre la structure du projet, notamment le cœur du système (core).

### Objectifs d'apprentissage

- Comprendre les bases de la programmation orientée objet.
- Concevoir et écrire des requêtes SQL efficaces qui permettent d'extraire précisément les données.
- Appliquer le principe de couplage faible.
- Développer du code réutilisable.
- Comprendre et utiliser l'injection de dépendances.
- Utiliser le design pattern Singleton dans un contexte de serveur web.

### Prérequis

1. **Connexion à la base de données**
   Pour les apprenants ayant des compétences SQL, initialisez la base de données pour établir la connexion (voir l'entrypoint `index.php`).

   ```php
   $ds = new PostgreDataSource(
       'localhost',
       5432,
       'your_database',
       'your_user',
       'your_pass'
   );

   Database::init($ds);
   // voir use Core\DataSources\*;
   ```

2. **Structure du projet**
   Les implémentations doivent se faire dans le répertoire `App/**`.

3. **Models**
   Contiennent les modèles de l'application (respecter l'encapsulation, implémenter `JsonSerializable` ou utiliser une couche `entities` qui l'implémente).

4. **Repositories**
   Classes d'accès aux données. Chaque Repository doit étendre `Repository` ou `RepositoryMutations` (prévoit `create`, `update`, `delete`). Voir `EmployeeRepository`.

5. **Services**
   Contient la logique métier, avec deux sous-dossiers : `Implementations` et `Interfaces`. Chaque service implémente son interface.

6. **Controllers**
   Doivent être placés dans `controllers/` et se terminer par `*Controller.php`. Ils doivent hériter de `Controller`.

### Routage

- **Convention RESTful** :

  - Implémenter `ResourceController` pour que les routes soient automatiquement enregistrées.

    | Méthode HTTP | Chemin              | Méthode Contrôleur |
    | ------------ | ------------------- | ------------------ |
    | GET          | /prefix/plural      | index              |
    | GET          | /prefix/plural/{id} | show               |
    | POST         | /prefix/plural      | store              |
    | PUT/PATCH    | /prefix/plural/{id} | update             |
    | DELETE       | /prefix/plural/{id} | destroy            |

- **Par annotation/attribut** :

  - Utilisez l'attribut `Route` pour spécifier le chemin, et pour chaque méthode, définissez le type HTTP et la sous-route (voir `SalaryController`).

- **Tester vos routes** :

  - Exemple : Projet dans `/www/example` → accéder via `localhost/example` ou `localhost/public/docs` pour une UI des endpoints.

  ![Api Docs Example](./public/example/1.png)

# Installation

- Cloner le dépôt : `github.com/zziane/boilerplate-php-fy1-sql`
- Copier dans le serveur Apache (`wamp64` ou `xampp`) : `c:/wamp64/www/exemple` ou `c:/xampp/htdocs/exemple`
- Lancer `composer install` pour les dépendances (ex. `doctrine/inflector`)
- Initialiser la base via le script dans `database/`
- Adapter la datasource (`MysqlDataSource` ou `PostgreDataSource`)
- Tester l'application via les endpoints générés

# Documentation

## Contrôleur

- Étendre `Core\Controller`, qui expose `json()` pour réponse CORS-friendly, et dispose de l'objet `Request`.

## Classe `Request`

Gère l'accès aux parties d'une requête HTTP : corps, fichiers, headers, paramètres GET...

### Méthodes disponibles

- `__construct()` : initialise méthode HTTP, headers, fichiers, JSON body (si applicable).
- `input(string $key, $default = null)` : valeur POST/JSON
- `file(string $key): ?array` : infos fichier envoyé
- `hasFile(string $key): bool`
- `headers(): array`
- `all(): array`
- `param(?string $key = null): mixed`
- `getMethod()` : méthode HTTP utilisée
- `relativeUrl(): string` : URL relative (utile pour router)

## Classe `Repository`

Applique le pattern Repository. Fournit une base d'accès générique aux données.

### Attributs

- `protected Database $db`
- `protected string $tableName`

### Méthodes

- `get(array $data, string $key)`
- `arrayMapper(array $data): array`
- `abstract protected function mapper(array $data): object`

## Classe `RepositoryMutations`

Étend `Repository`, fournit des méthodes génériques CRUD.

### Méthodes

- `save(array $data): int` → `INSERT INTO ...`
- `update(array $data, array $clauses): bool` → `UPDATE ...`
- `delete(array $clauses): bool` → `DELETE FROM ...`

### Exemple d'utilisation

```php
class EmployeeRepository extends RepositoryMutations
{
    public function __construct()
    {
        parent::__construct('employees');
    }

    protected function mapper(array $data): object
    {
        return new Employee($data['id'], $data['name'], $data['email']);
    }
}
```

RepositoryMutations est inspiré de `CrudRepository` de Spring Boot.

# Architecture

Le système est basé sur le principe **MVC2**, avec un **router dispatcher** qui analyse les URLs et appelle dynamiquement la méthode du contrôleur correspondante (comme un dispatcher servlet).

# Pour tester sur Postman

[Collection Postman](https://www.postman.com/simplon-devs/youcode-fil-rouge-a1/collection/9x2u8lq/youcode-fil-rouge-rattrapage)

# Recommandations

- Respecter la structure `App/`
- Trouver du plaisir dans la réalisation 😉
- **Bon courage !!**
