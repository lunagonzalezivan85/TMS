# Cotizador GCM

Aplicacion profesional de cotizacion de transporte.

## Estructura

- `frontend`: React + Vite
- `backend`: Node.js + Express + JWT
- `database`: migraciones y seed de PostgreSQL

## Frontend

```bash
cd frontend
npm install
npm run dev
```

URL local: `http://localhost:5173`

## Backend

```bash
cd backend
npm install
copy .env.example .env
npm run dev
```

URL local: `http://localhost:4000`

## Base de datos PostgreSQL

Instala PostgreSQL si aun no esta disponible. Luego crea una base llamada `cotizador_gcm`.

Cuando la base exista y `backend/.env` tenga el `DATABASE_URL` correcto, ejecuta:

```bash
cd backend
npm run db:migrate
npm run db:seed
```

Usuario inicial:

- Email: `admin@gcmtransportes.com`
- Password: `admin123`

## API inicial

- `POST /api/auth/login`
- `GET /api/auth/me`
- `GET /api/quotes`
- `POST /api/quotes`
- `GET /api/dashboard/summary`
