import fs from "node:fs/promises";
import path from "node:path";
import { fileURLToPath } from "node:url";
import pg from "pg";
import dotenv from "dotenv";

dotenv.config();

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const root = path.resolve(__dirname, "..", "..");
const file = process.argv[2];

if (!file) {
  console.error("Uso: node scripts/run-sql.js <archivo.sql>");
  process.exit(1);
}

if (!process.env.DATABASE_URL) {
  console.error("DATABASE_URL no esta configurada");
  process.exit(1);
}

const sqlPath = path.resolve(root, file);
const sql = (await fs.readFile(sqlPath, "utf8")).replace(/^\uFEFF/, "");
const pool = new pg.Pool({ connectionString: process.env.DATABASE_URL });

try {
  await pool.query(sql);
  console.log(`SQL ejecutado: ${file}`);
} finally {
  await pool.end();
}

