-- Función creada para que se calcule el IMC del usuario segun sus datos personales para poder añadirlo a la tabla de registro de su peso
DELIMITER //

CREATE FUNCTION registrar_peso(
    p_id_usuario INT,
    p_fecha DATE,
    p_peso DOUBLE
)
RETURNS INT
BEGIN
    DECLARE insert_id INT;
    DECLARE p_altura DOUBLE;
    DECLARE p_imc DOUBLE;

    -- Obtener la altura del usuario
    SELECT altura INTO p_altura
    FROM usuario
    WHERE id = p_id_usuario;

    -- Calcular el IMC
    SET p_imc = p_peso / (p_altura * p_altura);

    -- Insertar el registro de peso en la tabla
    INSERT INTO peso (id_usuario_id, fecha, peso, imc)
    VALUES (p_id_usuario, p_fecha, p_peso, p_imc);

    SET insert_id = LAST_INSERT_ID();

    RETURN insert_id;
END //

DELIMITER ;


-- Función para insertar los datos en la tabla de consumo diario

DELIMITER //

CREATE FUNCTION registrar_consumo(
    p_id_usuario INT,
    p_fecha DATE,
    p_comida VARCHAR(255),
    p_cantidad DOUBLE,
    p_momento VARCHAR(255)
)
RETURNS INT
BEGIN
    DECLARE insert_id INT;

    INSERT INTO consumo_dia (id_usuario_id, fecha, comida, cantidad, momento)
    VALUES (p_id_usuario, p_fecha, p_comida, p_cantidad, p_momento);

    SET insert_id = LAST_INSERT_ID();

    RETURN insert_id;
END //

DELIMITER ;
