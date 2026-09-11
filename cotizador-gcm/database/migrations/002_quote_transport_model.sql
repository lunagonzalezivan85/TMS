ALTER TABLE quotes
  ADD COLUMN IF NOT EXISTS volume_gallons NUMERIC(14,2),
  ADD COLUMN IF NOT EXISTS fuel_efficiency_km_per_gallon NUMERIC(5,2),
  ADD COLUMN IF NOT EXISTS fuel_price_per_gallon NUMERIC(14,4),
  ADD COLUMN IF NOT EXISTS freight_per_gallon NUMERIC(14,4),
  ADD COLUMN IF NOT EXISTS cost_breakdown JSONB NOT NULL DEFAULT '{}'::jsonb;
