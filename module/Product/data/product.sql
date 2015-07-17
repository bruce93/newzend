CREATE TABLE product (
  id int(11) NOT NULL auto_increment,
  product_name varchar(100) NOT NULL,
  description varchar(255) NULL,
  price float(15) NOT NULL,
  image varchar(150) NULL,
  PRIMARY KEY (id)
);
INSERT INTO product (product_name, description, price, image)
    VALUES  ('The  Military  Wives',  'In  My  Dreams', 20, 'http');
INSERT INTO product (product_name, description, price, image)
    VALUES  ('Adele',  '21', 20, 'http');
INSERT INTO product (product_name, description, price, image)
    VALUES  ('Bruce  Springsteen',  'Wrecking Ball (Deluxe)', 20, 'http');