-- Insert imagined players for clubs (if not already present)
-- Example players for club 'Samedi matin'
INSERT INTO joueur (nom, poste, club_id) VALUES
('Jean Dupont', 'Attaquant', (SELECT id FROM club WHERE nom = 'Samedi matin')),
('Paul Martin', 'Milieu', (SELECT id FROM club WHERE nom = 'Samedi matin')),
('Luc Bernard', 'Gardien', (SELECT id FROM club WHERE nom = 'Samedi matin'));

-- Example players for club 'As vita'
INSERT INTO joueur (nom, poste, club_id) VALUES
('Marc Leroy', 'Attaquant', (SELECT id FROM club WHERE nom = 'As vita')),
('Eric Morel', 'Milieu', (SELECT id FROM club WHERE nom = 'As vita')),
('Alain Petit', 'Gardien', (SELECT id FROM club WHERE nom = 'As vita'));

-- Insert imagined statistics for match Samedi matin vs As vita on 2025-06-12 09:00
INSERT INTO statistiquematch (rencontre_id, joueur_id, buts, passes, cleansheet, cartonsjaunes, cartonsrouges)
VALUES
(
  (SELECT id FROM rencontre WHERE date_heure = '2025-06-12 09:00:00' AND club1_id = (SELECT id FROM club WHERE nom = 'Samedi matin') AND club2_id = (SELECT id FROM club WHERE nom = 'As vita')),
  (SELECT id FROM joueur WHERE nom = 'Jean Dupont' AND club_id = (SELECT id FROM club WHERE nom = 'Samedi matin')),
  2, 1, false, 1, 0
),
(
  (SELECT id FROM rencontre WHERE date_heure = '2025-06-12 09:00:00' AND club1_id = (SELECT id FROM club WHERE nom = 'Samedi matin') AND club2_id = (SELECT id FROM club WHERE nom = 'As vita')),
  (SELECT id FROM joueur WHERE nom = 'Paul Martin' AND club_id = (SELECT id FROM club WHERE nom = 'Samedi matin')),
  0, 2, false, 0, 0
),
(
  (SELECT id FROM rencontre WHERE date_heure = '2025-06-12 09:00:00' AND club1_id = (SELECT id FROM club WHERE nom = 'Samedi matin') AND club2_id = (SELECT id FROM club WHERE nom = 'As vita')),
  (SELECT id FROM joueur WHERE nom = 'Luc Bernard' AND club_id = (SELECT id FROM club WHERE nom = 'Samedi matin')),
  0, 0, true, 0, 0
),
(
  (SELECT id FROM rencontre WHERE date_heure = '2025-06-12 09:00:00' AND club1_id = (SELECT id FROM club WHERE nom = 'Samedi matin') AND club2_id = (SELECT id FROM club WHERE nom = 'As vita')),
  (SELECT id FROM joueur WHERE nom = 'Marc Leroy' AND club_id = (SELECT id FROM club WHERE nom = 'As vita')),
  1, 0, false, 0, 1
),
(
  (SELECT id FROM rencontre WHERE date_heure = '2025-06-12 09:00:00' AND club1_id = (SELECT id FROM club WHERE nom = 'Samedi matin') AND club2_id = (SELECT id FROM club WHERE nom = 'As vita')),
  (SELECT id FROM joueur WHERE nom = 'Eric Morel' AND club_id = (SELECT id FROM club WHERE nom = 'As vita')),
  0, 1, false, 0, 0
),
(
  (SELECT id FROM rencontre WHERE date_heure = '2025-06-12 09:00:00' AND club1_id = (SELECT id FROM club WHERE nom = 'Samedi matin') AND club2_id = (SELECT id FROM club WHERE nom = 'As vita')),
  (SELECT id FROM joueur WHERE nom = 'Alain Petit' AND club_id = (SELECT id FROM club WHERE nom = 'As vita')),
  0, 0, true, 0, 0
);

-- Similar inserts can be created for other matches with imagined players and stats.
