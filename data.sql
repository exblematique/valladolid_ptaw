/*
    Create a user with administrator permission
    username: admin
    password: password
*/
INSERT INTO users (username, password, isAdmin) VALUES ('admin', '$2y$10$l0XzIFtL6b/Cb2yEvxOWuuMx7VhFuPpCaAzaFa0unjSg4dO96H6d6', TRUE);

/* Create list of objects */
/* BIKE */
INSERT INTO products (name, category, brand, color, price) VALUES ('ST 900 ALUMINIO 24 PULGADAS 9-12 AÑOS', 'BICICLETA', 'ROCKRIDER', 'ROJO', 299.99);
INSERT INTO products (name, category, brand, color, price) VALUES ('BMX 520 WIPE 20 PULGADAS', 'BICICLETA', 'BTWIN', 'GRIS AMARILLO', 184.99);
INSERT INTO products (name, category, brand, color, price) VALUES ('BICICLETA DE MONTAÑA ST 100 ALUMINIO MUJER 27,5" 21V', 'BICICLETA', 'ROCKRIDER', 'GRIS BLANCA ROSA', 199.99);
INSERT INTO products (name, category, brand, color, price) VALUES ('BICICLETA DE NIÑOS 900 RACING 16 PULGADAS ALUMINIO 4,5-6 AÑOS', 'BICICLETA', 'BTWIN', 'ROJO', 159.99);
INSERT INTO products (name, category, brand, color, price) VALUES ('BICICLETA DE NIÑOS MTB GREEN 16 PULGADAS 4,5-6 AÑOS', 'BICICLETA', 'ATRACTOR', 'VERDE', 109.99);
/* WATCH */
INSERT INTO products (name, category, brand, color, price) VALUES ('VIVOACTIVE 4 S RELOJ INTELIGENTE SMARTWATCH', 'RELOJ', 'GARMIN', 'NEGRO', 299.99);
INSERT INTO products (name, category, brand, color, price) VALUES ('RELOJ INTELIGENTE SMARTWATCH IONIC GPS PULSÓMETRO MUÑECA', 'RELOJ', 'FITBIT', 'NEGRO GRIS', 249.99);
INSERT INTO products (name, category, brand, color, price) VALUES ('RELOJ INTELIGENTE SMARTWATCH POLAR IGNITE GPS PULSÓMETRO MUÑECA', 'RELOJ', 'POLAR', 'NEGRO', 199.99);
/* SKATE */
INSERT INTO products (name, category, brand, color, price) VALUES ('LONGBOARD DANCE 500 TOTEM MAT', 'SKATE', 'OXELO', 'NEGRO', 119.99);
INSERT INTO products (name, category, brand, color, price) VALUES ('CRUISER SKATEBOARD YAMBA 100', 'SKATE', 'OXELO', 'AMARILLO VERDE', 39.99);
INSERT INTO products (name, category, brand, color, price) VALUES ('LONGBOARD PINTAIL 520 GRADIANT', 'SKATE', 'OXELO', NULL, 89.99);
