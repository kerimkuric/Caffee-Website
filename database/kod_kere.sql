-- Kod Kere — MySQL schema and sample data (Milestone 2)
-- Import in phpMyAdmin or: mysql -u root < database/kod_kere.sql

CREATE DATABASE IF NOT EXISTS kod_kere
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE kod_kere;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `reviews`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `reservations`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `users`;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `first_name` VARCHAR(80) NOT NULL,
    `last_name` VARCHAR(80) NOT NULL,
    `email` VARCHAR(120) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `role` ENUM('admin', 'customer') NOT NULL DEFAULT 'customer',
    `phone` VARCHAR(30) DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(80) NOT NULL UNIQUE,
    `description` VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `products` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT NOT NULL,
    `name` VARCHAR(120) NOT NULL,
    `description` VARCHAR(255) DEFAULT NULL,
    `price` DECIMAL(8,2) NOT NULL,
    `image_url` VARCHAR(255) DEFAULT NULL,
    `is_available` TINYINT(1) NOT NULL DEFAULT 1,
    CONSTRAINT `fk_products_category`
        FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `reservations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `reservation_date` DATE NOT NULL,
    `reservation_time` TIME NOT NULL,
    `party_size` INT NOT NULL,
    `status` ENUM('pending', 'confirmed', 'cancelled') NOT NULL DEFAULT 'pending',
    `notes` VARCHAR(255) DEFAULT NULL,
    CONSTRAINT `fk_reservations_user`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `orders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `status` ENUM('pending', 'preparing', 'ready', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
    `total` DECIMAL(8,2) NOT NULL DEFAULT 0.00,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_orders_user`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `order_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `quantity` INT NOT NULL,
    `unit_price` DECIMAL(8,2) NOT NULL,
    CONSTRAINT `fk_order_items_order`
        FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `fk_order_items_product`
        FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `reviews` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `product_id` INT DEFAULT NULL,
    `rating` INT NOT NULL,
    `comment` VARCHAR(500) DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_reviews_user`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `fk_reviews_product`
        FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample users. Password for both accounts: Password123!
INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password_hash`, `role`, `phone`) VALUES
(1, 'Kerim', 'Kuric', 'admin@kodkere.ba', '$2y$10$R9teCPNOTy84sWPvSCWrJuBEEOuQjb6r0go7Upx7jBF94y.dsvVB.', 'admin', '+387 33 555 121'),
(2, 'Amina', 'Hodzic', 'guest@kodkere.ba', '$2y$10$R9teCPNOTy84sWPvSCWrJuBEEOuQjb6r0go7Upx7jBF94y.dsvVB.', 'customer', '+387 61 222 333');

INSERT INTO `categories` (`id`, `name`, `description`) VALUES
(1, 'Hot drinks', 'Espresso and Bosnian coffee'),
(2, 'Cold drinks', 'Iced coffee and cold brew'),
(3, 'Pastry', 'Cakes and small sweets');

INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `price`, `image_url`, `is_available`) VALUES
(1, 1, 'Espresso', 'Short, strong house blend.', 2.50, 'img/menu-1.jpg', 1),
(2, 1, 'Bosanska kafa', 'Copper pot, sugar on the side, rahat lokum.', 4.00, 'img/menu-2.jpg', 1),
(3, 1, 'Latte', 'Espresso with steamed milk.', 5.00, 'img/menu-3.jpg', 1),
(4, 2, 'Iced latte', 'Espresso over ice with cold milk.', 5.50, 'img/menu-1.jpg', 1),
(5, 2, 'Cold brew', 'Steeped overnight, served over ice.', 6.00, 'img/menu-2.jpg', 1),
(6, 2, 'Chocolate mocha', 'Espresso, cocoa, and milk.', 6.00, 'img/menu-3.jpg', 1),
(7, 3, 'Hurmašice', 'Syrup-soaked pastry.', 3.50, 'img/menu-1.jpg', 1),
(8, 3, 'Cheesecake slice', 'Baked daily.', 4.50, 'img/menu-3.jpg', 1);

INSERT INTO `reservations` (`id`, `user_id`, `reservation_date`, `reservation_time`, `party_size`, `status`, `notes`) VALUES
(1, 2, '2026-11-08', '18:00:00', 2, 'confirmed', 'Window table if possible'),
(2, 2, '2026-11-15', '11:30:00', 4, 'pending', 'Weekend brunch');

INSERT INTO `orders` (`id`, `user_id`, `status`, `total`, `created_at`) VALUES
(1, 2, 'completed', 9.50, '2026-10-20 09:15:00'),
(2, 2, 'preparing', 11.50, '2026-11-01 16:40:00');

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `unit_price`) VALUES
(1, 1, 2, 1, 4.00),
(2, 1, 4, 1, 5.50),
(3, 2, 3, 1, 5.00),
(4, 2, 5, 1, 6.00);

INSERT INTO `reviews` (`id`, `user_id`, `product_id`, `rating`, `comment`) VALUES
(1, 2, 2, 5, 'Best Bosanska kafa near Ferhadija.'),
(2, 2, 8, 4, 'Cheesecake was rich; would come again.');
