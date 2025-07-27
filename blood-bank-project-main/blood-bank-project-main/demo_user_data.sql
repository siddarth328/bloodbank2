-- Demo user data
INSERT INTO users (
    name, 
    age, 
    mobile, 
    id_proof, 
    blood_group, 
    house_no, 
    colony, 
    street, 
    landmark, 
    city, 
    state, 
    height, 
    weight, 
    password, 
    role, 
    status
) VALUES (
    'Rahul Sharma',
    28,
    '9876543210',
    'DL1234567890',
    'O+',
    'Flat 302',
    'Green Valley Society',
    'MG Road',
    'Near City Mall',
    'Mumbai',
    'Maharashtra',
    175,
    70,
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    'user',
    'active'
); 