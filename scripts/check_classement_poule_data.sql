SELECT p.nom AS poule, c.nom AS club, cp.points, cp.matchsjouer, cp.gagnes, cp.nuls, cp.perdus, cp.butsPour, cp.butscontre, cp.goalaverage
FROM classement_poule cp
JOIN club c ON cp.club_id = c.id
JOIN poule p ON c.poule_id = p.id
ORDER BY p.nom, cp.points DESC;
