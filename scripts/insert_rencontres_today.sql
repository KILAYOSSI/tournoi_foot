-- Insert matches for Poule A on 2 June 2025 at 20:00
INSERT INTO rencontre (club1_id, club2_id, date_heure, scoreclub1, scoreclub2, joue)
VALUES 
  ((SELECT id FROM club WHERE nom = 'Samedi matin'), (SELECT id FROM club WHERE nom = 'As vita'), '2025-06-02 20:00:00', NULL, NULL, false),
  ((SELECT id FROM club WHERE nom = 'Zongo'), (SELECT id FROM club WHERE nom = 'Tomansé'), '2025-06-02 20:00:00', NULL, NULL, false);

-- Insert matches for Poule B on 2 June 2025 at 20:00
INSERT INTO rencontre (club1_id, club2_id, date_heure, scoreclub1, scoreclub2, joue)
VALUES 
  ((SELECT id FROM club WHERE nom = 'OFCA'), (SELECT id FROM club WHERE nom = 'alliace EC'), '2025-06-02 20:00:00', NULL, NULL, false),
  ((SELECT id FROM club WHERE nom = 'Dynamo FC'), (SELECT id FROM club WHERE nom = 'AWANOU'), '2025-06-02 20:00:00', NULL, NULL, false);

-- Insert matches for Poule C on 2 June 2025 at 20:00
INSERT INTO rencontre (club1_id, club2_id, date_heure, scoreclub1, scoreclub2, joue)
VALUES 
  ((SELECT id FROM club WHERE nom = 'aigle FC'), (SELECT id FROM club WHERE nom = 'Jungle FC'), '2025-06-02 20:00:00', NULL, NULL, false),
  ((SELECT id FROM club WHERE nom = 'Vision FC'), (SELECT id FROM club WHERE nom = 'BUFFles FC'), '2025-06-02 20:00:00', NULL, NULL, false);

-- Insert matches for Poule D on 2 June 2025 at 20:00
INSERT INTO rencontre (club1_id, club2_id, date_heure, scoreclub1, scoreclub2, joue)
VALUES 
  ((SELECT id FROM club WHERE nom = 'Singapour FC'), (SELECT id FROM club WHERE nom = 'Akpadamé FC'), '2025-06-02 20:00:00', NULL, NULL, false),
  ((SELECT id FROM club WHERE nom = 'LOKOSSA FC'), (SELECT id FROM club WHERE nom = 'TAMSIR FC'), '2025-06-02 20:00:00', NULL, NULL, false);
