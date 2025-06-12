SELECT club_id, COUNT(DISTINCT poule_id) as nb_poules
FROM classement_poule
GROUP BY club_id
HAVING nb_poules > 1;
