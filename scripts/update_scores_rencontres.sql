-- Update scores for matches on 2025-06-12 to imagined values and mark as played

UPDATE rencontre
SET scoreclub1 = 2, scoreclub2 = 1, joue = true
WHERE date_heure = '2025-06-12 09:00:00'
AND club1_id = (SELECT id FROM club WHERE nom = 'Samedi matin')
AND club2_id = (SELECT id FROM club WHERE nom = 'As vita');

UPDATE rencontre
SET scoreclub1 = 1, scoreclub2 = 3, joue = true
WHERE date_heure = '2025-06-12 11:00:00'
AND club1_id = (SELECT id FROM club WHERE nom = 'Zongo')
AND club2_id = (SELECT id FROM club WHERE nom = 'Tomansé');

UPDATE rencontre
SET scoreclub1 = 0, scoreclub2 = 0, joue = true
WHERE date_heure = '2025-06-12 09:00:00'
AND club1_id = (SELECT id FROM club WHERE nom = 'OFCA')
AND club2_id = (SELECT id FROM club WHERE nom = 'alliace EC');

UPDATE rencontre
SET scoreclub1 = 2, scoreclub2 = 2, joue = true
WHERE date_heure = '2025-06-12 11:00:00'
AND club1_id = (SELECT id FROM club WHERE nom = 'Dynamo FC')
AND club2_id = (SELECT id FROM club WHERE nom = 'AWANOU');

UPDATE rencontre
SET scoreclub1 = 1, scoreclub2 = 0, joue = true
WHERE date_heure = '2025-06-12 09:00:00'
AND club1_id = (SELECT id FROM club WHERE nom = 'aigle FC')
AND club2_id = (SELECT id FROM club WHERE nom = 'Jungle FC');

UPDATE rencontre
SET scoreclub1 = 3, scoreclub2 = 1, joue = true
WHERE date_heure = '2025-06-12 11:00:00'
AND club1_id = (SELECT id FROM club WHERE nom = 'Vision FC')
AND club2_id = (SELECT id FROM club WHERE nom = 'BUFFles FC');

UPDATE rencontre
SET scoreclub1 = 0, scoreclub2 = 1, joue = true
WHERE date_heure = '2025-06-12 09:00:00'
AND club1_id = (SELECT id FROM club WHERE nom = 'Singapour FC')
AND club2_id = (SELECT id FROM club WHERE nom = 'Akpadamé FC');

UPDATE rencontre
SET scoreclub1 = 2, scoreclub2 = 0, joue = true
WHERE date_heure = '2025-06-12 11:00:00'
AND club1_id = (SELECT id FROM club WHERE nom = 'LOKOSSA FC')
AND club2_id = (SELECT id FROM club WHERE nom = 'TAMSIR FC');
