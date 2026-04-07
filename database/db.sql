DROP DATABASE IF EXISTS yaamdb;
CREATE DATABASE yaamdb
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE yaamdb;

-- Tabla users: NO se crea aquí porque la manejará Laravel con su migración por defecto.

CREATE TABLE movies (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  source VARCHAR(45) NOT NULL,               -- ej: tmdb
  external_id VARCHAR(45) NOT NULL,          -- ej: id de TMDB
  title VARCHAR(255) NOT NULL,
  overview TEXT NOT NULL,
  release_date DATE NULL,
  poster_path VARCHAR(255) NULL,
  backdrop_path VARCHAR(255) NULL,
  runtime INT NULL,
  language VARCHAR(45) NULL,
  popularity DECIMAL(10,2) NULL,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_movies_source_external (source, external_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE comments (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  movie_id BIGINT UNSIGNED NOT NULL,
  body TEXT NOT NULL,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  INDEX idx_comments_user_id (user_id),
  INDEX idx_comments_movie_id (movie_id),
  CONSTRAINT fk_comments_user
    FOREIGN KEY (user_id) REFERENCES users (id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_comments_movie
    FOREIGN KEY (movie_id) REFERENCES movies (id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE favorites (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  movie_id BIGINT UNSIGNED NOT NULL,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  INDEX idx_favorites_user_id (user_id),
  INDEX idx_favorites_movie_id (movie_id),
  CONSTRAINT fk_favorites_user
    FOREIGN KEY (user_id) REFERENCES users (id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_favorites_movie
    FOREIGN KEY (movie_id) REFERENCES movies (id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  UNIQUE KEY uq_favorites_user_movie (user_id, movie_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;