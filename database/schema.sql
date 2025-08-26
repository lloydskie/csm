-- CSM Survey Responses Table
CREATE TABLE IF NOT EXISTS survey_responses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(100),
    branch VARCHAR(100),
    service_type VARCHAR(100),
    service_rating INT,
    staff_rating INT,
    response_time_rating INT,
    remarks TEXT,
    ip_address VARCHAR(45),
    geo_location VARCHAR(255),
    token VARCHAR(64),
    submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password_hash VARCHAR(255)
);

-- Survey Links Table for QR code tracking
CREATE TABLE IF NOT EXISTS survey_links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    token VARCHAR(64) UNIQUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    used TINYINT(1) DEFAULT 0
);
