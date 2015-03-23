CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
--
-- Sample Data
--
INSERT INTO `tasks` (`name`, `description`) VALUES
('Angular', 'Install Angular'),
('Php', 'Install Slim php');