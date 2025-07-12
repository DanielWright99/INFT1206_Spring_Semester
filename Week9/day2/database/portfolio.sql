CREATE TABLE feedback (
                          id SERIAL PRIMARY KEY,
                          name VARCHAR(100) NOT NULL,
                          feedback TEXT NOT NULL,
                          submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE feedback
ADD COLUMN user_id INTEGER,
ADD CONSTRAINT fk_user_id FOREIGN KEY (user_id) REFERENCES users(id);