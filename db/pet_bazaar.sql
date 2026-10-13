-- phpMyAdmin SQL Dump
-- version 4.8.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 13, 2026 at 12:52 PM
-- Server version: 10.1.33-MariaDB
-- PHP Version: 7.2.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pet_bazaar`
--

-- --------------------------------------------------------

--
-- Table structure for table `addtocart`
--

CREATE TABLE `addtocart` (
  `c_id` int(20) NOT NULL,
  `u_id` int(20) NOT NULL,
  `p_img` varchar(50) NOT NULL,
  `p_name` varchar(20) NOT NULL,
  `p_qty` int(10) NOT NULL,
  `p_price` int(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'cart',
  `bill_id` int(11) DEFAULT NULL,
  `p_id` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `addtocart`
--

INSERT INTO `addtocart` (`c_id`, `u_id`, `p_img`, `p_name`, `p_qty`, `p_price`, `email`, `status`, `bill_id`, `p_id`) VALUES
(17, 13, 'winter_cloth_dog.webp', ' Winter Coat', 3, 899, 'abc@gmail.com', 'cart', NULL, 0),
(24, 11, 'bird_decorative.jfif', 'Colorful Bird Bells', 1, 175, 'admin@gmail.com', 'cart', NULL, 21);

-- --------------------------------------------------------

--
-- Table structure for table `bill`
--

CREATE TABLE `bill` (
  `id` int(10) NOT NULL,
  `u_id` int(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pincode` varchar(10) NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `address` varchar(150) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `shipping` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `order_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `phone` varchar(20) NOT NULL,
  `payment` varchar(50) NOT NULL,
  `order_status` enum('Placed','Processing','Shipped','Delivered','Cancelled') NOT NULL DEFAULT 'Placed',
  `payment_status` enum('Pending','Paid','Failed') NOT NULL DEFAULT 'Pending',
  `cancelled_by` varchar(50) DEFAULT NULL,
  `cancelled_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bill`
--

INSERT INTO `bill` (`id`, `u_id`, `name`, `email`, `pincode`, `city`, `state`, `address`, `subtotal`, `shipping`, `total_amount`, `order_date`, `phone`, `payment`, `order_status`, `payment_status`, `cancelled_by`, `cancelled_at`) VALUES
(10, 12, 'jemika', 'jemika123@gmail.com', '378451', 'surat', 'Gujarat', 'surat', '160.00', '200.00', '360.00', '2026-09-12 21:37:00', '2147483647', 'Cash on Delivery', 'Delivered', 'Pending', NULL, NULL),
(11, 14, 'jenisa', 'jenisa@gmail.com', '361012', 'Rajkot', 'Gujarat', 'lilapur', '1089.00', '0.00', '1089.00', '2026-09-15 20:56:35', '7284796320', 'Cash on Delivery', 'Delivered', 'Pending', NULL, NULL),
(15, 11, 'madhuri Chauhan', 'admin@gmail.com', '361012', 'Jamnagar', 'Gujrat', 'Shuthar seri ,Pipartoda,Lalpur\r\nJamnagar', '389.00', '200.00', '589.00', '2026-09-17 22:30:51', '9909778278', 'Cash on Delivery', 'Cancelled', 'Failed', 'User', '2026-09-17 22:32:55'),
(16, 11, 'admin', 'admin@gmail.com', '123654', 'Jamnagar', 'Gujarat', 'junagadh', '540.00', '0.00', '540.00', '2026-09-21 14:49:00', '1584796320', 'Cash on Delivery', 'Delivered', 'Paid', NULL, NULL),
(17, 1, 'madhuri Chauhan', 'chauhanmadhuri231@gmail.com', '361012', 'Jamnagar', 'Gujarat', 'Shuthar seri ,Pipartoda,Lalpur', '487.00', '200.00', '687.00', '2026-09-21 21:02:48', '123456', 'Cash on Delivery', 'Cancelled', 'Pending', 'User', '2026-09-21 21:21:56');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(10) NOT NULL,
  `category` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `category`) VALUES
(3, 'Cat'),
(4, 'Dog'),
(8, 'Fish'),
(24, 'Bird');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `Id` int(3) NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(45) NOT NULL,
  `message` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`Id`, `name`, `email`, `message`) VALUES
(1, 'madhuri Chauhan', 'chauhanmadhuri231@gmail.c', 'good'),
(2, 'Jemika', 'jemika@gmail.con', 'Good');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `rating` int(11) NOT NULL,
  `feedback` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `name`, `email`, `rating`, `feedback`, `created_at`) VALUES
(1, 'madhuri Chauhan', 'chauhanmadhuri231@gmail.com', 3, 'good', '2026-08-26 02:40:12'),
(2, 'jemika', 'jemikasavaliya@gmail.com', 5, 'Exlent', '2026-09-10 14:57:53'),
(3, 'Disha', 'dishasongra@gmail.com', 4, 'Best', '2026-09-10 14:58:34');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `bill_id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `bill_id`, `u_id`, `product_id`, `product_name`, `product_price`, `quantity`, `subtotal`) VALUES
(43, 10, 12, 83, 'Paw Cleaner', '160.00', 1, '160.00'),
(44, 11, 14, 17, 'Bird Mirror Toy', '1089.00', 1, '1089.00'),
(48, 15, 11, 7, 'Canary Seed', '389.00', 1, '389.00'),
(49, 16, 11, 69, ' Dress', '540.00', 1, '540.00'),
(50, 17, 1, 24, 'Cozy  Cushion', '487.00', 1, '487.00');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(10) NOT NULL,
  `category` varchar(20) NOT NULL,
  `sub_category` varchar(20) NOT NULL,
  `product_name` varchar(30) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `price` int(10) NOT NULL,
  `qty` varchar(20) NOT NULL,
  `image` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `category`, `sub_category`, `product_name`, `description`, `price`, `qty`, `image`) VALUES
