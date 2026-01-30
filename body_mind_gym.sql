
-- %F4'! B'9/) 'D(J'F'*
CREATE DATABASE IF NOT EXISTS body_mind_gym;
USE body_mind_gym;

-- ,/HD 'DE3*./EJF
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ,/HD 'DE/1(JF
CREATE TABLE IF NOT EXISTS trainers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- ,/HD 'DECED'*
CREATE TABLE IF NOT EXISTS supplements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    image VARCHAR(255)
);

-- ,/HD 7D('* 'DECED'*
CREATE TABLE IF NOT EXISTS supplement_orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    supplement_id INT,
    quantity INT DEFAULT 1,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (supplement_id) REFERENCES supplements(id)
);

-- ,/HD 'D*:0J)
CREATE TABLE IF NOT EXISTS nutrition_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    calories INT,
    protein DECIMAL(5,2),
    carbs DECIMAL(5,2),
    fat DECIMAL(5,2),
    log_date DATE,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- ,/HD 'D9D', 'D7(J9J
CREATE TABLE IF NOT EXISTS physiotherapy_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    session_date DATETIME,
    notes TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- ,/HD 'D#39'1 ('D'4*1'C'*)
CREATE TABLE IF NOT EXISTS pricing_plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50),
    price DECIMAL(10,2),
    duration ENUM('monthly', 'yearly')
);

-- ,/HD '4*1'C'* 'DE3*./EJF
CREATE TABLE IF NOT EXISTS user_subscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    plan_id INT,
    start_date DATE,
    end_date DATE,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (plan_id) REFERENCES pricing_plans(id)
);

-- ,/HD 'DEB'D'*
CREATE TABLE IF NOT EXISTS blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    content TEXT,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ,/HD 'D#3&D) 'D4'&9)
CREATE TABLE IF NOT EXISTS faqs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT,
    answer TEXT
);

-- ,/HD 'D13'&D EF FEH0, 'D*H'5D
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    message TEXT,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ,/HD 'D/H1'*
CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    description TEXT,
    trainer_id INT,
    image VARCHAR(255),
    FOREIGN KEY (trainer_id) REFERENCES trainers(id)
);
