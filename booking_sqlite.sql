-- Converted SQLite-compatible dump
-- Source: booking.sql (MySQL/MariaDB) converted for local dev SQLite
PRAGMA foreign_keys = OFF;
BEGIN TRANSACTION;

-- ensure we don't fail if the table already exists
DROP TABLE IF EXISTS reserve;

CREATE TABLE reserve (
  id_table TEXT NOT NULL PRIMARY KEY,
  name_table TEXT NOT NULL,
  name TEXT NOT NULL,
  tel TEXT NOT NULL,
  email TEXT NOT NULL,
  status INTEGER NOT NULL,
  status_pay INTEGER NOT NULL,
  date_buy TEXT NOT NULL,
  seller TEXT NOT NULL,
  payment TEXT NOT NULL,
  cookie_ TEXT NOT NULL,
  date_del TEXT NOT NULL
);
-- clear existing rows if any (safe when table just created)
DELETE FROM reserve;

-- use INSERT OR REPLACE to avoid UNIQUE constraint failures when importing into a DB that already has rows
INSERT OR REPLACE INTO reserve (id_table, name_table, name, tel, email, status, status_pay, date_buy, seller, payment, cookie_, date_del) VALUES
('T_18', '18', 'VIP', '0000000000', '', 4, 1, '2025-01-09 17:18:55', 'ส่วนกลาง', '', '', ''),
('T_19', '19', 'VIP', '0000000000', '', 4, 1, '2025-01-09 17:26:45', 'ส่วนกลาง', '', '', ''),
('T_30', '30', 'VIP', '0000000000', '', 4, 1, '2025-01-09 17:19:09', 'ส่วนกลาง', '', '', ''),
('T_31', '31', 'VIP', '0000000000', '', 4, 1, '2025-01-09 17:27:32', 'ส่วนกลาง', '', '', ''),
('T_44', '44', 'sdf', '0000000000', 'sdf@gmail.com', 4, 1, '2025-01-13 16:35:22', 'ระบบออนไลน์', 'bGpkenhZekxnUHdLUklEMjNwd3BqZz09OjpjtauTYD7uLRfO3UJUbzZxOjo=', '', ''),
('T_45', '45', 'sdf', '0000000000', 'sdf@gmail.com', 3, 1, '2025-01-13 16:35:22', 'ระบบออนไลน์', 'bGpkenhZekxnUHdLUklEMjNwd3BqZz09OjpjtauTYD7uLRfO3UJUbzZxOjo=', '', ''),
('T_46', '46', 'sdf', '0000000000', 'sdf@gmail.com', 3, 1, '2025-01-13 16:35:22', 'ระบบออนไลน์', 'bGpkenhZekxnUHdLUklEMjNwd3BqZz09OjpjtauTYD7uLRfO3UJUbzZxOjo=', '', ''),
('T_6', '6', 'VIP', '0000000000', '', 4, 1, '2025-01-09 17:05:24', 'ส่วนกลาง', '', '', ''),
('T_7', '7', 'VIP', '0000000000', '', 4, 1, '2025-01-09 17:19:29', 'ส่วนกลาง', '', '', ''),
('T_8', '8', 'คุณเจ', '0000000000', '', 2, 0, '2025-01-13 16:42:30', 'ส่วนกลาง', '', '', '');

COMMIT;
PRAGMA foreign_keys = ON;
