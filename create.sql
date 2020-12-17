CREATE TABLE users (
   id           INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
   mail         VARCHAR(50) NOT NULL UNIQUE,
   password     VARCHAR(255) NOT NULL,
   address      VARCHAR(255) NOT NULL,
   postal       VARCHAR(5) NOT NULL,
   city         VARCHAR(255) NOT NULL,
   created_at   DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admin (
    id      INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_user INT NOT NULL,
    FOREIGN KEY (id_user) REFERENCES users(id)
);

CREATE TABLE products (
    id          INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name        VARCHAR(255) NOT NULL UNIQUE,
    category    VARCHAR(50)  NOT NULL,
    brand       VARCHAR(50)  NOT NULL,
    color       VARCHAR(50),
    price       DECIMAL(9,2) NOT NULL
);

CREATE TABLE orders (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_user INT NOT NULL,
    delivery_date DATE NOT NULL,
    FOREIGN KEY (id_user) REFERENCES users(id)
)

CREATE TABLE orders_products (
     id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
     id_order INT NOT NULL,
     FOREIGN KEY (id_order) REFERENCES orders(id)
     id_products INT NOT NULL,
     FOREIGN KEY (id_products) REFERENCES products(id)
     quantity INT NOT NULL,
)