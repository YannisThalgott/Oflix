# Requêtes SQL pour le projet

## Requêtes pour les pages

```sql
-- Récupérer tous les films.
SELECT * FROM `movie`;
```

```sql
-- Récupérer les acteurs et leur(s) rôle(s) pour un film donné.

-- Va chercher toutes les colonnes
SELECT *
-- De la table casting
FROM `casting`
-- Ainsi que celles de la table person, à la condition que la personne du casting = la personne de person
INNER JOIN `person` ON `casting.person_id`=`person`.`id`
-- Pour le film dont l'id est 1
WHERE `movie_id`=1
```

```sql
-- Récupérer les genres associés à un film donné.
-- Et si besoin le film avec (optionnel à voir si on l'a récupéré avant)
SELECT * FROM movie_genre
INNER JOIN genre
ON movie_genre.genre_id = genre.id 
INNER JOIN movie
ON movie_genre.movie_id = movie.id 
WHERE movie.id = 2
```

```sql
-- Récupérer les saisons associées à un film/série donné.
SELECT *
FROM `season`
WHERE `movie_id`=1
-- On trie par numéro de saison par sécurité pour les avoir dans l'ordre
ORDER BY `number` ASC
```

```sql
-- Récupérer les critiques pour un film donné.
SELECT *
FROM `review`
WHERE `movie_id` = 1

-- Avec jointure sur movie
SELECT *
FROM movie
INNER JOIN review
ON movie.id = review.movie_id
WHERE movie_id=1
```

```sql
-- Récupérer les critiques pour un film donné, ainsi que le nom de l'utilisateur associé.
```

```sql
-- Calculer, pour chaque film, la moyenne des critiques par film (en une seule requête).
```

```sql
-- Voir : https://sql.sh/fonctions/agregation/avg
-- Calculer, pour un film donné, la moyenne des critiques par film (en une seule requête).
-- Afin de calculer le rating d'un film
-- Sélectionne la moyenne des rating
SELECT movie_id, AVG(`rating`)
-- De la table review
FROM `review`
-- Pour le film 1
WHERE `movie_id`=1
```

## Requêtes de recherche

```sql

-- Voir : https://sql.sh/fonctions/year
-- Récupérer tous les films pour une année de sortie donnée.
SELECT * FROM `movie` WHERE YEAR(`release_date`) = 1980;

-- https://sql.sh/cours/where/like
SELECT * FROM `movie` WHERE `release_date` LIKE '2011%';
```

```sql
-- Récupérer tous les films dont le titre est fourni (titre complet).
SELECT *
FROM `movie`
WHERE `title` = `Epic Movie`
```

```sql
-- Récupérer tous les films dont le titre contient une chaîne donnée.
SELECT *
FROM `movie`
WHERE `title` LIKE '%the%'
```

## Bonus : Pagination

Nombre de films par page : 10 (par ex.)

```sql
-- Récupérer la liste des films de la page 2 (grâce à LIMIT).
SELECT *
FROM `movie`
LIMIT 10 OFFSET 10
```

Testez la requête en faisant varier le nombre de films par page et le numéro de page.

