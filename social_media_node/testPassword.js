const bcrypt = require('bcryptjs');

// Password and hashed password for testing
const password = '123'; // Use the password used for registration
const hashedPasswordFromDb = '$2a$10$REnDSRtNzsRr53.Ax8JVeOZkPI5oqjHYexGzs4Ky.ZLlXvcZYAcsS'; // Hash from DB

// Compare the plaintext password with the hashed password
bcrypt.compare(password, hashedPasswordFromDb, (err, isMatch) => {
    if (err) {
        console.error('Error during comparison:', err);
        return;
    }
    console.log('Password match:', isMatch); // Should be true if passwords match
});
