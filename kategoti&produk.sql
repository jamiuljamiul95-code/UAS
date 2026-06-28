CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE,
    icon VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    slug VARCHAR(180) NOT NULL UNIQUE,
    description TEXT,
    thumbnail VARCHAR(255) DEFAULT NULL,
    preview_image VARCHAR(255) DEFAULT NULL,
    file_path VARCHAR(255) DEFAULT NULL,
    price DECIMAL(12,2) NOT NULL DEFAULT 0,
    discount DECIMAL(5,2) DEFAULT 0,
    sales INT DEFAULT 0,
    status ENUM('draft','published') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- contoh data kategori
INSERT INTO categories (name, slug, icon) VALUES
('Template Canva', 'template-canva', 'ti-template'),
('Preset Lightroom', 'preset-lightroom', 'ti-camera'),
('Template Photoshop', 'template-photoshop', 'ti-photo'),
('Template CapCut', 'template-capcut', 'ti-video'),
('Mockup Design', 'mockup-design', 'ti-frame'),
('Font Premium', 'font-premium', 'ti-typography'),
('UI Kit', 'ui-kit', 'ti-layout'),
('Template PowerPoint', 'template-powerpoint', 'ti-presentation');