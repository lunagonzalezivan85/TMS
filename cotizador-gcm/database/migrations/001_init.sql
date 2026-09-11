CREATE EXTENSION IF NOT EXISTS pgcrypto;

CREATE TABLE IF NOT EXISTS users (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  name VARCHAR(120) NOT NULL,
  email VARCHAR(180) NOT NULL UNIQUE,
  password_hash TEXT NOT NULL,
  role VARCHAR(30) NOT NULL DEFAULT 'seller',
  is_active BOOLEAN NOT NULL DEFAULT true,
  created_at TIMESTAMPTZ NOT NULL DEFAULT now(),
  updated_at TIMESTAMPTZ NOT NULL DEFAULT now(),
  CONSTRAINT users_role_check CHECK (role IN ('admin', 'manager', 'seller'))
);

CREATE TABLE IF NOT EXISTS quotes (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  quote_number BIGSERIAL UNIQUE,
  customer_name VARCHAR(160) NOT NULL,
  origin VARCHAR(160) NOT NULL,
  destination VARCHAR(160) NOT NULL,
  vehicle_type VARCHAR(80) NOT NULL,
  distance_km NUMERIC(12,2) NOT NULL,
  base_cost NUMERIC(14,2) NOT NULL,
  margin_percent NUMERIC(5,2) NOT NULL,
  margin_amount NUMERIC(14,2) NOT NULL,
  final_price NUMERIC(14,2) NOT NULL,
  status VARCHAR(30) NOT NULL DEFAULT 'draft',
  created_by UUID NOT NULL REFERENCES users(id),
  created_at TIMESTAMPTZ NOT NULL DEFAULT now(),
  updated_at TIMESTAMPTZ NOT NULL DEFAULT now(),
  CONSTRAINT quotes_status_check CHECK (status IN ('draft', 'sent', 'approved', 'rejected'))
);

CREATE INDEX IF NOT EXISTS idx_quotes_created_at ON quotes(created_at DESC);
CREATE INDEX IF NOT EXISTS idx_quotes_created_by ON quotes(created_by);
