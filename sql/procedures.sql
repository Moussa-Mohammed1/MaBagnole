DELIMITER $$ 
        CREATE PROCEDURE create_reservation(
            IN id_client_param INT,
            IN id_car_param INT,
            IN date_reservation_param DATETIME,
            IN pickupLocation_param VARCHAR(200),
            IN retournLocation_param VARCHAR(200),
            IN startDate_param DATETIME,
            IN endDate_param DATETIME
        ) 
    BEGIN
        INSERT INTO reservation(
                id_client,
                id_car,
                date_reservation,
                pickupLocation,
                retournLocation,
                startDate,
                endDate 
            )
        VALUES (
                id_client_param,
                id_car_param,
                date_reservation_param,
                pickupLocation_param,
                retournLocation_param,
                startDate_param,
                endDate_param
            );
    END $$ 
DELIMITER;