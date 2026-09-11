export function errorHandler(error, req, res, next) {
  console.error(error);

  if (error.name === "ZodError") {
    return res.status(400).json({ message: "Datos invalidos", errors: error.errors });
  }

  return res.status(error.status || 500).json({
    message: error.message || "Error interno del servidor",
  });
}
