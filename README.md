#

## Users

```mysql
CREATE USER 'final_project'@'localhost' IDENTIFIED BY '<PASSWORD>';
GRANT ALL PRIVILEGES ON *.* TO 'final_project'@'localhost';
```

## Tables

```mysql
CREATE TABLE users (
  id MEDIUMINT NOT NULL AUTO_INCREMENT,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  UNIQUE INDEX unique_users (email),
  PRIMARY KEY (id)
);

CREATE TABLE yarns (
  id MEDIUMINT NOT NULL AUTO_INCREMENT,
  colorway VARCHAR(20) NOT NULL,
  manufacturer VARCHAR(20) NOT NULL,
  name VARCHAR(20) NOT NULL,
  purchased DATE NOT NULL,
  purchaser_id MEDIUMINT NOT NULL REFERENCES users(id),
  private TINYINT(1) NOT NULL DEFAULT 1,
  weight VARCHAR(20) NOT NULL,
  PRIMARY KEY (id)
);
```