DROP TABLE IF EXISTS amh_email_log;

CREATE TABLE `amh_email_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `to` text NOT NULL,
  `to_comp_add` text NOT NULL,
  `to_city` text NOT NULL,
  `from` text NOT NULL,
  `subject` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `email_body` text NOT NULL,
  `salut` text NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `ref` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




DROP TABLE IF EXISTS bag;

CREATE TABLE `bag` (
  `bag_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer` varchar(60) NOT NULL,
  `job_type` varchar(60) NOT NULL,
  `size` varchar(60) NOT NULL,
  `cost_constant` double NOT NULL,
  `paper_type` varchar(60) NOT NULL,
  `plate_type` varchar(60) NOT NULL,
  `print_cost_per_color` double NOT NULL,
  `item_per_page` double NOT NULL,
  `required_quantity` double NOT NULL,
  `on_plate` double NOT NULL,
  `bind_type` varchar(60) NOT NULL,
  `profit_margin` double NOT NULL,
  `folding_price` double NOT NULL,
  `folding_count` double NOT NULL,
  `page_waste` double NOT NULL,
  `vat` double NOT NULL,
  `lam_type` varchar(255) NOT NULL,
  `commitonprice` double NOT NULL,
  `cvat` double NOT NULL,
  `private` varchar(255) NOT NULL,
  `machine_type` double NOT NULL,
  `unit_price` double NOT NULL,
  `total_price` double NOT NULL,
  `total_price_vat` double NOT NULL,
  `machine_run` double NOT NULL,
  `print_price` double NOT NULL,
  `required_paper` double NOT NULL,
  `required_paper_per_reem` double NOT NULL,
  `paper_cost` double NOT NULL,
  `plate_price` double NOT NULL,
  `profit` double NOT NULL,
  `lamination` double NOT NULL,
  `date` date NOT NULL,
  `payment_status` int(11) NOT NULL,
  `folding_price_calc` double NOT NULL,
  `print_color` double NOT NULL,
  PRIMARY KEY (`bag_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO bag VALUES("2","Safi","Paper Bag","20X15.5X6.5","3000","2","1","4","3","500","1","2","50","0","12","10","15","2","0","0","choose","1","167.16","83580","96117","2000","8000","176.66666666667","1.7666666666667","7420","2800","27860","9500","2024-08-26","0","0","0"),
("3","Safi","Paper Bag Printing","-","3000","2","1","1","3","400","1","2","60","0.6","6","20","15","3","0","0","choose","1","164.32","65728","75587.2","1600","1600","153.33333333333","1.5333333333333","6440","2800","24648","5800","2024-08-26","0","1440","4"),
("4","Safi","Paper Bag Printing small","A3","1500","2","1","1","4","400","2","2","60","0.6","6","20","15","3","0","0","choose","1","149.52","59808","68779.2","800","800","120","1.2","5040","2800","22428","5800","2024-08-26","0","1440","4"),
("5","Safi","Paper Bag Printing small","A3","1500","2","1","1","4","500","2","2","60","0.6","6","20","15","3","0","0","choose","1","145.408","72704","83609.6","1000","1000","145","1.45","6090","2800","27264","7250","2024-08-27","0","1800","4");



DROP TABLE IF EXISTS bank;

