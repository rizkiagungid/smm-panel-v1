-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 10, 2023 at 03:44 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.0.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jagoansmm`
--

-- --------------------------------------------------------

--
-- Table structure for table `balance_logs`
--

CREATE TABLE `balance_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(5) COLLATE utf8_swedish_ci NOT NULL,
  `amount` double NOT NULL,
  `note` text COLLATE utf8_swedish_ci NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'SoundCloud'),
(2, 'Telegram'),
(3, 'Instagram Story Views'),
(4, 'Instagram Live Video'),
(5, 'Instagram Story / Impressions / Saves / Profile Visit'),
(6, 'Twitter Views & Impressions'),
(7, 'Linkedin'),
(8, 'Website Traffic'),
(9, 'Youtube Views'),
(10, 'Spotify'),
(11, 'Facebook Page Likes & Page Followers'),
(12, 'Pinterest'),
(13, 'Shopee/Tokopedia/Bukalapak'),
(14, 'Instagram Like Indonesia'),
(15, 'Instagram Likes'),
(16, 'TikTok Followers'),
(17, 'TikTok Likes'),
(18, 'Youtube Likes / Dislikes / Shares / Comment'),
(19, 'Instagram Followers Indonesia'),
(20, 'Facebook Followers / Friends'),
(21, 'Facebook Post Likes / Comments / Shares'),
(22, 'Instagram Followers [ No Refill ]'),
(23, 'TikTok View/share'),
(24, 'Youtube Live Stream / Youtube Premiered Waiting'),
(25, 'Likee app'),
(26, 'Youtube View Target Negara'),
(27, 'Instagram Followers [guaranteed]'),
(28, 'Twitter Indonesia'),
(29, 'Youtube View Jam Tayang'),
(30, 'Instagram TV'),
(31, 'Instagram Followers Indonesia Guaranted/Refill'),
(32, 'Youtube Subscribers'),
(33, 'Instagram Comments'),
(34, 'Clubhouse'),
(35, 'TikTok INDONESIA'),
(36, 'Instagram Reels'),
(37, 'Facebook Video Views / Live Stream'),
(38, 'Twitter Followers'),
(39, 'YouTube Shorts'),
(40, 'Youtube View  [ untuk monetisasi - penghasil duit ]'),
(41, 'Tiktok Live Streams'),
(42, 'Instagram VERIFIED '),
(43, 'Instagram Views'),
(44, 'Instagram Like Komentar [ top koment ]'),
(45, '- PROMO - ON OFF'),
(46, 'Twitter Favorites/Like'),
(47, 'Instagram Followers Indonesia [ Gender/Kelamin ]'),
(48, 'Tiktok Comments '),
(49, 'Twitch'),
(50, 'Facebook Post Like Emoticon'),
(51, 'Facebook Reels Short Video'),
(52, 'YouTube Live Stream [ Harga Murah ] [ 30 Minutes to 24 Hours]');

-- --------------------------------------------------------

--
-- Table structure for table `deposits`
--

CREATE TABLE `deposits` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `payment` enum('pulsa','bank','redeem') COLLATE utf8_swedish_ci NOT NULL,
  `type` enum('manual','auto') COLLATE utf8_swedish_ci NOT NULL,
  `method_name` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `post_amount` double NOT NULL,
  `amount` double NOT NULL,
  `note` text COLLATE utf8_swedish_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `status` enum('Pending','Canceled','Success') COLLATE utf8_swedish_ci NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deposit_methods`
--

CREATE TABLE `deposit_methods` (
  `id` int(11) NOT NULL,
  `payment` enum('pulsa','bank') COLLATE utf8_swedish_ci NOT NULL,
  `type` enum('manual','auto') COLLATE utf8_swedish_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `note` text COLLATE utf8_swedish_ci NOT NULL,
  `rate` double NOT NULL,
  `min_amount` double NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_logs`
--

CREATE TABLE `login_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip_address` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Dumping data for table `login_logs`
--

