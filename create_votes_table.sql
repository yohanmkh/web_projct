USE so_app;

CREATE TABLE IF NOT EXISTS answer_votes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    answer_id INT,
    user_id INT,
    vote_type ENUM('upvote', 'downvote'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_vote (answer_id, user_id),
    FOREIGN KEY (answer_id) REFERENCES answers(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
); 