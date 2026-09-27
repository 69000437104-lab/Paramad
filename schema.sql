-- SQL Schema for Order Food System (ร้านก๋วยจั๊บญวณ)
CREATE DATABASE IF NOT EXISTS `orderfood` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `orderfood`;

-- Drop existing tables if re-running
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `menu_items`;
DROP TABLE IF EXISTS `categories`;
SET FOREIGN_KEY_CHECKS = 1;

-- Categories Table
CREATE TABLE `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert Noodle & Guay Jub Categories (Only Noodle / Guay Jub / Tom Sen)
INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'ก๋วยจั๊บญวน / ต้มเส้น'),
(2, 'ก๋วยเตี๋ยว / เกาเหลา');

-- Menu Items Table
CREATE TABLE `menu_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `description` TEXT,
  `price` DECIMAL(10,2) NOT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `is_available` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert Noodle & Guay Jub Menu Items
INSERT INTO `menu_items` (`id`, `category_id`, `name`, `description`, `price`, `image`, `is_available`) VALUES
(1, 1, 'ก๋วยจั๊บญวนหมูสับ + หมูยอ (ใส่ไข่)', 'เส้นก๋วยจั๊บเหนียวนุ่ม โรยหอมเจียว หมูสับแน่นๆ หมูยออุบล และไข่ต้มยางมะตูม', 55.00, 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?auto=format&fit=crop&w=600&q=80', 1),
(2, 1, 'ก๋วยจั๊บญวนซี่โครงหมูอบ', 'ซี่โครงหมูตุ๋นเปื่อยละลายในปาก น้ำซุปกระดูกหมูกล่อมกล่อม หอมพริกไทยดำ', 65.00, 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?auto=format&fit=crop&w=600&q=80', 1),
(3, 1, 'ต้มเส้นไก่ต้มซุปเข้มข้น', 'วุ้นเส้นเหนียวนุ่ม ต้มในน้ำซุปตีนไก่และน่องไก่เปื่อย หอมตีนไก่ตุ๋นยาจีน', 50.00, 'https://images.unsplash.com/photo-1555126634-323283e090fa?auto=format&fit=crop&w=600&q=80', 1),
(4, 1, 'ต้มเส้นผสมก๋วยจั๊บญวน (เมนูฮิต)', 'เมนูผสมผสาน 2 เส้นในถ้วยเดียว วุ้นเส้น + เส้นก๋วยจั๊บญวน เครื่องแน่นๆ', 60.00, 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?auto=format&fit=crop&w=600&q=80', 1),
(5, 2, 'ก๋วยเตี๋ยวต้มยำโบราณ (หมูสับ/ไข่มะตูม)', 'ต้มยำมะนาวสด ถั่วลิสงคั่วเอง เผ็ดเปรี้ยวลงตัว ถั่วและพริกผัดใหม่วันต่อวัน', 60.00, 'https://images.unsplash.com/photo-1617093727343-374698b1b08d?auto=format&fit=crop&w=600&q=80', 1),
(6, 2, 'ก๋วยเตี๋ยวเรือหมูน้ำตกเข้มข้น', 'น้ำตกรสเข้มข้นใส่กะทิและสมุนไพร หอมพริกผัดสูตรเด็ดของทางร้าน', 55.00, 'https://images.unsplash.com/photo-1617093727343-374698b1b08d?auto=format&fit=crop&w=600&q=80', 1),
(7, 2, 'เกาเหลาต้มยำรวมมิตร', 'เกาเหลาสดใส่หมูสับ หมูยอ ตีนไก่เปื่อย ไข่ยางมะตูม ไม่ใส่เส้น', 70.00, 'https://images.unsplash.com/photo-1547592166-23ac45744acd?auto=format&fit=crop&w=600&q=80', 1);

-- Orders Table
CREATE TABLE `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_number` VARCHAR(20) NOT NULL UNIQUE,
  `customer_name` VARCHAR(100) NOT NULL,
  `customer_phone` VARCHAR(20) NOT NULL,
  `table_no` VARCHAR(50) DEFAULT NULL,
  `order_type` ENUM('dine_in', 'takeaway', 'delivery') DEFAULT 'dine_in',
  `total_amount` DECIMAL(10,2) NOT NULL,
  `status` ENUM('รอรับออเดอร์', 'กำลังทำ', 'เสร็จแล้ว', 'ยกเลิก') DEFAULT 'รอรับออเดอร์',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Order Items Table
CREATE TABLE `order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `menu_item_id` INT DEFAULT NULL,
  `item_name` VARCHAR(150) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `quantity` INT NOT NULL,
  `subtotal` DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
