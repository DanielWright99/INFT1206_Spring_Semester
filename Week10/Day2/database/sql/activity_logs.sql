CREATE TABLE activity_logs(
    id SERIAL PRIMARY KEY,
    user_id INTERGER REFERENCES users(id),
    action VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);