CREATE TABLE `bank` (
  `bank_id` int(11) NOT NULL AUTO_INCREMENT,
  `client` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `date` varchar(100) NOT NULL,
  `reference_number` varchar(100) NOT NULL,
  `info_amount` double NOT NULL,
  `amount` double NOT NULL,
  `verified` int(11) NOT NULL DEFAULT -1,
  `jobnumber` varchar(2555) NOT NULL,
  `taxwithholding` float NOT NULL,
  `check_no` varchar(155) DEFAULT NULL,
  PRIMARY KEY (`bank_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO bank VALUES("1","Prime Meat and Food Products ","","2024-08-25","BB49578","5000","0","1","jn_0030","0","0"),
("2","UNDP","","2024-08-28","BB49578","100000","98000","1","jn_0036","0","0");



DROP TABLE IF EXISTS bank_payment;

CREATE TABLE `bank_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client` varchar(200) NOT NULL,
  `method` varchar(200) NOT NULL,
  `ref` varchar(200) NOT NULL,
  `amount` varchar(200) NOT NULL,
  `jobnumber` varchar(200) NOT NULL,
  `tax` varchar(200) NOT NULL,
  `verify` varchar(200) NOT NULL,
  `date` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




DROP TABLE IF EXISTS bankdb;

CREATE TABLE `bankdb` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bankname` varchar(255) NOT NULL,
  `accountnumber` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




DROP TABLE IF EXISTS banner;

CREATE TABLE `banner` (
  `banner_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer` text NOT NULL,
  `job_type` text NOT NULL,
  `size` text NOT NULL,
  `required_quantity` float NOT NULL,
  `total_price` float NOT NULL,
  `unit_price` float NOT NULL,
  `total_price_vat` float NOT NULL,
  `vat` float NOT NULL,
  `cvat` double NOT NULL,
  `width` float NOT NULL,
  `lengths` float NOT NULL,
  `kare` float NOT NULL,
  `totalkare` float NOT NULL,
  `commitonprice` float NOT NULL,
  `date` date NOT NULL,
  `private` varchar(255) NOT NULL,
  `unitbanner_type` varchar(255) NOT NULL,
  `product` varchar(255) NOT NULL,
  `payment_status` tinyint(1) NOT NULL DEFAULT 0,
  `banner_metal` varchar(255) NOT NULL,
  `banner_metal_type` int(11) DEFAULT NULL,
  PRIMARY KEY (`banner_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO banner VALUES("8","UNDP","Backdrop banner","300X500","1","8250","1","9487.5","15","0","5","3","15","15","0","2024-08-27","choose","1","outsource","0","no","0"),
("9","UNDP","Backdrop banner","300X500","1","8250","8250","9487.5","15","0","5","3","15","15","0","2024-08-27","choose","1","outsource","1","no","0");



DROP TABLE IF EXISTS banner_metal;

CREATE TABLE `banner_metal` (
  `banner_metal_id` int(11) NOT NULL AUTO_INCREMENT,
  `banner_metal_name` varchar(100) NOT NULL,
  `price` double NOT NULL,
  `stock_id` int(11) NOT NULL,
  PRIMARY KEY (`banner_metal_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO banner_metal VALUES("1","Rollup WIdebase","7500","6");



DROP TABLE IF EXISTS banner_out;

CREATE TABLE `banner_out` (
  `banner_out_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer` text NOT NULL,
  `job_type` text NOT NULL,
  `size` text NOT NULL,
  `required_quantity` float NOT NULL,
  `total_price` float NOT NULL,
  `unit_price` float NOT NULL,
  `total_price_vat` float NOT NULL,
  `vat` float NOT NULL,
  `cvat` double NOT NULL,
  `width` float NOT NULL,
  `lengths` float NOT NULL,
  `kare` float NOT NULL,
  `totalkare` float NOT NULL,
  `commitonprice` float NOT NULL,
  `date` date NOT NULL,
  `private` varchar(255) NOT NULL,
  `unitbanner_type` int(11) NOT NULL,
  `payment_status` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`banner_out_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO banner_out VALUES("9","UNDP","Backdrop banner","300X500","1","3450","3450","3967.5","15","0","5","3","0","230","0","2024-08-27","","0","1");



DROP TABLE IF EXISTS bind;

CREATE TABLE `bind` (
  `bind_id` int(11) NOT NULL AUTO_INCREMENT,
  `bind_type` varchar(100) NOT NULL,
  `bind_price` int(11) NOT NULL,
  PRIMARY KEY (`bind_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO bind VALUES("1","Saddle Stich","3"),
("2","Paper Bag Binding with Rob","50"),
("3","Perfect Binding A4","18");



DROP TABLE IF EXISTS book;

CREATE TABLE `book` (
  `book_id` int(11) NOT NULL AUTO_INCREMENT,
  `common_var` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `cover_input` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `page_input` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `cover_output` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `page_output` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `total_output` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `date` date NOT NULL,
  `private` varchar(255) NOT NULL,
  `payment_status` tinyint(1) NOT NULL DEFAULT 0,
  `machine_type` int(11) NOT NULL,
  `digital_machine_type` int(11) DEFAULT NULL,
  `digital_machine_run` double DEFAULT NULL,
  `digital_print_side` double DEFAULT NULL,
  `types` varchar(255) NOT NULL,
  `print_type` varchar(255) NOT NULL,
  PRIMARY KEY (`book_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO book VALUES("17","{\"customer\":\"Hamrawi Advertising\",\"job_type\":\"Booklet printing 12 pages including cover full color\",\"constant_cost\":\"2000\",\"required_quantity\":\"100\",\"profit_margin\":\"30\",\"vat\":\"15\",\"cvat\":\"0\",\"bind_id\":\"1\",\"folding_price\":\"0\",\"folding_counts\":\"0\",\"type\":null,\"size\":\"A4\",\"commitonprice\":\"0\",\"page_waste_paper\":\"10\",\"type_s\":\"cover\"}","{\"cover_paper_id\":\"1\",\"cover_plate_id\":\"1\",\"cover_page_per_a1\":\"4\",\"cover_print_cost\":\"1\",\"cover_on_plate\":\"1\",\"cover_print_color\":\"4\",\"cover_waste_paper\":\"10\",\"cover_lamination_type\":\"none\",\"cover_print_side\":\"1\"}","{\"page_paper_id_a\":\"1\",\"page_plate_id_a\":\"1\",\"page_per_a1_a\":\"16\",\"page_print_cost_a\":\"1\",\"number_of_page_a\":\"8\",\"page_on_plate_a\":\"4\",\"page_print_color_a\":\"4\",\"page_lam_type_a\":\"none\",\"page_paper_id_b\":\"1\",\"page_plate_id_b\":\"1\",\"page_per_a1_b\":\"\",\"page_print_cost_b\":\"\",\"number_of_page_b\":\"\",\"page_on_plate_b\":\"\",\"page_print_color_b\":\"\",\"page_lam_type_b\":\"none\",\"page_paper_id_c\":\"1\",\"page_plate_id_c\":\"1\",\"page_per_a1_c\":\"\",\"page_print_cost_c\":\"\",\"number_of_page_c\":\"\",\"page_on_plate_c\":\"\",\"page_print_color_c\":\"\",\"page_lam_type_c\":\"none\",\"page_paper_id_d\":\"1\",\"page_plate_id_d\":\"1\",\"page_per_a1_d\":\"\",\"page_print_cost_d\":\"\",\"number_of_page_d\":\"\",\"page_on_plate_d\":\"\",\"page_print_color_d\":\"\",\"page_lam_type_d\":\"none\"}","{\"cover_machine_run\":400,\"cover_printing_price\":400,\"cover_required_paper\":0.11000000000000001,\"cover_plate_price\":2800,\"cover_paper_cost\":627.0000000000001,\"cover_lamination_price\":0,\"cover_total_cost\":0,\"cover_required_paper_full\":27.5}","{\"page_machine_run\":800,\"page_printing_price\":800,\"page_required_paper\":0,\"page_plate_price\":5600,\"page_paper_cost\":1254.0000000000002,\"page_lamination_price\":0,\"page_number_of_plate\":null,\"page_waste_paper_total\":0,\"page_total_required_paper\":0.22000000000000003,\"page_total_cost\":0,\"folding_total_price\":0,\"page_paper_require_a_with_waste_mod_full\":55,\"page_paper_require_b_with_waste_mod_full\":0,\"page_paper_require_c_with_waste_mod_full\":0,\"page_paper_require_d_with_waste_mod_full\":0,\"machine_run_page_a\":800,\"machine_run_page_b\":0,\"machine_run_page_c\":0,\"machine_run_page_d\":0,\"machine_type_page_a\":\"1\",\"machine_type_page_b\":\"1\",\"machine_type_page_c\":\"1\",\"machine_type_page_d\":\"1\"}","{\"total_cost\":13781,\"profit_margin\":4134.3,\"total_price\":17915.3,\"total_price_vat\":20602.594999999998,\"unit_price\":179.153,\"unit_price_vat\":206.02595}","2024-08-27","choose","0","1","","","","cover","book"),
("18","{\"customer\":\"Addis Hiwot Hospital \",\"job_type\":\"25cmx17cm company profile, 36 pages including cover, text 150 gm, cover 250 gm with lamination, staple binding\",\"constant_cost\":\"2000\",\"required_quantity\":\"100\",\"profit_margin\":\"60\",\"vat\":\"15\",\"cvat\":\"0\",\"bind_id\":\"1\",\"folding_price\":\"0.20\",\"folding_counts\":\"4\",\"type\":null,\"size\":\"25X17\",\"commitonprice\":\"0\",\"page_waste_paper\":\"15\",\"type_s\":\"cover\"}","{\"cover_paper_id\":\"2\",\"cover_plate_id\":\"1\",\"cover_page_per_a1\":\"4\",\"cover_print_cost\":\"1\",\"cover_on_plate\":\"1\",\"cover_print_color\":\"4\",\"cover_waste_paper\":\"10\",\"cover_lamination_type\":\"4\",\"cover_print_side\":\"2\"}","{\"page_paper_id_a\":\"1\",\"page_plate_id_a\":\"1\",\"page_per_a1_a\":\"16\",\"page_print_cost_a\":\"1\",\"number_of_page_a\":\"32\",\"page_on_plate_a\":\"4\",\"page_print_color_a\":\"4\",\"page_lam_type_a\":\"none\",\"page_paper_id_b\":\"1\",\"page_plate_id_b\":\"1\",\"page_per_a1_b\":\"\",\"page_print_cost_b\":\"\",\"number_of_page_b\":\"\",\"page_on_plate_b\":\"\",\"page_print_color_b\":\"\",\"page_lam_type_b\":\"none\",\"page_paper_id_c\":\"1\",\"page_plate_id_c\":\"1\",\"page_per_a1_c\":\"\",\"page_print_cost_c\":\"\",\"number_of_page_c\":\"\",\"page_on_plate_c\":\"\",\"page_print_color_c\":\"\",\"page_lam_type_c\":\"none\",\"page_paper_id_d\":\"1\",\"page_plate_id_d\":\"1\",\"page_per_a1_d\":\"\",\"page_print_cost_d\":\"\",\"number_of_page_d\":\"\",\"page_on_plate_d\":\"\",\"page_print_color_d\":\"\",\"page_lam_type_d\":\"none\"}","{\"cover_machine_run\":800,\"cover_printing_price\":800,\"cover_required_paper\":0.275,\"cover_plate_price\":2800,\"cover_paper_cost\":1155,\"cover_lamination_price\":1200,\"cover_total_cost\":0,\"cover_required_paper_full\":27.5}","{\"page_machine_run\":3200,\"page_printing_price\":3200,\"page_required_paper\":0,\"page_plate_price\":22400,\"page_paper_cost\":5244,\"page_lamination_price\":0,\"page_number_of_plate\":null,\"page_waste_paper_total\":0,\"page_total_required_paper\":0.92,\"page_total_cost\":0,\"folding_total_price\":80,\"page_paper_require_a_with_waste_mod_full\":230,\"page_paper_require_b_with_waste_mod_full\":0,\"page_paper_require_c_with_waste_mod_full\":0,\"page_paper_require_d_with_waste_mod_full\":0,\"machine_run_page_a\":3200,\"machine_run_page_b\":0,\"machine_run_page_c\":0,\"machine_run_page_d\":0,\"machine_type_page_a\":\"1\",\"machine_type_page_b\":\"1\",\"machine_type_page_c\":\"1\",\"machine_type_page_d\":\"1\"}","{\"total_cost\":39179,\"profit_margin\":23507.399999999998,\"total_price\":62686.399999999994,\"total_price_vat\":72089.35999999999,\"unit_price\":626.8639999999999,\"unit_price_vat\":720.8935999999999}","2024-08-27","choose","0","1","","","","cover","book"),
("19","{\"customer\":\"Addis Hiwot Hospital \",\"job_type\":\"25cmx17cm company profile, 36 pages including cover, text 150 gm, cover 250 gm with lamination, staple binding\",\"constant_cost\":\"2000\",\"required_quantity\":\"100\",\"profit_margin\":\"60\",\"vat\":\"15\",\"cvat\":\"0\",\"bind_id\":\"1\",\"folding_price\":\"0.20\",\"folding_counts\":\"4\",\"type\":null,\"size\":\"25X17\",\"commitonprice\":\"0\",\"page_waste_paper\":\"15\",\"type_s\":\"digital\",\"digital_cost\":1900}","{\"cover_paper_id\":null,\"cover_plate_id\":null,\"cover_page_per_a1\":\"4\",\"cover_print_cost\":\"1\",\"cover_on_plate\":\"1\",\"cover_print_color\":\"4\",\"cover_waste_paper\":\"10\",\"cover_lamination_type\":0,\"cover_print_side\":\"2\",\"unitbanner_type\":\"5\",\"digital_lamination_type\":\"2\"}","{\"page_paper_id_a\":\"1\",\"page_plate_id_a\":\"1\",\"page_per_a1_a\":\"16\",\"page_print_cost_a\":\"1\",\"number_of_page_a\":\"32\",\"page_on_plate_a\":\"4\",\"page_print_color_a\":\"4\",\"page_lam_type_a\":\"none\",\"page_paper_id_b\":\"1\",\"page_plate_id_b\":\"1\",\"page_per_a1_b\":\"\",\"page_print_cost_b\":\"\",\"number_of_page_b\":\"\",\"page_on_plate_b\":\"\",\"page_print_color_b\":\"\",\"page_lam_type_b\":\"none\",\"page_paper_id_c\":\"1\",\"page_plate_id_c\":\"1\",\"page_per_a1_c\":\"\",\"page_print_cost_c\":\"\",\"number_of_page_c\":\"\",\"page_on_plate_c\":\"\",\"page_print_color_c\":\"\",\"page_lam_type_c\":\"none\",\"page_paper_id_d\":\"1\",\"page_plate_id_d\":\"1\",\"page_per_a1_d\":\"\",\"page_print_cost_d\":\"\",\"number_of_page_d\":\"\",\"page_on_plate_d\":\"\",\"page_print_color_d\":\"\",\"page_lam_type_d\":\"none\"}","{\"cover_machine_run\":0,\"cover_printing_price\":0,\"cover_required_paper\":0,\"cover_plate_price\":0,\"cover_paper_cost\":0,\"cover_lamination_price\":0,\"cover_total_cost\":0}","{\"page_machine_run\":3200,\"page_printing_price\":3200,\"page_required_paper\":0,\"page_plate_price\":22400,\"page_paper_cost\":5244,\"page_lamination_price\":0,\"page_number_of_plate\":null,\"page_waste_paper_total\":0,\"page_total_required_paper\":0.92,\"page_total_cost\":0,\"folding_total_price\":80,\"page_paper_require_a_with_waste_mod_full\":230,\"page_paper_require_b_with_waste_mod_full\":0,\"page_paper_require_c_with_waste_mod_full\":0,\"page_paper_require_d_with_waste_mod_full\":0,\"machine_run_page_a\":3200,\"machine_run_page_b\":0,\"machine_run_page_c\":0,\"machine_run_page_d\":0,\"machine_type_page_a\":\"1\",\"machine_type_page_b\":\"1\",\"machine_type_page_c\":\"1\",\"machine_type_page_d\":\"1\"}","{\"total_cost\":37024,\"profit_margin\":22214.399999999998,\"total_price\":59238.399999999994,\"total_price_vat\":68124.15999999999,\"unit_price\":592.3839999999999,\"unit_price_vat\":681.2415999999998}","2024-08-28","choose","0","1","1","100","1","digital","book");



DROP TABLE IF EXISTS brocher;

CREATE TABLE `brocher` (
  `brocher_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer` varchar(100) NOT NULL,
  `job_type` varchar(100) NOT NULL,
  `size` text NOT NULL,
  `paper_id` int(11) NOT NULL,
  `plate_id` int(11) NOT NULL,
  `constant_price` double NOT NULL,
  `margin_in` double NOT NULL,
  `item_per_page` double NOT NULL,
  `required_quantity` double NOT NULL,
  `per_cent` double NOT NULL,
  `on_plate` double NOT NULL,
  `print_side` double NOT NULL,
  `print_color` double NOT NULL,
  `die_cut_count` double NOT NULL,
  `die_cut_count2` double NOT NULL,
  `lamination_price` double NOT NULL,
  `waste` double NOT NULL,
  `vat` double NOT NULL,
  `cvat` double NOT NULL,
  `machine_run` double NOT NULL,
  `printing_price` double NOT NULL,
  `required_paper` double NOT NULL,
  `required_paper_full` double NOT NULL,
  `paper_cost` double NOT NULL,
  `total_cost` double NOT NULL,
  `P__M` double NOT NULL,
  `die_cut` double NOT NULL,
  `lamination` double NOT NULL,
  `fold` double NOT NULL,
  `fold_price` double NOT NULL,
  `total_price` double NOT NULL,
  `unit_price` double NOT NULL,
  `unit_price_vat` double NOT NULL,
  `including_vat` double NOT NULL,
  `commitonprice` float NOT NULL,
  `date` date NOT NULL,
  `private` varchar(10) NOT NULL,
  `lam_type` varchar(255) NOT NULL DEFAULT 'none',
  `payment_status` tinyint(1) NOT NULL DEFAULT 0,
  `machine_type` int(11) NOT NULL,
  PRIMARY KEY (`brocher_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO brocher VALUES("7","Color Advert","Folder Printing with pocket on 250 gm Full color print with lamination ","A2","2","1","1000","30","2","1500","0.5","1","1","4","0","0","19","10","15","0","6000","3000","7.6","760","31920","67670","20301","0","28500","450","0.3","87971","58.647333333333","67.444433333333","101166.65","0","2024-08-28","choose","2","0","1"),
("8","American Friends Service Committee","Three Fold Brochure on 250 gm, full color print, one side Matt lamination","A4","2","1","1000","40","8","500","0.8","2","2","4","0","0","8","7","15","0","2000","1600","0.695","69.5","2919","12469","4987.6","0","4000","150","0.3","17456.6","34.9132","40.15018","20075.09","0","2024-08-28","choose","5","0","1"),
("9","American Friends Service Committee","Three Fold Brochure on 250 gm, full color print, one side Matt lamination","A4","2","1","1000","40","8","500","0.8","2","2","4","0","0","8","7","15","0","2000","1600","0.695","69.5","2919","12469","4987.6","0","4000","150","0.3","17456.6","34.9132","40.15018","20075.09","0","2024-08-28","choose","5","0","1"),
("10","American Friends Service Committee","Three Fold Brochure on 250 gm, full color print, one side Matt lamination(Two Type of Jobs)","A4","2","1","1000","40","8","1000","0.8","2","2","4","0","0","8","7","15","0","4000","3200","1.32","132","5544","20844","8337.6","0","8000","300","0.3","29181.6","29.1816","33.55884","33558.84","0","2024-08-28","choose","5","1","1");



DROP TABLE IF EXISTS brocher_group;

CREATE TABLE `brocher_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`data`)),
  `type` varchar(100) NOT NULL,
  `customer` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




DROP TABLE IF EXISTS check_lamination;

CREATE TABLE `check_lamination` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lam_id` int(11) NOT NULL,
  `care_lamination` double NOT NULL,
  `base_care` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO check_lamination VALUES("1","1","323.2","430"),
("2","2","60","430");



DROP TABLE IF EXISTS compare;

CREATE TABLE `compare` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




DROP TABLE IF EXISTS compare_price;

CREATE TABLE `compare_price` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




DROP TABLE IF EXISTS constants;

CREATE TABLE `constants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paper_type` varchar(100) NOT NULL,
  `price` int(11) NOT NULL,
  `per_reem` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;




DROP TABLE IF EXISTS customer;

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(100) NOT NULL,
  `tin_number` varchar(100) NOT NULL,
  `phone_number` varchar(100) NOT NULL,
  `management_id` int(11) NOT NULL,
  PRIMARY KEY (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO customer VALUES("7","Islamic Relife","-","-","17"),
("8","Safi","-","-","18"),
("9","Mohamed Lalo Yesuf","0002202450","-","19"),
("10","Hamrawi Advertising","-","-","20"),
("11","Addis Hiwot Hospital ","-","-","21"),
("12","UNDP","-","-","22"),
("13","Color Advert","-","-","23"),
("14","Eclips Printing Press","-","-","24"),
("15","American Friends Service Committee","-","-","25");



DROP TABLE IF EXISTS d_constants;

CREATE TABLE `d_constants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `db` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO d_constants VALUES("5","Paper","paper","2023-05-06"),
("6","Plate","plate","2023-05-06"),
("7","Binding","bind","2023-05-06"),
("8","Unit Banner","unitbanner","2023-05-06"),
("9","Unit Digital","unitdigital","2023-05-06"),
("10","Unit Design","unitdesign","2023-05-06"),
("12","Lamination","laminationdb","10/30/2023"),
("13","page","pagedb","10/30/2023"),
("14","Banner Metal","banner_metal","2024-05-06");



DROP TABLE IF EXISTS deliver;

CREATE TABLE `deliver` (
  `generate_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer` text NOT NULL,
  `job_description` text NOT NULL,
  `size` text NOT NULL,
  `quantity` text NOT NULL,
  `total_price` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `unit_price` text NOT NULL,
  `price_vat` text NOT NULL,
  `types` text NOT NULL,
  `advance` double NOT NULL,
  `remainder` double NOT NULL,
  `created_at` date NOT NULL,
  PRIMARY KEY (`generate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




DROP TABLE IF EXISTS design;

CREATE TABLE `design` (
  `digital_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer` text NOT NULL,
  `job_type` text NOT NULL,
  `total_price` float NOT NULL,
  `unit_price` float NOT NULL,
  `total_price_vat` int(11) NOT NULL,
  `vat` int(11) NOT NULL,
  `cvat` double NOT NULL,
  `commitonprice` float NOT NULL,
  `date` date NOT NULL,
  `private` varchar(255) NOT NULL,
  `designtype` int(11) NOT NULL,
  `designprice` int(11) NOT NULL,
  `number_of_pages` int(11) NOT NULL,
  `vatornot` varchar(255) NOT NULL,
  `required_quantity` int(11) NOT NULL,
  `payment_status` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`digital_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO design VALUES("1","UNDP","Design","300000","150000","345000","15","0","0","2024-08-27","choose","2","0","500","","2","0");



DROP TABLE IF EXISTS digital;

CREATE TABLE `digital` (
  `digital_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer` text NOT NULL,
  `job_type` text NOT NULL,
  `size` text NOT NULL,
  `required_quantity` text NOT NULL,
  `total_price` float NOT NULL,
  `unit_price` float NOT NULL,
  `unit` double NOT NULL,
  `total_price_vat` int(11) NOT NULL,
  `vat` int(11) NOT NULL,
  `vatornot` varchar(10) NOT NULL,
  `cvat` double NOT NULL,
  `commitonprice` float NOT NULL,
  `date` date NOT NULL,
  `private` varchar(255) NOT NULL,
  `payment_status` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`digital_id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO digital VALUES("1","Islamic Relife","Signboard preparation_On site: Side stand poles(Tapela Holder): 50*50*2mm thick RHS with a Total Height of 350cm, 2. Content Desplay (Tapela): Height 150cm, Width 200cm with tapela thickness of 2mm, 3. All the content should have to painted on the tapela as per the attached content, 4. All contents should have to painted on both sides of the the tapela, 5. All the contentes should have to be painted accurately as shown on the attached c., The stand poles should be painted with blue color that match to the logo. All parts of the signboard should be soaked with Anti rust. All the paint work should be apply 3 coats of painting.","150X200","7","211525","30217.8","34750.5","243254","15","yes","0","0","2024-08-26","","0"),
("2","Islamic Relife","Signboard preparation_Main road: Side stand poles(Tapela Holder): 50*50*2mm thick RHS with a Total Height of 340cm, 2. Content Desplay (Tapela): Height 140cm, Width 180cm with tapela thickness of 2mm, 3. All the content should have to painted on the tapela as per the attached content, 4. All contents should have to painted on both sides of the the tapela, 5. All the contentes should have to be painted accurately as shown on the attached content., The stand poles should be painted with blue color that match to the logo. All parts of the signboard should be soaked with Anti rust before blue painting, All the paint work should be apply 3 coats of painting.","140X180","8","232350","29043.7","33400.25","267202","15","yes","0","0","2024-08-26","","0"),
("3","Islamic Relife","Polyester, Small,Medium,Large,XL,XXl,XXXL,Free size,Colored,Black,White","-","1","521.739","521.739","600","600","15","yes","0","0","2024-08-26","","0"),
("4","Islamic Relife","Vests Blend fabric Small,Medium,Large,XL,XXl,XXXL,Free size","-","1","2347.83","2347.83","2700","2700","15","yes","0","0","2024-08-26","","0"),
("5","Islamic Relife","Caps, Cotton twill, Polyester, Mesh","-","1","869.565","869.565","1000","1000","15","yes","0","0","2024-08-26","","0"),
("6","Islamic Relife","Hoodies & Shirts, Fleece, Cotton Blend, Polyester, Small,Medium,Large,XL,XXl,XXXL,Free size,Colored,Black,White","-","1","2434.78","2434.78","2800","2800","15","yes","0","0","2024-08-26","","0"),
("7","Islamic Relife","Logo Tags, Woven, Fabric, PVC, Metal","-","1","1739.13","1739.13","2000","2000","15","yes","0","0","2024-08-26","","0"),
("8","Islamic Relife","Cotton,Organic Cotton, Small,Medium,Large,XL,XXl,XXXL,Free size,Colored,Black,White","-","1","869.565","869.565","1000","1000","15","yes","0","0","2024-08-26","","0"),
("9","Islamic Relife","Branded pen Metalic","-","1","304.348","304.348","350","350","15","yes","0","0","2024-08-26","","0"),
("10","Islamic Relife","Branded pen Plastic ","-","1","113.043","113.043","130","130","15","yes","0","0","2024-08-26","","0"),
("11","Islamic Relife","Letter head full color Digital print","-","1","8.69565","8.69565","10","10","15","yes","0","0","2024-08-26","","0"),
("12","Islamic Relife","Brochures, Glossy Paper or Matt paper 150 gm, ","A4","50","2000","40","46","2300","15","yes","0","0","2024-08-26","","0"),
("13","Islamic Relife","Brochures, Glossy Paper or Matt paper 150 gm, ","A4","500","6086.96","12.1739","14","7000","15","yes","0","0","2024-08-26","","0"),
("14","Islamic Relife","Brochures, Glossy Paper or Matt paper 150 gm, ","A4","1000","9565.22","9.56522","11","11000","15","yes","0","0","2024-08-26","","0"),
("15","Islamic Relife","Brochures, Glossy Paper or Matt paper 150 gm, ","A5","50","1000","20","23","1150","15","yes","0","0","2024-08-26","","0"),
("16","Islamic Relife","Brochures, Glossy Paper or Matt paper 150 gm, ","A5","500","4347.83","8.69565","10","5000","15","yes","0","0","2024-08-26","","0"),
("17","Islamic Relife","Brochures, Glossy Paper or Matt paper 150 gm, ","A5","1000","9565.22","9.56522","11","11000","15","yes","0","0","2024-08-26","","0"),
("18","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 8 Plus Cover, Digital Print, Glossy or matt paper","A4","50","11087","221.739","255","12750","15","yes","0","0","2024-08-26","","0"),
("19","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 8 Plus Cover, Digital Print, Glossy or matt paper","A4","100","22173.9","221.739","255","25500","15","yes","0","0","2024-08-26","","0"),
("20","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 8 Plus Cover, Digital Print, Glossy or matt paper","A4","500","97826.1","195.652","225","112500","15","yes","0","0","2024-08-26","","0"),
("21","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 8 Plus Cover, Digital Print","A5","50","5478.26","109.565","126","6300","15","yes","0","0","2024-08-26","","0"),
("22","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 8 Plus Cover, Digital Print","A5","100","10434.8","104.348","120","12000","15","yes","0","0","2024-08-26","","0"),
("23","Islamic Relife","Notebooks	Hardcover, Spiral- Bound, Recycled","A5","50","13043.5","260.87","300","15000","15","yes","0","0","2024-08-26","","0"),
("24","Islamic Relife","Notebooks	Hardcover, Spiral- Bound, Recycled","A5","100","24782.6","247.826","285","28500","15","yes","0","0","2024-08-26","","0"),
("25","Islamic Relife","Notebooks	Hardcover, Spiral- Bound, Recycled","A5","150","35869.6","239.13","275","41250","15","yes","0","0","2024-08-26","","0"),
("26","Islamic Relife","Notebooks	Hardcover, Spiral- Bound, Recycled","A6","50","5217.39","104.348","120","6000","15","yes","0","0","2024-08-26","","0"),
("27","Islamic Relife","Notebooks	Hardcover, Spiral- Bound, Recycled","A6","100","9130.43","91.3043","105","10500","15","yes","0","0","2024-08-26","","0"),
("28","Islamic Relife","Notebooks	Hardcover, Spiral- Bound, Recycled","A6","150","13043.5","86.9565","100","15000","15","yes","0","0","2024-08-26","","0"),
("29","Islamic Relife","Notebooks	Hardcover, Spiral- Bound, Recycled","B5","50","13913","278.261","320","16000","15","yes","0","0","2024-08-26","","0"),
("54","Islamic Relife","Dispatch Note ,5 copies with 50 leaf","-","1","1217.39","1217.39","1400","1400","15","yes","0","0","2024-08-26","","0"),
("55","Islamic Relife","Goods Receiving Note ,5 copies with 50 leaf","-","1","1217.39","1217.39","1400","1400","15","yes","0","0","2024-08-26","","0"),
("56","Mohamed Lalo Yesuf","vinyl sticker print and cut","3.5X5","4000","8486.96","2.12174","2.44","9760","15","yes","0","0","2024-08-27","","0"),
("57","Safi","1 kg sticker print and cut","5.4X8","864","2682.16","3.10435","3.57","3084","15","yes","0","0","2024-08-27","","0"),
("58","Safi","Plastic Bag label ","14.5X14.5","294","4953.9","16.85","16.85","5697","15","no","0","0","2024-08-27","","0"),
("59","Safi","5 liter jerkin lable ","14.5X14.5","264","3307.92","12.53","12.53","3804","15","no","0","0","2024-08-27","","0"),
("60","Safi","Prayer and guaranty sticker","3.5X3.5","650","1690","2.6","2.6","1944","15","no","0","0","2024-08-27","","0"),
("61","Addis Hiwot Hospital ","Agenda with UV Print ","A5","50","26087","521.739","600","30000","15","yes","0","0","2024-08-28","","0");



DROP TABLE IF EXISTS digital_pr;

CREATE TABLE `digital_pr` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Printing_type` text DEFAULT NULL,
  `Price` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;




DROP TABLE IF EXISTS digital_print;

CREATE TABLE `digital_print` (
  `jkhhjk` int(11) NOT NULL AUTO_INCREMENT,
  `kjkj` text DEFAULT NULL,
  `jhkjh` int(11) DEFAULT NULL,
  PRIMARY KEY (`jkhhjk`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;




DROP TABLE IF EXISTS email_log;

CREATE TABLE `email_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `to` text NOT NULL,
  `to_comp_add` text NOT NULL,
  `to_city` text NOT NULL,
  `from` text NOT NULL,
  `subject` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `email_body` text NOT NULL,
  `salut` text NOT NULL,
  `ref` text NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO email_log VALUES("1","Elegant Deign and Print","Gabon Street","Addis ababa, Ethiopia","Elias","Payment request","I hope this message finds you well. We are writing to request payment for the printing services provided by [Your Company Name] as per your order. Below are the details of the service:\n\nService Provided: Printing of Business Cards\nQuantity: 1,000 units\nUnit Price (Including VAT): 12.00 Birr per unit\nTotal Amount: 12,000 Birr\n\nKindly arrange the payment of 12,000 Birr at your earliest convenience. You can make the payment through [mention your preferred payment methods such as bank transfer, cash, etc.]. Please ensure that the payment is completed within [mention payment terms, e.g., 7 days] from the date of this request.","Best Regards,","EDP/000223","2024-08-28 12:27:40");



DROP TABLE IF EXISTS file;

CREATE TABLE `file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) NOT NULL,
  `extension` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

INSERT INTO file VALUES("1","jn_0006_British Council_masteradmin_Business Card GGGI_G_A4_500_2024-08-24.pdf","pdf","2024-08-24");



DROP TABLE IF EXISTS generate;

CREATE TABLE `generate` (
  `generate_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer` text NOT NULL,
  `job_description` text NOT NULL,
  `size` text NOT NULL,
  `quantity` text NOT NULL,
  `total_price` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `unit_price` text NOT NULL,
  `price_vat` text NOT NULL,
  `types` text NOT NULL,
  PRIMARY KEY (`generate_id`)
) ENGINE=InnoDB AUTO_INCREMENT=148 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO generate VALUES("147","UNDP","Backdrop banner","300X500","1","8250","8250","9487.5","payment");



DROP TABLE IF EXISTS info;

CREATE TABLE `info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `address` text NOT NULL,
  `phone_number` text NOT NULL,
  `vat` double NOT NULL,
  `tin_number` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `vat_number` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

INSERT INTO info VALUES("1","Gabon Street","+251 913121812","15","0027147876","11065620824");



DROP TABLE IF EXISTS laminationdb;

CREATE TABLE `laminationdb` (
  `lam_id` int(11) NOT NULL AUTO_INCREMENT,
  `lam_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `lam_price` float NOT NULL,
  `width` double NOT NULL,
  `height` double NOT NULL,
  `care_lamination` double NOT NULL,
  `stock_id` int(11) NOT NULL,
  PRIMARY KEY (`lam_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO laminationdb VALUES("3","Bag Lamination__45X31","14.5","0.45","0.31","0.1395","2"),
("2","A2 Matt Lamination","19","0.43","0.62","0.2666","2"),
("4","A3 Lamination one side","12","0.43","0.32","0.1376","2"),
("5","A4 lamination","8","0.215","0.32","0.0688","0");



DROP TABLE IF EXISTS machine_run;

CREATE TABLE `machine_run` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `device_count` varchar(255) NOT NULL,
  `calc_count` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO machine_run VALUES("1","MO single Unit ","3500000","4400"),
("2","Konica Minolta accuriopress c4070","250000","1086000");



DROP TABLE IF EXISTS machine_run_log;

CREATE TABLE `machine_run_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `machine_id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `count` double NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `job_number` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=107 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO machine_run_log VALUES("1","1","brocher","6000","2024-08-24 11:41:35","jn_0001"),
("2","1","Reversed","6000","2024-08-24 11:43:08","jn_0001"),
("3","1","brocher","6000","2024-08-24 11:51:12","jn_0002"),
("4","1","brocher","6000","2024-08-24 11:58:13","jn_0003"),
("5","1","Reversed","6000","2024-08-24 12:01:06","jn_0003"),
("6","1","Reversed","6000","2024-08-24 12:01:08","jn_0002"),
("7","1","brocher","6000","2024-08-24 12:09:55","jn_0004"),
("8","1","Reversed","6000","2024-08-24 12:11:14","jn_0004"),
("9","1","brocher","6000","2024-08-24 12:20:33","jn_0005"),
("10","1","Reversed","6000","2024-08-24 12:21:11","jn_0005"),
("11","1","book","4000","2024-08-24 14:29:44","jn_0006"),
("12","1","book","10000","2024-08-24 14:29:44","jn_0006"),
("13","1","book","0","2024-08-24 14:29:44","jn_0006"),
("14","1","book","0","2024-08-24 14:29:44","jn_0006"),
("15","1","book","0","2024-08-24 14:29:44","jn_0006"),
("16","1","book","4000","2024-08-24 15:48:10","jn_0009"),
("17","1","book","10000","2024-08-24 15:48:10","jn_0009"),
("18","1","book","0","2024-08-24 15:48:10","jn_0009"),
("19","1","book","0","2024-08-24 15:48:10","jn_0009"),
("20","1","book","0","2024-08-24 15:48:10","jn_0009"),
("21","1","book","4000","2024-08-24 15:48:23","jn_0010"),
("22","1","book","10000","2024-08-24 15:48:23","jn_0010"),
("23","1","book","0","2024-08-24 15:48:23","jn_0010"),
("24","1","book","0","2024-08-24 15:48:23","jn_0010"),
("25","1","book","0","2024-08-24 15:48:23","jn_0010"),
("26","1","book","4000","2024-08-24 16:17:02","jn_0011"),
("27","1","book","10000","2024-08-24 16:17:02","jn_0011"),
("28","1","book","0","2024-08-24 16:17:02","jn_0011"),
("29","1","book","0","2024-08-24 16:17:02","jn_0011"),
("30","1","book","0","2024-08-24 16:17:02","jn_0011"),
("31","1","book","4000","2024-08-24 16:22:29","jn_0012"),
("32","1","book","10000","2024-08-24 16:22:29","jn_0012"),
("33","1","book","0","2024-08-24 16:22:29","jn_0012"),
("34","1","book","0","2024-08-24 16:22:29","jn_0012"),
("35","1","book","0","2024-08-24 16:22:29","jn_0012"),
("36","1","book","4000","2024-08-24 16:33:45","jn_0013"),
("37","1","book","10000","2024-08-24 16:33:45","jn_0013"),
("38","1","book","0","2024-08-24 16:33:45","jn_0013"),
("39","1","book","0","2024-08-24 16:33:45","jn_0013"),
("40","1","book","0","2024-08-24 16:33:45","jn_0013"),
("41","1","book","4000","2024-08-24 16:35:59","jn_0014"),
("42","1","book","10000","2024-08-24 16:35:59","jn_0014"),
("43","1","book","0","2024-08-24 16:35:59","jn_0014"),
("44","1","book","0","2024-08-24 16:35:59","jn_0014"),
("45","1","book","0","2024-08-24 16:35:59","jn_0014"),
("46","1","Reversed","4000","2024-08-24 16:37:04","jn_0014"),
("47","1","Reversed","10000","2024-08-24 16:37:04","jn_0014"),
("48","1","Reversed","0","2024-08-24 16:37:04","jn_0014"),
("49","1","Reversed","0","2024-08-24 16:37:04","jn_0014"),
("50","1","Reversed","0","2024-08-24 16:37:04","jn_0014"),
("51","1","bag","800","2024-08-24 21:09:42","jn_0017"),
("52","1","brocher","6000","2024-08-24 21:20:46","jn_0021"),
("53","1","brocher","6000","2024-08-24 21:23:31","jn_0022"),
("54","1","brocher","6000","2024-08-25 12:58:35","jn_0028"),
("55","1","book","4000","2024-08-25 12:59:52","jn_0029"),
("56","1","book","10000","2024-08-25 12:59:52","jn_0029"),
("57","1","book","0","2024-08-25 12:59:52","jn_0029"),
("58","1","book","0","2024-08-25 12:59:52","jn_0029"),
("59","1","book","0","2024-08-25 12:59:52","jn_0029"),
("60","1","brocher","6000","2024-08-25 13:15:58","jn_0030"),
("61","1","Reversed","6000","2024-08-25 21:22:21","jn_0030"),
("62","1","Reversed","4000","2024-08-25 21:22:27","jn_0029"),
("63","1","Reversed","10000","2024-08-25 21:22:27","jn_0029"),
("64","1","Reversed","0","2024-08-25 21:22:27","jn_0029"),
("65","1","Reversed","0","2024-08-25 21:22:27","jn_0029"),
("66","1","Reversed","0","2024-08-25 21:22:27","jn_0029"),
("67","1","Reversed","6000","2024-08-25 21:22:45","jn_0021"),
("68","1","Reversed","800","2024-08-25 21:22:54","jn_0017"),
("69","1","Reversed","4000","2024-08-25 21:23:10","jn_0013"),
("70","1","Reversed","10000","2024-08-25 21:23:10","jn_0013"),
("71","1","Reversed","0","2024-08-25 21:23:10","jn_0013"),
("72","1","Reversed","0","2024-08-25 21:23:10","jn_0013"),
("73","1","Reversed","0","2024-08-25 21:23:10","jn_0013"),
("74","1","Reversed","4000","2024-08-25 21:23:27","jn_0012"),
("75","1","Reversed","10000","2024-08-25 21:23:27","jn_0012"),
("76","1","Reversed","0","2024-08-25 21:23:27","jn_0012"),
("77","1","Reversed","0","2024-08-25 21:23:27","jn_0012"),
("78","1","Reversed","0","2024-08-25 21:23:27","jn_0012"),
("79","1","Reversed","4000","2024-08-25 21:23:30","jn_0011"),
("80","1","Reversed","10000","2024-08-25 21:23:30","jn_0011"),
("81","1","Reversed","0","2024-08-25 21:23:30","jn_0011"),
("82","1","Reversed","0","2024-08-25 21:23:30","jn_0011"),
("83","1","Reversed","0","2024-08-25 21:23:30","jn_0011"),
("84","1","Reversed","4000","2024-08-25 21:23:32","jn_0010"),
("85","1","Reversed","10000","2024-08-25 21:23:32","jn_0010"),
("86","1","Reversed","0","2024-08-25 21:23:32","jn_0010"),
("87","1","Reversed","0","2024-08-25 21:23:32","jn_0010"),
("88","1","Reversed","0","2024-08-25 21:23:32","jn_0010"),
("89","1","Reversed","4000","2024-08-25 21:23:34","jn_0009"),
("90","1","Reversed","10000","2024-08-25 21:23:34","jn_0009"),
("91","1","Reversed","0","2024-08-25 21:23:34","jn_0009"),
("92","1","Reversed","0","2024-08-25 21:23:34","jn_0009"),
("93","1","Reversed","0","2024-08-25 21:23:34","jn_0009"),
("94","1","Reversed","4000","2024-08-25 21:23:41","jn_0006"),
("95","1","Reversed","10000","2024-08-25 21:23:41","jn_0006"),
("96","1","Reversed","0","2024-08-25 21:23:41","jn_0006"),
("97","1","Reversed","0","2024-08-25 21:23:41","jn_0006"),
("98","1","Reversed","0","2024-08-25 21:23:41","jn_0006"),
("99","1","Reversed","6000","2024-08-25 21:23:54","jn_0028"),
("100","1","Reversed","6000","2024-08-25 21:23:56","jn_0022"),
("101","2","single_page","271500","2024-08-28 07:29:44","jn_0032"),
("102","2","single_page","271500","2024-08-28 07:32:19","jn_0033"),
("103","2","single_page","271500","2024-08-28 07:33:12","jn_0034"),
("104","2","single_page","271500","2024-08-28 07:46:13","jn_0035"),
("105","1","single_page","400","2024-08-28 07:50:03","jn_0036"),
("106","1","brocher","4000","2024-08-28 17:22:00","jn_0037");



DROP TABLE IF EXISTS multi_page;

CREATE TABLE `multi_page` (
  `multi_page_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer` text NOT NULL,
  `job_type` text NOT NULL,
  `size` text NOT NULL,
  `required_quantity` float NOT NULL,
  `total_price` float NOT NULL,
  `unit_price` float NOT NULL,
  `total_price_vat` float NOT NULL,
  `vat` float NOT NULL,
  `cvat` double NOT NULL,
  `width` float NOT NULL,
  `commitonprice` float NOT NULL,
  `date` date NOT NULL,
  `bind_price` double NOT NULL,
  `page_price` double NOT NULL,
  `cover_price` double NOT NULL,
  `nopage_a` int(11) NOT NULL,
  `nopage_b` int(11) NOT NULL,
  `nopage_c` int(11) NOT NULL,
  `nopage_d` int(11) NOT NULL,
  `bind_id` int(11) NOT NULL,
  `page_id_a` int(11) NOT NULL,
  `page_id_b` int(11) NOT NULL,
  `page_id_c` int(11) NOT NULL,
  `page_id_d` int(11) NOT NULL,
  `paper_id` int(11) NOT NULL,
  `private` varchar(255) NOT NULL,
  `lam_type` varchar(100) NOT NULL,
  `machine_run` double NOT NULL,
  `payment_status` tinyint(1) NOT NULL DEFAULT 0,
  `print_side` int(11) NOT NULL,
  `machine_type` int(11) NOT NULL,
  `machine_type_page_a` int(11) NOT NULL,
  `machine_type_page_b` int(11) NOT NULL,
  `machine_type_page_c` int(11) NOT NULL,
  `machine_type_page_d` int(11) NOT NULL,
  `machine_run_page_a` double NOT NULL,
  `machine_run_page_b` double NOT NULL,
  `machine_run_page_c` double NOT NULL,
  `machine_run_page_d` double NOT NULL,
  `cover_print_side` double NOT NULL,
  PRIMARY KEY (`multi_page_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO multi_page VALUES("1","Addis Hiwot Hospital ","company profile 17cmx25cm with 36 pages back and front inside 150gm ,cover page 300gm with staple binding","17X25","50","33500","642","38525","15","0","0","0","2024-08-28","3","0","47","32","0","0","0","1","5","5","5","5","4","choose","4","50","0","0","2","1","1","1","1","1600","0","0","0","0"),
("2","Addis Hiwot Hospital ","annual package 16 pages A5 size","A5","100","33850","338.5","38927.5","15","0","0","0","2024-08-28","3","0","23.5","16","0","0","0","1","5","5","5","5","6","choose","5","100","0","0","2","1","1","1","1","1600","0","0","0","0"),
("3","Eclips Printing Press","5 OFL Journey Booklet, 44 pages including cover, cover 250 gm Text with lamination, 150 gm  full color","A5","101","41864.5","414.5","48144.2","15","0","0","0","2024-08-28","3","0","23.5","40","0","0","0","1","7","5","5","5","6","choose","5","101","0","0","1","1","1","1","1","4040","0","0","0","0"),
("4","Eclips Printing Press","5 OFL Journey Booklet, 44 pages including cover, cover 250 gm Text with lamination, 150 gm  full color","A5","101","44238","438","50873.7","15","0","0","0","2024-08-28","3","0","47","40","0","0","0","1","7","5","5","5","8","choose","5","101","0","0","1","1","1","1","1","4040","0","0","0","0");



DROP TABLE IF EXISTS multipagedb;

CREATE TABLE `multipagedb` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_type` varchar(255) NOT NULL,
  `page_price` float NOT NULL,
  PRIMARY KEY (`page_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




DROP TABLE IF EXISTS office_stock;

CREATE TABLE `office_stock` (
  `stock_id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_type` varchar(100) NOT NULL,
  `stock_quantity` double NOT NULL,
  `ratio` double NOT NULL,
  `stock_quantity2` double NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `dangerzone` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `removed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `catagory` varchar(255) NOT NULL,
  PRIMARY KEY (`stock_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO office_stock VALUES("2","250 gm A3","200","1","200","2024-08-28 07:50:03","100","2024-08-28 07:50:03","2024-08-28 07:50:03",""),
("3","150 gm A3","15","1","15","2024-08-28 05:08:44","15","2024-08-28 05:08:44","2024-08-28 05:08:44",""),
("4","Tesr","10","1","10","2024-08-28 05:09:06","1","2024-08-28 05:09:06","2024-08-28 05:09:06",""),
("5","A4 150 gm","1000","1","1000","2024-08-28 08:36:41","100","2024-08-28 08:36:41","2024-08-28 08:36:41",""),
("6","A5","1000","1","1000","2024-08-28 08:49:01","500","2024-08-28 08:49:01","2024-08-28 08:49:01",""),
("7","A4 250 gm Art","0","1","0","2024-08-28 11:40:30","100","2024-08-28 11:40:30","2024-08-28 11:40:30","");



DROP TABLE IF EXISTS office_stock_log;

CREATE TABLE `office_stock_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `customer` varchar(255) NOT NULL,
  `stock_id` int(11) NOT NULL,
  `status` varchar(100) NOT NULL,
  `last_quantity` double NOT NULL,
  `added_removed` double NOT NULL,
  `reason` longtext NOT NULL,
  `jobnumber` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `removed_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO office_stock_log VALUES("1","25","","3","remove_quantity","10","1","letter head printing","jn_0016","2024-08-27 19:02:19","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("2","25","","3","remove_quantity","10","4","BC","jn_0016","2024-08-27 19:02:39","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("3","25","","3","remove_quantity","10","1","asd","jn_0016","2024-08-27 19:02:52","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("4","25","","2","remove_quantity","400","10","asd","jn_0016","2024-08-27 19:03:06","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("5","25","","3","remove_quantity","10","5","BC","jn_0016","2024-08-27 20:30:15","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("6","25","","3","add_quantity","10","5","","","2024-08-28 05:08:44","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("7","25","","3","remove_quantity","15","1","No","jn_0031","2024-08-28 05:09:28","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("8","7","UNDP","2","remove_quantity","400","500","43354","jn_0032","2024-08-28 07:29:44","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("9","25","","2","add_quantity","-100","500","","","2024-08-28 07:31:36","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("10","7","UNDP","2","remove_quantity","400","500","43354","jn_0033","2024-08-28 07:32:19","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("11","25","","2","add_quantity","-100","1000","","","2024-08-28 07:32:45","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("12","7","UNDP","2","remove_quantity","900","500","43354","jn_0034","2024-08-28 07:33:12","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("13","7","UNDP","2","remove_quantity","400","500","43354","jn_0035","2024-08-28 07:46:13","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("14","25","","2","add_quantity","-100","500","","","2024-08-28 07:48:33","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("15","7","UNDP","2","remove_quantity","400","200","Keychain Printing ","jn_0036","2024-08-28 07:50:03","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("16","25","","7","remove_quantity","1000","1","no","jn_0036","2024-08-28 10:22:17","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("17","25","","7","remove_quantity","999","999","restored","jn_0036","2024-08-28 11:40:30","0000-00-00 00:00:00","0000-00-00 00:00:00");



DROP TABLE IF EXISTS otherdigital;

CREATE TABLE `otherdigital` (
  `otherdigital_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer` text NOT NULL,
  `job_type` text NOT NULL,
  `size` text NOT NULL,
  `required_quantity` float NOT NULL,
  `unit_digital_type` int(11) NOT NULL,
  `total_price` float NOT NULL,
  `unit_price` float NOT NULL,
  `total_price_vat` float NOT NULL,
  `vat` float NOT NULL,
  `cvat` double NOT NULL,
  `commitonprice` float NOT NULL,
  `date` date NOT NULL,
  `private` varchar(255) NOT NULL,
  `machine_run` double NOT NULL,
  `payment_status` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`otherdigital_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




DROP TABLE IF EXISTS pagedb;

CREATE TABLE `pagedb` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_type` varchar(255) NOT NULL,
  `page_price` float NOT NULL,
  `print_side` int(11) NOT NULL,
  `stock_id` int(11) NOT NULL,
  PRIMARY KEY (`page_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO pagedb VALUES("5","A4 150 gm Full color one side","19","1","5"),
("3","None","0","0","1"),
("4","250 gm A3Full color","47","1","2"),
("6","250 gm A4","23.5","1","7"),
("7","A5 150gm Art","9.5","1","6"),
("8","250 gm A4 Full color 2 side","47","1","7");



DROP TABLE IF EXISTS paper;

CREATE TABLE `paper` (
  `paper_id` int(11) NOT NULL AUTO_INCREMENT,
  `paper_type` varchar(100) NOT NULL,
  `price` double NOT NULL,
  `per_reem` double NOT NULL,
  `stock_id` int(11) NOT NULL,
  PRIMARY KEY (`paper_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO paper VALUES("1","150 gm Art 61X86","5700","250","1"),
("2","250 gm Art 61X86","4200","100","3"),
("3","250gm test ","4000","100","9");



DROP TABLE IF EXISTS payment;

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `job_number` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `client` varchar(100) NOT NULL,
  `date` varchar(100) NOT NULL,
  `enddate` varchar(255) NOT NULL,
  `job_description` longtext NOT NULL,
  `size` varchar(100) NOT NULL,
  `quantity` varchar(100) NOT NULL,
  `unit_price` varchar(100) NOT NULL,
  `advance` varchar(100) NOT NULL,
  `remained` varchar(100) NOT NULL,
  `total` varchar(100) NOT NULL,
  `status` varchar(200) NOT NULL,
  `updated_at` date NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`payment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO payment VALUES("15","jn_0015","25","British Council","2024-08-24","2024-08-24","32","A4","23","567.609","500","15013.25805","15013.25805","start","2024-08-24","31"),
("16","jn_0016","25","British Council","2024-08-24","2024-08-24","32","A4","23","567.609","0","15013.25805","15013.25805","start","2024-08-24","32"),
("31","jn_0031","25","UNDP","2024-08-27","2024-08-27","Backdrop banner","300X500","1","8250","0","8487.5","9487.5","start","2024-08-27","54"),
("32","jn_0032","25","UNDP","2024-08-28","2024-08-28","43354","A4","500","26077","0","14994275","14994275","start","2024-08-28","55"),
("33","jn_0033","25","UNDP","2024-08-28","2024-08-28","43354","A4","500","26077","0","14994275","14994275","start","2024-08-28","56"),
("34","jn_0034","25","UNDP","2024-08-28","2024-08-28","43354","A4","500","26077","0","14994275","14994275","start","2024-08-28","57"),
("35","jn_0035","25","UNDP","2024-08-28","2024-08-28","43354","A4","500","26077","0","14994275","14994275","start","2024-08-28","58"),
("36","jn_0036","25","UNDP","2024-08-28","2024-08-28","Keychain Printing ","A4","200","49.46","0","10375.8","11375.8","start","2024-08-28","59"),
("37","jn_0037","25","American Friends Service Committee","2024-08-28","2024-08-28","Three Fold Brochure on 250 gm, full color print, one side Matt lamination(Two Type of Jobs)","A4","1000","29.1816","0","33558.84","33558.84","start","2024-08-28","60");



DROP TABLE IF EXISTS performa_log;

CREATE TABLE `performa_log` (
  `generate_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer` text NOT NULL,
  `job_description` text NOT NULL,
  `size` text NOT NULL,
  `quantity` text NOT NULL,
  `total_price` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `unit_price` text NOT NULL,
  `price_vat` text NOT NULL,
  `types` text NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `ref` text NOT NULL,
  PRIMARY KEY (`generate_id`)
) ENGINE=InnoDB AUTO_INCREMENT=148 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO performa_log VALUES("1","British Council","Broucher 150 gm two side print","A4","3000","56940.3","18.9801","854104.5","brocher","2024-08-24 11:45:01",""),
("2","British Council","Rollup With Stand & Installation High Quality(Wide Base)","80X200","5","51150","10230","58822.5","banner","2024-08-24 14:41:34",""),
("3","Islamic Relife","Signboard preparation_Main road: Side stand poles(Tapela Holder): 50*50*2mm thick RHS with a Total Height of 340cm, 2. Content Desplay (Tapela): Height 140cm, Width 180cm with tapela thickness of 2mm, 3. All the content should have to painted on the tapela as per the attached content, 4. All contents should have to painted on both sides of the the tapela, 5. All the contentes should have to be painted accurately as shown on the attached content., The stand poles should be painted with blue color that match to the logo. All parts of the signboard should be soaked with Anti rust before blue painting, All the paint work should be apply 3 coats of painting.","140X180","8","232350","29043.7","267202","digital","2024-08-26 09:15:30",""),
("4","Islamic Relife","Signboard preparation_On site: Side stand poles(Tapela Holder): 50*50*2mm thick RHS with a Total Height of 350cm, 2. Content Desplay (Tapela): Height 150cm, Width 200cm with tapela thickness of 2mm, 3. All the content should have to painted on the tapela as per the attached content, 4. All contents should have to painted on both sides of the the tapela, 5. All the contentes should have to be painted accurately as shown on the attached c., The stand poles should be painted with blue color that match to the logo. All parts of the signboard should be soaked with Anti rust. All the paint work should be apply 3 coats of painting.","150X200","7","211525","30217.8","243254","digital","2024-08-26 09:15:30",""),
("5","Islamic Relife","Cotton,Organic Cotton, Small,Medium,Large,XL,XXl,XXXL,Free size,Colored,Black,White","-","1","869.565","869.565","1000","digital","2024-08-26 19:55:32",""),
("6","Islamic Relife","Polyester, Small,Medium,Large,XL,XXl,XXXL,Free size,Colored,Black,White","-","1","521.739","521.739","600","digital","2024-08-26 19:55:32",""),
("7","Islamic Relife","Brochures, Glossy Paper or Matt paper 150 gm, ","A5","1000","9565.22","9.56522","11000","digital","2024-08-26 19:56:39",""),
("8","Islamic Relife","Brochures, Glossy Paper or Matt paper 150 gm, ","A5","500","4347.83","8.69565","5000","digital","2024-08-26 19:56:39",""),
("9","Islamic Relife","Brochures, Glossy Paper or Matt paper 150 gm, ","A5","50","1000","20","1150","digital","2024-08-26 19:56:39",""),
("10","Islamic Relife","Brochures, Glossy Paper or Matt paper 150 gm, ","A4","1000","9565.22","9.56522","11000","digital","2024-08-26 19:56:39",""),
("11","Islamic Relife","Brochures, Glossy Paper or Matt paper 150 gm, ","A4","500","6086.96","12.1739","7000","digital","2024-08-26 19:56:39",""),
("12","Islamic Relife","Brochures, Glossy Paper or Matt paper 150 gm, ","A4","50","2000","40","2300","digital","2024-08-26 19:56:39",""),
("13","Islamic Relife","Letter head full color Digital print","-","1","8.69565","8.69565","10","digital","2024-08-26 19:56:39",""),
("14","Islamic Relife","Branded pen Plastic ","-","1","113.043","113.043","130","digital","2024-08-26 19:56:39",""),
("15","Islamic Relife","Branded pen Metalic","-","1","304.348","304.348","350","digital","2024-08-26 19:56:39",""),
("16","Islamic Relife","Hoodies & Shirts, Fleece, Cotton Blend, Polyester, Small,Medium,Large,XL,XXl,XXXL,Free size,Colored,Black,White","-","1","2434.78","2434.78","2800","digital","2024-08-26 19:56:39",""),
("17","Islamic Relife","Caps, Cotton twill, Polyester, Mesh","-","1","869.565","869.565","1000","digital","2024-08-26 19:56:39",""),
("18","Islamic Relife","Vests Blend fabric Small,Medium,Large,XL,XXl,XXXL,Free size","-","1","2347.83","2347.83","2700","digital","2024-08-26 19:56:39",""),
("19","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 8 Plus Cover, Digital Print, Glossy or matt paper","A4","50","11086.956521739","221.73913043478","12750","manual","2024-08-26 19:57:05",""),
("20","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 8 Plus Cover, Digital Print, Glossy or matt paper","A4","100","22173.913043478","221.73913043478","25500","manual","2024-08-26 19:57:13",""),
("21","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 8 Plus Cover, Digital Print, Glossy or matt paper","A4","500","97826.086956522","195.65217391304","112500","manual","2024-08-26 19:57:25",""),
("22","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 32 Plus Cover, Digital Print,Glossy paper,Matte paper ","A4","500","173670","347.34","199720.5","book","2024-08-26 19:58:27",""),
("23","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 32 Plus Cover, Digital Print,Glossy paper,Matte paper ","A4","100","68218.2","682.182","78450.93","book","2024-08-26 19:58:27",""),
("24","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 8 Plus Cover, Digital Print","A5","50","5478.2608695652","109.5652173913","6300","manual","2024-08-26 19:58:59",""),
("25","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 8 Plus Cover, Digital Print","A5","100","10434.782608696","104.34782608696","12000","manual","2024-08-26 19:59:10",""),
("26","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 64 Plus Cover, Digital Print,Glossy paper,Matte paper ","A4","500","337190","674.38","387768.5","book","2024-08-26 19:59:17",""),
("27","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 64 Plus Cover, Digital Print,Glossy paper,Matte paper ","A4","100","129238.2","1292.382","148623.93","book","2024-08-26 19:59:17",""),
("28","Islamic Relife","Polyester, Small,Medium,Large,XL,XXl,XXXL,Free size,Colored,Black,White","-","1","521.739","521.739","600","digital","2024-08-26 20:23:55",""),
("29","Islamic Relife","Cotton,Organic Cotton, Small,Medium,Large,XL,XXl,XXXL,Free size,Colored,Black,White","-","1","869.565","869.565","1000","digital","2024-08-26 20:24:11",""),
("30","Islamic Relife","Vests Blend fabric Small,Medium,Large,XL,XXl,XXXL,Free size","-","1","2347.83","2347.83","2700","digital","2024-08-26 20:24:19",""),
("31","Islamic Relife","Caps, Cotton twill, Polyester, Mesh","-","1","869.565","869.565","1000","digital","2024-08-26 20:24:27",""),
("32","Islamic Relife","Hoodies & Shirts, Fleece, Cotton Blend, Polyester, Small,Medium,Large,XL,XXl,XXXL,Free size,Colored,Black,White","-","1","2434.78","2434.78","2800","digital","2024-08-26 20:24:40",""),
("33","Islamic Relife","Branded pen Metalic","-","1","304.348","304.348","350","digital","2024-08-26 20:24:51",""),
("34","Islamic Relife","Branded pen Plastic ","-","1","113.043","113.043","130","digital","2024-08-26 20:24:58",""),
("35","Islamic Relife","Notebooks	Hardcover, Spiral- Bound, Recycled","B5","150","37826.1","252.174","43500","digital","2024-08-26 20:25:28",""),
("36","Islamic Relife","Notebooks	Hardcover, Spiral- Bound, Recycled","B5","100","26087","260.87","30000","digital","2024-08-26 20:25:28",""),
("37","Islamic Relife","Notebooks	Hardcover, Spiral- Bound, Recycled","B5","50","13913","278.261","16000","digital","2024-08-26 20:25:28",""),
("38","Islamic Relife","Notebooks	Hardcover, Spiral- Bound, Recycled","A6","150","13043.5","86.9565","15000","digital","2024-08-26 20:25:28",""),
("39","Islamic Relife","Notebooks	Hardcover, Spiral- Bound, Recycled","A6","100","9130.43","91.3043","10500","digital","2024-08-26 20:25:28",""),
("40","Islamic Relife","Notebooks	Hardcover, Spiral- Bound, Recycled","A6","50","5217.39","104.348","6000","digital","2024-08-26 20:25:28",""),
("41","Islamic Relife","Notebooks	Hardcover, Spiral- Bound, Recycled","A5","150","35869.6","239.13","41250","digital","2024-08-26 20:25:28",""),
("42","Islamic Relife","Notebooks	Hardcover, Spiral- Bound, Recycled","A5","100","24782.6","247.826","28500","digital","2024-08-26 20:25:28",""),
("43","Islamic Relife","Notebooks	Hardcover, Spiral- Bound, Recycled","A5","50","13043.5","260.87","15000","digital","2024-08-26 20:25:28",""),
("44","Islamic Relife","Letter head full color Digital print","-","1","8.69565","8.69565","10","digital","2024-08-26 20:25:35",""),
("45","Islamic Relife","Brochures, Glossy Paper or Matt paper 150 gm, ","A5","1000","9565.22","9.56522","11000","digital","2024-08-26 20:25:54",""),
("46","Islamic Relife","Brochures, Glossy Paper or Matt paper 150 gm, ","A5","500","4347.83","8.69565","5000","digital","2024-08-26 20:25:54",""),
("47","Islamic Relife","Brochures, Glossy Paper or Matt paper 150 gm, ","A5","50","1000","20","1150","digital","2024-08-26 20:25:54",""),
("48","Islamic Relife","Brochures, Glossy Paper or Matt paper 150 gm, ","A4","1000","9565.22","9.56522","11000","digital","2024-08-26 20:25:54",""),
("49","Islamic Relife","Brochures, Glossy Paper or Matt paper 150 gm, ","A4","500","6086.96","12.1739","7000","digital","2024-08-26 20:25:54",""),
("50","Islamic Relife","Brochures, Glossy Paper or Matt paper 150 gm, ","A4","50","2000","40","2300","digital","2024-08-26 20:25:54",""),
("51","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 8 Plus Cover, Digital Print, Glossy or matt paper","A4","500","97826.1","195.652","112500","digital","2024-08-26 20:27:08",""),
("52","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 8 Plus Cover, Digital Print, Glossy or matt paper","A4","100","22173.9","221.739","25500","digital","2024-08-26 20:27:08",""),
("53","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 8 Plus Cover, Digital Print, Glossy or matt paper","A4","50","11087","221.739","12750","digital","2024-08-26 20:27:08",""),
("54","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 8 Plus Cover, Digital Print","A5","100","10434.8","104.348","12000","digital","2024-08-26 20:27:36",""),
("55","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 8 Plus Cover, Digital Print","A5","50","5478.26","109.565","6300","digital","2024-08-26 20:27:36",""),
("56","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 8 Plus Cover, Digital Print,Glossy paper,Matte paper ","A5","500","51815","103.63","59587.25","book","2024-08-26 20:30:30",""),
("57","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 64 Plus Cover, Digital Print,Glossy paper,Matte paper ","A5","500","93455","186.91","107473.25","book","2024-08-26 20:46:55",""),
("58","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 64 Plus Cover, Digital Print,Glossy paper,Matte paper ","A5","100","43491","434.91","50014.65","book","2024-08-26 20:46:55",""),
("59","Safi","Paper Bag","20X15.5X6.5","500","83580","167.16","96117","bag","2024-08-26 20:47:00",""),
("60","Islamic Relife","Signboard preparation_Main road: Side stand poles(Tapela Holder): 50*50*2mm thick RHS with a Total Height of 340cm, 2. Content Desplay (Tapela): Height 140cm, Width 180cm with tapela thickness of 2mm, 3. All the content should have to painted on the tapela as per the attached content, 4. All contents should have to painted on both sides of the the tapela, 5. All the contentes should have to be painted accurately as shown on the attached content., The stand poles should be painted with blue color that match to the logo. All parts of the signboard should be soaked with Anti rust before blue painting, All the paint work should be apply 3 coats of painting.","140X180","8","232350","29043.7","267202","digital","2024-08-26 20:52:48",""),
("61","Islamic Relife","Signboard preparation_On site: Side stand poles(Tapela Holder): 50*50*2mm thick RHS with a Total Height of 350cm, 2. Content Desplay (Tapela): Height 150cm, Width 200cm with tapela thickness of 2mm, 3. All the content should have to painted on the tapela as per the attached content, 4. All contents should have to painted on both sides of the the tapela, 5. All the contentes should have to be painted accurately as shown on the attached c., The stand poles should be painted with blue color that match to the logo. All parts of the signboard should be soaked with Anti rust. All the paint work should be apply 3 coats of painting.","150X200","7","211525","30217.8","243254","digital","2024-08-26 20:52:48",""),
("62","Islamic Relife","Polyester, Small,Medium,Large,XL,XXl,XXXL,Free size,Colored,Black,White","-","1","521.739","521.739","600","digital","2024-08-26 21:11:58",""),
("63","Islamic Relife","Cotton,Organic Cotton, Small,Medium,Large,XL,XXl,XXXL,Free size,Colored,Black,White","-","1","869.565","869.565","1000","digital","2024-08-26 21:12:14",""),
("64","Islamic Relife","Notebooks	Hardcover, Spiral- Bound, Recycled","B5","150","37826.1","252.174","43500","digital","2024-08-26 21:12:45",""),
("65","Islamic Relife","Notebooks	Hardcover, Spiral- Bound, Recycled","B5","100","26087","260.87","30000","digital","2024-08-26 21:12:45",""),
("66","Islamic Relife","Notebooks	Hardcover, Spiral- Bound, Recycled","B5","50","13913","278.261","16000","digital","2024-08-26 21:12:45",""),
("67","Islamic Relife","Notebooks	Hardcover, Spiral- Bound, Recycled","A6","150","13043.5","86.9565","15000","digital","2024-08-26 21:12:45",""),
("68","Islamic Relife","Notebooks	Hardcover, Spiral- Bound, Recycled","A6","100","9130.43","91.3043","10500","digital","2024-08-26 21:12:45",""),
("69","Islamic Relife","Notebooks	Hardcover, Spiral- Bound, Recycled","A6","50","5217.39","104.348","6000","digital","2024-08-26 21:12:45",""),
("70","Islamic Relife","Notebooks	Hardcover, Spiral- Bound, Recycled","A5","150","35869.6","239.13","41250","digital","2024-08-26 21:12:45",""),
("71","Islamic Relife","Notebooks	Hardcover, Spiral- Bound, Recycled","A5","100","24782.6","247.826","28500","digital","2024-08-26 21:12:45",""),
("72","Islamic Relife","Notebooks	Hardcover, Spiral- Bound, Recycled","A5","50","13043.5","260.87","15000","digital","2024-08-26 21:12:45",""),
("73","Islamic Relife","Letter head full color Digital print","-","1","8.69565","8.69565","10","digital","2024-08-26 21:12:45",""),
("74","Islamic Relife","Branded pen Plastic ","-","1","113.043","113.043","130","digital","2024-08-26 21:12:45",""),
("75","Islamic Relife","Branded pen Metalic","-","1","304.348","304.348","350","digital","2024-08-26 21:12:45",""),
("76","Islamic Relife","Hoodies & Shirts, Fleece, Cotton Blend, Polyester, Small,Medium,Large,XL,XXl,XXXL,Free size,Colored,Black,White","-","1","2434.78","2434.78","2800","digital","2024-08-26 21:12:45",""),
("77","Islamic Relife","Caps, Cotton twill, Polyester, Mesh","-","1","869.565","869.565","1000","digital","2024-08-26 21:12:45",""),
("78","Islamic Relife","Vests Blend fabric Small,Medium,Large,XL,XXl,XXXL,Free size","-","1","2347.83","2347.83","2700","digital","2024-08-26 21:12:45",""),
("79","Islamic Relife","Brochures, Glossy Paper or Matt paper 150 gm, ","A5","1000","9565.22","9.56522","11000","digital","2024-08-26 21:13:06",""),
("80","Islamic Relife","Brochures, Glossy Paper or Matt paper 150 gm, ","A5","500","4347.83","8.69565","5000","digital","2024-08-26 21:13:06",""),
("81","Islamic Relife","Brochures, Glossy Paper or Matt paper 150 gm, ","A5","50","1000","20","1150","digital","2024-08-26 21:13:06",""),
("82","Islamic Relife","Brochures, Glossy Paper or Matt paper 150 gm, ","A4","1000","9565.22","9.56522","11000","digital","2024-08-26 21:13:06",""),
("83","Islamic Relife","Brochures, Glossy Paper or Matt paper 150 gm, ","A4","500","6086.96","12.1739","7000","digital","2024-08-26 21:13:06",""),
("84","Islamic Relife","Brochures, Glossy Paper or Matt paper 150 gm, ","A4","50","2000","40","2300","digital","2024-08-26 21:13:06",""),
("85","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 8 Plus Cover, Digital Print, Glossy or matt paper","A4","500","97826.1","195.652","112500","digital","2024-08-26 21:13:20",""),
("86","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 8 Plus Cover, Digital Print, Glossy or matt paper","A4","100","22173.9","221.739","25500","digital","2024-08-26 21:13:20",""),
("87","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 8 Plus Cover, Digital Print, Glossy or matt paper","A4","50","11087","221.739","12750","digital","2024-08-26 21:13:20",""),
("88","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 64 Plus Cover, Digital Print,Glossy paper,Matte paper ","A4","500","337190","674.38","387768.5","book","2024-08-26 21:19:07",""),
("89","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 64 Plus Cover, Digital Print,Glossy paper,Matte paper ","A4","100","129238.2","1292.382","148623.93","book","2024-08-26 21:19:07",""),
("90","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 32 Plus Cover, Digital Print,Glossy paper,Matte paper ","A4","500","173670","347.34","199720.5","book","2024-08-26 21:19:07",""),
("91","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 32 Plus Cover, Digital Print,Glossy paper,Matte paper ","A4","100","68218.2","682.182","78450.93","book","2024-08-26 21:19:07",""),
("92","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 64 Plus Cover, Digital Print, Glossy or matt paper","A4","50","79565.2","1591.3","91500","digital","2024-08-26 21:22:23",""),
("93","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 32 Plus Cover, Digital Print, Glossy or matt paper","A4","50","40434.8","808.696","46500","digital","2024-08-26 21:22:23",""),
("94","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 8 Plus Cover, Digital Print","A5","100","10434.8","104.348","12000","digital","2024-08-26 21:24:11",""),
("95","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 8 Plus Cover, Digital Print","A5","50","5478.26","109.565","6300","digital","2024-08-26 21:24:11",""),
("96","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 64 Plus Cover, Digital Print,Glossy paper,Matte paper ","A5","500","93455","186.91","107473.25","book","2024-08-26 21:28:20",""),
("97","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 64 Plus Cover, Digital Print,Glossy paper,Matte paper ","A5","100","43491","434.91","50014.65","book","2024-08-26 21:28:20",""),
("98","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 32 Plus Cover, Digital Print,Glossy paper,Matte paper ","A5","500","110135","220.27","126655.25","book","2024-08-26 21:28:20",""),
("99","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 32 Plus Cover, Digital Print,Glossy paper,Matte paper ","A5","100","46227","462.27","53161.05","book","2024-08-26 21:28:58",""),
("100","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 64 Plus Cover, Digital Print, Glossy or matt paper","A4","50","79565.2","1591.3","91500","digital","2024-08-26 21:30:34",""),
("101","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 32 Plus Cover, Digital Print, Glossy or matt paper","A4","50","40434.8","808.696","46500","digital","2024-08-26 21:30:34",""),
("102","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 64 Plus Cover, Digital Print, Glossy or matt paper","A5","50","39130.4","782.609","45000","digital","2024-08-26 21:38:17",""),
("103","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 32 Plus Cover, Digital Print, Glossy or matt paper","A5","50","20217.4","404.348","23250","digital","2024-08-26 21:38:17",""),
("104","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 8 Plus Cover, Digital Print, Glossy or matt paper","A5","50","5521.74","110.435","6350","digital","2024-08-26 21:38:17",""),
("105","Islamic Relife","Mugs Print","-","10","4782.6086956522","478.26086956522","5500","manual","2024-08-26 21:39:42",""),
("106","Islamic Relife","Keychain Printing ","-","50","7391.3043478261","147.82608695652","8500","manual","2024-08-26 21:41:03",""),
("107","Islamic Relife","vinyl sticker print Per care meter squer","1X1","1","565.21739130435","565.21739130435","650","manual","2024-08-26 21:41:44",""),
("108","Islamic Relife","Rollup With Stand & Installation","200X80","1","7739.1304347826","7739.1304347826","8900","manual","2024-08-26 21:43:06",""),
("109","Islamic Relife","Pollster flag","1X1","1","4347.8260869565","4347.8260869565","5000","manual","2024-08-26 21:45:25",""),
("110","Islamic Relife","Wall calendar","1X1","1","521.73913043478","521.73913043478","600","manual","2024-08-26 21:47:18",""),
("111","Islamic Relife","Desk Calendar","1X1","1","608.69565217391","608.69565217391","700","manual","2024-08-26 21:47:59",""),
("112","Islamic Relife","lanyards with print with DTF print","-","1","304.34782608696","304.34782608696","350","manual","2024-08-26 21:49:10",""),
("113","Islamic Relife","PR, NCR Paper, 3 copies with 50 leaf","-","1","869.5652173913","869.5652173913","1000","manual","2024-08-26 21:51:53",""),
("114","Islamic Relife","Store Return Voucher, NCR Paper, 3 copies with 50 leaf","-","1","869.5652173913","869.5652173913","1000","manual","2024-08-26 21:52:22",""),
("115","Islamic Relife","Petty Cash, NCR Paper, 3 copies with 50 leaf","-","1","695.65217391304","695.65217391304","800","manual","2024-08-26 21:53:02",""),
("116","Islamic Relife","Petty Cash, NCR Paper,2 copies with 50 leaf","-","1","695.65217391304","695.65217391304","800","manual","2024-08-26 21:54:33",""),
("117","Islamic Relife","Inter office asset borrowing form, NCR Paper,2 copies with 50 leaf","-","1","695.65217391304","695.65217391304","800","manual","2024-08-26 21:55:17",""),
("118","Islamic Relife","Log Sheet, NCR Paper,3 copies with 50 leaf","-","1","869.5652173913","869.5652173913","1000","manual","2024-08-26 21:56:40",""),
("119","Islamic Relife","Dispatch Note ,5 copies with 50 leaf","-","1","1217.3913043478","1217.3913043478","1400","manual","2024-08-26 21:58:13",""),
("120","Islamic Relife","Goods Receiving Note ,5 copies with 50 leaf","-","1","1217.3913043478","1217.3913043478","1400","manual","2024-08-26 21:58:35",""),
("121","Mohamed Lalo Yesuf","vinyl sticker print and cut","3.5X5","4000","8486.9565217391","2.1217391304348","9760","manual","2024-08-27 07:46:29",""),
("122","Safi","1 kg sticker print and cut","5.4X8","864","2682.1565217391","3.104347826087","3084.48","manual","2024-08-27 09:02:57",""),
("123","Safi","Paper Bag Printing small","A3","400","59808","149.52","68779.2","bag","2024-08-27 09:03:28",""),
("124","Safi","Paper Bag","20X15.5X6.5","500","83580","167.16","96117","bag","2024-08-27 09:03:28",""),
("125","Safi","Prayer and guaranty sticker","3.5X3.5","650","1690","2.6","1944","digital","2024-08-27 09:16:26",""),
("126","Safi","5 liter jerkin lable ","14.5X14.5","264","3307.92","12.53","3804","digital","2024-08-27 09:16:26",""),
("127","Safi","Plastic Bag label ","14.5X14.5","294","4953.9","16.85","5697","digital","2024-08-27 09:16:26",""),
("128","Safi","Paper Bag Printing small","A3","500","72704","145.408","83609.6","bag","2024-08-27 09:25:57",""),
("129","Safi","Paper Bag","20X15.5X6.5","500","83580","167.16","96117","bag","2024-08-27 09:25:57",""),
("130","Safi","Prayer and guaranty sticker","3.5X3.5","650","1690","2.6","1944","digital","2024-08-27 09:27:00",""),
("131","Safi","5 liter jerkin lable ","14.5X14.5","264","3307.92","12.53","3804","digital","2024-08-27 09:27:00",""),
("132","Safi","Plastic Bag label ","14.5X14.5","294","4953.9","16.85","5697","digital","2024-08-27 09:27:00",""),
("133","Safi","1 kg sticker print and cut","5.4X8","864","2682.16","3.10435","3084","digital","2024-08-27 09:27:00",""),
("134","Hamrawi Advertising","Booklet printing 12 pages including cover full color","A4","100","17915.3","179.153","20602.595","","2024-08-27 11:48:51",""),
("135","Addis Hiwot Hospital ","25cmx17cm company profile, 36 pages including cover, text 150 gm, cover 250 gm with lamination, staple binding","25X17","100","62686.4","626.864","72089.36","","2024-08-27 12:41:08",""),
("136","Color Advert","Folder Printing with pocket on 250 gm Full color print with lamination ","A2","1500","87971","58.647333333333","101166.65","brocher","2024-08-28 08:00:37",""),
("137","Color Advert","Folder Printing with pocket on 250 gm Full color print with lamination ","A2","1500","87971","58.647333333333","101166.65","brocher","2024-08-28 08:06:36",""),
("138","Addis Hiwot Hospital ","annual package 16 pages A5 size","A5","100","33850","338.5","38927.5","Multi Page Digital","2024-08-28 08:53:07",""),
("139","Addis Hiwot Hospital ","company profile 17cmx25cm with 36 pages back and front inside 150gm ,cover page 300gm with staple binding","17X25","50","33500","670","38525","Multi Page Digital","2024-08-28 08:53:13",""),
("140","Addis Hiwot Hospital ","Agenda with UV Print ","A5","50","26086.956521739","521.73913043478","30000","manual","2024-08-28 08:54:32",""),
("141","Eclips Printing Press","5 OFL Journey Booklet, 44 pages including cover, cover 250 gm Text with lamination, 150 gm  full color","A5","101","44238","438","50873.7","Multi Page Digital","2024-08-28 10:09:30",""),
("142","American Friends Service Committee","Three Fold Brochure on 250 gm, full color print, one side Matt lamination(Two Type of Jobs)","A4","1000","29181.6","29.1816","437724","brocher","2024-08-28 10:31:03",""),
("143","American Friends Service Committee","Three Fold Brochure on 250 gm, full color print, one side Matt lamination","A4","1000","29181.6","29.1816","437724","brocher","2024-08-28 10:31:03",""),
("144","American Friends Service Committee","Three Fold Brochure on 250 gm, full color print, one side Matt lamination(Two Type of Jobs)","A4","1000","29181.6","29.1816","437724","brocher","2024-08-28 10:32:46",""),
("145","American Friends Service Committee","Three Fold Brochure on 250 gm, full color print, one side Matt lamination","A4","500","17456.6","34.9132","261849","brocher","2024-08-28 10:32:46",""),
("146","UNDP","Keychain Printing ","A4","200","10000","50","11500","Single Page Digital","2024-08-28 12:23:47",""),
("147","UNDP","Backdrop banner","300X500","1","8250","8250","9487.5","payment","2024-08-28 12:24:10","");



DROP TABLE IF EXISTS performa_logs;

CREATE TABLE `performa_logs` (
  `generate_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer` text NOT NULL,
  `job_description` text NOT NULL,
  `size` text NOT NULL,
  `quantity` text NOT NULL,
  `total_price` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `unit_price` text NOT NULL,
  `price_vat` text NOT NULL,
  `types` text NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `ref` text NOT NULL,
  `validity` varchar(155) NOT NULL,
  `quote_sender` varchar(255) NOT NULL,
  `requested_by` varchar(255) NOT NULL,
  PRIMARY KEY (`generate_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO performa_logs VALUES("1","British Council","Broucher 150 gm two side print","A4","3000","56940.3","18.9801","65481.345","brocher","2024-08-24 00:00:00","EDP/000196","15","Elias","Betlhem"),
("2","British Council","Rollup With Stand & Installation High Quality(Wide Base)","80X200","5","51150","10230","58822.5","banner","2024-08-24 00:00:00","EDP/000197","15","Elias","Meron"),
("3","Islamic Relife","Signboard preparation_Main road: Side stand poles(Tapela Holder): 50*50*2mm thick RHS with a Total Height of 340cm, 2. Content Desplay (Tapela): Height 140cm, Width 180cm with tapela thickness of 2mm, 3. All the content should have to painted on the tapela as per the attached content, 4. All contents should have to painted on both sides of the the tapela, 5. All the contentes should have to be painted accurately as shown on the attached content., The stand poles should be painted with blue color that match to the logo. All parts of the signboard should be soaked with Anti rust before blue painting, All the paint work should be apply 3 coats of painting.","140X180","8","232350","29043.7","267202.5","digital","2024-08-26 00:00:00","EDP/000198","15","Elias","Endalkachew"),
("4","Islamic Relife","Signboard preparation_On site: Side stand poles(Tapela Holder): 50*50*2mm thick RHS with a Total Height of 350cm, 2. Content Desplay (Tapela): Height 150cm, Width 200cm with tapela thickness of 2mm, 3. All the content should have to painted on the tapela as per the attached content, 4. All contents should have to painted on both sides of the the tapela, 5. All the contentes should have to be painted accurately as shown on the attached c., The stand poles should be painted with blue color that match to the logo. All parts of the signboard should be soaked with Anti rust. All the paint work should be apply 3 coats of painting.","150X200","7","211525","30217.8","243253.75","digital","2024-08-26 00:00:00","EDP/000199","15","Elias","Endalkachew"),
("5","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 64 Plus Cover, Digital Print,Glossy paper,Matte paper ","A4","100","895351.19565","1292.382","1029653.8749975","book","2024-08-26 00:00:00","EDP/000200","15","Elias","Meron"),
("6","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 64 Plus Cover, Digital Print,Glossy paper,Matte paper ","A5","500","156357","186.91","179810.55","book","2024-08-26 00:00:00","EDP/000201","60","32032","ayele"),
("7","Safi","Paper Bag","20X15.5X6.5","500","107362.648","167.16","123467.0452","bag","2024-08-26 00:00:00","EDP/000202","60","32032","ayele"),
("8","Islamic Relife","Letter head full color Digital print","-","1","41208.72865","8.69565","47390.0379475","digital","2024-08-26 00:00:00","EDP/000203","60","32032","ayele"),
("9","Islamic Relife","Vests Blend fabric Small,Medium,Large,XL,XXl,XXXL,Free size","-","1","3739.134","2347.83","4300.0041","digital","2024-08-26 00:00:00","EDP/000204","60","32032","ayele"),
("10","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 64 Plus Cover, Digital Print,Glossy paper,Matte paper ","A5","100","329621.465","434.91","379064.68475","book","2024-08-26 00:00:00","EDP/000205","60","32032","ayele"),
("11","Islamic Relife","Signboard preparation_On site: Side stand poles(Tapela Holder): 50*50*2mm thick RHS with a Total Height of 350cm, 2. Content Desplay (Tapela): Height 150cm, Width 200cm with tapela thickness of 2mm, 3. All the content should have to painted on the tapela as per the attached content, 4. All contents should have to painted on both sides of the the tapela, 5. All the contentes should have to be painted accurately as shown on the attached c., The stand poles should be painted with blue color that match to the logo. All parts of the signboard should be soaked with Anti rust. All the paint work should be apply 3 coats of painting.","150X200","7","443875","30217.8","510456.25","digital","2024-08-26 00:00:00","EDP/000206","15","Elias","Meron"),
("12","Islamic Relife","Report (Annual Magazine) (Full color), Number of Pages 32 Plus Cover, Digital Print, Glossy or matt paper","A4","50","120000","808.696","138000","digital","2024-08-26 00:00:00","EDP/000207","15","Elias","Meron"),
("13","Islamic Relife","Petty Cash, NCR Paper, 3 copies with 50 leaf","-","1","695.65217391304","695.65217391304","800","manual","2024-08-26 00:00:00","EDP/000208","15","Elias","Meron"),
("14","Islamic Relife","Goods Receiving Note ,5 copies with 50 leaf","-","1","1585137.5678239","1217.3913043478","1822908.2029975","manual","2024-08-26 00:00:00","EDP/000209","-","Elias","IRE"),
("15","Mohamed Lalo Yesuf","vinyl sticker print and cut","3.5X5","4000","8486.9565217391","2.1217391304348","9760","manual","2024-08-27 00:00:00","EDP/000210","15","Elias","Endalkachew"),
("16","Safi","Plastic Bag label ","14.5X14.5","294","156021.97652174","16.85","179425.273","digital","2024-08-27 00:00:00","EDP/000211","15","Elias","Ebrahim"),
("17","Safi","1 kg sticker print and cut","5.4X8","864","168917.98","3.10435","194255.677","digital","2024-08-27 00:00:00","EDP/000212","15","Elias","Ebrahim"),
("18","Hamrawi Advertising","Booklet printing 12 pages including cover full color","A4","100","17915.3","179.153","20602.595","","2024-08-27 00:00:00","EDP/000213","15","Elias","Abe"),
("19","Addis Hiwot Hospital ","25cmx17cm company profile, 36 pages including cover, text 150 gm, cover 250 gm with lamination, staple binding","25X17","100","62686.4","626.864","72089.36","","2024-08-27 00:00:00","EDP/000214","15","Elias","sumeya"),
("20","Color Advert","Folder Printing with pocket on 250 gm Full color print with lamination ","A2","1500","87971","58.647333333333","101166.65","brocher","2024-08-28 00:00:00","EDP/000215","15","Elias","Dawit"),
("21","Color Advert","Folder Printing with pocket on 250 gm Full color print with lamination ","A2","1500","87971","58.647333333333","101166.65","brocher","2024-08-28 00:00:00","EDP/000216","15","Elias","Dawit"),
("22","Addis Hiwot Hospital ","company profile 17cmx25cm with 36 pages back and front inside 150gm ,cover page 300gm with staple binding","17X25","50","67350","670","77452.5","Multi Page Digital","2024-08-28 00:00:00","EDP/000217","15","Elias","sumeya"),
("23","Addis Hiwot Hospital ","Agenda with UV Print ","A5","50","26086.956521739","521.73913043478","30000","manual","2024-08-28 00:00:00","EDP/000218","15","Elias","sumeya"),
("24","Eclips Printing Press","5 OFL Journey Booklet, 44 pages including cover, cover 250 gm Text with lamination, 150 gm  full color","A5","101","44238","438","50873.7","Multi Page Digital","2024-08-28 00:00:00","EDP/000219","15","Elias","Shikur"),
("25","American Friends Service Committee","Three Fold Brochure on 250 gm, full color print, one side Matt lamination","A4","1000","58363.2","29.1816","67117.68","brocher","2024-08-28 00:00:00","EDP/000220","15","Elias","Robel"),
("26","American Friends Service Committee","Three Fold Brochure on 250 gm, full color print, one side Matt lamination","A4","500","46638.2","34.9132","53633.93","brocher","2024-08-28 00:00:00","EDP/000221","15","Elias","Robel"),
("27","UNDP","Keychain Printing ","A4","200","10000","50","11500","Single Page Digital","2024-08-28 00:00:00","EDP/000222","15","Elias","Meron");



DROP TABLE IF EXISTS plate;

CREATE TABLE `plate` (
  `plate_id` int(11) NOT NULL AUTO_INCREMENT,
  `plate_name` varchar(100) NOT NULL,
  `price` double NOT NULL,
  PRIMARY KEY (`plate_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO plate VALUES("1","KORD","700");



DROP TABLE IF EXISTS price_data;

CREATE TABLE `price_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase` varchar(200) NOT NULL,
  `sale` varchar(200) NOT NULL,
  `date` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




DROP TABLE IF EXISTS price_quote_log;

CREATE TABLE `price_quote_log` (
  `generate_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer` text NOT NULL,
  `vat_number` text NOT NULL,
  `tin_number` text NOT NULL,
  `address` text NOT NULL,
  `quote_sender` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `requested_by` text NOT NULL,
  `phone_no` text NOT NULL,
  `vat` text NOT NULL,
  `valid` double NOT NULL,
  `items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`items`)),
  `ref` varchar(255) NOT NULL,
  `grand_total` varchar(255) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`generate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




DROP TABLE IF EXISTS print_data;

CREATE TABLE `print_data` (
  `data_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer` varchar(100) DEFAULT NULL,
  `job_type` varchar(100) DEFAULT NULL,
  `paper_type` varchar(100) DEFAULT NULL,
  `constant_price` int(11) DEFAULT NULL,
  `margin_in` int(11) DEFAULT NULL,
  `item_per_page` int(11) DEFAULT NULL,
  `required_quantity` int(11) DEFAULT NULL,
  `per_cent` int(11) DEFAULT NULL,
  `on_plate` int(11) DEFAULT NULL,
  `print_side` int(11) DEFAULT NULL,
  `print_color` int(11) DEFAULT NULL,
  `die_cut_count` int(11) DEFAULT NULL,
  `die_cut_count2` int(11) DEFAULT NULL,
  `lamination_price` int(11) DEFAULT NULL,
  `waste` int(11) DEFAULT NULL,
  `vat` int(11) DEFAULT NULL,
  `number_of_plate` int(11) DEFAULT NULL,
  `number_of_page` int(11) DEFAULT NULL,
  `cover_print_count` int(11) DEFAULT NULL,
  `cover_item_per_a1` int(11) DEFAULT NULL,
  `plate_price` int(11) DEFAULT NULL,
  `stich` int(11) DEFAULT NULL,
  `machine_run` int(11) DEFAULT NULL,
  `printing_price` int(11) DEFAULT NULL,
  `required_paper` int(11) DEFAULT NULL,
  `waste_paper` int(11) DEFAULT NULL,
  `total_required_paper` int(11) DEFAULT NULL,
  `paper_cost` int(11) DEFAULT NULL,
  `cover_rim_require` int(11) DEFAULT NULL,
  `cover_price` int(11) DEFAULT NULL,
  `total_cost` int(11) DEFAULT NULL,
  `P__M` int(11) DEFAULT NULL,
  `unit_price` int(11) DEFAULT NULL,
  `die_cut` int(11) DEFAULT NULL,
  `lamination` int(11) DEFAULT NULL,
  `total_price` int(11) DEFAULT NULL,
  `including_vat` int(11) DEFAULT NULL,
  PRIMARY KEY (`data_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;




DROP TABLE IF EXISTS project_connect;

CREATE TABLE `project_connect` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `management_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO project_connect VALUES("1","9","5","2","1"),
("2","9","7","4","2"),
("3","9","8","5","3"),
("4","9","9","6","4"),
("5","9","11","8","5"),
("6","9","19","16","6"),
("7","9","20","17","7"),
("8","9","21","18","8"),
("9","9","22","19","9"),
("10","9","23","20","10"),
("11","9","24","21","11"),
("12","9","25","22","12"),
("13","9","28","25","13"),
("14","9","29","26","14"),
("15","9","31","28","15"),
("16","9","32","29","16"),
("17","9","33","30","17"),
("18","9","34","31","18"),
("19","9","35","32","19"),
("20","9","36","33","20"),
("21","9","37","34","21"),
("22","10","38","35","22"),
("23","9","39","36","23"),
("24","9","40","37","24"),
("25","9","41","38","25"),
("26","9","42","39","26"),
("27","9","49","45","27"),
("28","10","50","47","28"),
("29","9","51","48","29"),
("30","14","52","49","30"),
("31","22","54","1","31"),
("32","22","55","2","32"),
("33","22","56","3","33"),
("34","22","57","4","34"),
("35","22","58","5","35"),
("36","22","59","6","36"),
("37","25","60","7","37");



DROP TABLE IF EXISTS recent_order;

CREATE TABLE `recent_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer` text NOT NULL,
  `total_price` float NOT NULL,
  `type` text NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

INSERT INTO recent_order VALUES("1","British Council","56940.3","brocher","2024-08-24"),
("2","British Council","56940.3","brocher","2024-08-24"),
("3","British Council","83383.6","book","2024-08-24"),
("4","British Council","58822.5","banner","2024-08-24"),
("5","British Council","11764.5","banner","2024-08-24"),
("6","British Council","83383.6","book","2024-08-24"),
("7","British Council","58822.5","banner","2024-08-24"),
("8","British Council","58822.5","banner","2024-08-24"),
("9","British Council","83383.6","book","2024-08-24"),
("10","British Council","83383.6","book","2024-08-24"),
("11","British Council","91023.1","book","2024-08-24"),
("12","British Council","37051.5","bag","2024-08-24"),
("13","British Council","15013.2","banner","2024-08-24"),
("14","British Council","15013.2","banner","2024-08-24"),
("15","British Council","18975","banner","2024-08-24"),
("16","British Council","22515.3","brocher","2024-08-24"),
("17","franol","22515.3","brocher","2024-08-24"),
("18","franol","22515.3","brocher","2024-08-25"),
("19","British Council","91408","book","2024-08-25"),
("20","Prime Meat and Food Products ","22515.3","brocher","2024-08-25"),
("21","British Council","91408","book","2024-08-25"),
("22","Islamic Relife","243254","digital","2024-08-26"),
("23","Islamic Relife","267202","digital","2024-08-26"),
("24","Safi","96117","bag","2024-08-26"),
("25","Islamic Relife","600","digital","2024-08-26"),
("26","Islamic Relife","1000","digital","2024-08-26"),
("27","Islamic Relife","2700","digital","2024-08-26"),
("28","Islamic Relife","2800","digital","2024-08-26"),
("29","Islamic Relife","2000","digital","2024-08-26"),
("30","Islamic Relife","1000","digital","2024-08-26"),
("31","Islamic Relife","350","digital","2024-08-26"),
("32","Islamic Relife","130","digital","2024-08-26"),
("33","Islamic Relife","10","digital","2024-08-26"),
("34","Islamic Relife","2300","digital","2024-08-26"),
("35","Islamic Relife","7000","digital","2024-08-26"),
("36","Islamic Relife","11000","digital","2024-08-26"),
("37","Islamic Relife","1150","digital","2024-08-26"),
("38","Islamic Relife","5000","digital","2024-08-26"),
("39","Islamic Relife","11000","digital","2024-08-26"),
("40","Islamic Relife","12750","digital","2024-08-26"),
("41","Islamic Relife","25500","digital","2024-08-26"),
("42","Islamic Relife","112500","digital","2024-08-26"),
("43","Islamic Relife","60989.1","book","2024-08-26"),
("44","Islamic Relife","184702","book","2024-08-26"),
("45","Islamic Relife","141358","book","2024-08-26"),
("46","Islamic Relife","387768","book","2024-08-26"),
("47","Islamic Relife","6300","digital","2024-08-26"),
("48","Islamic Relife","12600","digital","2024-08-26"),
("49","Islamic Relife","59587.2","book","2024-08-26"),
("50","Islamic Relife","53161.1","book","2024-08-26"),
("51","Islamic Relife","126655","book","2024-08-26"),
("52","Islamic Relife","50014.6","book","2024-08-26"),
("53","Islamic Relife","107473","book","2024-08-26"),
("54","Islamic Relife","15000","digital","2024-08-26"),
("55","Islamic Relife","28500","digital","2024-08-26"),
("56","Islamic Relife","41250","digital","2024-08-26"),
("57","Islamic Relife","6000","digital","2024-08-26"),
("58","Islamic Relife","10500","digital","2024-08-26"),
("59","Islamic Relife","15000","digital","2024-08-26"),
("60","Islamic Relife","16000","digital","2024-08-26"),
("61","Islamic Relife","30000","digital","2024-08-26"),
("62","Islamic Relife","43500","digital","2024-08-26"),
("63","Islamic Relife","46500","digital","2024-08-26"),
("64","Islamic Relife","91500","digital","2024-08-26"),
("65","Islamic Relife","6350","digital","2024-08-26"),
("66","Islamic Relife","23250","digital","2024-08-26"),
("67","Islamic Relife","45000","digital","2024-08-26"),
("68","Islamic Relife","5500","digital","2024-08-26"),
("69","Islamic Relife","8500","digital","2024-08-26"),
("70","Islamic Relife","650","digital","2024-08-26"),
("71","Islamic Relife","100","digital","2024-08-26"),
("72","Islamic Relife","8900","digital","2024-08-26"),
("73","Islamic Relife","5000","digital","2024-08-26"),
("74","Islamic Relife","3000","digital","2024-08-26"),
("75","Islamic Relife","600","digital","2024-08-26"),
("76","Islamic Relife","700","digital","2024-08-26"),
("77","Islamic Relife","350","digital","2024-08-26"),
("78","Islamic Relife","1000","digital","2024-08-26"),
("79","Islamic Relife","1000","digital","2024-08-26"),
("80","Islamic Relife","1000","digital","2024-08-26"),
("81","Islamic Relife","800","digital","2024-08-26"),
("82","Islamic Relife","800","digital","2024-08-26"),
("83","Islamic Relife","1000","digital","2024-08-26"),
("84","Islamic Relife","1200","digital","2024-08-26"),
("85","Islamic Relife","1400","digital","2024-08-26"),
("86","Islamic Relife","1400","digital","2024-08-26"),
("87","Safi","75587.2","bag","2024-08-26"),
("88","Safi","68779.2","bag","2024-08-26"),
("89","Mohamed Lalo Yesuf","9760","digital","2024-08-27"),
("90","Safi","3084.48","digital","2024-08-27"),
("91","Safi","5696.98","digital","2024-08-27"),
("92","Safi","3804.11","digital","2024-08-27"),
("93","Safi","1943.5","digital","2024-08-27"),
("94","Safi","83609.6","bag","2024-08-27"),
("95","Hamrawi Advertising","20602.6","book","2024-08-27"),
("96","Addis Hiwot Hospital ","72089.4","book","2024-08-27"),
("97","UNDP","9487.5","banner","2024-08-27"),
("98","UNDP","9487.5","banner","2024-08-27"),
("99","UNDP","345000","design","2024-08-27"),
("100","UNDP","14994300","single_page","2024-08-28"),
("101","UNDP","14994300","single_page","2024-08-28"),
("102","UNDP","14994300","single_page","2024-08-28"),
("103","UNDP","14994300","single_page","2024-08-28"),
("104","UNDP","11375.8","single_page","2024-08-28"),
("105","Color","87971","brocher","2024-08-28"),
("106","Color Advert","36915","multi_page","2024-08-28"),
("107","Addis Hiwot Hospital ","38927.5","multi_page","2024-08-28"),
("108","Addis Hiwot Hospital ","30000","digital","2024-08-28"),
("109","Eclips Printing Press","48144.2","multi_page","2024-08-28"),
("110","Eclips Printing Press","50873.7","multi_page","2024-08-28"),
("111","American Friends Service Committee","17456.6","brocher","2024-08-28"),
("112","American Friends Service Committee","29181.6","brocher","2024-08-28"),
("113","American Friends Service Committee","29181.6","brocher","2024-08-28"),
("114","Addis Hiwot Hospital ","68124.2","book","2024-08-28");



DROP TABLE IF EXISTS refnumber;

CREATE TABLE `refnumber` (
  `refnum_id` int(11) NOT NULL AUTO_INCREMENT,
  `refnum` int(11) NOT NULL,
  PRIMARY KEY (`refnum_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




DROP TABLE IF EXISTS remainder;

CREATE TABLE `remainder` (
  `generate_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer` text NOT NULL,
  `job_description` text NOT NULL,
  `size` text NOT NULL,
  `quantity` text NOT NULL,
  `total_price` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `unit_price` text NOT NULL,
  `price_vat` text NOT NULL,
  `types` text NOT NULL,
  `advance` double NOT NULL,
  `remainder` double NOT NULL,
  `created_at` date NOT NULL,
  PRIMARY KEY (`generate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




DROP TABLE IF EXISTS sales;

CREATE TABLE `sales` (
  `sales_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer` text NOT NULL,
  `tin_number` text NOT NULL,
  `price_before_vat` float NOT NULL,
  `vat` float NOT NULL,
  `price_including_vat` float NOT NULL,
  `machine_number` text NOT NULL,
  `holding_tax` float NOT NULL,
  `receipt_number` text NOT NULL,
  `sales_date` date NOT NULL,
  `update_date` date NOT NULL,
  PRIMARY KEY (`sales_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




DROP TABLE IF EXISTS sales_withoutvat;

CREATE TABLE `sales_withoutvat` (
  `sales_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer` text NOT NULL,
  `sale_price` float NOT NULL,
  `sales_date` date NOT NULL,
  `update_date` date NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`sales_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




DROP TABLE IF EXISTS sales_withoutvat_purchase;

CREATE TABLE `sales_withoutvat_purchase` (
  `sales_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer` text NOT NULL,
  `sale_price` float NOT NULL,
  `sales_date` date NOT NULL,
  `update_date` date NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`sales_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




DROP TABLE IF EXISTS sales_withvat;

CREATE TABLE `sales_withvat` (
  `sales_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer` text NOT NULL,
  `price_before_vat` float NOT NULL,
  `vat` float NOT NULL,
  `price_including_vat` float NOT NULL,
  `with_holding_tax` text NOT NULL,
  `sales_date` date NOT NULL,
  `update_date` date NOT NULL,
  `recitnum` text NOT NULL,
  PRIMARY KEY (`sales_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




DROP TABLE IF EXISTS single_page;

CREATE TABLE `single_page` (
  `single_page_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer` text NOT NULL,
  `job_type` text NOT NULL,
  `size` text NOT NULL,
  `required_quantity` float NOT NULL,
  `total_price` float NOT NULL,
  `unit_price` float NOT NULL,
  `total_price_vat` float NOT NULL,
  `vat` float NOT NULL,
  `cvat` double NOT NULL,
  `width` float NOT NULL,
  `commitonprice` float NOT NULL,
  `date` date NOT NULL,
  `private` varchar(255) NOT NULL,
  `print_side` int(11) NOT NULL,
  `machine_run` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `payment_status` tinyint(1) NOT NULL DEFAULT 0,
  `machine_type` int(11) NOT NULL,
  PRIMARY KEY (`single_page_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO single_page VALUES("1","UNDP","43354","A4","500","13038500","26077","14994300","15","35","3","5000","2024-08-28","choose","543","271500","2","1","2"),
("2","UNDP","43354","A4","500","13038500","26077","14994300","15","35","3","5000","2024-08-28","choose","543","271500","2","1","2"),
("3","UNDP","43354","A4","500","13038500","26077","14994300","15","35","3","5000","2024-08-28","choose","543","271500","2","1","2"),
("4","UNDP","43354","A4","500","13038500","26077","14994300","15","35","3","5000","2024-08-28","choose","543","271500","2","1","2"),
("5","UNDP","Keychain Printing ","A4","200","9892","49.46","11375.8","15","0","3","0","2024-08-28","choose","2","400","4","1","1");



DROP TABLE IF EXISTS singlepagedb;

CREATE TABLE `singlepagedb` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_type` varchar(255) NOT NULL,
  `page_price` float NOT NULL,
  PRIMARY KEY (`page_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




DROP TABLE IF EXISTS stock;

CREATE TABLE `stock` (
  `stock_id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_type` varchar(100) NOT NULL,
  `stock_quantity` double NOT NULL,
  `ratio` double NOT NULL,
  `stock_quantity2` double NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `dangerzone` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `removed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `catagory` varchar(255) NOT NULL,
  `width` double DEFAULT NULL,
  `height` double DEFAULT NULL,
  `care_lamination` double DEFAULT NULL,
  `total_lamination` double DEFAULT NULL,
  PRIMARY KEY (`stock_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO stock VALUES("3","250 gm Art 100 leaf_61X86","-1.2","100","-120","2024-08-28 17:22:00","3","2024-08-28 17:22:00","2024-08-28 17:22:00","paper","","","",""),
("5","Sticker 1.07MX50M","10","1","10","2024-08-24 12:06:54","1","2024-08-24 12:06:54","2024-08-24 12:06:54","banner","1.07","50","53.5","535"),
("6","Rollup Wide Base","10","1","10","2024-08-25 21:23:39","5","2024-08-25 21:23:39","2024-08-25 21:23:39","","","","",""),
("8","Grayback 91X50","15.92","1","15.92","2024-08-25 21:23:39","1","2024-08-25 21:23:39","2024-08-25 21:23:39","banner","0.91","50","45.5","216.58"),
("9","250gm test","10","1","10","2024-08-26 16:31:08","1","2024-08-26 16:31:08","2024-08-26 16:31:08","paper","","","","");



DROP TABLE IF EXISTS stock_log;

CREATE TABLE `stock_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `customer` varchar(255) NOT NULL,
  `stock_id` int(11) NOT NULL,
  `status` varchar(100) NOT NULL,
  `last_quantity` double NOT NULL,
  `added_removed` double NOT NULL,
  `reason` longtext NOT NULL,
  `jobnumber` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `removed_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=138 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO stock_log VALUES("1","25","British Council","1","remove_quantity","2500","385","Broucher 150 gm two side print","jn_0001","2024-08-24 11:41:35","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("2","0","","1","add_quantity","2115","385","Reversed","","2024-08-24 11:43:08","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("3","25","British Council","2","remove_quantity","4300","412.8","Broucher 150 gm two side print","jn_0002","2024-08-24 11:51:12","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("4","25","British Council","1","remove_quantity","2500","385","Broucher 150 gm two side print","jn_0002","2024-08-24 11:51:12","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("5","25","British Council","2","remove_quantity","3887.2","412.8","Broucher 150 gm two side print","jn_0003","2024-08-24 11:58:13","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("6","25","British Council","1","remove_quantity","2115","385","Broucher 150 gm two side print","jn_0003","2024-08-24 11:58:13","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("7","0","","2","add_quantity","3474.4","412.8","Reversed","","2024-08-24 12:01:06","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("8","0","","1","add_quantity","1730","385","Reversed","","2024-08-24 12:01:06","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("9","0","","2","add_quantity","3887.2","412.8","Reversed","","2024-08-24 12:01:08","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("10","0","","1","add_quantity","2115","385","Reversed","","2024-08-24 12:01:08","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("11","25","British Council","2","remove_quantity","4300","412.8","Broucher 150 gm two side print","jn_0004","2024-08-24 12:09:55","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("12","25","British Council","1","remove_quantity","2500","385","Broucher 150 gm two side print","jn_0004","2024-08-24 12:09:55","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("13","0","","2","add_quantity","3887.2","412.8","Reversed","","2024-08-24 12:11:14","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("14","0","","1","add_quantity","2115","385","Reversed","","2024-08-24 12:11:14","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("15","25","British Council","2","remove_quantity","4300","412.8","Broucher 150 gm two side print","jn_0005","2024-08-24 12:20:33","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("16","25","British Council","1","remove_quantity","2500","385","Broucher 150 gm two side print","jn_0005","2024-08-24 12:20:33","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("17","0","","2","add_quantity","3887.2","412.8","Reversed","","2024-08-24 12:21:11","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("18","0","","1","add_quantity","2115","385","Reversed","","2024-08-24 12:21:11","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("19","6","British Council","1","remove_quantity","2500","656","24 page including cover","jn_0006","2024-08-24 14:29:44","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("20","6","British Council","1","remove_quantity","1844","0","24 page including cover","jn_0006","2024-08-24 14:29:44","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("21","6","British Council","1","remove_quantity","1844","0","24 page including cover","jn_0006","2024-08-24 14:29:44","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("22","6","British Council","1","remove_quantity","1844","0","24 page including cover","jn_0006","2024-08-24 14:29:44","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("23","6","British Council","3","remove_quantity","2000","131","24 page including cover","jn_0006","2024-08-24 14:29:44","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("24","6","British Council","8","remove_quantity","227.5","9.1","Rollup With Stand & Installation High Quality(Wide Base)","jn_0007","2024-08-24 14:51:03","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("25","6","British Council","6","remove_quantity","10","5","Rollup With Stand & Installation High Quality(Wide Base)","jn_0007","2024-08-24 14:51:03","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("26","6","British Council","8","remove_quantity","218.4","1.82","Rollup With Stand & Installation High Quality(Wide Base)","jn_0008","2024-08-24 14:52:51","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("27","6","British Council","6","remove_quantity","5","1","Rollup With Stand & Installation High Quality(Wide Base)","jn_0008","2024-08-24 14:52:51","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("28","6","British Council","2","remove_quantity","4300","68.8","24 page including cover","jn_0009","2024-08-24 15:48:10","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("29","6","British Council","1","remove_quantity","1844","656","24 page including cover","jn_0009","2024-08-24 15:48:10","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("30","6","British Council","1","remove_quantity","1188","0","24 page including cover","jn_0009","2024-08-24 15:48:10","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("31","6","British Council","1","remove_quantity","1188","0","24 page including cover","jn_0009","2024-08-24 15:48:10","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("32","6","British Council","1","remove_quantity","1188","0","24 page including cover","jn_0009","2024-08-24 15:48:10","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("33","6","British Council","3","remove_quantity","1869","131","24 page including cover","jn_0009","2024-08-24 15:48:10","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("34","6","British Council","1","remove_quantity","1188","656","24 page including cover","jn_0010","2024-08-24 15:48:23","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("35","6","British Council","1","remove_quantity","532","0","24 page including cover","jn_0010","2024-08-24 15:48:23","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("36","6","British Council","1","remove_quantity","532","0","24 page including cover","jn_0010","2024-08-24 15:48:23","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("37","6","British Council","1","remove_quantity","532","0","24 page including cover","jn_0010","2024-08-24 15:48:23","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("38","6","British Council","3","remove_quantity","1738","131","24 page including cover","jn_0010","2024-08-24 15:48:23","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("39","6","British Council","1","remove_quantity","532","656","24 page including cover","jn_0011","2024-08-24 16:17:02","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("40","6","British Council","1","remove_quantity","-124","0","24 page including cover","jn_0011","2024-08-24 16:17:02","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("41","6","British Council","1","remove_quantity","-124","0","24 page including cover","jn_0011","2024-08-24 16:17:02","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("42","6","British Council","1","remove_quantity","-124","0","24 page including cover","jn_0011","2024-08-24 16:17:02","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("43","6","British Council","3","remove_quantity","1607","131","24 page including cover","jn_0011","2024-08-24 16:17:02","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("44","6","British Council","1","remove_quantity","-124","656","24 page including cover","jn_0012","2024-08-24 16:22:29","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("45","6","British Council","1","remove_quantity","-780","0","24 page including cover","jn_0012","2024-08-24 16:22:29","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("46","6","British Council","1","remove_quantity","-780","0","24 page including cover","jn_0012","2024-08-24 16:22:29","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("47","6","British Council","1","remove_quantity","-780","0","24 page including cover","jn_0012","2024-08-24 16:22:29","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("48","6","British Council","3","remove_quantity","1476","131","24 page including cover","jn_0012","2024-08-24 16:22:29","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("49","6","British Council","1","remove_quantity","-780","656","24 page including cover","jn_0013","2024-08-24 16:33:45","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("50","6","British Council","1","remove_quantity","-1436","0","24 page including cover","jn_0013","2024-08-24 16:33:45","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("51","6","British Council","1","remove_quantity","-1436","0","24 page including cover","jn_0013","2024-08-24 16:33:45","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("52","6","British Council","1","remove_quantity","-1436","0","24 page including cover","jn_0013","2024-08-24 16:33:45","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("53","6","British Council","3","remove_quantity","1345","131","24 page including cover","jn_0013","2024-08-24 16:33:45","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("54","6","British Council","2","remove_quantity","4231.2","90.3","24 page including cover","jn_0014","2024-08-24 16:35:59","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("55","6","British Council","1","remove_quantity","-1436","656","24 page including cover","jn_0014","2024-08-24 16:35:59","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("56","6","British Council","1","remove_quantity","-2092","0","24 page including cover","jn_0014","2024-08-24 16:35:59","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("57","6","British Council","1","remove_quantity","-2092","0","24 page including cover","jn_0014","2024-08-24 16:35:59","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("58","6","British Council","1","remove_quantity","-2092","0","24 page including cover","jn_0014","2024-08-24 16:35:59","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("59","6","British Council","3","remove_quantity","1214","131","24 page including cover","jn_0014","2024-08-24 16:35:59","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("60","25","","2","add_quantity","4140.9","90.3","Reversed","","2024-08-24 16:37:04","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("61","25","","1","add_quantity","-2092","656","Reversed","","2024-08-24 16:37:04","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("62","25","","1","add_quantity","-1436","0","Reversed","","2024-08-24 16:37:04","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("63","25","","1","add_quantity","-1436","0","Reversed","","2024-08-24 16:37:04","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("64","25","","1","add_quantity","-1436","0","Reversed","","2024-08-24 16:37:04","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("65","25","","3","add_quantity","1083","131","Reversed","","2024-08-24 16:37:04","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("66","6","British Council","0","remove_quantity","0","23","32","jn_0015","2024-08-24 20:55:34","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("67","6","British Council","0","remove_quantity","0","23","32","jn_0016","2024-08-24 21:06:59","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("68","25","British Council","3","remove_quantity","1214","110","request","jn_0017","2024-08-24 21:09:42","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("69","25","British Council","1","remove_quantity","-1436","385","Broucher 150 gm two side print","jn_0021","2024-08-24 21:20:46","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("70","25","franol","1","remove_quantity","-1821","385","Broucher 150 gm two side print","jn_0022","2024-08-24 21:23:31","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("71","25","franol","1","remove_quantity","-2206","385","Broucher 150 gm two side print","jn_0028","2024-08-25 12:58:35","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("72","6","British Council","2","remove_quantity","4231.2","90.3","24 page including cover","jn_0029","2024-08-25 12:59:52","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("73","6","British Council","1","remove_quantity","-2591","656","24 page including cover","jn_0029","2024-08-25 12:59:52","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("74","6","British Council","1","remove_quantity","-3247","0","24 page including cover","jn_0029","2024-08-25 12:59:52","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("75","6","British Council","1","remove_quantity","-3247","0","24 page including cover","jn_0029","2024-08-25 12:59:52","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("76","6","British Council","1","remove_quantity","-3247","0","24 page including cover","jn_0029","2024-08-25 12:59:52","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("77","6","British Council","3","remove_quantity","1104","125","24 page including cover","jn_0029","2024-08-25 12:59:52","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("78","25","Prime Meat and Food Products ","1","remove_quantity","-3247","385","Broucher 150 gm two side print","jn_0030","2024-08-25 13:15:58","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("79","25","","1","add_quantity","-3632","385","Reversed","","2024-08-25 21:22:21","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("80","25","","2","add_quantity","4140.9","90.3","Reversed","","2024-08-25 21:22:27","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("81","25","","1","add_quantity","-3247","656","Reversed","","2024-08-25 21:22:27","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("82","25","","1","add_quantity","-2591","0","Reversed","","2024-08-25 21:22:27","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("83","25","","1","add_quantity","-2591","0","Reversed","","2024-08-25 21:22:27","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("84","25","","1","add_quantity","-2591","0","Reversed","","2024-08-25 21:22:27","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("85","25","","3","add_quantity","979","125","Reversed","","2024-08-25 21:22:27","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("86","25","","1","add_quantity","-2591","385","Reversed","","2024-08-25 21:22:45","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("87","25","","3","add_quantity","1104","110","Reversed","","2024-08-25 21:22:54","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("88","25","","0","add_quantity","0","23","Reversed","","2024-08-25 21:22:56","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("89","25","","0","add_quantity","0","23","Reversed","","2024-08-25 21:23:01","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("90","25","","0","add_quantity","0","23","Reversed","","2024-08-25 21:23:07","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("91","25","","1","add_quantity","-2206","656","Reversed","","2024-08-25 21:23:10","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("92","25","","1","add_quantity","-1550","0","Reversed","","2024-08-25 21:23:10","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("93","25","","1","add_quantity","-1550","0","Reversed","","2024-08-25 21:23:10","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("94","25","","1","add_quantity","-1550","0","Reversed","","2024-08-25 21:23:10","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("95","25","","3","add_quantity","1214","131","Reversed","","2024-08-25 21:23:10","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("96","25","","1","add_quantity","-1550","656","Reversed","","2024-08-25 21:23:27","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("97","25","","1","add_quantity","-894","0","Reversed","","2024-08-25 21:23:27","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("98","25","","1","add_quantity","-894","0","Reversed","","2024-08-25 21:23:27","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("99","25","","1","add_quantity","-894","0","Reversed","","2024-08-25 21:23:27","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("100","25","","3","add_quantity","1345","131","Reversed","","2024-08-25 21:23:27","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("101","25","","1","add_quantity","-894","656","Reversed","","2024-08-25 21:23:30","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("102","25","","1","add_quantity","-238","0","Reversed","","2024-08-25 21:23:30","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("103","25","","1","add_quantity","-238","0","Reversed","","2024-08-25 21:23:30","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("104","25","","1","add_quantity","-238","0","Reversed","","2024-08-25 21:23:30","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("105","25","","3","add_quantity","1476","131","Reversed","","2024-08-25 21:23:30","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("106","25","","1","add_quantity","-238","656","Reversed","","2024-08-25 21:23:32","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("107","25","","1","add_quantity","418","0","Reversed","","2024-08-25 21:23:32","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("108","25","","1","add_quantity","418","0","Reversed","","2024-08-25 21:23:32","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("109","25","","1","add_quantity","418","0","Reversed","","2024-08-25 21:23:32","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("110","25","","3","add_quantity","1607","131","Reversed","","2024-08-25 21:23:32","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("111","25","","2","add_quantity","4231.2","68.8","Reversed","","2024-08-25 21:23:34","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("112","25","","1","add_quantity","418","656","Reversed","","2024-08-25 21:23:34","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("113","25","","1","add_quantity","1074","0","Reversed","","2024-08-25 21:23:34","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("114","25","","1","add_quantity","1074","0","Reversed","","2024-08-25 21:23:34","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("115","25","","1","add_quantity","1074","0","Reversed","","2024-08-25 21:23:34","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("116","25","","3","add_quantity","1738","131","Reversed","","2024-08-25 21:23:34","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("117","25","","8","add_quantity","5","1.82","Reversed","","2024-08-25 21:23:36","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("118","25","","6","add_quantity","4","1","Reversed","","2024-08-25 21:23:36","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("119","25","","8","add_quantity","6.82","9.1","Reversed","","2024-08-25 21:23:39","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("120","25","","6","add_quantity","5","5","Reversed","","2024-08-25 21:23:39","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("121","25","","1","add_quantity","1074","656","Reversed","","2024-08-25 21:23:41","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("122","25","","1","add_quantity","1730","0","Reversed","","2024-08-25 21:23:41","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("123","25","","1","add_quantity","1730","0","Reversed","","2024-08-25 21:23:41","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("124","25","","1","add_quantity","1730","0","Reversed","","2024-08-25 21:23:41","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("125","25","","3","add_quantity","1869","131","Reversed","","2024-08-25 21:23:41","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("126","25","","0","add_quantity","0","23","Reversed","","2024-08-25 21:23:46","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("127","25","","1","add_quantity","1730","385","Reversed","","2024-08-25 21:23:54","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("128","25","","1","add_quantity","2115","385","Reversed","","2024-08-25 21:23:56","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("129","25","","0","add_quantity","0","23","Reversed","","2024-08-25 21:24:02","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("130","25","","0","add_quantity","0","23","Reversed","","2024-08-26 17:47:57","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("131","25","","0","add_quantity","0","23","Reversed","","2024-08-26 20:46:14","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("132","25","","0","add_quantity","0","23","Reversed","","2024-08-26 20:46:20","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("133","25","","0","add_quantity","0","23","Reversed","","2024-08-26 20:46:26","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("134","25","","0","add_quantity","0","23","Reversed","","2024-08-26 20:46:32","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("135","25","","0","add_quantity","0","23","Reversed","","2024-08-26 20:46:44","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("136","25","","3","remove_quantity","14","2","BC","Office Stock","2024-08-28 07:50:40","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("137","25","American Friends Service Committee","3","remove_quantity","12","132","Three Fold Brochure on 250 gm, full color print, one side Matt lamination(Two Type of Jobs)","jn_0037","2024-08-28 17:22:00","0000-00-00 00:00:00","0000-00-00 00:00:00");



DROP TABLE IF EXISTS summary_table;

CREATE TABLE `summary_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `total_sales_vat` double DEFAULT 0,
  `total_purchases_vat` double DEFAULT 0,
  `difference` double GENERATED ALWAYS AS (`total_purchases_vat` - `total_sales_vat`) VIRTUAL,
  `decision` varchar(255) GENERATED ALWAYS AS (case when `total_sales_vat` > `total_purchases_vat` then _utf8mb4'Customer owes money' when `total_sales_vat` < `total_purchases_vat` then _utf8mb4'Government owes money' else _utf8mb4'Balanced' end) VIRTUAL,
  `last_month` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




DROP TABLE IF EXISTS table_calendar;

CREATE TABLE `table_calendar` (
  `table_id` int(11) NOT NULL AUTO_INCREMENT,
  `common_var` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `table_input` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `table_output` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`table_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;




DROP TABLE IF EXISTS test;

CREATE TABLE `test` (
  `test1` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`test1`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




DROP TABLE IF EXISTS textdb;

CREATE TABLE `textdb` (
  `text_id` int(11) NOT NULL AUTO_INCREMENT,
  `text_type` varchar(255) NOT NULL,
  `text_price` float NOT NULL,
  PRIMARY KEY (`text_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




DROP TABLE IF EXISTS unitbanner;

CREATE TABLE `unitbanner` (
  `unitbanner_id` int(11) NOT NULL AUTO_INCREMENT,
  `unitbanner_name` text NOT NULL,
  `price` float NOT NULL,
  `outprice` float NOT NULL,
  `stock_id` int(11) NOT NULL,
  PRIMARY KEY (`unitbanner_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO unitbanner VALUES("1","Banner per M2","550","230","0"),
("2","Grayback_91X500","1500","0","8");



DROP TABLE IF EXISTS unitdesign;

CREATE TABLE `unitdesign` (
  `unitdesign_id` int(11) NOT NULL AUTO_INCREMENT,
  `unitdesign_name` text NOT NULL,
  `price` float NOT NULL,
  PRIMARY KEY (`unitdesign_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO unitdesign VALUES("1","Poster Design","3000"),
("2","Page Design","300");



DROP TABLE IF EXISTS unitdigital;

CREATE TABLE `unitdigital` (
  `unitdigital_id` int(11) NOT NULL AUTO_INCREMENT,
  `unitdigital_name` text NOT NULL,
  `price` float NOT NULL,
  `stock_id` int(11) NOT NULL,
  PRIMARY KEY (`unitdigital_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




DROP TABLE IF EXISTS user;

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `previledge` varchar(100) NOT NULL,
  `module` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `payment` varchar(10) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

INSERT INTO user VALUES("25","masteradmin","admin","administrator","{\"calcview\":1,\"calcadd\":1,\"calcedit\":1,\"calcdelete\":1,\"calcgenerate\":1,\"constview\":1,\"constadd\":1,\"constedit\":1,\"constdelete\":1,\"constgenerate\":1,\"stockview\":1,\"stockadd\":1,\"stockedit\":1,\"stockdelete\":1,\"stockgenerate\":1,\"dataview\":1,\"dataadd\":1,\"dataedit\":1,\"datadelete\":1,\"datagenerate\":1,\"jobview\":1,\"jobedit\":1,\"saleview\":1,\"saleadd\":1,\"saleedit\":1,\"saledelete\":1,\"salegenerate\":1,\"reportview\":1,\"userview\":1,\"useradd\":1,\"useredit\":1,\"userdelete\":1,\"usergenerate\":1,\"custview\":1,\"custadd\":1,\"custedit\":1,\"custdelete\":1,\"custgenerate\":1,\"generateview\":1,\"fileview\":1,\"backview\":1,\"payview\":1,\"payadd\":1,\"payedit\":1,\"paydelete\":1,\"paygenerate\":1,\"payverify\":1,\"bankview\":1,\"bankadd\":1,\"bankedit\":1,\"bankdelete\":1,\"bankgenerate\":1,\"bankverify\":1,\"vatview\":1,\"vatadd\":1,\"vatedit\":1,\"vatdelete\":1,\"vatgenerate\":1,\"banksview\":1,\"banksadd\":1,\"banksedit\":1,\"banksdelete\":1,\"banksgenerate\":1,\"profileview\":1,\"brocherview\":1,\"bookview\":1,\"manualview\":1,\"digitalview\":1,\"bannerview\":1,\"designview\":1,\"singlepageview\":1,\"multipageview\":1}","no"),
("43","ermi","321","administrator","{\"calcview\":1,\"calcadd\":0,\"calcedit\":0,\"calcdelete\":0,\"calcgenerate\":0,\"constview\":1,\"constadd\":0,\"constedit\":0,\"constdelete\":0,\"constgenerate\":0,\"stockview\":1,\"stockadd\":0,\"stockedit\":0,\"stockdelete\":0,\"stockgenerate\":0,\"dataview\":1,\"dataadd\":0,\"dataedit\":0,\"datadelete\":0,\"datagenerate\":0,\"jobview\":1,\"jobedit\":0,\"saleview\":1,\"saleadd\":0,\"saleedit\":0,\"saledelete\":0,\"salegenerate\":0,\"reportview\":1,\"userview\":1,\"useradd\":1,\"useredit\":1,\"userdelete\":1,\"usergenerate\":1,\"custview\":1,\"custadd\":1,\"custedit\":1,\"custdelete\":1,\"custgenerate\":1,\"generateview\":1,\"fileview\":1,\"backview\":1,\"payview\":1,\"payadd\":1,\"payedit\":1,\"paydelete\":1,\"paygenerate\":1,\"payverify\":0,\"bankview\":1,\"bankadd\":1,\"bankedit\":1,\"bankdelete\":1,\"bankgenerate\":1,\"bankverify\":0,\"vatview\":1,\"vatadd\":1,\"vatedit\":1,\"vatdelete\":1,\"vatgenerate\":1,\"banksview\":1,\"banksadd\":1,\"banksedit\":1,\"banksdelete\":1,\"banksgenerate\":1,\"profileview\":1}","No"),
("44","franol","admin","administrator","{\"calcview\":1,\"calcadd\":0,\"calcedit\":1,\"calcdelete\":0,\"calcgenerate\":1,\"constview\":1,\"constadd\":0,\"constedit\":0,\"constdelete\":0,\"constgenerate\":0,\"stockview\":1,\"stockadd\":0,\"stockedit\":0,\"stockdelete\":0,\"stockgenerate\":0,\"dataview\":1,\"dataadd\":1,\"dataedit\":1,\"datadelete\":0,\"datagenerate\":1,\"jobview\":0,\"jobedit\":0,\"saleview\":0,\"saleadd\":0,\"saleedit\":0,\"saledelete\":0,\"salegenerate\":0,\"reportview\":0,\"userview\":0,\"useradd\":0,\"useredit\":0,\"userdelete\":0,\"usergenerate\":0,\"custview\":0,\"custadd\":0,\"custedit\":0,\"custdelete\":0,\"custgenerate\":0,\"generateview\":0,\"fileview\":0,\"backview\":0,\"payview\":1,\"payadd\":1,\"payedit\":1,\"paydelete\":0,\"paygenerate\":1,\"payverify\":0,\"bankview\":1,\"bankadd\":1,\"bankedit\":0,\"bankdelete\":0,\"bankgenerate\":0,\"bankverify\":0,\"vatview\":1,\"vatadd\":0,\"vatedit\":0,\"vatdelete\":0,\"vatgenerate\":0,\"banksview\":1,\"banksadd\":1,\"banksedit\":0,\"banksdelete\":0,\"banksgenerate\":0,\"profileview\":0,\"brocherview\":1,\"bookview\":1,\"manualview\":1,\"digitalview\":0,\"bannerview\":1,\"designview\":0,\"singlepageview\":0,\"multipageview\":1}","yes");



DROP TABLE IF EXISTS wall_calendar;

CREATE TABLE `wall_calendar` (
  `wall_id` int(11) NOT NULL AUTO_INCREMENT,
  `common_var` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `wall_input` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `wall_output` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`wall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;




