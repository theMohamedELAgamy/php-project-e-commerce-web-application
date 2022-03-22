
CREATE database ecommerce  ;
 use ecommerce; 

    CREATE TABLE  users (
         user_id INT PRIMARY KEY AUTO_INCREMENT ,
         user_email VARCHAR(100) NOT NULL,
         user_password VARCHAR(500) NOT NULL
        );


    CREATE TABLE products (
        product_id INT PRIMARY KEY AUTO_INCREMENT,
        download_file_link VARCHAR(100) NOT NULL,
        product_name VARCHAR(100) NOT NULL
        );

    CREATE TABLE tokens (
        user_id INT NOT NULL REFERENCES users(user_Id),
        remember_me_token VARCHAR(500) NOT NULL
    );


    CREATE TABLE orders (
        order_id INT PRIMARY KEY AUTO_INCREMENT,
        order_date datetime DEFAULT NOW(),
        counter INT DEFAULT 0,
        user_id INT REFERENCES users(user_id),
        product_id INT REFERENCES products(product_id)
    );

