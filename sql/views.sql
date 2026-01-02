CREATE VIEW ListeVoitures AS
SELECT
    c.id_car,
    c.marque,
    c.model,
    c.prix,
    c.image,
    c.status,
    c.description AS car_description,

    cat.id_category,
    cat.nom AS category_name,

    COUNT(a.id_avis) AS total_avis

FROM car c
JOIN category cat 
    ON c.id_category = cat.id_category

LEFT JOIN avis a 
    ON c.id_car = a.id_car
    AND a.deleted_at IS NULL

GROUP BY c.id_car;
