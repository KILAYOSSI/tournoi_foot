-- Script SQL pour supprimer les doublons dans classement_poule en conservant la première entrée par club et poule

DELETE cp1 FROM classement_poule cp1
INNER JOIN classement_poule cp2 
WHERE 
    cp1.id > cp2.id
    AND cp1.club_id = cp2.club_id
    AND cp1.poule_id = cp2.poule_id;
