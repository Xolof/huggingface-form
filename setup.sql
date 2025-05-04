-- Create a table for users
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT UNIQUE,
    email TEXT UNIQUE,
    isAdmin INTEGER,
    password TEXT
);

-- Create a table for posts
CREATE TABLE IF NOT EXISTS posts (
    post_id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    post TEXT,
    publish_unix_timestamp INTEGER,
    FOREIGN KEY(user_id) REFERENCES users(id)
);

-- Insert sample data into users
INSERT INTO users (name, email, isAdmin, password) VALUES
    ('admin', 'admin@admin.se', 1, '$2y$12$Xxtt8i6aGcXn1.WHxRR6j.8JrDAjuZV/5JiduRFx0HzSvENhcrEOu');

-- Insert sample data into posts
INSERT INTO posts (user_id, post, publish_unix_timestamp) VALUES
    (1, 'Hello, World! This is some text.', 1546015394),
    (1, 'Hi! This is another post.', 1746015394);
