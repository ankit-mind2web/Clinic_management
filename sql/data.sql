INSERT INTO users 
(id, full_name, email, mobile, password_hash, role, status, created_at, email_verified, email_verify_token, email_token_expires)
VALUES
(1, 'Ankit Rawat', 'ankitrawat@gmail.com', '9564286555',
 '$2y$12$Z4CtYa/bfIAnUgiseMCYee5YeCQ7CKwApp1bAx8wUt6...',
 'patient', 'active', '2025-12-22 12:54:20', 1, NULL, NULL),

(2, 'Amit Kumar', 'amit123@gmail.com', '9544855685',
 '$2y$12$ia23ZcTRGBiIEk9aQGYDGOpreIqtHb5dcsyKGfxM/Iz...',
 'doctor', 'active', '2025-12-23 12:58:05', 1, NULL, NULL),

(4, 'Super Admin', 'admin@wellcare.com', '9999999999',
 '$2y$12$jT02R7PQhvrEKiBpVrL3quM12i8zE6MWA8p/0nTUsh9...',
 'admin', 'active', '2025-12-23 16:54:13', 0, NULL, NULL),

(5, 'QWERTY', 'qwerty@gmail.com', '4854687568',
 '$2y$12$m8o2ZWOOFXizbXts6gW33uIiR5uzMseOGpPQkfCut34...',
 'doctor', 'active', '2025-12-24 18:43:01', 0, NULL, NULL),

(7, 'qweqewerwrwer', 'qwertyuy@gmail.com', '8687576576',
 '$2y$12$w/3QzcusbfMRqbpF0L/gweMUaI312hPtwc9RlUNr.Ya...',
 'patient', 'pending', '2025-12-25 17:42:36', 0, NULL, NULL),

(8, 'mickal', 'mickal@gmail.com', '8786767676',
 '$2y$12$WrMmQ2eZCgGM3TsGAQeNkOubnFX.FK/fdvqIK4YotCa...',
 'patient', 'pending', '2025-12-25 17:43:30', 0, NULL, NULL),

(9, 'popoye', 'popoye@gmail.com', '8676786875',
 '$2y$12$/XgnacBVbOBTTuLpvG7qju1HEok4UfoEoYbpBZJMRa5...',
 'doctor', 'active', '2025-12-25 17:44:47', 0, NULL, NULL),

(10, 'Ajay Roy', 'ajay@gmail.com', '9562354879',
 '$2y$12$wgaBOR.vdyO3pG/iQvCyQOTzF8d7HPmGh1BoUcWFuYD...',
 'patient', 'pending', '2025-12-25 18:02:48', 1, NULL, NULL),

(11, 'trait', 'trait@gmail.com', '6755464765',
 '$2y$12$UbFl4uL43Obk0cdPV0/9mOgWHoPMNPdIIzwqQmFbzYJ...',
 'patient', 'pending', '2025-12-25 18:45:27', 1, NULL, NULL),

(12, 'Sumit Garg', 'sumitgarg@gmail.com', '8956459965',
 '$2y$12$bQArkMogoKzuTee5q60OLOi.2NSyTVzYH6ETl6Colqk...',
 'doctor', 'pending', '2025-12-29 17:50:16', 0, NULL, NULL);


/*specialization table */
INSERT INTO specializations (id, name, description)
VALUES
(1, 'cardiology', 'Heart Related problem'),
(3, 'General health', 'overall physical, mental, and emotional well-being'),
(4, 'Orthopedics', 'musculoskeletal system, including bones, joints, ligaments, tendons, and muscles'),
(5, 'Dermatology', 'skin, hair, nails, and mucous membranes, and their disorders');


/* profile table */
INSERT INTO profile (id, gender, dob, address, status)
VALUES
(1, 'Others', '2002-03-13', '102, sunny enclavedgdfgfdgfdgfdg', 'Verified'),
(2, 'Male', '1998-06-10', 'adsfad', 'Pending'),
(10, 'Male', '1990-07-11', 'aljdflajdsf', 'Pending'),
(11, 'Male', '2025-12-09', 'dfdsfdsf', 'Pending'),
(12, 'Male', '1993-03-17', 'Roopnagar', 'Pending');


/*doctor_specializations*/
INSERT INTO doctor_specializations 
(id, doctor_id, specialization_id, experience, created_at)
VALUES
(1, 2, 3, 2, '2025-12-29 13:22:23'),
(2, 2, 1, 5, '2025-12-29 13:24:27');