(5, 'Bird', 'Food', 'Sunflower Seeds', 'A nutritious and protein-rich bird food that provides essential vitamins, healthy fats, and energy for parrots, budgies, cockatiels, and other pet birds.', 450, '20', 'sun_seeds_bird.webp'),
(6, 'Bird', 'Food', 'Millet Seed Mix', 'A nutritious seed mix that keeps pet birds healthy and active.', 350, '15', 'millet_bird.webp'),
(7, 'Bird', 'Food', 'Canary Seed', 'A healthy seed that supports energy and overall well-being in pet birds.', 389, '18', 'canary_seeds_bird.jpg'),
(9, 'Bird', 'Food', 'Mixed Bird Seed', 'A balanced blend of seeds for complete daily nutrition for pet birds.', 458, '47', 'mixed_seed_bird.webp'),
(10, 'Bird', 'Food', 'Parrot Food Mix', 'A nutritious food blend specially made for healthy and active parrots.', 265, '21', 'parrot_bird.webp'),
(11, 'Bird', 'Food', 'Finch Seed Mix', 'A wholesome seed blend specially made for the daily nutrition of finches.', 354, '28', 'finch_bird.jpg'),
(12, 'Bird', 'Food', 'High Protein', 'A protein-rich diet that supports healthy growth and energy in pet birds.', 1178, '19', 'high_protien_bird.webp'),
(13, 'Bird', 'Food', 'Vitamin Enriched ', 'A vitamin-rich formula that supports your bird\'s health and immunity.', 496, '15', 'vitamin_bird.webp'),
(14, 'Bird', 'Decorative', 'Bird Cage Swing', 'A colorful swing for birds to play and relax.', 220, '18', 'sungrow_decor_bird.jpg'),
(15, 'Bird', 'Decorative', 'Bird Hanging Toy', 'A fun hanging toy that keeps birds active.', 224, '18', 'toy_decor_bird.webp'),
(16, 'Bird', 'Decorative', 'Wooden Bird Perch', 'A natural wooden perch for comfortable resting.', 155, '17', 'wooden_decor_bird.webp'),
(17, 'Bird', 'Decorative', 'Bird Mirror Toy', 'A mirror toy that entertains pet birds.', 1089, '27', 'mirror_decor_bird.jpg'),
(18, 'Bird', 'Decorative', 'Bird Rope Perch', 'A soft rope perch for climbing and resting.', 458, '19', 'rope_decore_bird.webp'),
(19, 'Bird', 'Decorative', 'Artificial Bird Nest', 'A decorative nest for bird cages.', 879, '11', 'nest_decore_bird.webp'),
(20, 'Bird', 'Decorative', ' Cage Decoration Set', 'A complete set of colorful cage decorations.', 1789, '9', 'set_decore_bird.webp'),
(21, 'Bird', 'Decorative', 'Colorful Bird Bells', 'Bright bells that encourage birds to play.', 175, '12', 'bird_decorative.jfif'),
(22, 'Cat', 'Bad', 'Round Bed', 'A soft round bed for cozy and comfortable sleep.', 578, '18', 'round_bed_cat.webp'),
(23, 'Cat', 'Bad', 'Soft Plush', 'A plush bed that keeps your cat warm and relaxed.', 877, '2', 'soft_bed_cat.webp'),
(24, 'Cat', 'Bad', 'Cozy  Cushion', 'A comfortable cushion for your cat\'s daily rest.', 487, '8', 'cushion_bed_cat.webp'),
(25, 'Cat', 'Bad', 'Donut Bed', 'A fluffy donut-shaped bed for extra comfort and security.', 489, '11', 'donut_bed_cat.webp'),
(26, 'Cat', 'Bad', ' Sleeping Mat', 'A soft sleeping mat for everyday comfort.', 452, '5', 'mat_bed_cat.jpg'),
(27, 'Cat', 'Bad', 'Luxury  Sofa', 'A stylish sofa bed designed for premium cat comfort.', 1999, '14', 'sofa_bed_cat.webp'),
(28, 'Cat', 'Bad', ' Basket ', 'A woven basket bed for a cozy resting space.', 987, '18', 'basket_bed_cat.jpg'),
(29, 'Cat', 'Bad', 'Hooded', 'A covered bed that provides warmth and privacy.', 1018, '19', 'hooded_bed_cat.jpg'),
(30, 'Cat', 'cloth', 'T-Shirt', 'A soft and lightweight T-shirt for everyday comfort.', 650, '26', 'tshirt_cloth_cat.jpg'),
(31, 'Cat', 'cloth', ' Hoodie', 'A cozy hoodie that keeps your cat warm and stylish.', 784, '24', 'hoodies_cloth_cat.webp'),
(32, 'Cat', 'cloth', ' Sweater', 'A warm sweater perfect for cold weather.', 482, '16', 'sweater_cloth_cat.webp'),
(33, 'Cat', 'cloth', 'Backpack', 'A comfortable jacket for extra warmth and protection', 1487, '10', 'jacket_cloth_cat.jpg'),
(34, 'Cat', 'cloth', ' Raincoat', 'A waterproof raincoat to keep your cat dry.', 590, '41', 'raicoat_cloth_cat.webp'),
(35, 'Cat', 'cloth', 'Pajamas', 'Soft pajamas for a cozy and comfortable sleep.', 429, '20', 'pajamas_cloth+cat.webp'),
(36, 'Cat', 'cloth', 'Dress', 'A cute dress for special occasions and daily style.', 751, '42', 'dress_cloth_cat.webp'),
(38, 'Cat', 'cloth', 'Costume Outfit', 'A fun costume outfit for parties and festivals', 785, '10', 'outfit_cloth_cat.jpg'),
(39, 'Cat', 'Food', 'Dry  Food', 'A balanced dry food for your cat\'s daily nutrition.', 420, '11', 'dry_food_cat.webp'),
(40, 'Cat', 'Food', 'Goofy Tails Himalaya', 'A tasty wet meal rich in essential nutrients.', 482, '18', 'himalayn_food_cat.webp'),
(41, 'Cat', 'Food', 'Whiskas', 'A complete diet for healthy adult cats.', 425, '18', 'wishkas_food_cat.webp'),
(42, 'Cat', 'Food', 'Friskies', 'A complete and tasty cat food that supports your cat\'s daily health and nutrition.', 785, '14', 'friskies_food_cat.webp'),
(43, 'Cat', 'Food', 'Royal Canin', 'A premium formula designed for complete feline nutrition.', 289, '21', 'royal_food_cat.webp'),
(44, 'Cat', 'Food', 'Drools ', 'A protein-rich food that supports strong muscles and health.', 457, '10', 'drool_food_cat.webp'),
(45, 'Cat', 'Food', 'Fancy Feast ', 'A delicious premium meal made with quality ingredients.', 997, '18', 'friency_food_cat.webp'),
(46, 'Cat', 'Food', 'Sheba Wet ', 'A flavorful gourmet meal for adult cats.', 725, '18', 'sheba_food_cat.webp'),
(47, 'Cat', 'Grroming', ' Brush', 'A soft brush that removes loose hair and keeps the coat smooth.', 324, '20', 'brush_grro_cat.webp'),
(48, 'Cat', 'Grroming', 'Slicker Brush', 'A gentle brush that removes tangles and loose fur.', 230, '10', 'slicker_grro_cat.webp'),
(49, 'Cat', 'Grroming', 'Flea Comb', 'A fine-tooth comb that helps remove fleas and dirt.', 210, '26', 'comb_grro_cat.webp'),
(50, 'Cat', 'Grroming', 'Nail Clipper', 'A safe clipper for trimming your cat\'s nails.', 410, '13', 'nail_grro_cat.webp'),
(51, 'Cat', 'Grroming', 'Shampoo', 'A quiet trimmer for safe and easy fur grooming.', 480, '24', 'shampoo_grro_cat.jpg'),
(52, 'Cat', 'Grroming', 'Ear Cleaning Wipes', 'Soft wipes that gently clean your cat\'s ears.', 1420, '18', 'wipe_grro_cat.webp'),
(53, 'Cat', 'Grroming', 'Eye Cleaning Pads', 'Gentle pads that help keep your cat\'s eyes clean and fresh.', 480, '4', 'eye_grro_cat.jpg'),
(54, 'Cat', 'Grroming', 'Grooming Kit', 'A complete grooming set for your cat\'s daily care.', 1859, '28', 'kit_grro_cat.webp'),
(55, 'Dog', 'Bad', 'Round ', 'A soft round bed for cozy and comfortable sleep.', 768, '27', 'round_bed_dog.jpg'),
(56, 'Dog', 'Bad', 'Orthopedic ', 'A supportive bed that provides comfort for joints and muscles.', 899, '14', 'orthopedic_bed_dog.webp'),
(57, 'Dog', 'Bad', 'Memory Foam ', 'A foam bed that offers superior comfort and support.', 985, '15', 'memory_bad_dog.webp'),
(58, 'Dog', 'Bad', 'Foldable ', 'A portable bed perfect for travel and storage.', 1017, '20', 'foldable_bad_dog.webp'),
(59, 'Dog', 'Bad', 'Luxury Sofa', 'A stylish sofa bed designed for premium comfort.', 1975, '14', 'sofa_bad_dog.webp'),
(60, 'Dog', 'Bad', 'Cooling Mat', 'A cooling mat that helps keep dogs comfortable in hot weather.', 1045, '12', 'cooling_bad_dog.webp'),
(61, 'Dog', 'Bad', 'Hooded ', 'A covered bed that provides warmth, privacy, and comfort.', 1145, '13', 'hooded_bad_dog.webp'),
(62, 'Dog', 'Bad', 'Waterproof ', 'A durable waterproof bed that is easy to clean.', 999, '14', 'water_bad_dog.webp'),
(63, 'Dog', 'cloth', 'T-Shirt', 'A soft and comfortable T-shirt for everyday wear.', 546, '31', 'tshirt_cloth_dog.webp'),
(64, 'Dog', 'cloth', ' Hoodie', 'A cozy hoodie that keeps your dog warm and stylish.', 489, '23', 'hoodies_cloth_dog.webp'),
(65, 'Dog', 'cloth', ' Sweater', 'A warm sweater perfect for cold weather.', 482, '10', 'sweater_cloth_dog.webp'),
(66, 'Dog', 'cloth', ' Jacket', 'A durable jacket that provides warmth and protection.', 875, '14', 'jacket_cloth_dog.webp'),
(67, 'Dog', 'cloth', ' Raincoat', 'A waterproof raincoat that keeps your dog dry.', 478, '10', 'raincot_cloth_dog.webp'),
(68, 'Dog', 'cloth', ' Winter Coat', 'A thick winter coat for extra warmth during cold days.', 899, '15', 'winter_cloth_dog.webp'),
(69, 'Dog', 'cloth', ' Dress', 'A cute dress for special occasions and daily style.', 540, '10', 'dress_cloth_dog.webp'),
(70, 'Dog', 'cloth', ' Costume Outfit', 'A fun costume outfit for parties and festive events.', 1059, '13', 'outfit_cloth_dog.webp'),
(71, 'Dog', 'Food', 'Premium Adult ', 'Balanced nutrition for adult dogs with protein, vitamins and essential nutrients for daily health.', 1034, '10', 'adult_food_dog.avif'),
(72, 'Dog', 'Food', 'Puppy Growth Food', 'Specially formulated puppy food to support healthy growth, energy and development.', 897, '-3', 'puppy_food_dog.webp'),
(73, 'Dog', 'Food', 'Chicken Flavour', 'Tasty chicken-flavoured food with quality nutrients to make daily meals delicious and nutritious.', 998, '2', 'meat_food_dog.jpg'),
(74, 'Dog', 'Food', 'Lamb & Rice', 'A tasty lamb and rice recipe providing balanced nutrition for everyday feeding.', 994, '28', 'gobble_food_dog.webp'),
(75, 'Dog', 'Food', 'Beef Flavour ', 'Delicious beef-flavoured meal packed with essential nutrients for active dogs.', 620, '3', 'beef_food_dog.webp'),
(76, 'Dog', 'Food', 'Small Breed', 'Specially sized and balanced food for small-breed dogs and their daily nutritional needs.', 785, '3', 'small_food_dog.webp'),
(77, 'Dog', 'Food', 'Vegetable & Chicken ', 'A tasty combination of chicken and vegetables with essential nutrients for daily meals.', 755, '21', 'vegitable_food_dog.webp'),
(78, 'Dog', 'Food', 'Fidele', 'Nutritious food formulated for older dogs to support their everyday health and wellbeing.', 866, '21', 'fidele_food_dog.webp'),
(79, 'Dog', 'Grroming', ' Shampoo', 'Gentle shampoo for a clean and fresh coat', 1034, '10', 'shampoo_grro_dog.webp'),
(80, 'Dog', 'Grroming', ' Conditioner', 'Helps keep your dog\'s coat soft and smooth.', 2017, '14', 'conditionar_grro_dog.webp'),
(81, 'Dog', 'Grroming', ' Brush', 'Removes loose hair and keeps the coat neat.', 240, '10', 'brush+grro_dog.webp'),
(82, 'Dog', 'Grroming', 'Flea Comb', 'Helps remove fleas and loose dirt from fur.', 140, '30', 'comb_grro_dog.webp'),
(83, 'Dog', 'Grroming', 'Paw Cleaner', 'Helps clean dirt from your dog\'s paws.', 160, '6', 'paw_grro_dog.jpg'),
(84, 'Dog', 'Grroming', 'Nail Clipper', 'Easy-to-use clipper for maintaining dog nails.', 140, '10', 'nail_grro_dog.webp'),
(88, 'Fish', 'Bowl', 'Classic Round Glass Fish Bowl', 'Clear glass bowl, perfect for small fish.', 750, '8', '1.png'),
(89, 'Fish', 'Bowl', 'Mini Round Fish Bowl', 'Small and stylish bowl for small fish.', 840, '8', '4.png'),
(90, 'Fish', 'Bowl', 'Fish-Shaped Glass Bowl', 'Unique fish-shaped bowl for small fish.', 890, '7', '8.png'),
(91, 'Fish', 'Bowl', 'Premium Round Aquarium Bowl', 'Elegant round bowl for a beautiful aquarium setup.', 1140, '12', '7.png'),
(92, 'Fish', 'Bowl', 'Triangle Glass Fish Bowl', 'Modern triangle-shaped bowl for small fish.', 1487, '14', '6.png'),
(93, 'Fish', 'Decorative', 'Mini Green Aquarium Tree', 'Small artificial tree that gives your aquarium a natural green look.', 157, '14', 'deco_log_fish.webp'),
(94, 'Fish', 'Decorative', 'Aquarium Hut Decoration', 'Attractive hut decoration that adds a unique underwater look.', 540, '15', 'deco_house_fish.jpg'),
(95, 'Fish', 'Decorative', 'Bonsai Aquarium Plant', 'Artificial bonsai plant for a natural and elegant aquarium setup.', 578, '10', 'deco_plant_deco.webp'),
(96, 'Fish', 'Decorative', 'Natural Aquarium Stones', 'Natural-looking stones that give the aquarium a beautiful rocky appearance.', 871, '13', 'deco_stone_fisha.webp'),
(97, 'Fish', 'Decorative', 'Colorful Aquarium Stones', 'Smooth colorful stones that add attractive decoration to the fish tank.', 540, '12', 'deco_color_fish.webp'),
(98, 'Fish', 'Decorative', 'Wooden Root ', 'Root-shaped aquarium decoration for a natural underwater appearance.', 549, '8', 'deco_wood_fish.webp'),
(99, 'Fish', 'Decorative', 'Sunken Ship ', 'Small shipwreck decoration for creating an exciting underwater scene.', 987, '5', 'deco_ship_fish.jpg'),
(100, 'Fish', 'Decorative', 'Aquarium Log ', 'Artificial log that gives the aquarium a natural forest-like appearance.', 584, '4', 'deco_log_fish.webp'),
(101, 'Fish', 'Food', 'Fish Flakes', 'Nutritious flakes for daily feeding of aquarium fish', 354, '4', 'flack_food_fish.jpg'),
(102, 'Fish', 'Food', 'Fish Pellets', 'Easy-to-feed pellets that provide balanced fish nutrition.\r\n', 471, '5', 'pellet_food_fish.webp'),
(103, 'Fish', 'Food', 'Shrimp Food', 'Special sinking food for aquarium shrimp.', 450, '4', 'premier_food_fish.webp'),
(104, 'Fish', 'Food', 'Koi Fish Food', 'Balanced floating food for healthy koi growth.', 240, '10', '1790077792_koi_food_fish.webp'),
(105, 'Fish', 'Food', 'Guppy Food', 'Small food pellets suitable for daily guppy feeding.', 547, '14', '1790077510_guppy_food_fish.webp'),
(106, 'Fish', 'Food', 'Granules', 'Small granules for easy daily fish feeding.', 350, '16', '1790077465_bits_food_fish.webp');

