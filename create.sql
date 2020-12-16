CREATE TABLE users (
   id           INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
   username     VARCHAR(50) NOT NULL UNIQUE,
   password     VARCHAR(255) NOT NULL,
   created_at   DATETIME DEFAULT CURRENT_TIMESTAMP,
   isAdmin      BOOLEAN DEFAULT FALSE
);

CREATE TABLE products (
    id          INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name        VARCHAR(255) NOT NULL UNIQUE,
    category    VARCHAR(50)  NOT NULL,
    brand       VARCHAR(50)  NOT NULL,
    color       VARCHAR(50),
    price       DECIMAL(9,2) NOT NULL
)