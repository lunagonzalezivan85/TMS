import pg from "pg";
import { env } from "./env.js";

export const pool = new pg.Pool({
  connectionString: env.databaseUrl,
});

export async function query(text, params) {
  const start = Date.now();
  const result = await pool.query(text, params);
  if (env.nodeEnv === "development") {
    console.log("db", { rows: result.rowCount, durationMs: Date.now() - start });
  }
  return result;
}
