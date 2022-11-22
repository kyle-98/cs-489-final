CREATE TABLE hurricanes (
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  name MEDIUMTEXT NOT NULL,
  year INT NOT NULL,
  coords MEDIUMTEXT NOT NULL,
  data MEDIUMTEXT NOT NULL,
  starting_month VARCHAR(30) NOT NULL,
  highest_wind INT NOT NULL,
  named_storm_days INT NOT NULL);
