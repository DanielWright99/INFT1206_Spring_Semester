CREATE TABLE feedback (
                          id SERIAL PRIMARY KEY,
                          name VARCHAR(100) NOT NULL,
                          feedback TEXT NOT NULL,
                          submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);