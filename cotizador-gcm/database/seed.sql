-- Password: admin123
INSERT INTO users (name, email, password_hash, role)
VALUES (
  'Administrador GCM',
  'admin@gcmtransportes.com',
  '$2a$10$VMrL2IyVbqIfHjyo5eQRZOe6gs0qv73qxjK2dUbOSeccb9mXKx6QG',
  'admin'
)
ON CONFLICT (email) DO NOTHING;
