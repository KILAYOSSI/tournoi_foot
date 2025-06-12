-- Requêtes SQL pour assigner les clubs aux poules selon leur nom

UPDATE club
SET poule_id = 1
WHERE nom IN ('Samedi matin', 'As vita', 'Zongo', 'Tomansé');

UPDATE club
SET poule_id = 2
WHERE nom IN ('OFCA', 'alliace EC', 'Dynamo FC', 'AWANOU');

UPDATE club
SET poule_id = 3
WHERE nom IN ('aigle FC', 'Jungle FC', 'Vision FC', 'BUFFles FC');

UPDATE club
SET poule_id = 4
WHERE nom IN ('Singapour FC', 'Akpadamé FC', 'LOKOSSA FC', 'TAMSIR FC');
