-- Demo admin data
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
    'Admin User',
    35,
    '9999999999',
    'ADMIN123',
    'O+',
    'Office 101',
    'Admin Block',
    'Main Road',
    'Near Government Hospital',
    'New Delhi',
    'Delhi',
    180,
    75,
    '$2y$10$8K1p/a0dL1LXMIgoEDFrwOc8B7U7iFj3qY5J3J3J3J3J3J3J3J3J3J', -- password: admin123
    'admin',
    'active'
); 