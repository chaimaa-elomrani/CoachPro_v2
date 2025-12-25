Debutant
SELECT * FROM participants ;
SELECT p.nom , p.prenom FROM participants p ; 
SELECT COUNT(*) FROM participants ;
SELECT p.nom FROM participants p WHERE year(date_naissance) > 1995 ;
SELECT * FROM participants order BY nom ASC;

intermédiaire
SELECT COUNT(*) FROM participants GROUP BY nationalite ; 
SELECT COUNT(*) FROM participants GROUP BY nationalite HAVING(COUNT(*)) >2 ; 