INSERT INTO `login_logs` (`id`, `user_id`, `ip_address`, `created_at`) VALUES
(1, 4, '::1', '2023-01-10 09:33:13'),
(2, 4, '::1', '2023-01-10 09:42:21');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `content` text COLLATE utf8_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `created_at`, `content`) VALUES
(1, '2022-12-22 22:43:02', 'Hallo, Selamat Datang !');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_name` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `data` text COLLATE utf8_swedish_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` double NOT NULL,
  `profit` double NOT NULL,
  `start_count` int(11) NOT NULL DEFAULT 0,
  `remains` int(11) NOT NULL DEFAULT 0,
  `status` enum('Pending','Processing','Error','Partial','Success') COLLATE utf8_swedish_ci NOT NULL,
  `provider_id` int(11) NOT NULL,
  `provider_order_id` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `is_api` int(1) NOT NULL DEFAULT 0,
  `is_refund` int(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `api_order_log` text COLLATE utf8_swedish_ci DEFAULT NULL,
  `api_status_log` text COLLATE utf8_swedish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `content` text COLLATE utf8_swedish_ci NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `content`, `updated_at`) VALUES
(1, 'Informasi Kontak\r\nRizki Agung Test\r\n0812-9005-36111', '2019-03-20 10:18:14'),
(2, 'Syarat & Ketentuan RIZKIAGUNGID telah ditetapkan kesepakatan-kesepakatan berikut.\r\n\r\n1. UMUM\r\n\r\nDengan mendaftar dan menggunakan layanan Youtube secara otomatis anda menyetujui semua ketentuan yang kami buat. Ketentuan bisa saja berubah sewaktu-waktu tanpa pemberitahuan terlebih dahulu.\r\n\r\n2. LAYANAN\r\n\r\nRIZKIAGUNGID hanya untuk sarana promosi. Hanya untuk membatu meningkatkan \"penampilan\" Akun sosial media anda.\r\nRIZKIAGUNGID tidak dapat memastikan pengikut baru anda akan berinteraksi dengan anda.\r\nRIZKIAGUNGID hanya menjamin anda akan mendapatkan pengikut sesuai yang anda bayar.\r\nRIZKIAGUNGID tidak menjamin 100% dari akun kami memiliki gambar profil atau bio yang lengkap.\r\nRIZKIAGUNGID tidak akan mengembalikan saldo jika anda salah memesan. Pastikan anda memasukan data yang benar sebelum memesan layanan.\r\nRIZKIAGUNGID Anda Tidak Dapat Melakukan Pemesanan Untuk Hal Yang Bersifat Melanggar Hukum.\r\nRIZKIAGUNGID Tidak Menjamin Semua Layanan Dapat Bertahan Selamanya.\r\n\r\n3. TANGGUNG JAWAB\r\n\r\nRIZKIAGUNGID sama sekali tidak bertanggung jawab atas kerugian yang mungkin terjadi pada bisnis anda.\r\nRIZKIAGUNGID tidak bertanggung jawab jika terjadi penanguhan akun,penghapusan foto atau video atau bahkan pembokiran akun sosial media anda.\r\nRIZKIAGUNGID tidak bertanggung jawab atas penyalahgunaan layanan yang kami sediakan.\r\nRIZKIAGUNGID di bebaskan dari segala tuntutan hukum.\r\n\r\n4. HARGA\r\n\r\nHarga yang kami tawarkan dapat berubah sewaktu-waktu. Dengan pemberitahuan atau tanpa pemberitahuan.\r\n\r\n5. PEMESANAN\r\n\r\nPesanan yang sudah di input tidak dapat di batalkan.\r\nWaktu pengerjaan yang kami lampirkan di diskripsi hanyalah perkiraan.\r\n\r\n6. SALDO\r\n\r\nTidak ada pengembalian uang yang akan dilakukan ke metode pembayaran Anda. Setelah deposit selesai, tidak ada cara untuk mengembalikannya. Anda harus menggunakan saldo Anda atas perintah dari Youtube.\r\nAnda setuju bahwa setelah Anda menyelesaikan pembayaran, Anda tidak akan mengajukan sengketa atau tagihan balik kepada kami karena alasan apa pun.\r\n\r\n7. AKUN\r\n\r\nKami tidak akan membantu apapun yang terjadi pada akun anda jika data yang anda inputkan saat pendaftaran tidak sesuai dengan kriteria yang telah kami sarankan.\r\nJika Anda melakukan pendaftaran dan tidak melakukan deposit atau pengisian saldo dalam waktu lebih dari 1 hari maka akun Anda otomatis akan dinonaktifkan oleh sistem. Jika Anda terbukti melakukan kecurangan dalam bertransaksi di Youtube maka kami akan menonaktifkan atau bisa saja menghapus akun Anda dari website kami.', '2019-03-20 00:00:00'),
(3, '1. Apa itu RIZKIAGUNGID?\r\nRIZKIAGUNGID adalah sebuah platform bisnis yang menyediakan berbagai layanan sosial media marketing yang bergerak terutama di Indonesia. Dengan bergabung bersama kami, Anda dapat menjadi penyedia jasa sosial media atau reseller social media seperti jasa penambah Followers, Likes, dll.\r\n\r\n2. Bagaimana cara mendaftar di Youtube?\r\nAnda dapat langsung mendaftar di website Youtube pada halaman Daftar\r\n\r\n3. Bagaimana cara membuat pesanan?\r\nUntuk membuat pesanan sangatlah mudah, Anda hanya perlu masuk terlebih dahulu ke akun Anda dan menuju halaman pemesanan dengan mengklik menu yang sudah tersedia. Selain itu Anda juga dapat melakukan pemesanan melalui request API.\r\n\r\n4. Bagaimana cara melakukan deposit/isi saldo?\r\nUntuk melakukan deposit/isi saldo, Anda hanya perlu masuk terlebih dahulu ke akun Anda dan menuju halaman deposit dengan mengklik menu yang sudah tersedia. Kami menyediakan deposit melalui bank dan pulsa.', '2019-03-20 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `provider`
--

CREATE TABLE `provider` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `api_url_order` text COLLATE utf8_swedish_ci NOT NULL,
  `api_url_status` text COLLATE utf8_swedish_ci NOT NULL,
  `api_key` varchar(255) COLLATE utf8_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Dumping data for table `provider`
--

INSERT INTO `provider` (`id`, `name`, `api_url_order`, `api_url_status`, `api_key`) VALUES
(1, 'RASXMEDIA', 'https://rasxmedia.com/api/social-media', 'https://rasxmedia.com/api/social-media', '123456');

-- --------------------------------------------------------

--
-- Table structure for table `register_logs`
--

CREATE TABLE `register_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip_address` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `service_name` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `note` text COLLATE utf8_swedish_ci NOT NULL,
  `min` int(11) NOT NULL,
  `max` int(11) NOT NULL,
  `price` double NOT NULL,
  `profit` double NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `provider_id` int(11) NOT NULL,
  `provider_service_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_cat`
--

CREATE TABLE `service_cat` (
  `id` int(10) NOT NULL,
  `name` varchar(100) COLLATE utf8_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `msg` text COLLATE utf8_swedish_ci NOT NULL,
  `status` enum('Waiting','Responded','Closed') COLLATE utf8_swedish_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_replies`
--

CREATE TABLE `ticket_replies` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `is_admin` int(1) NOT NULL DEFAULT 0,
  `msg` text COLLATE utf8_swedish_ci NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `full_name` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `balance` double NOT NULL,
  `level` enum('Member','Reseller','Admin') COLLATE utf8_swedish_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `api_key` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `remember_me` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `voucher_code` text COLLATE utf8_swedish_ci NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `balance_logs`
--
ALTER TABLE `balance_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deposits`
--
ALTER TABLE `deposits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deposit_methods`
--
ALTER TABLE `deposit_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_logs`
--
ALTER TABLE `login_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `provider`
--
ALTER TABLE `provider`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `register_logs`
--
ALTER TABLE `register_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_cat`
--
ALTER TABLE `service_cat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `balance_logs`
--
ALTER TABLE `balance_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `deposits`
--
ALTER TABLE `deposits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deposit_methods`
--
ALTER TABLE `deposit_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_logs`
--
ALTER TABLE `login_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `provider`
--
ALTER TABLE `provider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `register_logs`
--
ALTER TABLE `register_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=440;

--
-- AUTO_INCREMENT for table `service_cat`
--
ALTER TABLE `service_cat`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
