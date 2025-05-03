


CREATE TABLE IF NOT EXISTS users(
    id INT auto_increment primary key,
    fullname VARCHAR(100) NOT NULL,
    user_name VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE
);

CREATE TABLE IF NOT EXISTS contact(
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    isguest BOOLEAN DEFAULT TRUE,
    user_id INT NULL,
    fullname VARCHAR(100) NULL,
    user_name VARCHAR(50) NULL,
    message TEXT NOT NULL,
    message_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

insert into users(id, fullname, user_name, password, is_admin)
VALUES(0, 'Mr.Admin', 'admin', '$2y$10$4LFwDCIX9Mc.vu3tRW9T/.MleDa8jeCIhQu4YMvlfPzsIjcXTsIEG', TRUE );
