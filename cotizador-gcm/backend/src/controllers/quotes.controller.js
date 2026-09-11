import { execFile } from "node:child_process";
import fs from "node:fs/promises";
import path from "node:path";
import { fileURLToPath } from "node:url";
import { z } from "zod";
import { query } from "../config/db.js";
import { asyncHandler } from "../utils/asyncHandler.js";

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const backendRoot = path.resolve(__dirname, "..", "..");
const defaultPythonPath =
  "C:\\Users\\Darwin Siezar\\.cache\\codex-runtimes\\codex-primary-runtime\\dependencies\\python\\python.exe";

const quoteSchema = z.object({
  customerName: z.string().min(2).default("Cotizacion GCM"),
  customerRuc: z.string().max(40).optional(),
  customerPhone: z.string().max(40).optional(),
  origin: z.string().min(1).default("Volumen programado"),
  destination: z.string().min(1).default("Ruta calculada"),
  vehicleType: z.string().min(2).default("Camion rigido"),
  distanceKm: z.number().positive(),
  baseCost: z.number().nonnegative(),
  marginPercent: z.number().min(0).max(100),
  marginAmount: z.number().optional(),
  finalPrice: z.number().nonnegative().optional(),
  volumeGallons: z.number().positive().optional(),
  fuelEfficiencyKmPerGallon: z.number().positive().optional(),
  fuelPricePerGallon: z.number().positive().optional(),
  freightPerGallon: z.number().nonnegative().optional(),
  costBreakdown: z.record(z.any()).optional(),
});

function sanitizeFilePart(value) {
  return String(value || "Cliente")
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "")
    .replace(/[^a-zA-Z0-9_-]+/g, "_")
    .replace(/^_+|_+$/g, "")
    .slice(0, 80) || "Cliente";
}

function runPdfGenerator(payload) {
  const pythonPath = process.env.PDF_PYTHON_PATH || defaultPythonPath;
  const scriptPath = path.join(backendRoot, "scripts", "generate_quote_pdf.py");

  return new Promise((resolve, reject) => {
    const child = execFile(
      pythonPath,
      [scriptPath],
      { maxBuffer: 1024 * 1024 * 4 },
      (error, stdout, stderr) => {
        if (error) {
          error.message = `${error.message}\n${stderr}`;
          reject(error);
          return;
        }

        try {
          resolve(JSON.parse(stdout));
        } catch (parseError) {
          parseError.message = `${parseError.message}\n${stdout}\n${stderr}`;
          reject(parseError);
        }
      }
    );

    child.stdin.write(JSON.stringify(payload));
    child.stdin.end();
  });
}

async function insertQuote(data, userId) {
  const marginAmount = data.marginAmount ?? data.baseCost * (data.marginPercent / 100);
  const finalPrice = data.finalPrice ?? data.baseCost + marginAmount;

  const result = await query(
    `INSERT INTO quotes (
      customer_name, origin, destination, vehicle_type, distance_km,
      base_cost, margin_percent, margin_amount, final_price, created_by,
      volume_gallons, fuel_efficiency_km_per_gallon, fuel_price_per_gallon,
      freight_per_gallon, cost_breakdown, customer_ruc, customer_phone
    ) VALUES ($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14,$15,$16,$17)
    RETURNING *`,
    [
      data.customerName,
      data.origin,
      data.destination,
      data.vehicleType,
      data.distanceKm,
      data.baseCost,
      data.marginPercent,
      marginAmount,
      finalPrice,
      userId,
      data.volumeGallons ?? null,
      data.fuelEfficiencyKmPerGallon ?? null,
      data.fuelPricePerGallon ?? null,
      data.freightPerGallon ?? null,
      JSON.stringify(data.costBreakdown || {}),
      data.customerRuc || null,
      data.customerPhone || null,
    ]
  );

  return result.rows[0];
}

export const createQuote = asyncHandler(async (req, res) => {
  const data = quoteSchema.parse(req.body);
  const quote = await insertQuote(data, req.user.sub);

  res.status(201).json({ quote });
});

export const getQuote = asyncHandler(async (req, res) => {
  const result = await query(
    `SELECT q.*, u.name AS created_by_name
     FROM quotes q
     JOIN users u ON u.id = q.created_by
     WHERE q.id = $1`,
    [req.params.id]
  );

  if (result.rowCount === 0) {
    return res.status(404).json({ message: "Cotizacion no encontrada" });
  }

  res.json({ quote: result.rows[0] });
});

export const updateQuote = asyncHandler(async (req, res) => {
  const data = quoteSchema.parse(req.body);
  const marginAmount = data.marginAmount ?? data.baseCost * (data.marginPercent / 100);
  const finalPrice = data.finalPrice ?? data.baseCost + marginAmount;

  const result = await query(
    `UPDATE quotes
     SET customer_name = $1,
         origin = $2,
         destination = $3,
         vehicle_type = $4,
         distance_km = $5,
         base_cost = $6,
         margin_percent = $7,
         margin_amount = $8,
         final_price = $9,
         volume_gallons = $10,
         fuel_efficiency_km_per_gallon = $11,
         fuel_price_per_gallon = $12,
         freight_per_gallon = $13,
         cost_breakdown = $14,
         customer_ruc = $15,
         customer_phone = $16,
         updated_at = now()
     WHERE id = $17
     RETURNING *`,
    [
      data.customerName,
      data.origin,
      data.destination,
      data.vehicleType,
      data.distanceKm,
      data.baseCost,
      data.marginPercent,
      marginAmount,
      finalPrice,
      data.volumeGallons ?? null,
      data.fuelEfficiencyKmPerGallon ?? null,
      data.fuelPricePerGallon ?? null,
      data.freightPerGallon ?? null,
      JSON.stringify(data.costBreakdown || {}),
      data.customerRuc || null,
      data.customerPhone || null,
      req.params.id,
    ]
  );

  if (result.rowCount === 0) {
    return res.status(404).json({ message: "Cotizacion no encontrada" });
  }

  res.json({ quote: result.rows[0] });
});

export const deleteQuote = asyncHandler(async (req, res) => {
  const result = await query("DELETE FROM quotes WHERE id = $1 RETURNING id", [req.params.id]);

  if (result.rowCount === 0) {
    return res.status(404).json({ message: "Cotizacion no encontrada" });
  }

  res.status(204).send();
});

export const exportQuotePdf = asyncHandler(async (req, res) => {
  const data = quoteSchema.parse(req.body);
  const quote = await insertQuote(data, req.user.sub);
  const filename = `${sanitizeFilePart(quote.customer_name)}_${quote.quote_number}.pdf`;
  const generated = await runPdfGenerator({ quote, filename });
  const pdf = await fs.readFile(generated.path);

  res.setHeader("Content-Type", "application/pdf");
  res.setHeader("Content-Disposition", `attachment; filename="${filename}"`);
  res.send(pdf);

  fs.unlink(generated.path).catch(() => {});
});

export const listQuotes = asyncHandler(async (req, res) => {
  const result = await query(
    `SELECT q.*, u.name AS created_by_name
     FROM quotes q
     JOIN users u ON u.id = q.created_by
     ORDER BY q.created_at DESC
     LIMIT 100`
  );

  res.json({ quotes: result.rows });
});
