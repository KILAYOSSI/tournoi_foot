-- Requête SQL corrigée pour assigner les clubs aux poules avec les bons IDs de poule

UPDATE club
SET poule_id = 21
WHERE nom IN ('Samedi matin', 'As vita', 'Zongo', 'Tomansé');

UPDATE club
SET poule_id = 22
WHERE nom IN ('OFCA', 'alliace EC', 'Dynamo FC', 'AWANOU');

UPDATE club
SET poule_id = 23
WHERE nom IN ('aigle FC', 'Jungle FC', 'Vision FC', 'BUFFles FC');

UPDATE club
SET poule_id = 24
WHERE nom IN ('Singapour FC', 'Akpadamé FC', 'LOKOSSA FC', 'TAMSIR FC');
