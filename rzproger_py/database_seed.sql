-- Insert test users
INSERT INTO users (name, email, password, is_admin, created_at) VALUES
('Администратор', 'admin@gmail.com', '$2y$10$HBvxcZvM9KcHj1FoPyKS7e4iSvNHkvuK10J3oGnWwmUbzHl0MYrpC', true, NOW()), -- password: 12345678
('Иван Петров', 'ivan@gmail.com', '$2y$10$HBvxcZvM9KcHj1FoPyKS7e4iSvNHkvuK10J3oGnWwmUbzHl0MYrpC', false, NOW()), -- password: 12345678
('Мария Сидорова', 'maria@gmail.com', '$2y$10$HBvxcZvM9KcHj1FoPyKS7e4iSvNHkvuK10J3oGnWwmUbzHl0MYrpC', false, NOW()); -- password: 12345678

-- Insert test events
INSERT INTO events (title, description, type, start_datetime, duration, price, location, image_path, created_at) VALUES
('Концерт "Весенний джаз"', 'Прекрасный вечер джазовой музыки в исполнении лучших музыкантов Бишкека. В программе: классический джаз, блюз и современные композиции.', 'concert', '2025-05-19 19:00:00', 180, 1500.00, 'г. Бишкек, ул. Советская 123, Джаз-клуб "Блюз"', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcShlj48XbcId5fKeSE1JDmjVAv5cGtTptd5Nw&s', NOW()),

('Выставка современного искусства', 'Выставка работ современных художников Кыргызстана. Живопись, скульптура, инсталляции и цифровое искусство.', 'exhibition', '2025-05-30 10:00:00', 480, 500.00, 'г. Бишкек, ул. Ибраимова 78, Галерея "Современник"', 'https://albione.ru/upload/iblock/ff9/ff968e2c46b56b84a4010265d4e59577.jpg', NOW()),

('IT-конференция "Технологии будущего"', 'Ежегодная конференция для IT-специалистов. Доклады о новых технологиях, мастер-классы и нетворкинг.', 'conference', '2025-05-31 09:00:00', 360, 2000.00, 'г. Бишкек, ул. Манаса 45, Бизнес-центр "Азия"', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTQKQBiM4soNLaKPOzLP5-i1XWpbZ2J84QGkw&s', NOW()),

('Мастер-класс по приготовлению плова', 'Научитесь готовить настоящий узбекский плов под руководством шеф-повара. Все ингредиенты включены в стоимость.', 'workshop', '2025-06-02 14:00:00', 180, 2500.00, 'г. Бишкек, ул. Киевская 56, Кулинарная студия "Вкус"', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT0nBUlQh95uIeV4n5VPfklNjXy2llHyNVVDA&s', NOW()),

('Фестиваль уличной еды', 'Большой фестиваль уличной еды со всего мира. Более 50 фудтраков, живая музыка и развлечения.', 'other', '2025-06-10 12:00:00', 480, 300.00, 'г. Бишкек, ул. Чуй 89, Парк "Дружба"', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTl1rrKSccl-zBgOnyK2Z6xtzoT8B8WSyWTOQ&s', NOW());

-- Insert test comments
INSERT INTO comments (user_id, event_id, content, created_at) VALUES
(2, 1, 'Отличный концерт! Очень понравилось исполнение.', NOW()),
(3, 1, 'Прекрасная атмосфера и замечательная музыка.', NOW()),
(2, 2, 'Интересная выставка, особенно понравились инсталляции.', NOW()),
(3, 3, 'Очень информативная конференция, много полезных контактов.', NOW()),
(2, 4, 'Научился готовить плов не хуже ресторанного!', NOW());

-- Insert test favorites
INSERT INTO favorites (user_id, event_id, created_at) VALUES
(2, 1, NOW()),
(2, 3, NOW()),
(3, 2, NOW()),
(3, 4, NOW()),
(3, 5, NOW()); 