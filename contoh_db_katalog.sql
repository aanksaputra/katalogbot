-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 13, 2016 at 02:24 PM
-- Server version: 5.5.52-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bookmart_katalog`
--

-- --------------------------------------------------------

--
-- Table structure for table `katalog_buku`
--

CREATE TABLE IF NOT EXISTS `katalog_buku` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `id` varchar(9) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `kode` varchar(25) DEFAULT NULL,
  `judul` varchar(250) DEFAULT NULL,
  `penulis` varchar(150) DEFAULT NULL,
  `isbn` varchar(150) DEFAULT NULL,
  `harga` varchar(150) DEFAULT NULL,
  `ukuran` varchar(25) DEFAULT NULL,
  `sinopsis` text,
  `profil_penulis` text,
  `kategori` varchar(100) DEFAULT NULL,
  `tahun_terbit` varchar(4) DEFAULT NULL,
  `penerbit` varchar(150) DEFAULT NULL,
  `stock` varchar(5) DEFAULT NULL,
  `photo` varchar(50) DEFAULT NULL,
  `tanggal_input` datetime NOT NULL,
  PRIMARY KEY (`no`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `katalog_buku`
--

INSERT INTO `katalog_buku` (`no`, `id`, `username`, `password`, `kode`, `judul`, `penulis`, `isbn`, `harga`, `ukuran`, `sinopsis`, `profil_penulis`, `kategori`, `tahun_terbit`, `penerbit`, `stock`, `photo`, `tanggal_input`) VALUES
(1, '138383046', 'admin', '202cb962ac59075b964b07152d234b70', 'BMK-763901Z', 'Aku Datang Memenuhi Panggilan-Mu: Panduan Doa dan Ibadah Haji/Umrah', 'Freddy Rangkuti, Siti Haniah', '978-979-22-9135-3', 'Rp. 56000.00 ,-', '13.5 x 20', 'Persiapkan diri Anda menjawab panggilan-Nya ke Tanah Suci dengan\nAku Datang Memenuhi Panggilan-Mu: Panduan Doa & Ibadah Haji/Umrah\n\nMenggunakan metode foto yang dapat “berbicara”, buku ini disusun agar enak dilihat dan mudah dibaca, untuk pembaca dari segala usia.\n\nKeterangan penting, seperti:\n- persiapan diri—jasmani dan rohani\n- kegiatan di asrama dan pesawat\n- tata cara ibadah haji/umrah dan ziarah\n- tawaf, sai, wukuf di arafah, dan ziarah di Madinah\n- tip soal makanan, minuman, juga keselamatan diri\n- tempat yang mustajab untuk berdoa, seperti Arafah, Multazam, dan Raudah; serta\n- evaluasi diri saat kembali ke tanah air\n\nmempermudah Anda melaksanakan ibadah yang sangat penting dalam hidup kaum muslim, yaitu haji dan umrah. Diselingi kisah-kisah unik dan lucu di Tanah Suci, buku ini menyediakan pengetahuan yang membuat ibadah Anda berjalan lebih nyaman, aman, juga  menyenangkan. Pada akhirnya, kunjungan Anda ke rumah Allah pun jadi lebih khusyuk dan “dekat”.', 'http://www.gramediapustakautama.com/author/38505/detail', 'Religi', '2016', 'Kompas Gramedia', '5', 're-buku-picture-87152.jpg', '2016-09-22 14:47:35'),
(2, '138383046', 'admin', '202cb962ac59075b964b07152d234b70', 'BMK-962157K', 'Hidup Sesudah Mati', 'Raymond A. Moody JR. M.D.', '978-979-22-9914-4', 'Rp. 45000.00 ,-', '13.5 x 20', 'Pengalaman Kematian\nSeorang sedang menghadapi maut dan pada saat ia mencapai puncak krisis fisiknya, ia mendengar bahwa ia dinyatakan mati oleh dokternya. Ia mulai mendengar suatu bunyi yang tidak menyenangkan, suara berdering atau mendesing, dan pada saat yang sama ia merasa dirinya bergerak dengan cepat melalui suatu terowongan panjang yang gelap. Setelah ini, ternyata ia berada di luar jasadnya.... Tak lama kemudian hal-hal lain mulai terjadi. Arwah-arwah lain mulai menyongsong dan menolongnya. Ia melihat arwah saudara-saudara dan kawan-kawannya yang telah meninggal, dan suatu roh yang penuh kehangatan dan cinta kasih yang belum pernah ditemuinya, suatu makhluk cahaya, muncul di hadapannya. Selama lima tahun Dr. Raymond Moody telah mempelajari lebih dari seratus orang yang telah mengalami “kematian klinis” dan telah dihidupkan kembali. Sangat menakjubkan bahwa cerita mereka mengenai pengalaman ini menunjukkan ciri-ciri yang sama.\n\n“Penyelidikan seperti yang disajikan oleh Dr. Moody dalam buku inilah yang akan memberi penjelasan kepada banyak orang, dan akan menegaskan apa yang telah diajarkan kepada kita selama dua ribu tahun—bahwa ada kehidupan setelah mati.”\n—Dari kata pengantar oleh Elisabeth Kubler-Ross, M.D.', 'http://www.gramediapustakautama.com/author/35157/detail', 'Religi', '2016', 'Kompas Gramedia', '5', 're-buku-picture-87350.jpg', '2016-09-24 02:06:48');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
