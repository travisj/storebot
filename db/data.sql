insert into `user` (username, password, email) values ('admin', md5('admin'), 'admin@storebot.com');

insert into `category` (name, slug) values ('Postcards', 'postcards'), ('Buttons', 'buttons'), ('T-Shirts', 't-shirts'), ('Random Things', 'random-things');
set @category_id = last_insert_id();

insert into `product` (name, slug, category_id, description) values ('Test Product', 'test-product', @category_id, 'this is a test product inserted by the setup script');
set @product_id = last_insert_id();

insert into `item` (product_id, name, quantity, price) values (@product_id, 'Small', 10, 5), (@product_id, 'Medium', 10, 6), (@product_id, 'Large', 10, 10);