-- --------------------------------------------------------

--
-- Table structure for table `ragistration`
--

CREATE TABLE `ragistration` (
  `Id` int(10) NOT NULL,
  `fullname` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(10) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `city` varchar(20) NOT NULL,
  `address` varchar(40) NOT NULL,
  `utype` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ragistration`
--

INSERT INTO `ragistration` (`Id`, `fullname`, `email`, `password`, `phone`, `gender`, `city`, `address`, `utype`) VALUES
(1, 'madhuri Chauhan', 'chauhanmadhuri231@gmail.com', '123', '123456', 'Female', 'Jamnagar', 'Shuthar seri ,Pipartoda,Lalpur', ''),
(7, 'dimple', 'dimple@gmail.com', 'd@123', '2147483647', 'Female', 'Jamnagar', 'jamngar', ''),
(11, 'admin', 'admin@gmail.com', 'admin123', '1584796320', 'Female', 'Jamnagar', 'junagadh', 'admin'),
(12, 'jemika', 'jemika123@gmail.com', 'jemika123', '2147483647', 'Female', '', 'surat', ''),
(13, 'abc', 'abc@gmail.com', '123456', '123456', 'Female', 'Rajkot', 'yryru', ''),
(14, 'jenisa', 'jenisa@gmail.com', 'jenisa123', '7284796320', 'Male', 'Rajkot', 'lilapur', '');

-- --------------------------------------------------------

--
-- Table structure for table `sub_cat`
--

CREATE TABLE `sub_cat` (
  `id` int(2) NOT NULL,
  `category_name` varchar(20) NOT NULL,
  `sub_cat` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sub_cat`
--

INSERT INTO `sub_cat` (`id`, `category_name`, `sub_cat`) VALUES
(1, 'Bird', 'Food'),
(2, 'Bird', 'Decorative'),
(3, 'Cat', 'Food'),
(4, 'Cat', 'cloth'),
(5, 'Cat', 'Grroming'),
(6, 'Cat', 'Bad'),
(7, 'Dog', 'Food'),
(8, 'Dog', 'cloth'),
(9, 'Dog', 'Grroming'),
(10, 'Dog', 'Bad'),
(11, 'Fish', 'Food'),
(12, 'Fish', 'Decorative'),
(16, 'Fish', 'Bowl');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addtocart`
--
ALTER TABLE `addtocart`
  ADD PRIMARY KEY (`c_id`);

--
-- Indexes for table `bill`
--
ALTER TABLE `bill`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_bill_u_id` (`u_id`),
  ADD KEY `idx_bill_order_date` (`order_date`),
  ADD KEY `idx_bill_order_status` (`order_status`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_orders_bill_id` (`bill_id`),
  ADD KEY `idx_orders_product_id` (`product_id`),
  ADD KEY `idx_orders_u_id` (`u_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ragistration`
--
ALTER TABLE `ragistration`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `sub_cat`
--
ALTER TABLE `sub_cat`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addtocart`
--
ALTER TABLE `addtocart`
  MODIFY `c_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `bill`
--
ALTER TABLE `bill`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `Id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `ragistration`
--
ALTER TABLE `ragistration`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `sub_cat`
--
ALTER TABLE `sub_cat`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_bill` FOREIGN KEY (`bill_id`) REFERENCES `bill` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
